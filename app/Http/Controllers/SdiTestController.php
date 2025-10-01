<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SdiService;
use App\Services\SdiRealService;
use App\Models\CompanyProfile;
use App\Models\Anagrafica;

class SdiTestController extends Controller
{
    /**
     * Dashboard test SDI
     */
    public function index()
    {
        $sdiService = new SdiService(true); // Modalità test
        $sdiRealService = new SdiRealService(false); // Modalità reale
        
        $data = [
            'configured' => $sdiService->isConfigured(),
            'connection' => $sdiService->testConnection(),
            'real_connection' => $sdiRealService->testRealConnection(),
            'company' => CompanyProfile::first(),
            'credentials' => $sdiService->getSdiCredentials(),
            'test_mode' => config('sdi.test_mode', true),
            'certificate_exists' => file_exists(config('sdi.certificate_path', ''))
        ];
        
        return view('sdi.test', $data);
    }
    
    /**
     * Test generazione XML fattura
     */
    public function generateTestInvoice()
    {
        $sdiService = new SdiService(true);
        
        // Dati fattura di test
        $invoiceData = [
            'numero' => 'TEST001',
            'data' => date('Y-m-d'),
            'progressivo' => 'TEST' . time(),
            'tipo_documento' => 'TD01',
            'divisa' => 'EUR',
            'totale' => 122.00,
            'codice_destinatario' => '0000000',
            'cliente' => [
                'ragione_sociale' => 'Cliente Test SRL',
                'partita_iva' => '12345678901',
                'codice_fiscale' => '12345678901',
                'indirizzo' => 'Via Test 123',
                'cap' => '00100',
                'citta' => 'Roma',
                'provincia' => 'RM',
                'nazione' => 'IT'
            ],
            'righe' => [
                [
                    'descrizione' => 'Prodotto di test',
                    'quantita' => 1,
                    'prezzo_unitario' => 100.00,
                    'prezzo_totale' => 100.00,
                    'aliquota_iva' => 22.00
                ]
            ],
            'riepiloghi_iva' => [
                [
                    'aliquota' => 22.00,
                    'imponibile' => 100.00,
                    'imposta' => 22.00
                ]
            ]
        ];
        
        try {
            // Genera XML
            $xml = $sdiService->generateInvoiceXml($invoiceData);
            
            // Valida XML
            $validation = $sdiService->validateXml($xml);
            
            // Invia a SDI (salva in locale)
            $result = $sdiService->sendToSdi($xml, $invoiceData);
            
            return response()->json([
                'success' => true,
                'xml_length' => strlen($xml),
                'validation' => $validation,
                'sdi_result' => $result,
                'xml_preview' => substr($xml, 0, 500) . '...'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Visualizza XML generato
     */
    public function viewXml($filename)
    {
        $path = 'invoices/sent/' . $filename;
        
        if (!\Storage::disk('local')->exists($path)) {
            abort(404, 'File XML non trovato');
        }
        
        $xml = \Storage::disk('local')->get($path);
        
        return response($xml)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
    
    /**
     * Lista file XML generati
     */
    public function listXmlFiles()
    {
        $files = [];
        
        if (\Storage::disk('local')->exists('invoices/sent/')) {
            $allFiles = \Storage::disk('local')->files('invoices/sent/');
            
            foreach ($allFiles as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'xml') {
                    $files[] = [
                        'filename' => basename($file),
                        'path' => $file,
                        'size' => \Storage::disk('local')->size($file),
                        'modified' => \Storage::disk('local')->lastModified($file)
                    ];
                }
            }
        }
        
        return response()->json($files);
    }
}
