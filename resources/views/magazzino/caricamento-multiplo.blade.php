@extends('layouts.app')

@section('title', 'Caricamento Multiplo - Gestionale Negozio')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-upload"></i> Caricamento Multiplo Varianti</h3>
                <p class="mb-0 text-muted">Carica rapidamente tutte le varianti di taglia e colore per un prodotto</p>
            </div>
            <div class="card-body">
                <form action="{{ route('magazzino.salva-multiplo') }}" method="POST" id="form-multiplo">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="prodotto_id" class="form-label">Prodotto *</label>
                                <select class="form-select @error('prodotto_id') is-invalid @enderror" 
                                        id="prodotto_id" name="prodotto_id" required>
                                    <option value="">Seleziona prodotto...</option>
                                    @foreach($prodotti as $prodotto)
                                        <option value="{{ $prodotto->id }}" {{ old('prodotto_id') == $prodotto->id ? 'selected' : '' }}>
                                            {{ $prodotto->nome }} ({{ $prodotto->codice_prodotto }}) - €{{ number_format($prodotto->prezzo, 2) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('prodotto_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="scorta_minima_globale" class="form-label">Scorta Minima (per tutte) *</label>
                                <input type="number" class="form-control @error('scorta_minima_globale') is-invalid @enderror" 
                                       id="scorta_minima_globale" name="scorta_minima_globale" min="0" value="{{ old('scorta_minima_globale', 5) }}" required>
                                @error('scorta_minima_globale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h5>Selezione Rapida Taglie</h5>
                            <div class="btn-group-vertical w-100" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="selezionaTaglie(['S', 'M', 'L', 'XL'])">
                                    Taglie Standard (S, M, L, XL)
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="selezionaTaglie(['XS', 'S', 'M', 'L', 'XL', 'XXL'])">
                                    Taglie Complete (XS → XXL)
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="selezionaTaglie(['42', '44', '46', '48', '50'])">
                                    Taglie Numeriche (42-50)
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Selezione Rapida Colori</h5>
                            <div class="btn-group-vertical w-100" role="group">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selezionaColori(['Nero', 'Bianco', 'Grigio'])">
                                    Colori Base
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selezionaColori(['Nero', 'Bianco', 'Rosso', 'Blu', 'Verde'])">
                                    Colori Popolari
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="selezionaColori(['Nero', 'Bianco', 'Grigio', 'Rosso', 'Blu', 'Verde', 'Giallo', 'Rosa'])">
                                    Gamma Completa
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Varianti da Creare</h5>
                        <div>
                            <button type="button" class="btn btn-success btn-sm" onclick="generaGriglia()">
                                <i class="bi bi-grid"></i> Genera Griglia
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="svuotaGriglia()">
                                <i class="bi bi-trash"></i> Svuota
                            </button>
                        </div>
                    </div>

                    <div id="griglia-varianti" class="mb-4">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> 
                            Seleziona taglie e colori sopra, poi clicca "Genera Griglia" per creare tutte le combinazioni.
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('magazzino.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Annulla
                        </a>
                        <button type="submit" class="btn btn-primary" id="btn-salva" disabled>
                            <i class="bi bi-check-circle"></i> Salva Tutte le Varianti
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let taglieSelezionate = [];
let coloriSelezionati = [];

function selezionaTaglie(taglie) {
    taglieSelezionate = [...taglie];
    updateSelectionDisplay();
}

function selezionaColori(colori) {
    coloriSelezionati = [...colori];
    updateSelectionDisplay();
}

function updateSelectionDisplay() {
    // Aggiorna la visualizzazione delle selezioni (opzionale)
}

function generaGriglia() {
    if (taglieSelezionate.length === 0 || coloriSelezionati.length === 0) {
        alert('Seleziona almeno una taglia e un colore!');
        return;
    }

    const griglia = document.getElementById('griglia-varianti');
    let html = '<div class="table-responsive"><table class="table table-bordered table-sm">';
    
    // Header
    html += '<thead><tr><th>Taglia</th>';
    coloriSelezionati.forEach(colore => {
        html += `<th>${colore}</th>`;
    });
    html += '</tr></thead><tbody>';
    
    // Righe
    let index = 0;
    taglieSelezionate.forEach(taglia => {
        html += `<tr><td><strong>${taglia}</strong></td>`;
        coloriSelezionati.forEach(colore => {
            html += `
                <td>
                    <input type="hidden" name="varianti[${index}][taglia]" value="${taglia}">
                    <input type="hidden" name="varianti[${index}][colore]" value="${colore}">
                    <input type="number" class="form-control form-control-sm" 
                           name="varianti[${index}][quantita]" 
                           min="0" value="0" 
                           placeholder="Qtà"
                           style="width: 80px;">
                </td>
            `;
            index++;
        });
        html += '</tr>';
    });
    
    html += '</tbody></table></div>';
    
    griglia.innerHTML = html;
    document.getElementById('btn-salva').disabled = false;
}

function svuotaGriglia() {
    document.getElementById('griglia-varianti').innerHTML = `
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> 
            Seleziona taglie e colori sopra, poi clicca "Genera Griglia" per creare tutte le combinazioni.
        </div>
    `;
    document.getElementById('btn-salva').disabled = true;
}
</script>
@endsection