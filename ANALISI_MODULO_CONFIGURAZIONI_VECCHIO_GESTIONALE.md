# ANALISI MODULO CONFIGURAZIONI - REPLICA VECCHIO GESTIONALE

## 1. PANORAMICA GENERALE

### Scopo Generale
Il modulo Configurazioni è il centro nevralgico del gestionale, fungendo da hub centrale per tutte le impostazioni che determinano il comportamento del sistema. Permette la personalizzazione completa del gestionale in base alle specifiche esigenze aziendali.

### Struttura delle 5 Sezioni

1. **Profilo Utente (profiloUtente.php)**
   - Ruolo: Gestisce l'identità aziendale e le configurazioni fiscali
   - Funzioni: Dati anagrafici, regime fiscale, codici IVA, credenziali fatturazione elettronica
   - Importanza: Base per tutti i documenti generati dal sistema

2. **Profilo Banche (profiloBanche.php)**
   - Ruolo: Centralizza la gestione delle coordinate bancarie
   - Funzioni: Gestione conti multipli, IBAN, codici ABI/CAB, SWIFT
   - Importanza: Essenziale per bonifici e riconciliazioni

3. **Gestione Tabelle (gestioneTabelle.php)** ✅ **COMPLETATA**
   - Ruolo: Amministra oltre 25 tabelle di sistema configurabili
   - Funzioni: Aliquote IVA, categorie, modalità pagamento, listini, causali magazzino
   - Importanza: Fornisce i dati di base per tutte le operazioni

4. **Moduli di Stampa (sezione di impostazioni.php)**
   - Ruolo: Configura output documentale e numerazioni
   - Funzioni: Layout stampe, numeratori automatici, associazioni dinamiche
   - Importanza: Determina l'aspetto e numerazione dei documenti

5. **Impostazioni Sistema (impostazioni.php)**
   - Ruolo: Configurazioni avanzate e import/export
   - Funzioni: Numeratori, importazione dati, utenze multiple
   - Importanza: Personalizzazione operativa del gestionale

### Flusso Operativo Logico
**Ordine di configurazione consigliato:**
1. Profilo Utente (primo step obbligatorio)
2. Gestione Tabelle (dati base necessari) ✅
3. Profilo Banche (coordinate finanziarie)
4. Moduli di Stampa (output documentale)
5. Impostazioni Sistema (configurazioni avanzate)

**Dipendenze principali:**
- Il Profilo Utente deve essere configurato prima di qualsiasi documento
- Le Tabelle base devono esistere prima di Stampe e Numeratori
- Le Banche sono indipendenti ma necessarie per fatturazione

### Permessi e Accessi
- **Session-based**: Controllo tramite $_SESSION['menuList']
- **Permessi modulari**: Verifica per ID modulo specifico
- **Flag Admin**: $_SESSION['admin'] per funzioni avanzate
- **Gestione Subusers**: Sistema per utenti multipli con permessi differenziati

### Integrazione con il Gestionale
**Integrazione database:**
- Utilizza RedBeanPHP ORM per gestione dati
- Foreign keys con anagrafiche, documenti e magazzino
- Tabelle temporanee per menu dinamici

**Dipendenze funzionali:**
- **Fatturazione**: Regime fiscale, aliquote IVA, coordinate bancarie
- **Magazzino**: Categorie, causali, unità di misura
- **Anagrafiche**: Categorie clienti/fornitori, zone, modalità pagamento
- **Documenti**: Numeratori, layout stampe, dati aziendali

---

## 2. SEZIONE UTENTE

### 2.1 DATI AZIENDALI

**Campi Principali** (tutti opzionali, no validazioni obbligatorie):
- **Ragione Sociale** (`ragione_sociale`) - **READONLY** - Campo bloccato, non modificabile
- **Cognome** (`cognome`) - Input text standard
- **Nome** (`nome`) - Input text standard
- **Genere** (`genere`) - Radio buttons M/F, default "M"
- **Indirizzo Sede Legale** (`indirizzo`) - Input text
- **CAP** (`cap`) - Input text
- **Provincia** (`provincia`) - Select dinamica collegata a tabella `province`
- **Città** (`citta`) - Select dinamica collegata a tabella `comuni`
- **Nazione** (`nazione`) - Input text

**Contatti:**
- **Telefono 1/2** (`telefono1`, `telefono2`) - Input text
- **Telefax** (`telefax`) - Input text
- **Cellulare 1/2** (`cellulare1`, `cellulare2`) - Input text
- **E-Mail** (`email`) - Input email con validazione HTML5
- **WWW** (`www`) - Input url con validazione HTML5

**Validazioni:**
- Nessun campo obbligatorio
- Validazione frontend: `verificaForm('userProfile')` cerca attributi `data-required`
- Sanitizzazione: `sanitizeInputs()` prima del salvataggio

### 2.2 CONFIGURAZIONI FISCALI

**Campi Fiscali Principali:**
- **Regime Fiscale** (`regime_fiscale`) - Select popolata da tabella `regimifiscali`
- **P.IVA** (`piva`) - Input text, no validazione formato
- **Cod. Attività IVA** (`cod_attivita`) - Select da tabella `naturaiva`
- **Attività** (`attivita`) - Input text descrittivo

**Checkbox Fiscali:**
- **Contributo CONAI** (`conai`) - Toggle switch
- **Azienda IVA Esente** (`iva_esente`) - Toggle switch

**Campi Legali:**
- **Iscrizione Tribunale** (`iscrizione_tribunale`) - Input text
- **Tribunale di** (`tribunale`) - Input text
- **C.C.I.A.A.** (`cciaa`) - Input text
- **Capitale Sociale** (`capitale_sociale`) - Input text

### 2.3 SEZIONE PERSONA FISICA (Condizionale)

Raggruppata in container specifico:
- **Provincia di nascita** (`provincia_nascita`) - Select da `province`
- **Luogo di nascita** (`luogo_nascita`) - Select da `comuni`
- **Data di nascita** (`data_nascita`) - Input text

### 2.4 FATTURAZIONE ELETTRONICA

**Visibilità Condizionale:** Solo se `in_array(5,json_decode($_SESSION['menuList']))`

**Credenziali SDI:**
- **Username B2B** (`b2b_username`) - Credenziali SDI
- **Password B2B** (`b2b_password`) - Input password per SDI
- **Nota esplicativa** sui servizi B2B per fatturazione elettronica

### 2.5 AVATAR/FOTO UTENTE

**Funzionalità Upload:**
- **Input file:** `accept="image/png, image/jpeg"`
- **Preview live:** `imgProfilePreviews()` mostra anteprima immediata
- **Storage:** Directory `/userimgs/{session_id}/`
- **Dimensioni:** 24x24px rounded, hover espande a 96px width

**Utilizzo Avatar:**
- **Header profilo:** Appare anche in `#imgProfileHeader`
- **Default:** `generic_user.png` se non caricato
- **Database:** Tabella `uploadedimgazienda` con hash name

**Formati supportati:**
- **File:** PNG, JPEG
- **Estensioni:** jpg, jpeg, png, gif, pdf, doc, docx, xlsx, xls, pptx, ppt
- **Dimensione max:** 10MB

**Scopo Avatar:**
- Personalizzazione interfaccia utente
- Identificazione visiva nei documenti
- Branding aziendale nell'header

### 2.6 ASPETTI TECNICI

**Salvataggio Database:**
- **Tabella principale:** `anagraficaazienda` (RedBeanPHP ORM)
- **Endpoint:** `/saver/profiloUtenteSaver.php`
- **Metodo:** AJAX POST con FormData per file upload

**Gestione Upload:**
- **File handler:** `FileUploadHandler` class
- **UUID v4:** Generazione nomi file sicuri
- **Directory auto-create:** `userimgs/{session_id}/`

**Controlli Sicurezza:**
- **Session-based:** `$_SESSION['finsonId']` per isolamento tenant
- **Error logging:** `Logger::logEvent()` per debug
- **Sanitizzazione:** `sanitizeInputs()` prima del salvataggio

### 2.7 LAYOUT E ORGANIZZAZIONE

**Struttura Grid:**
- **Container:** `grid-cols-1 md:grid-cols-3` responsive
- **Avatar:** `col-span-3` full width con effetto hover
- **Campi:** Disposizione a 3 colonne su desktop

**Sezioni Logiche:**
1. **Avatar + Dati base**
2. **Fiscali + CONAI**
3. **Codici IVA + Attività**
4. **Persona Fisica** - Container separato
5. **B2B Fatturazione** - Condizionale
6. **Controlli salvataggio**

**Dipendenze Dinamiche:**
- **Provincia → Comuni:** `selectComs(this, 'citta')`
- **Provincia nascita → Comuni:** `selectComs(this, 1)`
- **Permessi B2B:** Visibilità condizionale modulo 5

**Stack Tecnologico:**
- **CSS:** Tailwind CSS per styling
- **JS:** jQuery per interazioni
- **ORM:** RedBeanPHP per persistenza dati
- **Upload:** Sistema robusto di upload file con validazioni frontend/backend

## 3. SEZIONE BANCHE

### 3.1 STRUTTURA DATI

**Gestione Multipla delle Banche:**
- **Sistema multi-banca:** Gestione illimitata di coordinate bancarie
- **Tabella:** `banche` (RedBeanPHP ORM) con identificazione UUID
- **Isolamento tenant:** Per `$_SESSION['finsonId']`

**Struttura Record Tabella `banche`:**
```sql
uuid           VARCHAR (Primary Key)
id_utente      VARCHAR (Foreign Key)
nome_banca     VARCHAR
abi            VARCHAR
cab            VARCHAR
cc             VARCHAR (Conto Corrente)
swift          VARCHAR
sia            VARCHAR
iban           VARCHAR
```

### 3.2 CAMPI SPECIFICI

**Tutti i Campi per Ogni Banca:**
1. **Nome Banca** (`nome_banca`) - Denominazione istituto
2. **ABI** (`abi`) - Codice Associazione Bancaria Italiana (5 cifre)
3. **CAB** (`cab`) - Codice di Avviamento Bancario (5 cifre)
4. **C/C** (`cc`) - Numero Conto Corrente
5. **SWIFT** (`swift`) - Codice BIC per trasferimenti internazionali
6. **SIA** (`sia`) - Codice Sistema Interbancario
7. **IBAN** (`iban`) - International Bank Account Number

**Formati e Validazioni:**

**Campi Obbligatori** (con `data-required=true`):
- **Nome Banca** ⚠️ **ERRORE:** valore precompilato con ragione sociale
- **C/C** (Numero Conto Corrente)
- **IBAN** (International Bank Account Number)

**Campi Opzionali:**
- ABI, CAB, SWIFT, SIA - **Nessuna validazione di formato implementata**

**Validazione Frontend:**
- **Funzione:** `verificaForm('newBanksProfile')`
- **Controllo:** Solo presenza valori per campi `data-required`
- **Sanitizzazione:** `sanitizeInputs()` prima del salvataggio

### 3.3 FUNZIONALITÀ

**Operazioni CRUD:**

**Visualizzazione:**
- **Lista readonly:** Tutti i record esistenti in griglia
- **Layout:** 8 colonne responsive (`grid-cols-2 md:grid-cols-8`)
- **ID dinamici:** Container con UUID per identificazione

**Aggiunta:**
- **Form separato:** Sezione dedicata per nuovi inserimenti
- **Button Reset:** `resetValues('newBanksProfile')`
- **Button Save:** `sanitizeInputs(saveBankProfile)`

**Modifica:**
- **Funzione:** `modificaBanche(idbanca)`
- **Meccanismo:** Popola form inserimento con dati esistenti
- **Identificazione:** UUID passato come parametro

**Eliminazione:**
- **Funzione:** `eliminaBanche(idbanca)`
- **⚠️ PROBLEMA:** Nessuna conferma utente - Eliminazione diretta
- **Effetto:** Transizione fade-out con classe CSS

**Banca Principale/Predefinita:**
- **❌ MANCANTE:** Nessun sistema di banca predefinita implementato
- Tutte le banche hanno stesso peso gerarchico
- Selezione manuale nelle altre sezioni del gestionale

### 3.4 ASPETTI TECNICI

**Database e Persistenza:**
- **Tabella:** `banche` (RedBeanPHP)
- **Chiave primaria:** `uuid` (UUID v4 generato via `CommonFunc::generateUuidV4()`)
- **Foreign key:** `id_utente` -> `$_SESSION['finsonId']`
- **Endpoint:** `/saver/profiloBancheSaver.php`

**Validazioni e Controlli:**

**Frontend Validation:**
- **Campi obbligatori:** Nome banca, C/C, IBAN
- **Autocomplete:** `off` per sicurezza
- **Bordi rossi:** Evidenziazione errori via `verificaForm()`

**Backend Validation:**
- **Session check:** Isolamento tenant
- **UUID validation:** Controllo esistenza record
- **Error logging:** `Logger::logEvent()` per debug

**Operazioni Supportate:**
1. **INSERT:** Nuovo UUID se `bankId` è default (`550c8e8e-8e78-42e6-8308-5c4ce2f4365c`)
2. **UPDATE:** Modifica record esistente via UUID
3. **DELETE:** `nome_banca = 'delete'`

### 3.5 LAYOUT DEL FORM

**Sezione Visualizzazione:**
- **Grid responsive:** 2 colonne mobile, 8 desktop
- **Campi readonly:** Tutti i dati non modificabili inline
- **Azioni:** Icone emoji 📝🗑️ per modifica/elimina

**Sezione Inserimento:**
- **Container separato:** `newBanksProfile`
- **Grid 9 colonne:** Include colonne per reset/save
- **Hidden fields:** `userId`, `bankId` per identificazione

### 3.6 INTEGRAZIONE CON GESTIONALE

**Utilizzo in Altri Moduli:**

**Anagrafiche Cliente/Fornitore:**
- **Tabella differente:** `banchetab` per elenco banche standard
- **Selezione:** Dropdown con banche predefinite di sistema
- **Campi aggiuntivi:** BIC, coordinate specifiche per soggetto

**Documenti e Fatturazione:**
- **Bonifici:** Coordinate bancarie aziendali per ricevimenti
- **Fatturazione elettronica:** IBAN per pagamenti automatici
- **Stampe:** Coordinate in documenti come DDT/Fatture

**Gestione Tabelle:**
- **Referenza:** `gestioneTabelle.php` carica `$profiloBanche = R::find('banche')`
- **Configurazione:** Integrazione con sistema tabelle

### 3.7 PROBLEMI IDENTIFICATI

1. **❌ Errore Form:** Nome banca precompilato con `$profilo->ragione_sociale` invece di campo vuoto
2. **❌ Mancanza Conferme:** Eliminazione diretta senza dialogo conferma
3. **❌ Validazioni Incomplete:** Nessuna validazione formato IBAN, ABI, CAB
4. **❌ Banca Predefinita:** Sistema mancante per identificare banca principale
5. **❌ Duplicazione Concetti:** Due sistemi distinti (`banche` vs `banchetab`) per gestione coordinate bancarie

**Note:** Il modulo gestisce efficacemente multiple coordinate bancarie ma presenta lacune in validazioni e UX che potrebbero essere migliorate.

## 4. SEZIONE MODULI DI STAMPA

### 4.1 TIPOLOGIE DI DOCUMENTI

**Documenti Configurabili** (basati su `$_SESSION['menuList']`):

**Abilitati condizionalmente:**
- **Preventivi** (modulo 3) - Codice: `PREV`, `PREV1-5`
- **DDT** (modulo 1) - Codici: `DDTCL`, `DDTFR`
- **Fatture** (modulo 5) - Codici: `FATTI`, `FATTP`
- **Note di Credito** (modulo 5) - Codice: `NDCR`
- **Ordini** (modulo 6) - Sistema numeratori disponibile

**Altri documenti supportati:**
- **Anagrafiche:** `ANAGCL`, `ANAGFR`, `ANAGAR`, `ANAGVE`, `ANAGSV`, `ANAGAG`
- **Stato Giacenze:** `STGIAC`

**Organizzazione Layout:**
- **Tabella:** `tipidocumenti` con campi `id`, `codice`, `tipo_documento`
- **Identificazione:** Codici univoci per ogni tipologia documento
- **Query specifiche:** File dedicati in `/documenti/querylist/` per ogni tipo

### 4.2 NUMERATORI AUTOMATICI

**Sistema Numerazione - Componenti Configurabili:**

1. **Valore di partenza** (`progr`) - Numero iniziale sequenziale
2. **Mese corrente** - Toggle per includere mese (MM)
3. **Anno corrente** - Toggle per includere anno (YYYY)
4. **Stringa personalizzata** - Prefisso/suffisso custom
5. **Separatore** - Carattere divisorio tra componenti
6. **Ordinamento trascinabile** - Sequenza personalizzabile (Sortable.js)

**Formato Numerazione:**
```javascript
// Template tokens:
{{p}} = Valore progressivo
{{m}} = Mese corrente
{{a}} = Anno corrente
{{s}} = Stringa personalizzata

// Esempio: "FATT/2024/03/001" 
// Ordine: [stringa, anno, mese, progressivo]
// Separatore: "/"
```

**Persistenza:**
- **Tabella:** `numeratori` con campi `tiponum`, `progr`, `val`, `{tipo}ord`
- **Campi salvati:**
  - `progr`: Valore progressivo corrente
  - `val`: JSON array `[anno_bool, mese_bool, stringa, separatore]`
  - `{tipo}ord`: JSON array ordinamento componenti `[0,1,2,3]`

**Validazioni:**
- **Campo obbligatorio:** Valore di partenza
- **Bordo rosso:** Evidenziazione errori su campi vuoti
- **Anteprima live:** Aggiornamento real-time durante configurazione

### 4.3 LAYOUT E TEMPLATE

**Sistema Template:**

**Tabella `template`:**
```sql
id           INT (Primary Key)
uuid         VARCHAR (UUID v4)
name         VARCHAR (Nome template)
modify_at    TIMESTAMP (Ultima modifica)
-- Altri campi per contenuto template
```

**Gestione Template:**
- **Visualizzazione:** Lista con nome e data modifica
- **Operazioni:** Modifica 📝, Clona 📋, Elimina 🗑️
- **Editor:** Sistema dedicato in `/reportbuilder/` per creazione/modifica

**Template Predefiniti/Personalizzabili:**
- **Sistema ibrido:** Template base + personalizzazioni complete
- **Report Builder:** Editor visuale per layout documenti
- **Clonazione:** Duplicazione template esistenti per nuove versioni

**Elementi Configurabili:**
- **Header/Footer:** Configurabili tramite editor
- **Logo aziendale:** Integrazione con avatar utente
- **Campi dinamici:** Placeholder per dati documento
- **Layout responsive:** Adattamento automatico formato

### 4.4 ASSOCIAZIONI DINAMICHE

**Meccanismo Associazioni:**

**Query JOIN per visualizzazione:**
```sql
SELECT a.uuid, td.tipo_documento, t.name
FROM associazionestampe a
LEFT JOIN tipidocumenti td ON a.tipo_stampa = td.id
LEFT JOIN template t ON a.template_stampa = t.id
```

**Tabella `associazionestampe`:**
```sql
uuid              VARCHAR (Primary Key)
tipo_stampa       INT (FK -> tipidocumenti.id)
template_stampa   INT (FK -> template.id)
```

**Funzionalità:**
- **Creazione:** Select dinamiche per tipo documento e template
- **Eliminazione:** Rimozione associazioni esistenti
- **Validazione:** Controllo obbligatorietà entrambi i campi

**Collegamento Runtime:**
```php
// Ricerca associazione per tipo documento
$docAss = R::findOne('associazionestampe', 'tipo_stampa = ?', [$docType->id]);

// Caricamento template associato
$template = R::findOne('template', 'id = ?', [$docAss->template_stampa]);

// Fallback per compatibilità (es. FATTP -> FATTI)
```

### 4.5 CONFIGURAZIONI SPECIFICHE

**Parametri per Documento:**

**Numeratori (per ogni tipo):**
- **Progressivo iniziale:** Numero partenza sequenza
- **Pattern personalizzato:** Combinazione mese/anno/stringa
- **Ordinamento dinamico:** Drag & drop dei componenti (Sortable.js)
- **Separatori custom:** Caratteri personalizzabili

**Template (associazioni):**
- **Template unico per tipo:** Un template per tipologia documento
- **Override specifici:** Possibilità template dedicati per sottotipi
- **Fallback automatici:** Sistema di template sostitutivi

**Opzioni Avanzate:**
- **Anteprima real-time:** Visualizzazione formato durante configurazione
- **Validazione input:** Controlli frontend/backend
- **Logging eventi:** Tracciamento modifiche configurazioni

### 4.6 ASPETTI TECNICI

**Persistenza Configurazioni:**

**Tabelle Database:**
1. **`numeratori`** - Configurazioni numerazione automatica
2. **`associazionestampe`** - Collegamenti documento-template
3. **`template`** - Layout stampe personalizzabili
4. **`tipidocumenti`** - Tipologie documento gestite

**File Coinvolti:**
- **Frontend:** `/impostazioni/assStampe.php`, `/impostazioni/numeratori/*.php`
- **Logic:** `/js/impostazioni.js`, `/jsstd/sortable.js`
- **Backend:** `/saver/assocStampe.php`, endpoint numeratori integrato
- **Rendering:** `/documenti/pdfcreator.php`, `/reportbuilder/`

**Integrazione Sistema Documenti:**

**Generazione PDF:**
1. **Risoluzione tipo:** Codice documento → `tipidocumenti`
2. **Caricamento dati:** Query specifica in `/querylist/`
3. **Ricerca associazione:** `associazionestampe` → `template`
4. **Rendering:** Template + dati → PDF output

**Numerazione Automatica:**
- **Incremento:** Auto-incremento alla creazione documento
- **Recupero formato:** Applicazione pattern configurato
- **Validazione unicità:** Controllo duplicati numerazione

**Sistema di Fallback:**
- **Template mancanti:** Fallback automatici (es. `FATTP` → `FATTI`)
- **Configurazioni base:** Valori default per nuove installazioni
- **Error handling:** Logging dettagliato per troubleshooting

**Note:** Il sistema Moduli di Stampa rappresenta un framework completo e flessibile per la gestione di documenti personalizzabili, con numerazione automatica sofisticata e template completamente configurabili attraverso interfaccia drag-and-drop e editor visuale.

## 5. SEZIONE IMPOSTAZIONI

### 5.1 CONFIGURAZIONI AVANZATE

**Parametri Sistema Disponibili:**

**Debug e Monitoring:**
- **Variabili ambiente:** Visualizzazione parametri database (DBHOST, DBPORT, DBUSER, DBNAME)
- **Sessione corrente:** Monitoraggio cliente attivo (`$_SESSION['finsonId']`)
- **phpMiniAdmin:** Configurazioni database management interface
- **Accesso riservato:** Solo utenti con flag `$_SESSION['admin']`

**Configurazioni Multi-tenancy:**
- **Database isolati:** Ogni cliente ha database dedicato
- **Session management:** Gestione sicura con rotazione ogni 15 minuti
- **Storage separato:** Directory dedicate per file utente (`/tmp/{finsonId}/`)

**Parametri Globali Non Configurabili via UI:**
- **Costanti sistema:** Definite in `.env` e `common.inc.php`
- **Timeout sessioni:** Configurazione fissa sicurezza
- **Limiti upload:** Dimensioni massime file (10MB per default)
- **Logging automatico:** Tracciamento eventi sistema

### 5.2 IMPORT/EXPORT DATI

**Sistema Importazione:**

**Formati Supportati:**
- **CSV:** Encoding UTF-8, separatori configurabili
- **Excel:** `.xlsx` (Office Open XML) e `.xls` (formato legacy)
- **Validazione formati:** Controllo strict via accept attribute

**Tipologie Dati Importabili:**
1. **Anagrafica Clienti** (valore=1)
2. **Anagrafica Articoli** (valore=2)
3. **Anagrafica Fornitori** (valore=3)

**Processo Importazione:**

**Step 1: Upload e Analisi**
- **Validazione file:** Controllo formato e dimensioni
- **Estrazione headers:** Parsing intestazioni prima riga
- **Salvataggio temporaneo:** Directory isolata `/tmp/{finsonId}/`
- **Struttura tabelle:** Recupero campi destinazione da database

**Step 2: Mappatura Campi**
- **Interface dinamica:** Generazione select per ogni campo sorgente
- **Mappatura 1:1:** Campo file → Campo database
- **Validazione pre-import:** Controllo coerenza tipologie dati

**Step 3: Elaborazione**
- **Trasformazione dati:** Applicazione mappature configurate
- **Validazione record:** Controlli integrità per ogni riga
- **Import batch:** Inserimento massivo ottimizzato

**Funzionalità Tecniche:**
- **File temporanei:** Sistema gestione `/tmp/{finsonId}/` con timestamp univoco
- **Parsing avanzato:** Supporto PhpSpreadsheet per Excel complesso
- **Error handling:** Logging dettagliato per troubleshooting
- **Sicurezza:** Validazione formati e isolamento file per utente

**Sistema Export:**

**Formati Output:**
- **Excel:** `.xlsx` via PhpSpreadsheet
- **CSV:** Formato standard con separatori configurabili
- **Styling avanzato:** Header colorati, allineamenti, formattazioni numeriche

**Tipologie Export:**
- **Riepilogo Clienti:** Totalizzazioni fatturato per cliente
- **Riepilogo Articoli:** Statistiche vendite per prodotto
- **Riepilogo Fornitori:** Analisi fatture passive
- **Query personalizzate:** Aggregazioni complesse con JOIN

### 5.3 UTENZE MULTIPLE

**Sistema Multi-User:**

**Architettura:**
- **Database Master:** Tabella `users` centrale per autenticazione
- **Relazione Padre-Figlio:** `hasfather` field per collegamento utenti
- **Database Condiviso:** Sub-utenti accedono stesso database cliente
- **Permessi ereditati:** Configurazioni dal master user

**Gestione Utenze:**

**Contatori Dinamici:**
- **Utenze totali:** `$mainUser->subusers` (limite contrattuale)
- **Utenze utilizzate:** Count `hasfather = $_SESSION['uuid']`
- **Indicatori visivi:** Verde/Arancione/Rosso base percentuale utilizzo

**Funzionalità CRUD:**
- **Creazione:** Form dinamico con prefisso username master
- **Attivazione/Disattivazione:** Toggle switch per `locked` field
- **Reset Password:** Generazione automatica + invio email
- **Eliminazione:** Rimozione completa sub-utente

**Configurazioni Specifiche Utente:**
- **Username format:** `{master_user}_{sub_user}` per identificazione gerarchica
- **Email separata:** Campo dedicato per comunicazioni
- **Password sicure:** Generazione automatica con criteri complexity
- **Stato attivazione:** Flag `locked` per controllo accessi

**Gestione Permessi e Ruoli:**
- **Permessi ereditati:** Sub-utenti hanno stessi diritti del master
- **Limitazioni:** Non possono creare altri sub-utenti
- **Audit trail:** Logging azioni per tracciabilità
- **Session isolation:** Sessioni indipendenti ma database condiviso

### 5.4 NUMERATORI AVANZATI

**Sistema Unificato:**

**Tipologie Gestite:**
- **Preventivi** (`tiponum = 'preventivi'`)
- **DDT** (`tiponum = 'ddt'`)
- **Fatture** (`tiponum = 'fatt'`)
- **Note di Credito** (`tiponum = 'ndc'`)
- **Ordini** (`tiponum = 'ordini'`)

**Struttura Avanzata:**
```javascript
// Ogni numeratore salva:
tiponum  // Identificatore tipo documento
progr    // Valore progressivo corrente
val      // JSON: [mese_bool, anno_bool, stringa, separatore]
{tipo}ord // JSON: Ordinamento componenti [0,1,2,3]
```

**Logica Replacement:**
- **Delete + Insert:** Rimozione vecchia configurazione prima del salvataggio
- **Atomic operation:** Transazione completa per coerenza dati
- **Configurazione per tipo:** Un numeratore attivo per tipologia documento

**Integrazione Runtime:**
- **Auto-increment:** Incremento automatico alla creazione documento
- **Pattern application:** Applicazione formato configurato
- **Fallback values:** Valori default per configurazioni mancanti

### 5.5 CONFIGURAZIONI OPERATIVE

**Workflow Default:**
- **Numeratori automatici:** Attivazione predefinita per nuovi tipi documento
- **Template associations:** Sistema fallback per associazioni mancanti
- **Database defaults:** Valori predefiniti per nuovi record anagrafici

**Personalizzazioni Sistema:**
- **File upload paths:** Directory personalizzate per utente
- **Session timeouts:** Configurazione sicurezza automatica
- **Logging levels:** Tracciamento eventi configurabile per debug

**Comportamento Generale:**
- **Error handling:** Gestione errori consistente con logging automatico
- **Cache management:** Sistema cache per performance query frequenti
- **Resource optimization:** Gestione memoria per file grandi

### 5.6 ASPETTI TECNICI

**Database Coinvolti:**

**Tabelle Impostazioni:**
- `numeratori` - Configurazioni numerazione automatica
- `associazionestampe` - Collegamenti template-documenti
- `template` - Layout stampe personalizzabili
- `tipidocumenti` - Tipologie documento gestite

**Tabelle Multi-tenancy:**
- `users` (master DB) - Autenticazione e gerarchia utenti
- File temporanei: `/tmp/{finsonId}/` per isolamento operazioni

**File Configurazione:**
- `.env` - Variabili ambiente database
- `common.inc.php` - Costanti sistema e inizializzazione
- `session.inc.php` - Configurazioni sicurezza sessioni
- Directory storage: Struttura file organizzata per tenant

**Integrazione Sezioni:**
- **Profilo Utente:** Credenziali B2B per fatturazione elettronica
- **Profilo Banche:** Coordinate per documenti e bonifici
- **Gestione Tabelle:** Valori base per configurazioni
- **Moduli Stampa:** Template e numeratori per output documenti

### 5.7 ALTRE FUNZIONALITÀ

**Sistemi Avanzati:**

**Admin Debug Interface:**
- **Monitoring database:** Visualizzazione parametri connessione real-time
- **Session tracking:** Tracciamento stato sessioni attive
- **Configuration validation:** Verifica coerenza setup sistema

**API Export Specializzate:**
- **Export ordini:** Sistema dedicato per estrazione dati ordini
- **Formati multipli:** Supporto output diversificati per integrazione
- **Query ottimizzate:** Performance elevate per volumi grandi

**Backup Automatici:**
- **File backup:** Sistema versioning automatico per file critici
- **Database snapshots:** Procedure backup incrementali
- **Recovery procedures:** Protocolli ripristino dati

**Sistemi di Sicurezza:**
- **Input sanitization:** Validazione rigorosa tutti input utente
- **SQL injection protection:** RedBeanPHP ORM per protezione automatica
- **File upload security:** Validazione formati e contenuti
- **Access control:** Verifiche permessi multi-livello

**Performance Optimization:**
- **Lazy loading:** Caricamento dati on-demand per interfacce complesse
- **Query optimization:** Indicizzazione e ottimizzazioni database
- **Caching strategies:** Sistema cache per dati frequentemente acceduti
- **Resource management:** Gestione memoria ottimizzata per upload grandi

**Note:** Il sistema Impostazioni rappresenta il centro di controllo avanzato del gestionale, fornendo strumenti sofisticati per import/export, gestione multi-utente, configurazioni sistema e monitoraggio, tutti integrati in un'architettura sicura e scalabile.

## 6. SEZIONE GESTISCI TABELLE ✅

### 6.1 ELENCO COMPLETO DELLE 27 TABELLE

**Categoria: Configurazioni IVA e Fiscali**
1. **aliquote_iva** - Aliquote IVA
2. **natura_iva** - Natura IVA
3. **associazioni_dinamiche** - Configuratore Associazioni Aliquote-Nature IVA

**Categoria: Anagrafiche e Classificazioni**
4. **categorie_articoli** - Categorie articoli
5. **categorie_clienti** - Categorie clienti
6. **categorie_fornitori** - Categorie fornitori
7. **settori_merceologici** - Settori merceologici
8. **zone** - Zone geografiche

**Categoria: Varianti Prodotto**
9. **taglie_colori** - Taglie e colori
10. **colori_varianti** - Colori varianti
11. **taglie_varianti** - Taglie varianti
12. **tipi_taglie** - Tipo di taglie

**Categoria: Magazzino e Logistica**
13. **causali_magazzino** - Causali di magazzino
14. **depositi** - Depositi
15. **ubicazioni** - Ubicazioni
16. **aspetto_beni** - Aspetto dei beni

**Categoria: Trasporti e Logistica**
17. **trasporto** - Trasporto
18. **trasporto_mezzo** - Trasporto a mezzo
19. **porto** - Porto

**Categoria: Commerciale e Pagamenti**
20. **modalita_pagamento** - Modalità di pagamento
21. **tipi_pagamento** - Tipo di pagamento
22. **listini** - Listini
23. **denominazione_prezzi** - Denominazione prezzi fissi
24. **condizioni** - Condizioni

**Categoria: Sistema e Misure**
25. **unita_misura** - Unità di misura
26. **valuta** - Valute
27. **banche** - Banche (anagrafica bancaria)

### 6.2 STRUTTURA E FUNZIONALITÀ

**Sistema CRUD Unificato - Architettura PageBuilder:**
- **Tabella meta:** `pagebuilder` con configurazioni per ogni tabella gestita
- **Rendering dinamico:** Interfaccia generata automaticamente da configurazione JSON
- **Pattern MVC:** Separazione vista-logica-dati attraverso configurazione

**Campi Standard Comuni:**
```javascript
// Configurazione per ogni tabella:
itemname     // JSON array nomi colonne UI
itemid       // JSON array ID elementi form  
itemvariable // JSON array nomi campi database
itemtype     // JSON array tipi input (text/select/checkbox/textarea)
columnsizes  // JSON array dimensioni colonne grid
mapping      // JSON configurazione relazioni per select
```

**Funzionalità CRUD Standard:**

**CREATE:** Form di inserimento con validazione
- **Campi obbligatori:** Attributo `data-required=true`
- **Validazione client:** `verificaForm()` prima del salvataggio
- **Sanitizzazione:** `sanitizeInputs()` per sicurezza input

**READ:** Visualizzazione paginata con ricerca
- **Filtro dinamico:** Search con `fullSearch()` per stringhe >3 caratteri
- **Rendering condizionale:** Grid responsive basata su `columnsizes`
- **Relazioni:** Risoluzione automatica select con tabelle collegate

**UPDATE:** Modifica inline con pre-popolamento campi
- **Funzione:** `editValues(uuid, containerDiv)`
- **Pre-popolamento:** Caricamento valori esistenti nel form

**DELETE:** Eliminazione con conferma
- **Soft delete:** Mantenimento referenziale integrità
- **Logging:** Tracciamento eliminazioni per audit

### 6.3 TABELLE SPECIFICHE - DETTAGLI

**Aliquote IVA (`aliquote_iva`):**
```sql
id           INT PRIMARY KEY
percentuale  DECIMAL(5,2)    -- Es: 22.00, 10.00, 4.00
descrizione  VARCHAR(255)    -- Es: "Ordinaria", "Ridotta"
```
- **Utilizzo:** Calcoli automatici fatturazione, DDT, preventivi
- **Validazioni:** Percentuale 0-100, descrizione obbligatoria

**Modalità Pagamento (`modalita_pagamento`):**
```sql
id              INT PRIMARY KEY
descrizione     VARCHAR(255)     -- Es: "Bonifico bancario", "Contrassegno"
giorni_scadenza INT              -- Giorni per scadenza pagamento
tipo_pagamento  VARCHAR(50)      -- Es: "immediato", "dilazionato"
```
- **Utilizzo:** Anagrafiche clienti/fornitori, documenti commerciali
- **Integrazione:** Collegata a `tipi_pagamento` per dettagli aggiuntivi

**Causali Magazzino (`causali_magazzino`):**
```sql
id            INT PRIMARY KEY
descrizione   VARCHAR(255)    -- Es: "Vendita", "Acquisto", "Reso"
tipo_movimento ENUM('E','U')  -- Entrata/Uscita
automatica    BOOLEAN         -- Movimento automatico sistema
segno         ENUM('+','-')   -- Segno movimento giacenza
```
- **Utilizzo:** DDT, movimenti magazzino, inventari
- **Comportamenti:** Calcolo automatico giacenze, controlli disponibilità

**Listini (`listini`):**
```sql
id           INT PRIMARY KEY
descrizione  VARCHAR(255)    -- Es: "Listino Base", "Listino Scontato"
percentuale  DECIMAL(5,2)    -- Sconto/maggiorazione %
attivo       BOOLEAN         -- Listino attivo
data_inizio  DATE           -- Validità da
data_fine    DATE           -- Validità fino
```
- **Utilizzo:** Preventivi, fatture, calcolo prezzi automatico
- **Integrazione:** Collegato a `denominazione_prezzi` per prezzi fissi specifici

**Nature IVA (`natura_iva`):**
```sql
id                     INT PRIMARY KEY
natura                 VARCHAR(10)     -- Es: "N1", "N2", "N3"
descrizione           VARCHAR(255)     -- Descrizione estesa
riferimento_normativo VARCHAR(255)     -- Riferimento di legge
esente                BOOLEAN         -- Operazione esente
```
- **Utilizzo:** Fatturazione elettronica, dichiarazioni IVA
- **Integrazione:** Sistema `associazioni_dinamiche` per collegamento automatico

### 6.4 SISTEMA ASSOCIAZIONI DINAMICHE

**Configuratore Speciale - Funzionalità Avanzata:**
- **Interfaccia dedicata:** UI specifica per gestione associazioni IVA
- **Creazione associazioni:** Collegamento automatico Aliquote-Nature
- **Validazione relazioni:** Controllo coerenza fiscale
- **Nome dinamico:** Generazione automatica identificatori

**Struttura Associazioni:**
```sql
-- Tabella associazioni (generata dinamicamente):
id            INT PRIMARY KEY
nome          VARCHAR(255)    -- Nome associazione  
descrizione   TEXT           -- Descrizione estesa
aliquota_id   INT            -- FK -> aliquoteiva.id
natura_id     INT            -- FK -> naturaiva.id
```

### 6.5 INTEGRAZIONE

**Collegamento con Altre Sezioni:**

**Profilo Utente:**
- **Regime fiscale:** Utilizza `natura_iva` per configurazioni specifiche
- **Codici IVA:** Integrazione con `aliquote_iva` per calcoli

**Moduli Stampa:**
- **Template documenti:** Utilizzano categorie per layout condizionali
- **Causali:** Integrazione con `causali_magazzino` per numerazione

**Utilizzo nel Gestionale:**

**Anagrafiche:**
- **Clienti/Fornitori:** Categorie, modalità pagamento, zone
- **Articoli:** Categorie, unità misura, varianti colore/taglia
- **Agenti:** Modalità pagamento, zone territoriali

**Documenti:**
- **Fatture:** Aliquote IVA, nature IVA, modalità pagamento
- **DDT:** Causali magazzino, aspetto beni, trasporto
- **Preventivi:** Listini, condizioni commerciali

### 6.6 ASPETTI TECNICI

**Tabella Meta (`pagebuilder`):**
```sql
-- Configurazione dinamica interfacce:
objname          VARCHAR(100)  -- Identificatore tabella
tablename        VARCHAR(100)  -- Nome tabella fisica database  
itemname         JSON         -- Configurazione colonne UI
itemid           JSON         -- ID elementi form
itemvariable     JSON         -- Mapping campi database
itemtype         JSON         -- Tipi input (text/select/checkbox)
columnsizes      JSON         -- Dimensioni colonne grid
mapping          JSON         -- Configurazioni relazioni select
research         BOOLEAN      -- Abilitazione ricerca
savefunctionname VARCHAR(100) -- Nome funzione salvataggio
```

**Sistema Validazione:**

**Frontend Validation:**
- **HTML5 constraints:** Required, pattern, type validation
- **JavaScript validation:** `verificaForm()` pre-submit
- **Real-time feedback:** Evidenziazione errori immediata

**Backend Validation:**
- **ORM protection:** RedBeanPHP automatic sanitization
- **Business rules:** Validazioni specifiche per dominio
- **Error logging:** Tracciamento errori per debugging

**Performance e Scalabilità:**
- **Lazy loading:** Caricamento dati on-demand
- **Pagination:** Gestione grandi volumi dati
- **Caching:** Memorizzazione configurazioni PageBuilder
- **Query optimization:** Indicizzazione campi frequenti

**Note:** Il sistema Gestione Tabelle rappresenta un framework dinamico e scalabile per la configurazione di tutti gli aspetti operativi del gestionale, con architettura meta-driven che permette l'aggiunta e modifica di tabelle senza modifiche al codice.

## 7. NOTE IMPLEMENTATIVE

### Stato Attuale
- ✅ **Dashboard Configurazioni**: Creata e funzionante
- ✅ **Gestisci Tabelle**: Completa e perfettamente funzionante
- ❌ **Profilo Utente**: Da rivedere/ricreare
- ❌ **Profilo Banche**: Da rivedere/ricreare
- ❌ **Moduli di Stampa**: Da rivedere/ricreare
- ❌ **Impostazioni**: Da rivedere/ricreare

### Approccio
- Mantenere la sezione Gestisci Tabelle esistente
- Valutare se ricreare le altre sezioni o modificare quelle esistenti
- Utilizzare il design attuale del progetto
- Replicare esattamente funzionalità e campi del vecchio gestionale

## 7. CHECKLIST COMPLETAMENTO
- [ ] Documentazione Sezione Utente
- [ ] Documentazione Sezione Banche
- [ ] Documentazione Sezione Moduli di Stampa
- [ ] Documentazione Sezione Impostazioni
- [ ] Implementazione Sezione Utente
- [ ] Implementazione Sezione Banche
- [ ] Implementazione Sezione Moduli di Stampa
- [ ] Implementazione Sezione Impostazioni
- [ ] Test completo integrazione