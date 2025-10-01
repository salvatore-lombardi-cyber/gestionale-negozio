<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Servizio per invio REALE a Sistema di Interscambio (SDI)
 * Integrazione con Agenzia delle Entrate
 */
class SdiRealService extends SdiService
{
    private $certificatePath;
    private $certificatePassword;
    private $sdiEndpoint;
    
    public function __construct($testMode = false)
    {
        parent::__construct($testMode);
        
        // Configurazione per ambiente reale
        $this->certificatePath = config('sdi.certificate_path');
        $this->certificatePassword = config('sdi.certificate_password');
        $this->sdiEndpoint = config('sdi.endpoint', 'https://ivaservizi.agenziaentrate.gov.it/ser/fatturapa/ricevi');
    }
    
    /**
     * Invia fattura REALMENTE a SDI
     */
    public function sendToSdiReal(string $xml, array $invoiceData): array
    {
        if ($this->testMode) {
            return parent::sendToSdi($xml, $invoiceData); // Modalità test
        }
        
        try {
            // 1. Firma digitalmente l'XML
            $signedXml = $this->signXml($xml);
            
            // 2. Prepara il payload per SDI
            $filename = $this->generateSdiFilename($invoiceData);
            
            // 3. Invia tramite HTTPS a SDI
            $response = Http::attach(
                'file', $signedXml, $filename
            )->withHeaders([
                'Content-Type' => 'multipart/form-data',
                'SOAPAction' => 'http://ivaservizi.agenziaentrate.gov.it/docs/xsd/fatture/v1.2'
            ])->post($this->sdiEndpoint);
            
            // 4. Gestisci risposta SDI
            return $this->handleSdiResponse($response, $invoiceData, $filename);
            
        } catch (\Exception $e) {
            Log::error('Errore invio reale a SDI', [
                'error' => $e->getMessage(),
                'invoice_data' => $invoiceData
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'sdi_error' => true
            ];
        }
    }
    
    /**
     * Firma digitalmente l'XML
     */
    private function signXml(string $xml): string
    {
        // Verifica esistenza certificato
        if (!file_exists($this->certificatePath)) {
            throw new \Exception('Certificato digitale non trovato: ' . $this->certificatePath);
        }
        
        try {
            // Carica certificato PKCS#12
            $certStore = file_get_contents($this->certificatePath);
            $certInfo = [];
            
            if (!openssl_pkcs12_read($certStore, $certInfo, $this->certificatePassword)) {
                throw new \Exception('Impossibile leggere certificato digitale');
            }
            
            // Firma XML con CAdES-BES
            $tempXmlFile = tempnam(sys_get_temp_dir(), 'fattura_');
            $tempSignedFile = tempnam(sys_get_temp_dir(), 'fattura_signed_');
            
            file_put_contents($tempXmlFile, $xml);
            
            // Usa openssl per firmare (formato CAdES-BES)
            $command = sprintf(
                'openssl smime -sign -in %s -out %s -signer %s -inkey %s -outform DER -nodetach',
                escapeshellarg($tempXmlFile),
                escapeshellarg($tempSignedFile),
                escapeshellarg($this->extractCertificateToFile($certInfo['cert'])),
                escapeshellarg($this->extractPrivateKeyToFile($certInfo['pkey']))
            );
            
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new \Exception('Errore firma digitale: ' . implode(' ', $output));
            }
            
            $signedXml = file_get_contents($tempSignedFile);
            
            // Cleanup
            unlink($tempXmlFile);
            unlink($tempSignedFile);
            
            return $signedXml;
            
        } catch (\Exception $e) {
            Log::error('Errore firma digitale', ['error' => $e->getMessage()]);
            throw new \Exception('Firma digitale fallita: ' . $e->getMessage());
        }
    }
    
    /**
     * Genera nome file secondo standard SDI
     */
    private function generateSdiFilename(array $invoiceData): string
    {
        $partitaIva = $this->company->partita_iva;
        $progressivo = str_pad($invoiceData['progressivo'] ?? '1', 5, '0', STR_PAD_LEFT);
        
        return "IT{$partitaIva}_{$progressivo}.xml.p7m";
    }
    
    /**
     * Gestisce risposta da SDI
     */
    private function handleSdiResponse($response, array $invoiceData, string $filename): array
    {
        $statusCode = $response->status();
        $body = $response->body();
        
        // Log della risposta
        Log::info('Risposta SDI', [
            'status' => $statusCode,
            'headers' => $response->headers(),
            'body_preview' => substr($body, 0, 500)
        ]);
        
        if ($statusCode === 200) {
            // Successo - analizza XML di risposta
            $responseXml = simplexml_load_string($body);
            
            if ($responseXml && isset($responseXml->identificativoSdI)) {
                $protocolId = (string)$responseXml->identificativoSdI;
                
                // Salva anche localmente per backup
                Storage::disk('local')->put("invoices/sent/{$filename}", $body);
                
                return [
                    'success' => true,
                    'filename' => $filename,
                    'protocol_id' => $protocolId,
                    'message' => 'Fattura inviata con successo a SDI',
                    'sdi_response' => $body
                ];
            }
        }
        
        // Errore SDI
        return [
            'success' => false,
            'filename' => $filename,
            'http_status' => $statusCode,
            'error' => 'Errore risposta SDI',
            'sdi_response' => $body
        ];
    }
    
    /**
     * Estrae certificato in file temporaneo
     */
    private function extractCertificateToFile(string $cert): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'cert_');
        file_put_contents($tempFile, $cert);
        return $tempFile;
    }
    
    /**
     * Estrae chiave privata in file temporaneo
     */
    private function extractPrivateKeyToFile($key): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'key_');
        $keyString = '';
        openssl_pkey_export($key, $keyString);
        file_put_contents($tempFile, $keyString);
        return $tempFile;
    }
    
    /**
     * Verifica stato fattura su SDI
     */
    public function checkInvoiceStatus(string $protocolId): array
    {
        try {
            // Query endpoint SDI per stato fattura
            $response = Http::withHeaders([
                'Content-Type' => 'application/xml'
            ])->post(config('sdi.query_endpoint'), [
                'identificativoSdI' => $protocolId,
                'identificativoTrasmittente' => $this->company->partita_iva
            ]);
            
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body());
                
                return [
                    'protocol_id' => $protocolId,
                    'status' => (string)$xml->stato ?? 'unknown',
                    'description' => (string)$xml->descrizione ?? 'N/A',
                    'last_update' => (string)$xml->dataUltimoAggiornamento ?? null
                ];
            }
            
            return [
                'protocol_id' => $protocolId,
                'status' => 'error',
                'error' => 'Impossibile verificare stato'
            ];
            
        } catch (\Exception $e) {
            return [
                'protocol_id' => $protocolId,
                'status' => 'error',
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Test connessione REALE a SDI
     */
    public function testRealConnection(): array
    {
        if (!$this->certificatePath || !file_exists($this->certificatePath)) {
            return [
                'connected' => false,
                'error' => 'Certificato digitale non configurato'
            ];
        }
        
        try {
            // Test ping a SDI
            $response = Http::timeout(10)->get($this->sdiEndpoint);
            
            return [
                'connected' => $response->status() < 500,
                'message' => 'Connessione SDI: ' . $response->status(),
                'endpoint' => $this->sdiEndpoint
            ];
            
        } catch (\Exception $e) {
            return [
                'connected' => false,
                'error' => 'Errore connessione SDI: ' . $e->getMessage()
            ];
        }
    }
}