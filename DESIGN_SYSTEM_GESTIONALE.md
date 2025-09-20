# Design System - Gestionale Negozio Verde

## 📋 Indice
1. [Palette Colori](#palette-colori)
2. [Button System](#button-system)
3. [Tabelle](#tabelle)
4. [Sistema Responsive](#sistema-responsive)
5. [Layout Structure](#layout-structure)
6. [Animazioni ed Effetti](#animazioni-ed-effetti)
7. [Form e Modal](#form-e-modal)
8. [Best Practices](#best-practices)

---

## 🎨 Palette Colori

### Colori Principali
```css
/* Gradiente principale gestionale */
--primary-gradient: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);

/* Gradiente button standard */
--button-gradient: linear-gradient(135deg, #667eea, #764ba2);

/* Gradiente sfondo generale */
--background-gradient: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
```

### Action Colors
```css
/* Button Azioni */
--action-view: linear-gradient(135deg, #48cae4, #0077b6);    /* Azzurro */
--action-edit: linear-gradient(135deg, #ffd60a, #ff8500);    /* Giallo */
--action-delete: linear-gradient(135deg, #f72585, #c5025a);  /* Rosa */
--action-success: linear-gradient(135deg, #029D7E, #4DC9A5); /* Verde */
--action-warning: linear-gradient(135deg, #ffc107, #e0a800); /* Arancione */
--action-secondary: linear-gradient(135deg, #6c757d, #545b62); /* Grigio */
```

### Status Colors
```css
/* Stati */
--status-active: #d4edda / #155724
--status-inactive: #f8d7da / #721c24
--badge-primary: rgba(102, 126, 234, 0.1) / #667eea
```

---

## 🔘 Button System

### Button Standard (.modern-btn)
```css
.modern-btn {
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.modern-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.modern-btn:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
}
```

### Action Buttons (.action-btn)
```css
.action-btn {
    border: none;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 0.8rem;
    font-weight: 600;
    margin: 0 2px;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    color: white;
}
```

### Mobile Action Buttons (.mobile-action-btn)
```css
.mobile-action-btn {
    flex: 1;
    min-width: 80px;
    border: none;
    border-radius: 10px;
    padding: 12px 8px;
    font-size: 0.8rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.3rem;
    text-align: center;
}

.mobile-action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    color: white;
}
```

### ❌ COSA NON FARE CON I BUTTON
- **NON usare effetti velo bianco** (::before pseudo-element)
- **NON usare gradienti diversi** da quelli stabiliti
- **NON cambiare hover effects** - sempre translateY(-2px)

---

## 📊 Tabelle

### Tabella Standard (.modern-table)
```css
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.modern-table thead th {
    background: linear-gradient(135deg, #029D7E 0%, #4DC9A5 100%);
    color: white;
    font-weight: 600;
    border: none;
    padding: 1rem;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.modern-table td {
    padding: 1rem;
    border-bottom: 1px solid rgba(226, 232, 240, 0.3);
    background: rgba(255, 255, 255, 0.8);
    vertical-align: middle;
}

.modern-table tbody tr {
    transition: all 0.3s ease;
    border: none;
}

.modern-table tbody tr:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: scale(1.01);
}
```

### Container Tabella
```css
/* NIENTE contenitore bianco - solo overflow */
.table-container {
    overflow: hidden;
}
```

---

## 📱 Sistema Responsive

### Struttura Base
1. **Desktop**: Mostra tabella, nascondi mobile cards
2. **Mobile (< 768px)**: Nascondi tabella, mostra mobile cards
3. **Mobile Small (< 576px)**: Layout verticale ottimizzato

### CSS Media Queries
```css
/* Mobile Cards - Nascoste di default */
.mobile-cards {
    display: none;
}

/* Card Mobile */
.card-mobile {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.card-mobile:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Responsive Breakpoints */
@media (max-width: 768px) {
    /* Nasconde tabella su mobile */
    .table-container .table-responsive {
        display: none;
    }
    
    .mobile-cards {
        display: block;
    }
    
    .management-container {
        padding: 1rem;
    }
    
    .management-header, .search-filters {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
}

@media (max-width: 576px) {
    .management-header .d-flex {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .mobile-action-btn {
        padding: 10px 6px;
        font-size: 0.7rem;
        min-width: 70px;
    }
}
```

### Template HTML Mobile Cards
```html
<!-- Cards Mobile -->
<div class="mobile-cards" id="mobileCards">
    @forelse($items as $item)
        <div class="item-card mobile-item-row" data-attribute1="value1" data-attribute2="value2">
            
            <div class="item-card-header">
                <h3 class="item-card-title">{{ $item->title }}</h3>
                <div class="d-flex flex-column align-items-end">
                    <span class="item-card-code">{{ $item->code }}</span>
                    <span class="status-badge">{{ $item->status }}</span>
                </div>
            </div>
            
            <div class="item-card-details">
                <div class="item-detail">
                    <span class="item-detail-label">Campo 1</span>
                    <span class="item-detail-value">{{ $item->field1 }}</span>
                </div>
                <div class="item-detail">
                    <span class="item-detail-label">Campo 2</span>
                    <span class="item-detail-value">{{ $item->field2 }}</span>
                </div>
            </div>
            
            <div class="item-card-actions">
                <button type="button" class="mobile-action-btn view" onclick="viewItem({{ $item->id }})">
                    <i class="bi bi-eye"></i>
                    <span>Visualizza</span>
                </button>
                <button type="button" class="mobile-action-btn edit" onclick="editItem({{ $item->id }})">
                    <i class="bi bi-pencil"></i>
                    <span>Modifica</span>
                </button>
                <button type="button" class="mobile-action-btn delete" onclick="deleteItem({{ $item->id }})">
                    <i class="bi bi-trash"></i>
                    <span>Elimina</span>
                </button>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="bi bi-icon"></i>
            <h4>Nessun dato trovato</h4>
            <p>Messaggio di stato vuoto</p>
        </div>
    @endforelse
</div>
```

---

## 🏗️ Layout Structure

### Header Pagina
```html
<div class="management-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center">
            <h1 class="management-title">
                <i class="bi bi-icon me-3" style="color: #f39c12; font-size: 2rem;"></i>
                Titolo Pagina
            </h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('back.route') }}" class="btn btn-secondary modern-btn">
                <i class="bi bi-arrow-left"></i> Torna Indietro
            </a>
            <button type="button" class="btn btn-success modern-btn" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="bi bi-plus-lg"></i> Nuovo Elemento
            </button>
            <button type="button" class="btn btn-warning modern-btn" onclick="exportData()">
                <i class="bi bi-download"></i> Esporta
            </button>
        </div>
    </div>
</div>
```

### Container Principale
```css
.management-container {
    padding: 2rem;
    min-height: calc(100vh - 70px);
}

.management-header {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}
```

### Filtri e Ricerca
```html
<div class="search-filters">
    <div class="row g-3">
        <div class="col-md-4">
            <input type="text" class="form-control search-input" id="searchInput" placeholder="Cerca..." onkeyup="filterTable()">
        </div>
        <div class="col-md-3">
            <select class="form-select filter-select" id="filter1" onchange="filterTable()">
                <option value="">Tutti</option>
                <option value="value1">Opzione 1</option>
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select filter-select" id="filter2" onchange="filterTable()">
                <option value="">Tutti</option>
                <option value="value2">Opzione 2</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-secondary modern-btn w-100" onclick="resetFilters()">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </button>
        </div>
    </div>
</div>
```

---

## ✨ Animazioni ed Effetti

### Hover Effects Standard
```css
/* Button Hover */
transform: translateY(-2px);
box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);

/* Card Hover */
transform: translateY(-5px);
box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);

/* Table Row Hover */
background: rgba(102, 126, 234, 0.05);
transform: scale(1.01);
```

### Transizioni Standard
```css
transition: all 0.3s ease;
```

### Filtro Mobile Cards (JavaScript)
```javascript
// Animazione show/hide mobile cards
if (shouldShow) {
    card.style.display = '';
    setTimeout(() => {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 100);
} else {
    card.style.opacity = '0';
    card.style.transform = 'translateY(-20px)';
    setTimeout(() => {
        card.style.display = 'none';
    }, 300);
}
```

---

## 📝 Form e Modal

### Modal Standard
```html
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 20px 20px 0 0;">
                <h5 class="modal-title">
                    <i class="bi bi-plus-lg me-2"></i>Titolo Modal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <!-- Form content -->
            </div>
            <div class="modal-footer" style="padding: 1.5rem 2rem; border-top: 1px solid #e9ecef;">
                <button type="button" class="btn btn-secondary modern-btn" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Annulla
                </button>
                <button type="submit" class="btn btn-success modern-btn">
                    <i class="bi bi-check-lg"></i> Salva
                </button>
            </div>
        </div>
    </div>
</div>
```

### Input Fields
```css
.search-input, .filter-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.search-input:focus, .filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}
```

---

## ✅ Best Practices

### 1. Struttura File
- Sempre estendere `layouts.app`
- CSS inline nella vista (per isolamento)
- JavaScript alla fine della vista

### 2. Naming Convention
- Classi: `kebab-case` (es: `management-header`)
- IDs: `camelCase` (es: `searchInput`)
- Data attributes: `kebab-case` (es: `data-status`)

### 3. Responsive
- Desktop first, poi mobile
- Sempre testare su mobile
- Mobile cards obbligatorie per tabelle

### 4. Performance
- Utilizzare `backdrop-filter: blur(10px)` per glassmorphism
- Transizioni CSS invece di JavaScript quando possibile
- Debounce sui filtri di ricerca

### 5. Accessibilità
- Sempre `title` sui button di azione
- Focus states per navigazione keyboard
- Alt text per immagini

### 6. JavaScript
```javascript
// Template filtri base
function filterTable() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filter1 = document.getElementById('filter1').value;
    
    // Filtro tabella desktop
    const rows = document.querySelectorAll('#tableBody tr:not(#noResults)');
    let visibleCount = 0;
    
    rows.forEach(row => {
        // Logica filtro tabella
    });
    
    // Filtro mobile cards
    const mobileCards = document.querySelectorAll('.mobile-item-row');
    let mobileVisibleCount = 0;
    
    mobileCards.forEach(card => {
        // Logica filtro cards
    });
}

function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filter1').value = '';
    filterTable();
}
```

---

## 🎯 Checklist per Nuove Viste

### ✅ Design
- [ ] Palette colori corretta
- [ ] Button con gradienti giusti
- [ ] Nessun effetto velo bianco
- [ ] Tabella con header verde
- [ ] Glassmorphism effect

### ✅ Responsive
- [ ] Mobile cards implementate
- [ ] CSS breakpoints corretti
- [ ] Layout verticale su mobile small
- [ ] Touch-friendly button

### ✅ JavaScript
- [ ] Filtri funzionano su tabella E mobile cards
- [ ] Animazioni smooth
- [ ] Reset filtri funzionale
- [ ] CRUD operations

### ✅ UX/UI
- [ ] Loading states
- [ ] Empty states
- [ ] Error handling
- [ ] Success feedback

---

*Questa documentazione deve essere seguita per ogni nuova vista per mantenere coerenza e qualità nel gestionale.*