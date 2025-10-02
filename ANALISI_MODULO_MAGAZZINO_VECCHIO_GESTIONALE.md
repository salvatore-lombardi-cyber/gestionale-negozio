# ANALISI MODULO MAGAZZINO - VECCHIO GESTIONALE

## PANORAMICA GENERALE

### Scopo Principale
Il modulo magazzino gestisce l'inventario completo dell'azienda attraverso:
- Movimentazioni di merce (carichi/scarichi/trasferimenti)
- Monitoraggio giacenze in tempo reale
- Reporting valorizzazione stock

### Integrazione con Altri Moduli
- **Anagrafica Clienti**: collega movimenti di scarico a clienti specifici
- **Anagrafica Fornitori**: collega carichi a fornitori
- **Anagrafica Articoli**: gestisce codici prodotto, descrizioni, prezzi (costo euro, costo medio)
- **Documenti di Trasporto (DDT)**: integrazione per generazione automatica movimenti da DDT
- **Fatturazione**: collega movimenti a documenti di vendita

### Flussi di Lavoro Principali
1. Carico merce → Incrementa giacenze
2. Scarico merce → Decrementa giacenze
3. Trasferimento tra depositi → Scarica da deposito A + Carica in deposito B
4. Consultazione giacenze → Vista aggregata quantità disponibili
5. Valorizzazione magazzino → Calcolo valore economico stock

## STRUTTURA E ORGANIZZAZIONE

### Organizzazione Prodotti
**Tabella principale**: `anagraficaarticoli`
- `codice_articolo`: Codice alfanumerico univoco
- `descrizione_articolo`: Descrizione estesa
- `codicebarre_articolo`: Barcode EAN/UPC
- `costoeuro_articolo`: Costo di acquisto standard
- `costomedio_articolo`: Costo medio ponderato
- `unitamisura_articoli`: FK a tabella unità di misura (pz, kg, mt, ecc.)
- `hasfather`: Flag per gestione varianti prodotto (varianti linkate a prodotto padre)

### Depositi
**Tabella**: `depositi`
- Multi-deposito supportato nativamente
- Ogni giacenza è specifica per coppia articolo+deposito
- Permette gestione magazzini multipli (es: centrale, negozi, cantieri)

### Movimenti
**Tabella**: `magazzino`
- Registro storico completo di ogni movimento
- Campi chiave: movimenti_gestione.php:82-90, 94-120

## LE 4 SEZIONI PRINCIPALI

### 1. MOVIMENTI DI MAGAZZINO - ANALISI DETTAGLIATA

**📍 File**: `magazzino/movimenti_gestione.php`, `movimenti_research.php`

#### Tipologie di Movimenti Supportati

| Tipo          | Codice Interno | Causale Default | Effetto Giacenze                                                |
|---------------|----------------|-----------------|-----------------------------------------------------------------|
| CARICO        | carico         | ID 1            | ➕ Incrementa giacenza deposito                                  |
| SCARICO       | scarico        | ID 2            | ➖ Decrementa giacenza deposito                                  |
| TRASFERIMENTO | trasferimento  | ID 4            | Crea 2 movimenti: trasferimento uscita + trasferimento ingresso |
| RETTIFICA     | (teorico)      | Custom          | Correzioni inventariali (non implementato nel codice visto)     |

#### Classificazione Movimenti

**MANUALI** (tutti i movimenti attuali):
- Inseriti dall'utente tramite interfaccia tab
- Richiede compilazione form completo
- Validazione client-side con `verificaForm()`

**DA DOCUMENTI** (integrazione parziale):
- DDT → Fattura: Conversione automatica (convertDDTtoFattura.php:1-100)
- **NON** esiste generazione automatica movimenti da DDT/Fatture
- Campo `riferimento_magazzino` permette tracciabilità manuale

**AUTOMATICI**: Non presenti nel sistema attuale

#### Movimenti Speciali - Trasferimento tra Depositi

```php
// magazzinoSaver.php:107-111
if($data['movimentazione'] == 'trasferimento') {
    scaricoMag($loadedRecord, $data);          // Scarico deposito sorgente
    $loadedRecordCar = R::dup($loadedRecord);  // Duplica record
    caricoMag($loadedRecordCar, $data);        // Carico deposito destinazione
}
```

**Particolarità**:
- Crea 2 record separati in tabella `magazzino`
- Uno con tipo "trasferimento uscita" (scarico)
- Uno con tipo "trasferimento ingresso" (carico)
- Campi specifici: `deposito_sorgente_magazzino` + `deposito_destinazione_magazzino`

#### Interfaccia e Funzionalità

**Architettura Tab** (movimenti_content.php:18-41):
```
┌─────────────────────────────────────────────────┐
│  Tab 1: GESTIONE MAGAZZINO (visualizzazione)    │
│  Tab 2: CARICO (inserimento)                    │
│  Tab 3: SCARICO (inserimento)                   │
│  Tab 4: TRASFERISCI (inserimento)               │
└─────────────────────────────────────────────────┘
```

**Workflow Carico**:
1. Selezione rapida da gestione: `magazzino.js:22-37`
2. Compilazione form manuale
3. Click "Esegui carico" → `eseguiCarico()` (magazzino.js:1-3)
4. Raccolta dati → `caricoScaricoTrasf('carico')` (magazzino.js:65-170)
5. Invio AJAX → `/saver/magazzinoSaver.php`

#### Campi Form CARICO (movimenti_carico.php:20-125)

| Campo                 | Tipo              | Obbligatorio | Default         | Note                                  |
|-----------------------|-------------------|--------------|-----------------|---------------------------------------|
| causale_magazzino     | SELECT            | ❌            | ID=1            | Dropdown causali                      |
| riferimento_magazzino | TEXT              | ❌            | -               | Riferimento libero (es: "DDT-001/24") |
| deposito_in_uso       | SELECT            | ✅            | 9999 (invalido) | Blocco se = 9999                      |
| lista_clienti         | SELECT            | ❌            | 0 (nessuno)     | FK anagraficaclienti                  |
| lista_fornitori       | SELECT            | ❌            | 0 (nessuno)     | FK anagraficafornitori                |
| articolo              | SELECT            | ❌            | -               | FK anagraficaarticoli                 |
| quantita              | NUMBER            | ✅            | -               | data-required=true                    |
| data                  | TEXT (datepicker) | ✅            | -               | Formato: dd/mm/yyyy (Flatpickr)       |

**Campi NASCOSTI automatici**:
- `userId`: `$_SESSION['finsonId']` (movimenti_content.php:59)
- `uuid`: Generato server-side con `CommonFunc::generateUuidV4()` (magazzinoSaver.php:94-95)
- `movimentazione`: Passato dalla funzione JS ('carico', 'scarico', 'trasferimento')

#### Controlli Automatici

**CLIENT-SIDE** (js/top.js):
```javascript
function verificaForm(selector) {
    // Valida tutti i campi con data-required=true
    // Se vuoto → bordo rosso + alert
    // Impedisce submit se non valido
}
```

**Validazioni specifiche** (magazzino.js:73-88):
```javascript
// Deposito obbligatorio
if($('#magazzino_carico #deposito_in_uso').val() != 9999) {
    workingDiv = 'magazzino_carico';
} else {
    showErrorModal("Deposito non valido");
    return; // BLOCCA ESECUZIONE
}
```

**SERVER-SIDE** (magazzinoSaver.php:82-86):
```php
// Validazione dati ricevuti
if (!$data) {
    http_response_code(400);
    echo json_encode(['message' => 'Nessun dato ricevuto']);
    exit;
}
```

#### Gestione Dati

**⚠️ PROBLEMA QUANTITÀ DECIMALI**:
```php
// magazzinoSaver.php:29, 36, 59, 66
$currentStoreTotal->quantita_attuale = (int)($currentStoreTotal->quantita_attuale + $loadedRecord->quantita);
```
- Input utente: 10.5 → Salvato come: 10
- Cast a `(int)` elimina decimali
- Mancanza gestione quantità frazionarie (kg, litri, metri)

**Associazione Prodotto**:
```php
// movimenti_carico.php:88-98
<select id="articolo">
    <?php
    $articoli = R::find("anagraficaarticoli");
    foreach ($articoli as $articolo) {
        echo "<option value='{$articolo['id']}'>" .
             "{$articolo['descrizione_articolo']} ({$articolo['codice_articolo']})</option>";
    }
    ?>
</select>
```

**❌ Movimenti Multipli NON SUPPORTATO**:
- Form singolo per movimento
- Nessun meccanismo "aggiungi riga"
- Backend accetta solo 1 movimento per richiesta POST

#### Causali e Motivazioni

**Causali Esistenti** (tabella `causalimagazzino`):

| ID  | Codice  | Descrizione (ipotesi) | Tipo Movimento | Default per       |
|-----|---------|-----------------------|----------------|-------------------|
| 1   | CAR001? | Carico Standard       | Carico         | Tab Carico        |
| 2   | SCA001? | Scarico Standard      | Scarico        | Tab Scarico       |
| 4   | TRA001? | Trasferimento         | Trasferimento  | Tab Trasferimento |

**✅ Causali Personalizzate POSSIBILI**:
1. Inserire nuovo record in `causalimagazzino`
2. Appare automaticamente in dropdown
3. Nessuna logica speciale legata al codice causale

**⚠️ CAUSALE NON INFLUISCE SUL CALCOLO**:
```php
// magazzinoSaver.php:16-72
// Decisione basata SOLO su $data['movimentazione']
if($data['movimentazione'] == 'carico') {
    caricoMag($loadedRecord, $data);  // +quantità
} else if($data['movimentazione'] == 'scarico') {
    scaricoMag($loadedRecord, $data); // -quantità
}
```

#### Integrazione con Documenti

**❌ NON IMPLEMENTATA** l'integrazione DDT/Fatture → Movimenti:

**Evidenze**:
1. `documentoDDTsaver.php`: Salva solo master+dettagli DDT, nessun movimento
2. `convertDDTtoFattura.php`: Converte DDT → Fattura, nessun movimento magazzino

**Workflow attuale**:
```
DDT Cliente → [Manuale] → Movimento Scarico
           ↓
       Fattura (senza movimenti auto)
```

**Campo Riferimento Manuale**:
```html
<!-- movimenti_carico.php:40-43 -->
<input type="text" id="riferimento_magazzino" placeholder="Riferimento...">
```
- Campo free-text per massima flessibilità
- Nessuna FK a tabelle `documentiddtmaster` o `fatturemaster`
- Utente deve digitare manualmente "DDT-001/24"

#### Controlli e Validazioni

**❌ NESSUN CONTROLLO GIACENZE NEGATIVE**:
```php
// magazzinoSaver.php:56-67
$currentStoreTotal->quantita_attuale = (int)($currentStoreTotal->quantita_attuale - $loadedRecord->quantita);
// Permette valori negativi senza warning!
```

**❌ LOTTI E NUMERI SERIALI NON GESTITI**:
- Nessun campo lotto, serial, batch nelle tabelle
- Solo esempio statico nella documentazione (`help/magazzino/ricerca-movimenti.php:424-425`)

**❌ NESSUN CONTROLLO TEMPORALE**:
- Movimento con data futura → Permesso
- Movimento con data antecedente inventario → Permesso
- Movimento con data successiva chiusura contabile → Permesso

**⚠️ MODIFICA/CANCELLAZIONE NON IMPLEMENTATA**:
- Nessun endpoint DELETE `/saver/magazzinoSaver.php`
- Lista movimenti ha campi readonly
- Sistema append-only (solo inserimenti)
- Correzioni tramite movimenti compensativi

#### Funzionalità

**Visualizzazione storico movimenti** con filtri avanzati
**Ricerca unificata** per: descrizione, codice articolo, barcode, data
**Filtro per deposito** (dropdown dedicato)
**Azioni rapide** su ogni riga:
- 📥 Carico articolo
- 📤 Scarico articolo  
- 🔀 Trasferimento tra depositi

**Campi visualizzati** (movimenti_gestione.php:20-56):
- Data movimento
- Tipo movimentazione (carico/scarico/trasferimento)
- Codice articolo
- Descrizione
- Deposito
- Causale (codice operazione)
- Codice a barre

**Ricerca intelligente** (movimenti_research.php:43-52):
- Minimo 3 caratteri per attivare ricerca
- Query full-text su tutti i campi correlati
- Funzione backend: `fullSearchMagazzino()` in database.inc.php:94-140

### 2. GESTIONE GIACENZE - ANALISI APPROFONDITA

**📍 File**: `magazzino/movimenti_giacenze.php`, `loaders/getGiacenze.php`

#### Funzionalità Principali

**Definizione**: La Gestione Giacenze è il modulo di consultazione e monitoraggio delle disponibilità di magazzino in tempo reale. A differenza dei Movimenti (che sono operativi), questa sezione è principalmente informativa e analitica.

**Operazioni Disponibili**:

| Operazione                | Funzione                                         | File Coinvolto               |
|---------------------------|--------------------------------------------------|------------------------------|
| Visualizza Giacenze       | Consulta giacenze filtrate per articolo/deposito | movimenti_giacenze.php:15-17 |
| Filtra per Articolo       | Limita visualizzazione a prodotto specifico      | movimenti_giacenze.php:22-34 |
| Filtra per Deposito       | Limita visualizzazione a magazzino specifico     | movimenti_giacenze.php:37-49 |
| Espandi Dettaglio         | Mostra analisi movimenti articolo/deposito       | magazzino.js:241-312         |
| Espandi Tutto             | Apre tutti i dettagli contemporaneamente         | magazzino.js:314-319         |
| Visualizza Stato Giacenze | Report valorizzazione completo                   | movimenti_stato_giacenze.php |

#### Differenza con "Movimenti di Magazzino"

```
┌─────────────────────────────────────────────────────────┐
│  MOVIMENTI DI MAGAZZINO                                 │
│  ------------------------------------------------       │
│  • Registro TRANSAZIONALE (ogni operazione)            │
│  • INSERIMENTO carico/scarico/trasferimento            │
│  • Visione STORICA (cosa è successo)                   │
│  • Modificano le giacenze                              │
│  • Tabella: magazzino (append-only)                    │
└─────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────┐
│  GESTIONE GIACENZE                                      │
│  ------------------------------------------------       │
│  • Vista AGGREGATA (situazione attuale)                │
│  • CONSULTAZIONE disponibilità                         │
│  • Visione ISTANTANEA (quanto c'è ora)                 │
│  • Calcolate dai movimenti                             │
│  • Tabella: totalimagazzino (aggiornata in tempo reale)│
└─────────────────────────────────────────────────────────┘
```

#### Calcolo e Aggiornamento Giacenze

**Aggiornamento in Tempo Reale** (tabella `totalimagazzino`):

```sql
-- Struttura tabella
CREATE TABLE totalimagazzino (
    id INT AUTO_INCREMENT PRIMARY KEY,
    aid INT,              -- FK anagraficaarticoli.id
    dep INT,              -- FK depositi.id
    quantita_attuale INT, -- Giacenza corrente
    UNIQUE KEY (aid, dep) -- Una riga per combinazione articolo+deposito
);
```

**Logica aggiornamento CARICO** (magazzinoSaver.php:26-40):
```php
$currentStoreTotal = R::findOne('totalimagazzino', 'aid = ? AND dep = ?',
                                 [$articolo, $deposito]);

if($currentStoreTotal) {
    // Aggiorna esistente: SOMMA quantità
    $currentStoreTotal->quantita_attuale =
        (int)($currentStoreTotal->quantita_attuale + $quantita_carico);
} else {
    // Crea nuova: INIZIALIZZA con quantità movimento
    $currentStoreTotal = R::dispense('totalimagazzino');
    $currentStoreTotal->aid = $articolo;
    $currentStoreTotal->dep = $deposito;
    $currentStoreTotal->quantita_attuale = $quantita_carico;
}
R::store($currentStoreTotal);
```

**⚡ TEMPO REALE** (sincronizzazione immediata):
1. Utente inserisce movimento carico (50 pz) → 2. AJAX POST `/saver/magazzinoSaver.php` → 3. Backend salva in tabella `magazzino` (storico) → 4. STESSO COMMIT DB: aggiorna `totalimagazzino` (+50) → 5. Response AJAX success → 6. Frontend ricarica tab Gestione Magazzino → 7. Giacenza visibile IMMEDIATAMENTE aggiornata

**❌ Ricalcolo Manuale NON IMPLEMENTATO**: Nessun pulsante "Ricalcola giacenze" o endpoint dedicato

**❌ Giacenze Iniziali**: Non esiste meccanismo dedicato. Soluzione attuale: movimento di carico manuale con causale "Inventario Iniziale"

#### Interfaccia Utente

**Layout Griglia Responsive** (movimenti_giacenze.php:53-61):

```
┌──────────────────────────────────────────────────────────────┐
│  [Visualizza per Articolo ▼] [Visualizza per Deposito ▼]    │
│  [Visualizza Giacenze]                                       │
├──────────────────────────────────────────────────────────────┤
│  Cod.Barre  │  Deposito │  Descrizione  │ Quantità │ Azioni │
├──────────────────────────────────────────────────────────────┤
│  12345678   │  DEP001   │  Mouse USB    │  50 PZ   │   🔍   │
│  ╰─────────────────────── [Dettaglio Espanso] ──────────────╯│
│    Esistenza: 50 │ Tot.Carico: 100 │ Tot.Scarico: 50        │
│    Costo €: 15 │ Valore €: 750 │ Valore Medio: 745          │
├──────────────────────────────────────────────────────────────┤
│  87654321   │  DEP002   │  Tastiera BT  │  25 PZ   │   🔍   │
└──────────────────────────────────────────────────────────────┘
```

**Filtri Combinati**:

| Filtro   | Tipo   | Opzioni         | Comportamento                                |
|----------|--------|-----------------|----------------------------------------------|
| Articolo | SELECT | Tutti / Singolo | articolo=0 → tutti, articolo=42 → solo ID 42 |
| Deposito | SELECT | Tutti / Singolo | deposito=0 → tutti, deposito=1 → solo DEP001 |

**Vista Dettaglio Espanso** (click 🔍 - magazzino.js:283-299):

```
┌─────────────────────────────────────────────────────────────┐
│  [Sfondo verde/rosso se giacenza positiva/negativa]         │
├─────────────────────────────────────────────────────────────┤
│  Esistenza │ Tot.Carico │ Tot.Scarico │ Costo € │ Costo Medio│
│     50     │    100     │      50     │  15,00  │   14,85    │
├─────────────────────────────────────────────────────────────┤
│  Valore € │ Valore Medio │
│   750,00  │    742,50    │
└─────────────────────────────────────────────────────────────┘
```

**Calcoli valorizzazioni** (magazzino.js:297-298):
```javascript
// Valore Euro = Quantità × Costo Unitario Fisso
valorEuro = (costoEuro * esistenza).toFixed(2);  // 15,00 × 50 = 750,00

// Valore Medio = Quantità × Costo Medio Ponderato  
valorMedio = (costoMedio * esistenza).toFixed(2); // 14,85 × 50 = 742,50
```

#### Correzioni e Aggiustamenti

**❌ Modifica Diretta Giacenze NON POSSIBILE**:
- Campi readonly in lista giacenze
- Nessun form di modifica  
- `totalimagazzino` aggiornata SOLO via movimenti

**Registrazione Correzioni Inventario** - METODO ATTUALE: Movimenti compensativi

**Workflow Rettifica Giacenza**:
```
SCENARIO: Inventario fisico trova 48 pz invece di 50 registrati

1. Operatore va in "Movimenti" → Tab "Scarico"
2. Compila:
   - Articolo: Mouse USB
   - Deposito: DEP001
   - Quantità: 2
   - Causale: "Rettifica Inventario" (causale dedicata)
   - Riferimento: "INV-2025-10-02"
   - Cliente/Fornitore: Nessuno
3. Click "Esegui scarico"
4. Sistema crea:
   - Record in magazzino (movimento storico)
   - Aggiorna totalimagazzino: 50 - 2 = 48
```

**❌ Controlli Privilegi NON IMPLEMENTATI**: Tutti gli utenti dello stesso tenant possono fare movimenti

**Tracciamento Modifiche**:
- ✅ **MOVIMENTI**: Tracciamento completo con UUID univoci
- ❌ **GIACENZE**: Nessun log modifiche, tabella `totalimagazzino` sovrascritta

#### Soglie e Alerting

**✅ Soglie Articolo Configurabili** (anagArticoli_varie.php:54-70):
```html
<!-- Giacenza Minima -->
<input type="number" id="giacenza_min_articoli">
<!-- Giacenza Massima -->
<input type="number" id="giacenza_max_articoli">
<!-- Sotto Scorta -->
<input type="number" id="sottoscorta_articoli">
```

**❌ Avvisi Automatici NON IMPLEMENTATI**: Nessun cron job, notifiche email/push, o badge/alert in interfaccia

**✅ Gestione Giacenze Negative SUPPORTATE**:
```php
// magazzinoSaver.php:59 - Permette giacenze negative senza warning
$currentStoreTotal->quantita_attuale =
    (int)($currentStoreTotal->quantita_attuale - $loadedRecord->quantita);
// Se 10 - 15 = -5 → ACCETTATO
```

**Visualizzazione giacenze negative** (magazzino.js:275-281):
```javascript
if(esistenza < 0) {
    var negClass = "font-bold text-red-500 ";  // Testo rosso
    var bgcolor = "bg-red-300";                 // Sfondo rosso
}
```

#### Gestione Multi-Magazzino

**✅ SUPPORTO NATIVO E COMPLETO**:

**Architettura dati**:
```
totalimagazzino
├── aid: 42 (Mouse USB)
│   ├── dep: 1 (Magazzino Centrale) → quantita: 50
│   ├── dep: 2 (Punto Vendita Roma) → quantita: 25
│   └── dep: 3 (Deposito Esterno)   → quantita: 0
└── aid: 43 (Tastiera BT)
    ├── dep: 1 → quantita: 80
    └── dep: 2 → quantita: 40
```

**✅ Trasferimenti tra Magazzini COMPLETAMENTE SUPPORTATO**:
```php
// 1 movimento logico = 2 record fisici
scaricoMag($loadedRecord, $data);      // Scarico da sorgente
$loadedRecordCar = R::dup($loadedRecord);
caricoMag($loadedRecordCar, $data);    // Carico in destinazione
```

#### Valorizzazione

**DUE METODI DISPONIBILI**:

**METODO 1: Costo Euro (Fisso)** ✅ Implementato
- Campo: `anagraficaarticoli.costoeuro_articolo`
- Prezzo di acquisto standard/listino
- Non cambia automaticamente
- Usato per valorizzazioni "ufficiali"

**METODO 2: Costo Medio Ponderato** ⚠️ Parzialmente Implementato
- Campo: `anagraficaarticoli.costomedio_articolo`
- NON aggiornato automaticamente (campo presente ma statico)
- Richiede aggiornamento manuale/batch

**❌ FIFO/LIFO NON SUPPORTATI**: Nessuna gestione lotti con data/costo

**✅ Valore Totale Magazzino DISPONIBILE**:
```sql
-- Query principale (movimenti_stato_giacenze.php:28-30)
SELECT SUM(t.quantita_attuale * a.costoeuro_articolo) AS ValoreTotale
FROM totalimagazzino t
LEFT JOIN anagraficaarticoli a ON a.id = t.aid
```

**Loader AJAX**: `getGiacenze.php` con 2 modalità:
1. `action=fullview` (loaders/getGiacenze.php:59-154):
   - Query JOIN complessa su totalimagazzino
   - Mostra tutte le giacenze con filtri
2. `action=detview` (loaders/getGiacenze.php:155-186):
   - Analisi movimenti raggruppati per tipo
   - Calcolo esistenza dinamica

**Frontend**: magazzino.js:172-239
- Funzione `visGiacenze()`: carica vista completa
- `loadArtDepDett()`: espande dettaglio movimento con calcolo valorizzazioni

### 3. STATO GIACENZE - ANALISI COMPLETA

**📍 File**: `magazzino/movimenti_stato_giacenze.php`

#### Scopo e Funzionalità

**Differenza tra "Gestione Giacenze" e "Stato Giacenze"**:

```
┌────────────────────────────────────────────────────────────┐
│  GESTIONE GIACENZE                                         │
│  ─────────────────────────────────────────────────────    │
│  SCOPO: Consultazione operativa quotidiana                │
│  UTENTE: Magazziniere, operatore vendite                  │
│  FUNZIONE: Verificare disponibilità specifica             │
│  INTERATTIVITÀ: Alta (filtri, espansioni, drill-down)     │
│  OUTPUT: Vista dinamica filtrata                          │
│  FORMATO: Griglia web interattiva                         │
└────────────────────────────────────────────────────────────┘

┌────────────────────────────────────────────────────────────┐
│  STATO GIACENZE                                            │
│  ─────────────────────────────────────────────────────    │
│  SCOPO: Reporting valorizzazione e analisi finanziaria    │
│  UTENTE: Responsabile, controller, amministrazione        │
│  FUNZIONE: Snapshot valore magazzino per contabilità      │
│  INTERATTIVITÀ: Bassa (solo visualizzazione + stampa)     │
│  OUTPUT: Report completo multi-sezione                    │
│  FORMATO: Vista statica stampabile (PDF)                  │
└────────────────────────────────────────────────────────────┘
```

**Matrice comparativa**:

| Caratteristica | Gestione Giacenze           | Stato Giacenze                             |
|----------------|-----------------------------|--------------------------------------------|
| Filtri         | ✅ Articolo + Deposito       | ❌ Nessuno (vista completa)                 |
| Drill-down     | ✅ Espandi dettaglio         | ❌ Tutto visibile                           |
| Valorizzazione | ⚠️ Solo in dettaglio        | ✅ Focus principale                         |
| Stampa         | ❌ Non disponibile           | ✅ PDF professionale                        |
| Aggregazioni   | ❌ Solo singola combinazione | ✅ Multi-livello (totale/deposito/articolo) |
| Varianti       | ❌ Non evidenziate           | ✅ Badge [VARIANTE]                         |

**✅ SOLO CONSULTAZIONE** (Read-only): Nessun form input modificabile, unica azione: Stampa PDF

#### Informazioni Fornite

**4 SEZIONI PRINCIPALI**:

**1. Valore Complessivo Asset** (linee 27-40):
```sql
SELECT SUM(t.quantita_attuale * a.costoeuro_articolo) AS v
FROM totalimagazzino t
LEFT JOIN anagraficaarticoli a ON a.id = t.aid
```
```
┌────────────────────────────────────┐
│  Valore complessivo asset:         │
│  € 125.430,00                      │
└────────────────────────────────────┘
```

**2. Valore Complessivo per Magazzino** (linee 43-77):
```sql
SELECT
    d.descrizione AS Magazzino,
    CONCAT(SUM(t.quantita_attuale), ' ', u.descrizione) AS Quantita,
    SUM(t.quantita_attuale * a.costoeuro_articolo) AS Valore
FROM totalimagazzino t
LEFT JOIN anagraficaarticoli a ON a.id = t.aid
LEFT JOIN unitamisura u ON a.unitamisura_articoli = u.id
LEFT JOIN depositi d ON d.id = t.dep
GROUP BY d.descrizione
```
```
┌─────────────────────┬───────────┬────────────┐
│  Magazzino          │ Quantità  │  Valore    │
├─────────────────────┼───────────┼────────────┤
│ Magazzino Centrale  │ 500 PZ    │ € 85.000   │
│ Punto Vendita Roma  │ 200 PZ    │ € 30.430   │
│ Deposito Esterno    │ 100 PZ    │ € 10.000   │
└─────────────────────┴───────────┴────────────┘
```

**3. Valore Complessivo Asset per Magazzino** (linee 80-124) - Dettaglio articolo × deposito:
```
┌───────────────┬─────────────┬──────────┬──────────┬──────────┐
│  Articolo     │ Magazzino   │ Quantità │ Costo €  │  Valore  │
├───────────────┼─────────────┼──────────┼──────────┼──────────┤
│ MOUSE-W01     │ DEP001      │ 50 PZ    │ € 15,00  │ € 750    │
│ MOUSE-W01     │ DEP002      │ 25 PZ    │ € 15,00  │ € 375    │
│ [VARIANTE]    │             │          │          │          │
│ TAST-BT01-BLK │ DEP001      │ 80 PZ    │ € 45,00  │ € 3.600  │
└───────────────┴─────────────┴──────────┴──────────┴──────────┘
```

**4. Valore Complessivo Asset Aggregato** (linee 127-168) - Totale cross-deposito:
```
┌───────────────┬───────────┬────────────┐
│  Articolo     │ Quantità  │  Valore    │
├───────────────┼───────────┼────────────┤
│ MOUSE-W01     │ 75 PZ     │ € 1.125    │  ← Somma di 50+25+0
│ TAST-BT01     │ 120 PZ    │ € 5.400    │  ← Somma multi-dep
└───────────────┴───────────┴────────────┘
```

#### Visualizzazione Dati

**Layout Verticale a Sezioni**:
```
┌─────────────────────────────────────────────────┐
│  In questo pannello troverai i valori degli    │
│  asset attualmente a magazzino.                 │
├─────────────────────────────────────────────────┤
│  [SEZIONE 1: Valore Totale]     € 125.430,00   │
├─────────────────────────────────────────────────┤
│  [SEZIONE 2: Per Magazzino]                    │
│  ┌──────────┬──────────┬──────────┐            │
├─────────────────────────────────────────────────┤
│  [SEZIONE 3: Dettaglio Art×Mag]                │
├─────────────────────────────────────────────────┤
│  [SEZIONE 4: Totali Articoli]                  │
├─────────────────────────────────────────────────┤
│                                  🖨️ Stampa     │
└─────────────────────────────────────────────────┘
```

**❌ Storico Giacenze NON DISPONIBILE**: Query sempre su `totalimagazzino` (snapshot attuale), nessun campo data

**❌ Grafici NON DISPONIBILI**: Solo tabelle HTML, nessun canvas o librerie chart

**❌ Filtri NON DISPONIBILI**: Vista completa fissa, nessun controllo utente

#### Informazioni Dettagliate

**Dati per Prodotto** (Sezione 3 - Dettaglio Art×Mag):

| Campo          | Descrizione            | Esempio                 | Fonte                                                      |
|----------------|------------------------|-------------------------|------------------------------------------------------------|
| Articolo       | Descrizione + Codice   | "Mouse USB (MOUSE-W01)" | anagraficaarticoli.descrizione_articolo                    |
| Badge Variante | Flag prodotto variante | "[VARIANTE]"            | anagraficaarticoli.hasfather                               |
| Magazzino      | Nome deposito          | "Magazzino Centrale"    | depositi.descrizione                                       |
| Quantità       | Giacenza + UdM         | "50 PZ"                 | totalimagazzino.quantita_attuale + unitamisura.descrizione |
| Costo €        | Costo unitario fisso   | "€ 15,00"               | anagraficaarticoli.costoeuro_articolo                      |
| Valore         | Qtà × Costo            | "€ 750,00"              | Calcolato (quantita_attuale * costoeuro_articolo)          |

**❌ Giacenze Minima/Massima NON VISUALIZZATE**: Campi presenti in anagrafica ma non mostrati nel report

**❌ Movimenti Recenti NON VISUALIZZATI**: Solo giacenza finale, nessun drill-down movimenti

**✅ Giacenze per Ubicazione**: Sezione 2 (aggregato per deposito) e Sezione 3 (dettaglio articolo×deposito)

#### Analisi e Report

**⚠️ Analisi Minime** (solo calcoli base):
- ✅ Somma valore totale magazzino
- ✅ Breakdown valore per deposito
- ✅ Aggregazione cross-deposito per articolo
- ❌ Analisi ABC, trend, rotazione stock NON disponibili

**✅ UN SOLO REPORT PREDEFINITO**: Stato Giacenze PDF

**Processo generazione PDF**:
1. Click 🖨️ in UI → 2. JS: `createPDF('STGIAC')` → 3. Fetch `/documenti/pdfcreator.php?cod_stampa=STGIAC` → 4. Query multiple (STGIAC.php:6-43) → 5. POST a ReportBro server → 6. Download blob PDF

**Formati Disponibili**:

| Formato | Supportato | Modalità  | Note                             |
|---------|------------|-----------|----------------------------------|
| PDF     | ✅          | Click 🖨️ | Template professionale ReportBro |
| Excel   | ❌          | -         | Non implementato                 |
| CSV     | ❌          | -         | Non implementato                 |

#### Alerting e Monitoraggio

**⚠️ SOLO Giacenze Negative Evidenziate**:
```php
// movimenti_stato_giacenze.php:71, 119, 163
if($valore > 0) {
    $class = 'font-normal';
} else {
    $class = 'text-red-500 font-bold';
}
```

**Indicatori Visivi Disponibili**:

| Indicatore       | Implementazione                | Condizione        |
|------------------|--------------------------------|-------------------|
| Rosso Bold       | class="text-red-500 font-bold" | valore < 0        |
| Badge [VARIANTE] | <span>[VARIANTE]</span>        | hasfather == true |
| Hover Gray       | class="hover:bg-gray-300"      | Rollover riga     |

**❌ Notifiche Automatiche NON IMPLEMENTATE**: Nessun sistema di alerting, cron job, o email

#### Periodo Temporale

**❌ Consultazione Data Specifica NON POSSIBILE**: 
- Vincolo architetturale: `totalimagazzino` = snapshot corrente
- Query sempre sui dati attuali, nessun campo data

**❌ Evoluzione Giacenze in Periodo NON SUPPORTATA**: Nessuna funzionalità trend/grafici storici

**Gestione Giacenze Storiche**:
```
┌─────────────────────────────────────────┐
│  magazzino (Storico movimenti)          │
│  • Tutti i movimenti dal Day 1          │
│  • Campo: data (timestamp movimento)    │
│  • Retention: Infinita (append-only)    │
└─────────────────────────────────────────┘
           ↓ Aggiorna in real-time
┌─────────────────────────────────────────┐
│  totalimagazzino (Snapshot corrente)    │
│  • Solo giacenza ATTUALE                │
│  • NO campo data                        │
│  • Overwrite (UPDATE in place)          │
└─────────────────────────────────────────┘
```

#### Integrazione con Altri Moduli

**❌ Ordini Clienti/Fornitori**: Nessuna integrazione visibile, impossibile vedere "disponibile a vendere" (ATP)

**❌ Impegni Futuri Magazzino NON GESTITI**: Mancano prenotazioni, ordini in transito, giacenza prevista

**❌ Integrazione Produzione/Acquisti**: Sistema retail/distribuzione puro, nessun MRP

#### Caratteristiche

**Evidenziazione giacenze negative** (rosso bold)
**Supporto varianti prodotto** (flag [VARIANTE])
**Stampa PDF** (funzione `createPDF('STGIAC')`)

**✅ Punti di Forza**:
1. Report Ufficiale Certificato per contabilità
2. Valorizzazione Completa (4 livelli aggregazione)
3. Multi-deposito con analisi distribuzione valore
4. Varianti Evidenziate con badge
5. Valori Negativi Visibili in rosso bold
6. Semplicità: report sempre completo e comparabile

**❌ Limitazioni Critiche**:
1. Nessuno Storico (impossibile vedere giacenze a data passata)
2. Nessun Filtro (vista sempre completa)
3. No Export Excel/CSV (solo PDF)
4. No Grafici (solo tabelle testuali)
5. No Soglie (sotto scorta/sovraccarico non evidenziati)
6. No ATP (giacenza fisica ≠ disponibile a vendere)
7. No Trend (impossibile vedere evoluzione)
8. No Analisi ABC (top articoli non ordinati per valore)
9. No Alert (sistema puramente consultivo)

### 4. DOCUMENTI DI TRASPORTO - ANALISI COMPLETA

**📍 File**: `documenti/ddtPanelCliente.php`, `documenti/ddtPanelFornitore.php`, `saver/documentoDDTsaver.php`

#### Tipologie e Gestione

**2 TIPOLOGIE PRINCIPALI supportate**:

```
┌──────────────────────────────────────────────────────┐
│  DDT CLIENTE (Uscita Merce)                         │
│  ───────────────────────────────────────────        │
│  Campo: ddttype = "cliente"                         │
│  Direzione: Azienda → Cliente                       │
│  Funzionalità:                                       │
│    • Gestione vettori (dettaglio trasporto)        │
│    • Modalità/tipo pagamento                       │
│    • Merce in conto vendita                        │
│    • Convertibile in FATTURA                       │
│  File: ddtPanelCliente.php                          │
└──────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────┐
│  DDT FORNITORE (Entrata Merce)                      │
│  ───────────────────────────────────────────────    │
│  Campo: ddttype = "fornitore"                       │
│  Direzione: Fornitore → Azienda                     │
│  Funzionalità:                                       │
│    • NO gestione vettori (ricezione passiva)       │
│    • NO conversione fattura (già ricevuta)         │
│    • Tracciabilità carico merce                    │
│  File: ddtPanelFornitore.php                        │
└──────────────────────────────────────────────────────┘
```

**Differenziazione nel database** (documentoDDTsaver.php:43-91):
```php
if($data['ddttype'] == "cliente") {
    // Gestione completa con vettori
    $vectorLists = array_filter($data, function($key) {
        return strpos($key, 'vectorList_') === 0;
    }, ARRAY_FILTER_USE_KEY);
    
    $loadedRecordMaster->ddtmodalitapagamento = $data['ddtmodalitapagamento'];
    $loadedRecordMaster->ddttipopagamento = $data['ddttipopagamento'];
    $loadedRecordMaster->dttmercecontovendita = $data['dttmercecontovendita'];
    
} else if($data['ddttype'] == "fornitore") {
    // Gestione semplificata solo articoli
    $articleLists = array_filter($data, function($key) {
        return strpos($key, 'articleFornitoreList_') === 0;
    }, ARRAY_FILTER_USE_KEY);
}
```

**❌ DDT di Trasferimento tra Magazzini NON GESTITO**: Nessun `ddttype = "trasferimento"`, trasferimenti gestiti tramite modulo Movimenti Magazzino separato

**❌ DDT Speciali NON SUPPORTATI**: Tipologie fisse (solo cliente/fornitore), nessun campo causale_ddt personalizzabile

#### Creazione e Compilazione

**Workflow Creazione Nuovo DDT**:
1. Documenti → DDT → Tab "Crea/Modifica Documenti di trasporto"
2. Scelta tipo DDT (panel cliente/fornitore)
3. Compilazione form master
4. Aggiunta articoli
5. (Solo DDT Cliente) Aggiunta vettori
6. Salvataggio

**Dati Obbligatori vs Opzionali**:

**DDT CLIENTE** (ddtPanelCliente.php:13-103):

| Campo              | Tipo          | Obbligatorio | Default       | Generazione              |
|--------------------|---------------|--------------|---------------|--------------------------|
| Numero DDT         | TEXT readonly | ✅            | Auto-generato | Numeratore tiponum='ddt' |
| Data DDT           | DATE          | ✅            | -             | Datepicker Flatpickr     |
| Cliente            | SELECT        | ⚠️           | "Nessuno" (0) | FK anagraficaclienti     |
| Modalità Pagamento | SELECT        | ❌            | -             | Da configurazione        |
| Tipo Pagamento     | SELECT        | ❌            | -             | Da configurazione        |
| Merce c/vendita    | CHECKBOX      | ❌            | false         | Boolean                  |

**DDT FORNITORE** (ddtPanelFornitore.php:10-97):

| Campo      | Tipo          | Obbligatorio | Default       |
|------------|---------------|--------------|---------------|
| Numero DDT | TEXT readonly | ✅            | Auto-generato |
| Data DDT   | DATE          | ✅            | -             |
| Fornitore  | SELECT        | ⚠️           | "Nessuno" (0) |

**Numerazione Automatica** (ddtPanelCliente.php:38-68):
```php
// Recupera configurazione numeratore
$ddtClienteStatus = R::findOne('numeratori', 'tiponum = ?', ['ddt']);
$ddtClienteValueArray = json_decode($ddtClienteStatus->val, true);

// Calcola prossimo numero con sostituzioni variabili
// Template: {{s}}{{a}}/{{p}} → Output: "DDT2025/00042"
```

**❌ Creazione da Ordini Preesistenti NON IMPLEMENTATO**: Nessun modulo "Ordini Clienti/Fornitori", DDT sempre creato manualmente

**Aggiunta Prodotti Dinamica** (ddtPanelCliente.php:122-200):
```javascript
$("#ddtAggArticolo").click(function() {
    articleList += 1; // Incrementa contatore
    
    // Crea nuova riga articolo dinamicamente
    $("#ddtArticContainer").append(`
        <div id="articleList_${articleList}" class="mb-2 grid grid-cols-9">
            <!-- Dropdown articolo, prezzo, peso, quantità, sconti, imponibile -->
        </div>
    `);
});
```

**Campi riga articolo DDT Cliente**:

| Campo      | ID Form                    | Descrizione        | Calcolo             |
|------------|----------------------------|--------------------|--------------------|
| Articolo   | ddtarticolocliente         | UUID articolo      | Dropdown anagrafica |
| Valore     | ddtvalorearticolocliente   | Prezzo unitario    | Manuale o da anagrafica |
| Peso       | ddtpesoarticolocliente     | Peso collo         | Manuale |
| Quantità   | ddtarticoloquantitacliente | Quantità           | Manuale |
| Valuta     | ddtvalutacliente           | Valuta transazione | Dropdown valute |
| Listino    | ddtlistinocliente          | % listino          | Dropdown listini |
| Sconto 1   | ddtclientesconto1          | Sconto %           | Manuale |
| Sconto 2   | ddtclientesconto2          | Sconto % cascata   | Manuale |
| Imponibile | ddtimponibilecliente       | Totale riga        | calcImp() automatico |

**✅ Modifica Quantità e Prezzi COMPLETAMENTE LIBERO**: Nessun controllo giacenze, prezzi o limiti sconti

#### Integrazione con Magazzino

**❌ NO - INTEGRAZIONE NON IMPLEMENTATA**:

**Evidenza critica** (documentoDDTsaver.php:1-132):
```php
// Salvataggio DDT completo
R::store($loadedRecordMaster);      // Master
R::store($loadedRecordVectorDetails); // Vettori  
R::store($loadedRecordArticleDetails); // Articoli

// ❌ MANCA: creaMovimentoMagazzino($ddtMaster, $ddtArticoli);
```

**Gap funzionale**:
- DDT salvato → Nessun movimento magazzino generato
- Operatore deve creare movimenti manualmente  
- Rischio disallineamento DDT/giacenze

**Workflow attuale**:
```
1. Crea DDT Cliente con 10 pz Mouse
2. DDT salvato correttamente in DB
3. ❌ Magazzino NON aggiornato (50 pz inalterati)
4. Operatore deve:
   → Magazzino → Tab Scarico
   → Articolo: Mouse  
   → Quantità: 10
   → Riferimento: "DDT-001/2025" (manuale!)
```

**⚠️ Scollegamento DDT-Movimenti NON APPLICABILE**: Collegamento mai creato, campo `riferimento_magazzino` free text

**❌ Quantità Parziali/Consegne Multiple NON GESTITE**: Nessun campo `quantità_evasa` vs `quantità_ordinata`, un DDT = una consegna

**Eliminazione DDT** supportata (documentoDDTsaver.php:117-119):
```php
if($data['status'] == 'delete') {
    R::trash($itemExist); // ⚠️ Eliminazione fisica permanente
}
```

#### Dati e Campi Specifici

**Struttura Tabella documentiddtmaster**:

| Campo                | Tipo        | Descrizione          | Esempio          |
|----------------------|-------------|----------------------|------------------|
| uuid                 | VARCHAR(36) | ID univoco           | "a3f5b2c1-..."   |
| ddtnumero            | VARCHAR     | Numero progressivo   | "DDT/2025/00042" |
| ddtdata              | DATE        | Data emissione       | "2025-10-02"     |
| ddtsoggetto          | INT         | FK cliente/fornitore | 15               |
| ddttype              | VARCHAR     | Tipo DDT             | "cliente"/"fornitore" |
| ddtmodalitapagamento | INT         | Modalità pagamento   | FK tabella modale |
| ddttipopagamento     | INT         | Tipo pagamento       | FK tabella tipi  |
| dttmercecontovendita | BOOLEAN     | Merce in c/vendita   | 0/1              |

**✅ Gestione Vettori COMPLETA** solo per DDT Cliente (tabella `documentiddtvectordetail`):
```php
// Estrazione multipli vettori (documentoDDTsaver.php:46-70)
$vectorLists = array_filter($data, function($key) {
    return strpos($key, 'vectorList_') === 0;
}, ARRAY_FILTER_USE_KEY);
```

**⚠️ Campi Limitati** rispetto a DDT standard:
- ✅ Merce in conto vendita, modalità/tipo pagamento, dati vettore
- ❌ Causale trasporto, aspetto esteriore beni, porto, numero DDT mittente, note generali

**⚠️ Peso, Volume, Colli GESTIONE PARZIALE**: Solo campo peso articolo, mancano volume e calcolo automatico peso totale

#### Numerazione e Protocollo

**✅ Sistema Configurabile** via tabella `numeratori`:
```json
{
    "tiponum": "ddt",
    "progr": 1,                      // Numero iniziale
    "pos": "{{s}}{{a}}/{{p}}",      // Template posizione  
    "val": [true, false, "DDT", "/"] // [Anno, Mese, Prefisso, Separatore]
}
```

**❌ UNICO NUMERATORE** per cliente+fornitore (stessa sequenza)

**Gestione Date**:

| Campo            | Descrizione              | Uso                      |
|------------------|--------------------------|--------------------------|
| ddtdata          | Data emissione DDT       | Obbligatoria, datepicker |
| ❌ data_trasporto | Data effettiva trasporto | NON presente             |
| ❌ data_consegna  | Data consegna merce      | NON presente             |

**⚠️ Controlli Sequenzialità INCOMPLETI**: Auto-incremento garantito ma mancano controlli gap numerazione e blocco backdating

#### Stampa e Formati

**✅ DUE TEMPLATE PDF** separati per tipo DDT:

**Template DDT Cliente** - Codice: DDTCL (pdfcreator.php:28-30)
**Template DDT Fornitore** - Codice: DDTFR (pdfcreator.php:32-34)

**Dati inclusi in stampa DDT Cliente** (DDTCL.php:156-165):
```php
$outSQLarray = [
    'Azienda' => R::getAll($Azienda),  // Dati mittente
    'DDT' => array_merge(
        R::getAll($DDTClienteMaster)[0],      // Intestazione
        R::getAll($DDTImponibile)[0],          // Totale calcolato
        R::getAll($DDTVettoriColliPeso)[0]     // Totali colli/peso
    ),
    'DDTArticoli' => R::getAll($DDTClienteDettaglio),  // Righe dettaglio
    'DDTVettori' => R::getAll($DDTVettoriDettaglio)[0] // Dati trasporto
];
```

**✅ Template Personalizzabili** via ReportBro:
1. ✅ Modifica grafica layout (via ReportBro designer GUI)
2. ✅ Aggiunta/rimozione campi dati disponibili
3. ❌ Aggiunta campi non in query (richiede modifica PHP)

**❌ Copie Multiple NON IMPLEMENTATO**: Nessuna dicitura "COPIA CLIENTE"/"COPIA TRASPORTATORE"

**Formati Disponibili**:

| Formato   | Supportato | Modalità         | Note                       |
|-----------|------------|------------------|----------------------------|
| PDF       | ✅          | Download blob    | Generato via ReportBro     |
| XML       | ❌          | -                | No formato elettronico DDT |
| JSON      | ⚠️         | Solo dati grezzi | Output query (debug)       |
| Excel/CSV | ❌          | -                | Non disponibile            |

#### Workflow e Stati

**❌ NESSUN SISTEMA DI STATI**: Nessun campo stato, workflow bozza→confermato→spedito, DDT sempre "definitivo" al salvataggio

**Workflow Attuale** (semplificato):
```
┌─────────────────────────────────────────────────┐
│  CREAZIONE                                      │
│  Compilazione form → Salvataggio                │
│  ↓                                              │
│  DEFINITIVO                                     │
│  (Nessuna modifica tracciata, solo overwrite)  │
│  ↓                                              │
│  EVENTUALE CONVERSIONE                          │
│  (Solo DDT Cliente → Fattura)                   │
│  ↓                                              │
│  ARCHIVIAZIONE                                  │
│  (Permanente in DB, no eliminazione soft)      │
└─────────────────────────────────────────────────┘
```

**Modifica DDT esistente** (documentoDDTsaver.php:32-41):
```php
if ($itemExist) {
    // ⚠️ SOVRASCRITTURA COMPLETA (no versioning)
    // Elimina vecchi dettagli articoli
    R::trashAll(R::find('documentiddtarticledetail', 'masterddt = ?', [$uuid]));
    
    // Elimina vecchi dettagli vettori (se cliente)  
    R::trashAll(R::find('documentiddtvectordetail', 'masterddt = ?', [$uuid]));
    
    // Salva nuovi dati (no storico)
    R::store($loadedRecordMaster);
}
```

**❌ NESSUN SISTEMA APPROVAZIONI**: Tutti gli utenti possono creare/modificare/eliminare/convertire DDT

#### Ricerca e Consultazione

**✅ Ricerca Full-Text** implementata (ddt_research.php:30-43):
```javascript
$('#textResearch').on('input', function(event) {
    const inputText = $('#textResearch').val();
    
    if(inputText.length >= 2) {  // Minimo 2 caratteri
        var url = 'documenti/ddt_gen_info.php?filter=' + inputText;
        loadContentInDiv(url, 'ddt_gen_info');
    }
});
```

**⚠️ Filtri Limitati** - solo ricerca testuale:
- ✅ Ricerca testuale generica (≥2 caratteri)
- ❌ Filtro per tipo, data, cliente/fornitore, stato, conversioni

**Azioni disponibili su ogni DDT**:

| Icona | Azione              | Funzione                  | Disponibilità             |
|-------|---------------------|---------------------------|---------------------------|
| 📋    | Clona               | cloneDDT(uuid)            | Tutti i DDT               |
| 📝    | Modifica            | loadDDT(uuid)             | Tutti i DDT               |
| 🧾    | Converti in Fattura | convertDDTtoFattura(uuid) | Solo DDT Cliente non convertiti |
| ✅     | Già convertito      | (solo indicatore)         | DDT Cliente convertiti    |

**❌ Storico Modifiche NON DISPONIBILE**: Nessuna tabella `_history`, impossibile vedere chi/quando/cosa è stato modificato

**✅ Collegamento DDT → Fatture TRACCIABILITÀ COMPLETA**:

**Campo tracciamento** (convertDDTtoFattura.php:143):
```php
$fatturaMaster->ddt_origine_uuid = $ddtUuid; // FK al DDT
```

**Verifica conversione esistente** (convertDDTtoFattura.php:53-63):
```php
// Blocco doppia conversione
$fatturaEsistente = R::findOne('fatturemaster', 'ddt_origine_uuid = ?', [$ddtUuid]);

if ($fatturaEsistente) {
    echo json_encode([
        'success' => false,
        'message' => "Il DDT {$ddtMaster->ddtnumero} è già stato convertito 
                      nella fattura {$fatturaEsistente->fattura_numero}"
    ]);
    exit;
}
```

**Report Conversioni** - Tab dedicato (ddt_conversioni_report.php:58-73):
```sql
SELECT 
    f.fattura_numero,
    f.data_fattura, 
    f.totale_fattura,
    d.ddtnumero as ddt_numero,
    d.ddtdata as ddt_data,
    c.nome_cliente
FROM fatturemaster f
LEFT JOIN documentiddtmaster d ON f.ddt_origine_uuid = d.uuid
LEFT JOIN anagraficaclienti c ON f.cliente_id = c.id
WHERE f.ddt_origine_uuid IS NOT NULL AND f.ddt_origine_uuid != ''
ORDER BY f.data_fattura DESC
```

#### Punti di Forza e Limitazioni

**✅ Punti di Forza**:
1. **Gestione Separata Cliente/Fornitore**: Flussi differenziati per uscita/entrata
2. **Dati Trasporto Completi**: Vettori, colli, peso per DDT cliente
3. **Conversione DDT→Fattura**: Automatica con tracciabilità completa
4. **Blocco Doppia Conversione**: Impossibile convertire 2 volte stesso DDT
5. **Numerazione Automatica**: Configurabile con anno/mese/prefisso
6. **Stampa PDF Professionale**: Template personalizzabili via ReportBro
7. **Ricerca Full-Text**: Rapida individuazione documenti
8. **Report Conversioni**: Vista dedicata DDT fatturati

**❌ Limitazioni Critiche**:
1. **NO Integrazione Magazzino**: Movimenti NON generati automaticamente
2. **NO Stati Documento**: Nessun workflow bozza/confermato/spedito
3. **NO Storico Modifiche**: Audit trail assente, nessun versioning
4. **NO Gestione Lotti/Seriali**: Tracciabilità merci limitata
5. **NO Quantità Parziali**: Un DDT = una consegna (no split)
6. **NO DDT Trasferimento**: Solo cliente/fornitore (no interno)
7. **NO Filtri Avanzati**: Solo ricerca testuale generica
8. **NO Approvazioni**: Nessun controllo autorizzazioni
9. **Eliminazione Permanente**: No soft delete, perdita dati
10. **NO Ordini Cliente**: Impossibile generare DDT da ordine

**🎯 Casi d'Uso Ideali**:
- ✅ Spedizioni a clienti con corriere (DDT cliente completo)
- ✅ Ricezione merce fornitori (DDT fornitore semplificato)
- ✅ Fatturazione differita (conversione DDT→Fattura)
- ✅ Archiviazione documenti trasporto
- ✅ Tracciabilità vendite pre-fattura

**⚠️ NON Adatto Per**:
- ❌ Gestione magazzino integrata (richiede double-entry manuale)
- ❌ Tracciabilità lotti (farmaceutico, alimentare)
- ❌ Consegne parziali multiple da stesso ordine
- ❌ Workflow approvazioni complessi
- ❌ Audit trail certificato (compliance normative)
- ❌ Trasferimenti inter-deposito (usare modulo Magazzino)

## FUNZIONALITÀ TRASVERSALI

### Gestione Fornitori/Clienti
Presente in form carico/scarico (movimenti_carico.php:73-85, movimenti_scarico.php:73-85):
- Dropdown selezione cliente (da anagraficaclienti)
- Dropdown selezione fornitore (da anagraficafornitori)
- Opzione "Nessuno" per movimenti interni

### Causali Magazzino
Tabella `causalimagazzino` per tipizzazione operazioni:
- ID 1: Carico standard (default tab Carico)
- ID 2: Scarico standard (default tab Scarico)
- ID 4: Trasferimento (default tab Trasferisci)

### Controlli e Validazioni
1. Deposito obbligatorio: Blocco se valore = 9999 (js/magazzino.js:73-85)
2. Sanitizzazione input: `CommonFunc::sanitizeInput()` su tutti i parametri
3. Validazione form client-side: `verificaForm()` su campi data-required=true
4. Gestione errori AJAX: Messaggi specifici per status 500/404/0

### Report e Stampe
- PDF Stato Giacenze: Funzione `createPDF('STGIAC')`
- Nessun export CSV/Excel esplicito (possibile estensione futura)

### Permessi/Accessi
Non visibile nel codice analizzato - probabile gestione in:
- Session management (`$_SESSION['finsonId']`)
- Sistema multi-tenant (database separati per impresa)

## ASPETTI TECNICI

### Database/Tecnologia
- **ORM**: RedBeanPHP (R::find, R::store, R::dispense)
- **Multi-tenant**: Database separati `impresa_{finsonId}`
- **Connessione dinamica**: `getDb($_SESSION['finsonId'])` (database.inc.php:7-47)

### Tabelle Chiave
- `magazzino` → Registro movimenti
- `totalimagazzino` → Giacenze aggregate (aid, dep, quantita_attuale)
- `anagraficaarticoli` → Anagrafica prodotti
- `depositi` → Anagrafica magazzini
- `causalimagazzino` → Codici causale
- `unitamisura` → Unità di misura

### API/Integrazioni
- **AJAX endpoints**:
  - POST `/saver/magazzinoSaver.php` → Salvataggio movimenti
  - POST `/loaders/getGiacenze.php` → Consultazione giacenze
- **B2B Interop**: Libreria `libs/b2binterop.php` (probabile integrazione EDI/XML per scambio documenti)

### Backup Dati
Non esplicitamente gestito nel modulo - probabile:
- Backup MySQL standard del hosting
- Logging operazioni critiche (`Logger::logEvent()`)

### Logiche di Business Complesse

**Trasferimenti** (magazzinoSaver.php:107-111):
1. Crea movimento scarico da deposito sorgente (trasferimento uscita)
2. Duplica record con `R::dup()`
3. Crea movimento carico in deposito destinazione (trasferimento ingresso)
4. Aggiorna 2 record in `totalimagazzino` (uno per deposito)

**Giacenze in tempo reale** (magazzinoSaver.php:16-42):
- Ogni movimento aggiorna immediatamente `totalimagazzino`
- Se record giacenza non esiste → creazione automatica
- Supporta giacenze negative (scorte esaurite)

**Costi articoli**:
- Costo Euro: Prezzo di acquisto fisso
- Costo Medio: Ponderato su carichi successivi (da implementare logica aggiornamento)

## ESEMPI PRATICI DI UTILIZZO

### Caso 1: Carico da fornitore
1. Tab "Carico" → Selezione fornitore "Acme SRL"
2. Articolo "Vite M8" (codice VIT001)
3. Deposito "Magazzino Centrale"
4. Quantità: 1000
5. Data: 02/10/2025
6. Click "Esegui carico" → AJAX a `magazzinoSaver.php`
7. **Risultato**:
   - Record in `magazzino` (tipo: carico)
   - `totalimagazzino.quantita_attuale += 1000`

### Caso 2: Trasferimento tra depositi
1. Tab "Gestione Magazzino" → Click 🔀 su articolo
2. Compila form trasferimento:
   - Deposito sorgente: "Magazzino Centrale"
   - Deposito destinazione: "Punto Vendita Roma"
   - Quantità: 50
3. Click "Esegui trasferimento"
4. **Risultato**:
   - 2 record in `magazzino` (scarico sorgente + carico destinazione)
   - Giacenza centrale: -50
   - Giacenza Roma: +50

### Caso 3: Consultazione giacenze
1. Tab "Gestione Giacenze"
2. Filtri: Articolo "Tutti", Deposito "Magazzino Centrale"
3. Click "Visualizza giacenze"
4. Lista con quantità disponibili
5. Click 🔍 su articolo → Espansione con:
   - Esistenza: 950
   - Totale carichi: 1000
   - Totale scarichi: 50
   - Valore: €950 × 2.50 = €2,375

## PUNTI DI FORZA

✅ Sistema multi-deposito nativo  
✅ Storico completo movimenti  
✅ Ricerca full-text potente  
✅ Valorizzazione automatica magazzino  
✅ UI intuitiva con tab e azioni rapide  
✅ Supporto varianti prodotto  

## POSSIBILI MIGLIORAMENTI

⚠️ Nessun controllo giacenze negative in fase di scarico  
⚠️ Costo medio non aggiornato automaticamente  
⚠️ Export Excel/CSV non implementato  
⚠️ Nessun audit trail modifiche/cancellazioni  
⚠️ Inventario fisico (conteggio vs teorico) non gestito  

## NOTE PER IMPLEMENTAZIONE NUOVO GESTIONALE

### Priorità Funzionalità da Replicare
1. **Sistema multi-deposito** - Fondamentale per gestione ubicazioni multiple
2. **Movimenti in tempo reale** - Aggiornamento immediato giacenze
3. **Ricerca full-text** - Esperienza utente ottimale
4. **Valorizzazione automatica** - Calcolo valore magazzino
5. **Integrazione con DDT** - Workflow vendita completo

### Miglioramenti da Implementare
1. **Controlli giacenze negative** - Avvisi prima dello scarico
2. **Costo medio dinamico** - Aggiornamento automatico
3. **Export avanzati** - Excel/CSV per analisi
4. **Audit trail completo** - Tracciabilità modifiche
5. **Inventario fisico** - Conteggio vs teorico con scostamenti
6. **Dashboard analytics** - KPI magazzino (rotation rate, dead stock, etc.)

### Considerazioni Tecniche
- Utilizzare transazioni database per movimenti complessi
- Implementare cache per query giacenze frequenti
- Prevedere API REST per integrazioni future
- Sistema di notifiche per soglie minime/massime
- Backup incrementali specifici per movimenti magazzino