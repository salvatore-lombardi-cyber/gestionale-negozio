# Documentazione Modulo Configurazioni

## Panoramica
Il modulo configurazioni è il sistema di amministrazione centrale del gestionale che permette di configurare tutti gli aspetti del sistema. È accessibile tramite l'icona dell'ingranaggio nella sidebar e contiene quattro sezioni principali.

## Struttura del Modulo

### 1. Profilo Utente (`profiloUtente.php`)

**Scopo**: Gestione del profilo aziendale e dei dati dell'utente principale del sistema.

**Funzionalità principali**:
- **Dati anagrafici azienda**: ragione sociale, cognome, nome, genere
- **Indirizzo sede legale**: indirizzo, CAP, provincia, città, nazione
- **Contatti**: telefoni (2), fax, cellulari (2), email, sito web
- **Dati fiscali**: P.IVA, codice attività IVA, regime fiscale, attività
- **Dati legali**: numero iscrizione tribunale, C.C.I.A.A., capitale sociale
- **Persona fisica**: provincia/luogo di nascita, data nascita, IVA esente
- **Fatturazione elettronica**: credenziali B2B per invio SDI
- **Upload immagine profilo** con preview

**Tabelle coinvolte**:
- `anagraficaazienda` - dati principali
- `uploadedimgazienda` - immagini profilo
- `province`, `comuni` - dati geografici
- `regimifiscali`, `naturaiva` - dati fiscali

**Implementazione tecnica**:
- Form responsive con validazione JavaScript
- Integrazione con API geografiche per province/comuni
- Upload file con preview immagini
- Salvataggio AJAX con sanitizzazione input

---

### 2. Profilo Banche (`profiloBanche.php`)

**Scopo**: Gestione delle coordinate bancarie aziendali per pagamenti e fatturazione.

**Funzionalità principali**:
- **Visualizzazione banche esistenti**: elenco readonly delle banche configurate
- **Gestione CRUD**: modifica ed eliminazione banche esistenti
- **Aggiunta nuove banche**: form per inserimento nuove coordinate
- **Dati bancari completi**: Nome banca, ABI, CAB, C/C, Swift, SIA, IBAN

**Tabelle coinvolte**:
- `banche` - coordinate bancarie

**Validazioni**:
- Nome banca obbligatorio
- Conto corrente obbligatorio  
- IBAN obbligatorio
- Codici ABI/CAB numerici

**Implementazione tecnica**:
- UUID per identificazione univoca record
- Form dinamico con validazione client-side
- Funzioni JavaScript per CRUD operations
- Reset automatico form dopo inserimento

---

### 3. Gestione Tabelle (`gestioneTabelle.php`)

**Scopo**: Configurazione di tutte le tabelle di sistema e dei dati di riferimento utilizzati dal gestionale.

**Funzionalità principali**:

#### Associazioni Dinamiche (Sezione Speciale)
- **Associazioni Nature IVA**: Configurazione avanzata delle nature IVA
- Sistema di associazioni dinamiche con interfaccia grafica avanzata

#### Tabelle Standard (24 tipologie):
1. **Aliquote IVA** - Percentuali IVA utilizzate
2. **Aspetto dei beni** - Caratteristiche fisiche merci
3. **Banche** - Istituti bancari di riferimento
4. **Categorie articoli** - Classificazione prodotti
5. **Categorie clienti** - Tipologie clientela
6. **Categorie fornitori** - Classificazione fornitori
7. **Taglie e colori** - Varianti prodotto base
8. **Causali magazzino** - Motivi movimenti stock
9. **Colori varianti** - Colori specifici varianti
10. **Condizioni** - Termini commerciali
11. **Denominazione prezzi fissi** - Listini predefiniti
12. **Depositi** - Ubicazioni fisiche magazzino
13. **Listini** - Listini prezzi
14. **Modalità pagamento** - Metodi pagamento
15. **Natura IVA** - Codici esenzioni/esclusioni IVA
16. **Porto** - Condizioni trasporto
17. **Settori merceologici** - Ambiti commerciali
18. **Taglie varianti** - Taglie specifiche
19. **Tipi taglie** - Sistemi taglie (EU, US, etc.)
20. **Tipi pagamento** - Categorizzazione pagamenti  
21. **Trasporto** - Modalità trasporto
22. **Trasporto a mezzo** - Mezzi di trasporto
23. **Ubicazioni** - Posizioni specifiche deposito
24. **Unità di misura** - UM per prodotti
25. **Valute** - Valute supportate
26. **Zone** - Zone geografiche/commerciali

**Implementazione tecnica**:
- Interfaccia a griglia con icone SVG personalizzate
- Sistema di caricamento dinamico tabelle
- Editor inline per modifica rapida
- Esportazione/importazione dati

---

### 4. Impostazioni (`impostazioni.php`)

**Scopo**: Configurazioni avanzate di sistema, numeratori, stampe e importazioni.

**Sezioni principali**:

#### A. Configurazione Stampe
- **Associazione template**: Collegamento documenti-template stampa
- **Gestione moduli stampa**: Configurazione layout stampe
- **Template personalizzati**: Upload e gestione template

#### B. Configurazione Numeratori
- **Numeratori documento**: DDT, Fatture, Preventivi, Ordini, Note di Credito
- **Personalizzazione formato numero**:
  - Valore di partenza
  - Uso mese corrente (MM)
  - Uso anno corrente (YYYY)
  - Stringa personalizzata
  - Separatori personalizzati
  - Anteprima in tempo reale

#### C. Importazione Dati
- **Import da file esterni**: Excel, CSV
- **Mappatura campi**: Associazione colonne file ↔ campi DB
- **Validazione dati**: Controllo coerenza pre-import
- **Log operazioni**: Tracciamento import

#### D. Gestione Utenze (se abilitato multi-user)
- **Creazione sub-utenti**: Account secondari
- **Gestione permessi**: Controllo accessi funzionalità
- **Reset password**: Strumenti amministratore
- **Lock/unlock account**: Controllo accessi

**File coinvolti**:
- `impostazioni/assStampe.php` - Gestione stampe
- `impostazioni/numeratori/*.php` - Configuratori numeratori
- `impostazioni/importDati.php` - Import manager
- `impostazioni/gestioneUtenze.php` - User management

---

## Implementazione in Altro Gestionale

### Architettura Raccomandata

#### 1. Struttura Database
```sql
-- Tabella profilo aziendale
CREATE TABLE company_profile (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_name VARCHAR(255),
    address TEXT,
    city VARCHAR(100),
    province VARCHAR(100),
    postal_code VARCHAR(10),
    country VARCHAR(100),
    phone1 VARCHAR(20),
    phone2 VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(100),
    vat_number VARCHAR(20),
    tax_code VARCHAR(20),
    -- altri campi...
);

-- Tabella coordinate bancarie
CREATE TABLE bank_accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    uuid VARCHAR(36) UNIQUE,
    bank_name VARCHAR(255) NOT NULL,
    abi VARCHAR(10),
    cab VARCHAR(10),
    account_number VARCHAR(50) NOT NULL,
    iban VARCHAR(34) NOT NULL,
    swift VARCHAR(11),
    -- altri campi...
);

-- Tabelle di configurazione (una per tipo)
CREATE TABLE tax_rates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(10),
    description VARCHAR(255),
    rate DECIMAL(5,2),
    active BOOLEAN DEFAULT TRUE
);

-- Template per altre tabelle di sistema
CREATE TABLE system_tables (
    table_name VARCHAR(50),
    id INT,
    code VARCHAR(20),
    description VARCHAR(255),
    active BOOLEAN DEFAULT TRUE,
    sort_order INT,
    PRIMARY KEY (table_name, id)
);
```

#### 2. Struttura Frontend

```php
// Controller principale configurazioni
class ConfigurationController {
    public function index() {
        // Dashboard configurazioni
    }
    
    public function userProfile() {
        // Gestione profilo utente
    }
    
    public function bankProfile() {
        // Gestione coordinate bancarie
    }
    
    public function systemTables() {
        // Gestione tabelle di sistema
    }
    
    public function settings() {
        // Impostazioni avanzate
    }
}

// Model per profilo aziendale
class CompanyProfile {
    protected $table = 'company_profile';
    
    public function getProfile() {
        // Recupera profilo aziendale
    }
    
    public function updateProfile($data) {
        // Aggiorna profilo con validazione
    }
}

// Model per coordinate bancarie
class BankAccount {
    protected $table = 'bank_accounts';
    
    public function getAll() {
        // Lista tutte le banche
    }
    
    public function create($data) {
        // Crea nuova coordinata bancaria
    }
    
    public function update($uuid, $data) {
        // Aggiorna coordinata esistente
    }
    
    public function delete($uuid) {
        // Elimina coordinata bancaria
    }
}
```

#### 3. Interfaccia Utente

```javascript
// Manager configurazioni client-side
class ConfigurationManager {
    constructor() {
        this.initEventHandlers();
    }
    
    // Gestione profilo utente
    saveUserProfile() {
        const formData = this.validateUserForm();
        if (formData) {
            this.submitUserProfile(formData);
        }
    }
    
    // Gestione coordinate bancarie
    addBankAccount() {
        const bankData = this.validateBankForm();
        if (bankData) {
            this.submitBankAccount(bankData);
        }
    }
    
    // Gestione tabelle di sistema
    loadSystemTable(tableName) {
        // Carica tabella specifica
        this.loadTable(tableName);
    }
    
    // Validazioni client-side
    validateUserForm() {
        // Validazione campi profilo utente
    }
    
    validateBankForm() {
        // Validazione IBAN, codici bancari
    }
}
```

#### 4. Sicurezza e Validazioni

```php
// Classe validazione dati
class ConfigurationValidator {
    public function validateCompanyProfile($data) {
        $rules = [
            'company_name' => 'required|max:255',
            'email' => 'email|nullable',
            'vat_number' => 'regex:/^[0-9]{11}$/',
            'iban' => 'iban'
        ];
        
        return $this->validate($data, $rules);
    }
    
    public function validateBankAccount($data) {
        $rules = [
            'bank_name' => 'required|max:255',
            'iban' => 'required|iban',
            'account_number' => 'required|max:50'
        ];
        
        return $this->validate($data, $rules);
    }
}

// Middleware per controllo permessi
class ConfigurationMiddleware {
    public function handle($request) {
        if (!$this->canAccessConfiguration($request->user())) {
            return redirect()->route('unauthorized');
        }
    }
}
```

### Considerazioni di Implementazione

#### Modularità
- Separare ogni sezione in moduli indipendenti
- Utilizzare pattern MVC per organizzazione codice
- Implementare API REST per operazioni CRUD

#### Personalizzazione  
- Sistema di configurazione flessibile per adattare campi
- Template engine per personalizzazione interfacce
- Hook system per estensioni future

#### Performance
- Caching configurazioni frequentemente utilizzate
- Lazy loading per tabelle di sistema
- Ottimizzazione query database

#### Audit Trail
- Log di tutte le modifiche configurazioni
- Tracciamento utente e timestamp
- Backup automatico prima modifiche critiche

Questa documentazione fornisce una guida completa per comprendere e replicare il modulo configurazioni in altri gestionali, mantenendo le funzionalità principali e adattandole alle specifiche architetture.