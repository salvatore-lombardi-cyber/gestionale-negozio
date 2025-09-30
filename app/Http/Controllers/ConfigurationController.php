<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyProfile;
use App\Models\BankAccount;
use App\Models\SystemTable;
use App\Models\TaxRate;
use App\Models\PaymentMethod;
use App\Models\Currency;
use App\Models\SystemSetting;
use App\Models\DocumentNumbering;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\RateLimiter;

/**
 * Controller per la gestione delle configurazioni di sistema
 * Implementa controlli di sicurezza OWASP e performance ottimizzate
 */
class ConfigurationController extends Controller
{
    public function index()
    {
        return view('configurations.index');
    }

    // === UTENTE ===
    public function utente()
    {
        $company = CompanyProfile::first() ?? new CompanyProfile();
        return view('configurations.utente', compact('company'));
    }

    public function updateUtente(Request $request)
    {
        // Rate limiting per operazioni critiche
        $rateLimiterKey = 'update-utente:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimiterKey);
            return back()->withErrors(['error' => "Troppe modifiche. Riprova tra {$seconds} secondi."]);
        }
        RateLimiter::hit($rateLimiterKey, 300); // 5 tentativi per 5 minuti

        $validated = $request->validate([
            'ragione_sociale' => 'nullable|string|max:255|regex:/^[\p{L}\p{N}\s\-\.\&]+$/u',
            'nome' => 'nullable|string|max:255|regex:/^[\p{L}\s\-\']+$/u',
            'cognome' => 'nullable|string|max:255|regex:/^[\p{L}\s\-\']+$/u',
            'genere' => 'nullable|in:M,F,Altro',
            'indirizzo_sede' => 'nullable|string|max:500',
            'cap' => 'nullable|string|max:10|regex:/^[0-9]{5}$/',
            'provincia' => 'nullable|string|max:100|regex:/^[\p{L}\s\-]+$/u',
            'citta' => 'nullable|string|max:100|regex:/^[\p{L}\s\-\']+$/u',
            'nazione' => 'nullable|string|max:100|regex:/^[\p{L}\s]+$/u',
            'telefono1' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'telefono2' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'fax' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'cellulare1' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'cellulare2' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'email' => 'nullable|email:rfc,dns|max:255',
            'sito_web' => 'nullable|url|max:255|regex:/^https?:\/\/.+$/',
            'partita_iva' => 'nullable|string|max:20|regex:/^[A-Z]{2}[0-9]{11}$/',
            'codice_attivita_iva' => 'nullable|string|max:20|regex:/^[0-9]{6}\.[0-9]{2}$/',
            'regime_fiscale' => 'nullable|string|max:255',
            'attivita' => 'nullable|string|max:500',
            'numero_tribunale' => 'nullable|string|max:255',
            'cciaa' => 'nullable|string|max:255',
            'capitale_sociale' => 'nullable|numeric|min:0|max:999999999.99',
            'provincia_nascita' => 'nullable|string|max:100|regex:/^[\p{L}\s\-]+$/u',
            'luogo_nascita' => 'nullable|string|max:100|regex:/^[\p{L}\s\-\']+$/u',
            'data_nascita' => 'nullable|date|before:today',
            'iva_esente' => 'boolean',
            'sdi_username' => 'nullable|string|max:255',
            'sdi_password' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:max_width=1000,max_height=1000'
        ]);

        $company = CompanyProfile::first() ?? new CompanyProfile();

        // Cripta dati sensibili SDI prima del salvataggio
        if (isset($validated['sdi_username'])) {
            $validated['sdi_username'] = Crypt::encryptString($validated['sdi_username']);
        }
        if (isset($validated['sdi_password'])) {
            $validated['sdi_password'] = Hash::make($validated['sdi_password']);
        }

        // Gestione sicura upload logo
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            
            // Validazioni aggiuntive per sicurezza
            $mimeType = $logo->getMimeType();
            if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])) {
                return back()->withErrors(['logo' => 'Formato file non valido.']);
            }

            // Elimina il vecchio logo se esiste
            if ($company->logo_path && Storage::disk('public')->exists($company->logo_path)) {
                Storage::disk('public')->delete($company->logo_path);
            }
            
            // Nome file sicuro
            $filename = 'logo_' . time() . '_' . Str::random(10) . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('company/logos', $filename, 'public');
            $validated['logo_path'] = $logoPath;
        }

        $company->fill($validated);
        $company->save();

        // Log operazione per audit
        Log::info('Profilo aziendale aggiornato', [
            'user_id' => Auth::id(),
            'company_id' => $company->id,
            'ip' => $request->ip()
        ]);

        // Invalida cache
        Cache::forget('company_profile');

        return redirect()->route('configurations.company-profile')
            ->with('success', 'Profilo aziendale aggiornato con successo!');
    }

    // === COORDINATE BANCARIE ===
    public function bankAccounts()
    {
        $accounts = BankAccount::where('active', true)->get();
        return view('configurations.bank-accounts', compact('accounts'));
    }

    public function storeBankAccount(Request $request)
    {
        // Rate limiting per creazione conti bancari
        $rateLimiterKey = 'create-bank-account:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 10)) {
            return back()->withErrors(['error' => 'Troppe creazioni. Attendi prima di aggiungere altri conti.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600); // 10 tentativi per ora

        $validated = $request->validate([
            'nome_banca' => 'required|string|max:255|regex:/^[\p{L}\p{N}\s\-\.\&]+$/u',
            'abi' => 'nullable|string|size:5|regex:/^[0-9]{5}$/',
            'cab' => 'nullable|string|size:5|regex:/^[0-9]{5}$/',
            'conto_corrente' => 'required|string|max:50|regex:/^[0-9]+$/',
            'iban' => ['required', 'string', 'size:27', 'regex:/^IT[0-9]{2}[A-Z][0-9]{5}[0-9]{5}[A-Z0-9]{12}$/'],
            'swift' => 'nullable|string|size:11|regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/',
            'sia' => 'nullable|string|size:5|regex:/^[A-Z0-9]{5}$/'
        ]);

        // Verifica univocità IBAN
        $existingIban = BankAccount::where('iban', $validated['iban'])
            ->where('active', true)
            ->first();
        
        if ($existingIban) {
            return back()->withErrors(['iban' => 'IBAN già esistente nel sistema.']);
        }

        // Cripta dati sensibili
        $validated['uuid'] = (string) Str::uuid();
        $validated['iban'] = Crypt::encryptString($validated['iban']);
        if ($validated['swift']) {
            $validated['swift'] = Crypt::encryptString($validated['swift']);
        }
        
        $bankAccount = BankAccount::create($validated);
        
        Log::info('Nuovo conto bancario creato', [
            'user_id' => Auth::id(),
            'bank_account_uuid' => $bankAccount->uuid,
            'bank_name' => $validated['nome_banca'],
            'ip' => $request->ip()
        ]);

        return redirect()->route('configurations.bank-accounts')
            ->with('success', 'Conto bancario aggiunto con successo!');
    }

    public function updateBankAccount(Request $request, $uuid)
    {
        // Validazione UUID format
        if (!Str::isUuid($uuid)) {
            abort(404, 'Conto bancario non trovato.');
        }

        $account = BankAccount::where('uuid', $uuid)->where('active', true)->firstOrFail();
        
        // Rate limiting per aggiornamenti
        $rateLimiterKey = 'update-bank-account:' . Auth::id() . ':' . $uuid;
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 5)) {
            return back()->withErrors(['error' => 'Troppi aggiornamenti. Attendi prima di modificare nuovamente.']);
        }
        RateLimiter::hit($rateLimiterKey, 900); // 5 tentativi per 15 minuti

        $validated = $request->validate([
            'nome_banca' => 'required|string|max:255|regex:/^[\p{L}\p{N}\s\-\.\&]+$/u',
            'abi' => 'nullable|string|size:5|regex:/^[0-9]{5}$/',
            'cab' => 'nullable|string|size:5|regex:/^[0-9]{5}$/',
            'conto_corrente' => 'required|string|max:50|regex:/^[0-9]+$/',
            'iban' => [
                'required', 'string', 'size:27', 
                'regex:/^IT[0-9]{2}[A-Z][0-9]{5}[0-9]{5}[A-Z0-9]{12}$/',
                Rule::unique('bank_accounts')->where(function ($query) {
                    return $query->where('active', true);
                })->ignore($account->id)
            ],
            'swift' => 'nullable|string|size:11|regex:/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/',
            'sia' => 'nullable|string|size:5|regex:/^[A-Z0-9]{5}$/'
        ]);

        // Cripta dati sensibili
        $validated['iban'] = Crypt::encryptString($validated['iban']);
        if ($validated['swift']) {
            $validated['swift'] = Crypt::encryptString($validated['swift']);
        }

        $account->update($validated);
        
        Log::info('Conto bancario aggiornato', [
            'user_id' => Auth::id(),
            'bank_account_uuid' => $uuid,
            'bank_name' => $validated['nome_banca'],
            'ip' => $request->ip()
        ]);

        return redirect()->route('configurations.bank-accounts')
            ->with('success', 'Conto bancario aggiornato con successo!');
    }

    public function deleteBankAccount($uuid)
    {
        // Validazione UUID format
        if (!Str::isUuid($uuid)) {
            abort(404, 'Conto bancario non trovato.');
        }

        $account = BankAccount::where('uuid', $uuid)->where('active', true)->firstOrFail();
        
        // Rate limiting per eliminazioni
        $rateLimiterKey = 'delete-bank-account:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 3)) {
            return back()->withErrors(['error' => 'Troppe eliminazioni. Attendi prima di eliminare altri conti.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600); // 3 tentativi per ora

        // Soft delete per audit trail
        $account->update([
            'active' => false,
            'deleted_at' => now(),
            'deleted_by' => Auth::id()
        ]);
        
        Log::warning('Conto bancario eliminato', [
            'user_id' => Auth::id(),
            'bank_account_uuid' => $uuid,
            'bank_name' => $account->nome_banca,
            'ip' => request()->ip()
        ]);

        return redirect()->route('configurations.bank-accounts')
            ->with('success', 'Conto bancario rimosso con successo!');
    }

    // === TABELLE DI SISTEMA ===
    public function systemTables()
    {
        // Cache con TTL di 15 minuti per performance
        $cacheKey = 'system_tables_data';
        $data = Cache::remember($cacheKey, 900, function () {
            return [
                'taxRates' => TaxRate::where('active', true)
                    ->select(['id', 'code', 'description', 'rate', 'created_at'])
                    ->orderBy('code')
                    ->get(),
                'paymentMethods' => PaymentMethod::where('active', true)
                    ->select(['id', 'code', 'description', 'type', 'created_at'])
                    ->orderBy('code')
                    ->get(),
                'currencies' => Currency::where('active', true)
                    ->select(['id', 'code', 'name', 'symbol', 'exchange_rate', 'created_at'])
                    ->orderBy('code')
                    ->get()
            ];
        });

        return view('configurations.system-tables', $data);
    }

    // Aliquote IVA
    public function storeTaxRate(Request $request)
    {
        $rateLimiterKey = 'store-tax-rate:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 20)) {
            return back()->withErrors(['error' => 'Troppe creazioni. Attendi prima di aggiungere altre aliquote.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600); // 20 tentativi per ora

        $validated = $request->validate([
            'code' => 'required|string|max:10|regex:/^[A-Z0-9_-]+$/|unique:tax_rates,code,NULL,id,active,1',
            'description' => 'required|string|max:255|regex:/^[\p{L}\p{N}\s\-\.\%\(\)]+$/u',
            'rate' => 'required|numeric|min:0|max:100|regex:/^\d{1,2}(\.\d{1,2})?$/'
        ]);

        $taxRate = TaxRate::create($validated);
        
        Log::info('Nuova aliquota IVA creata', [
            'user_id' => Auth::id(),
            'tax_rate_id' => $taxRate->id,
            'code' => $validated['code'],
            'rate' => $validated['rate'],
            'ip' => $request->ip()
        ]);

        // Invalida cache
        Cache::forget('system_tables_data');

        return redirect()->route('configurations.system-tables')
            ->with('success', 'Aliquota IVA aggiunta con successo!');
    }

    // Metodi di pagamento
    public function storePaymentMethod(Request $request)
    {
        $rateLimiterKey = 'store-payment-method:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 20)) {
            return back()->withErrors(['error' => 'Troppe creazioni. Attendi prima di aggiungere altri metodi.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600);

        $validated = $request->validate([
            'code' => 'required|string|max:10|regex:/^[A-Z0-9_-]+$/|unique:payment_methods,code,NULL,id,active,1',
            'description' => 'required|string|max:255|regex:/^[\p{L}\p{N}\s\-\.\(\)]+$/u',
            'type' => 'nullable|string|max:50|in:contanti,carta,bonifico,assegno,riba,rid'
        ]);

        $paymentMethod = PaymentMethod::create($validated);
        
        Log::info('Nuovo metodo di pagamento creato', [
            'user_id' => Auth::id(),
            'payment_method_id' => $paymentMethod->id,
            'code' => $validated['code'],
            'type' => $validated['type'] ?? 'generico',
            'ip' => $request->ip()
        ]);

        Cache::forget('system_tables_data');

        return redirect()->route('configurations.system-tables')
            ->with('success', 'Metodo di pagamento aggiunto con successo!');
    }

    // Valute
    public function storeCurrency(Request $request)
    {
        $rateLimiterKey = 'store-currency:' . Auth::id();
        if (RateLimiter::tooManyAttempts($rateLimiterKey, 10)) {
            return back()->withErrors(['error' => 'Troppe creazioni. Attendi prima di aggiungere altre valute.']);
        }
        RateLimiter::hit($rateLimiterKey, 3600);

        $validated = $request->validate([
            'code' => 'required|string|size:3|regex:/^[A-Z]{3}$/|unique:currencies,code,NULL,id,active,1',
            'name' => 'required|string|max:100|regex:/^[\p{L}\s]+$/u',
            'symbol' => 'required|string|max:5',
            'exchange_rate' => 'required|numeric|min:0.000001|max:999999.999999|regex:/^\d{1,6}(\.\d{1,6})?$/'
        ]);

        // Validazione ISO 4217 per codici valuta comuni
        $validCurrencyCodes = ['EUR', 'USD', 'GBP', 'JPY', 'CHF', 'CAD', 'AUD', 'CNY', 'SEK', 'NOK', 'DKK'];
        if (!in_array($validated['code'], $validCurrencyCodes)) {
            return back()->withErrors(['code' => 'Codice valuta non riconosciuto ISO 4217.']);
        }

        $currency = Currency::create($validated);
        
        Log::info('Nuova valuta creata', [
            'user_id' => Auth::id(),
            'currency_id' => $currency->id,
            'code' => $validated['code'],
            'exchange_rate' => $validated['exchange_rate'],
            'ip' => $request->ip()
        ]);

        Cache::forget('system_tables_data');

        return redirect()->route('configurations.system-tables')
            ->with('success', 'Valuta aggiunta con successo!');
    }

    // === IMPOSTAZIONI ===
    public function settings()
    {
        $numbering = DocumentNumbering::all()->keyBy('document_type');
        $settings = SystemSetting::all()->keyBy('key');

        return view('configurations.settings', compact('numbering', 'settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'numbering' => 'nullable|array',
            'settings' => 'nullable|array'
        ]);

        // Aggiorna numeratori
        if (isset($validated['numbering'])) {
            foreach ($validated['numbering'] as $docType => $data) {
                DocumentNumbering::updateOrCreate(
                    ['document_type' => $docType],
                    [
                        'current_number' => $data['current_number'] ?? 1,
                        'prefix' => $data['prefix'] ?? null,
                        'suffix' => $data['suffix'] ?? null,
                        'use_year' => isset($data['use_year']),
                        'use_month' => isset($data['use_month']),
                        'separator' => $data['separator'] ?? '/',
                        'reset_frequency' => $data['reset_frequency'] ?? 1
                    ]
                );
            }
        }

        // Aggiorna impostazioni generali
        if (isset($validated['settings'])) {
            foreach ($validated['settings'] as $key => $value) {
                SystemSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        }

        return redirect()->route('configurations.settings')
            ->with('success', 'Impostazioni aggiornate con successo!');
    }
}