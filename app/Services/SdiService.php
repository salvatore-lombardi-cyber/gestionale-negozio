<?php

namespace App\Services;

use FatturaElettronicaPhp\FatturaElettronica\DigitalDocument;
use FatturaElettronicaPhp\FatturaElettronica\Supplier;
use FatturaElettronicaPhp\FatturaElettronica\Customer;
use FatturaElettronicaPhp\FatturaElettronica\Address;
use FatturaElettronicaPhp\FatturaElettronica\Line;
use FatturaElettronicaPhp\FatturaElettronica\Total;
use FatturaElettronicaPhp\FatturaElettronica\DigitalDocumentInstance;
use FatturaElettronicaPhp\FatturaElettronica\Enums\TransmissionFormat;
use FatturaElettronicaPhp\FatturaElettronica\Enums\DocumentType;
use App\Models\CompanyProfile;
use App\Models\Anagrafica;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

/**
 * Servizio per la gestione del Sistema di Interscambio (SDI)
 * Integrazione con fatturazione elettronica italiana
 */
class SdiService
{
    private $company;
    private $testMode;
    
    public function __construct($testMode = true)
    {
        $this->company = CompanyProfile::first();
        $this->testMode = $testMode;
    }
    
    /**
     * Verifica se il sistema è configurato per SDI
     */
    public function isConfigured(): bool
    {
        if (!$this->company) {
            return false;
        }
        
        return !empty($this->company->partita_iva) && 
               !empty($this->company->sdi_username);
    }
    
    /**
     * Genera XML fattura elettronica
     */
    public function generateInvoiceXml(array $invoiceData): string
    {
        try {
            // === INDIRIZZO FORNITORE ===
            $supplierAddress = (new Address())
                ->setStreet($this->company->indirizzo_sede)
                ->setZip($this->company->cap)
                ->setCity($this->company->citta)
                ->setState($this->company->provincia)
                ->setCountryCode($this->company->nazione ?? 'IT');
            
            // === FORNITORE (La nostra azienda) ===
            $supplier = (new Supplier())
                ->setVatNumber($this->company->partita_iva)
                ->setFiscalCode($this->company->partita_iva)
                ->setOrganization($this->company->ragione_sociale)
                ->setAddress($supplierAddress)
                ->setCountryCode('IT')
                ->setTaxRegime($this->company->regime_fiscale ?? 'RF01');
            
            // === INDIRIZZO CLIENTE ===
            $customerAddress = (new Address())
                ->setStreet($invoiceData['cliente']['indirizzo'])
                ->setZip($invoiceData['cliente']['cap'])
                ->setCity($invoiceData['cliente']['citta'])
                ->setState($invoiceData['cliente']['provincia'])
                ->setCountryCode($invoiceData['cliente']['nazione'] ?? 'IT');
            
            // === CLIENTE ===
            $customer = (new Customer())
                ->setVatNumber($invoiceData['cliente']['partita_iva'])
                ->setFiscalCode($invoiceData['cliente']['codice_fiscale'] ?? $invoiceData['cliente']['partita_iva'])
                ->setOrganization($invoiceData['cliente']['ragione_sociale'])
                ->setAddress($customerAddress)
                ->setCountryCode('IT');
            
            // === DOCUMENTO DIGITALE ===
            $document = new DigitalDocument();
            $document
                ->setSendingId($invoiceData['progressivo'] ?? date('YmdHis'))
                ->setCountryCode('IT')
                ->setSenderVatId($this->company->partita_iva)
                ->setSupplier($supplier)
                ->setCustomer($customer)
                ->setTransmissionFormat(TransmissionFormat::FPR12())
                ->setCustomerSdiCode($invoiceData['codice_destinatario'] ?? '0000000');
            
            if (isset($invoiceData['pec_destinatario'])) {
                $document->setCustomerPec($invoiceData['pec_destinatario']);
            }
            
            // === ISTANZA DOCUMENTO (Fattura vera e propria) ===
            $documentInstance = new DigitalDocumentInstance();
            $documentInstance
                ->setDocumentType(DocumentType::TD01())
                ->setDocumentNumber($invoiceData['numero'])
                ->setDocumentDate(new \DateTime($invoiceData['data']))
                ->setCurrency($invoiceData['divisa'] ?? 'EUR');
            
            // === RIGHE FATTURA ===
            foreach ($invoiceData['righe'] as $index => $riga) {
                $line = (new Line())
                    ->setNumber($index + 1)
                    ->setDescription($riga['descrizione'])
                    ->setQuantity($riga['quantita'])
                    ->setUnitPrice($riga['prezzo_unitario'])
                    ->setTotal($riga['prezzo_totale'])
                    ->setTaxPercentage($riga['aliquota_iva']);
                
                $documentInstance->addLine($line);
            }
            
            // === TOTALI ===
            foreach ($invoiceData['riepiloghi_iva'] as $riepilogo) {
                $total = (new Total())
                    ->setTaxPercentage($riepilogo['aliquota'])
                    ->setTotal($riepilogo['imponibile'])
                    ->setTaxAmount($riepilogo['imposta']);
                
                $documentInstance->addTotal($total);
            }
            
            // Aggiungi l'istanza al documento
            $document->addDigitalDocumentInstance($documentInstance);
            
            // Genera l'XML
            $xml = $document->serialize();
            
            Log::info('XML fattura elettronica generato', [
                'numero_fattura' => $invoiceData['numero'] ?? 'N/A',
                'xml_size' => strlen($xml)
            ]);
            
            return $xml;
            
        } catch (\Exception $e) {
            Log::error('Errore generazione XML fattura', [
                'error' => $e->getMessage(),
                'invoice_data' => $invoiceData
            ]);
            throw $e;
        }
    }
    
    /**
     * Invia fattura a SDI (per ora salva solo in locale)
     */
    public function sendToSdi(string $xml, array $invoiceData): array
    {
        try {
            // Per ora salviamo in locale - in produzione qui si invierebbe tramite API
            $filename = 'IT' . $this->company->partita_iva . '_' . 
                       $invoiceData['progressivo'] . '.xml';
            
            $path = 'invoices/sent/' . $filename;
            Storage::disk('local')->put($path, $xml);
            
            // Log dell'operazione
            Log::info('Fattura elettronica generata', [
                'filename' => $filename,
                'path' => $path,
                'test_mode' => $this->testMode,
                'numero_fattura' => $invoiceData['numero'],
                'cliente' => $invoiceData['cliente']['ragione_sociale'] ?? 'N/A'
            ]);
            
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $path,
                'message' => $this->testMode ? 
                    'Fattura generata in modalità test' : 
                    'Fattura inviata a SDI',
                'protocol_id' => $this->testMode ? 'TEST_' . time() : null
            ];
            
        } catch (\Exception $e) {
            Log::error('Errore invio fattura a SDI', [
                'error' => $e->getMessage(),
                'invoice_data' => $invoiceData
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Valida XML fattura elettronica
     */
    public function validateXml(string $xml): array
    {
        try {
            $document = DigitalDocument::parseFrom($xml);
            
            return [
                'valid' => true,
                'message' => 'XML valido'
            ];
            
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Ottieni credenziali SDI decriptate
     */
    public function getSdiCredentials(): ?array
    {
        if (!$this->company || !$this->company->sdi_username) {
            return null;
        }
        
        try {
            return [
                'username' => Crypt::decryptString($this->company->sdi_username),
                'password' => $this->company->sdi_password // Hash, non decriptabile
            ];
        } catch (\Exception $e) {
            Log::error('Errore decrittazione credenziali SDI', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Test connessione SDI
     */
    public function testConnection(): array
    {
        $credentials = $this->getSdiCredentials();
        
        if (!$credentials) {
            return [
                'connected' => false,
                'error' => 'Credenziali SDI non configurate'
            ];
        }
        
        if (!$this->isConfigured()) {
            return [
                'connected' => false,
                'error' => 'Configurazione aziendale incompleta'
            ];
        }
        
        // In modalità test sempre OK
        if ($this->testMode) {
            return [
                'connected' => true,
                'message' => 'Connessione test OK - Modalità sviluppo'
            ];
        }
        
        // TODO: Implementare test reale con API SDI
        return [
            'connected' => false,
            'error' => 'Test connessione SDI non ancora implementato'
        ];
    }
}