@extends('layouts.app')

@section('title', 'Nuova Scorta - Gestionale Negozio')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-plus-circle"></i> Nuova Scorta</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('magazzino.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="prodotto_id" class="form-label">Prodotto *</label>
                        <select class="form-select @error('prodotto_id') is-invalid @enderror" 
                                id="prodotto_id" name="prodotto_id" required>
                            <option value="">Seleziona prodotto...</option>
                            @foreach($prodotti as $prodotto)
                                <option value="{{ $prodotto->id }}" {{ old('prodotto_id') == $prodotto->id ? 'selected' : '' }}>
                                    {{ $prodotto->nome }} ({{ $prodotto->codice_prodotto }})
                                </option>
                            @endforeach
                        </select>
                        @error('prodotto_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="taglia" class="form-label">Taglia *</label>
                                <select class="form-select @error('taglia') is-invalid @enderror" 
                                        id="taglia" name="taglia" required>
                                    <option value="">Seleziona taglia...</option>
                                    <option value="XXS" {{ old('taglia') == 'XXS' ? 'selected' : '' }}>XXS</option>
                                    <option value="XS" {{ old('taglia') == 'XS' ? 'selected' : '' }}>XS</option>
                                    <option value="S" {{ old('taglia') == 'S' ? 'selected' : '' }}>S</option>
                                    <option value="M" {{ old('taglia') == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="L" {{ old('taglia') == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="XL" {{ old('taglia') == 'XL' ? 'selected' : '' }}>XL</option>
                                    <option value="XXL" {{ old('taglia') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                    <option value="XXXL" {{ old('taglia') == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                                    <option value="4XL" {{ old('taglia') == '4XL' ? 'selected' : '' }}>4XL</option>
                                    <option value="5XL" {{ old('taglia') == '5XL' ? 'selected' : '' }}>5XL</option>
                                    <!-- Taglie numeriche -->
                                    <option value="38" {{ old('taglia') == '38' ? 'selected' : '' }}>38</option>
                                    <option value="40" {{ old('taglia') == '40' ? 'selected' : '' }}>40</option>
                                    <option value="42" {{ old('taglia') == '42' ? 'selected' : '' }}>42</option>
                                    <option value="44" {{ old('taglia') == '44' ? 'selected' : '' }}>44</option>
                                    <option value="46" {{ old('taglia') == '46' ? 'selected' : '' }}>46</option>
                                    <option value="48" {{ old('taglia') == '48' ? 'selected' : '' }}>48</option>
                                    <option value="50" {{ old('taglia') == '50' ? 'selected' : '' }}>50</option>
                                    <option value="52" {{ old('taglia') == '52' ? 'selected' : '' }}>52</option>
                                    <option value="54" {{ old('taglia') == '54' ? 'selected' : '' }}>54</option>
                                    <option value="56" {{ old('taglia') == '56' ? 'selected' : '' }}>56</option>
                                </select>
                                @error('taglia')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="colore" class="form-label">Colore *</label>
                                <select class="form-select @error('colore') is-invalid @enderror" 
                                        id="colore" name="colore" required>
                                    <option value="">Seleziona colore...</option>
                                    <option value="Nero" {{ old('colore') == 'Nero' ? 'selected' : '' }}>Nero</option>
                                    <option value="Bianco" {{ old('colore') == 'Bianco' ? 'selected' : '' }}>Bianco</option>
                                    <option value="Grigio" {{ old('colore') == 'Grigio' ? 'selected' : '' }}>Grigio</option>
                                    <option value="Rosso" {{ old('colore') == 'Rosso' ? 'selected' : '' }}>Rosso</option>
                                    <option value="Blu" {{ old('colore') == 'Blu' ? 'selected' : '' }}>Blu</option>
                                    <option value="Verde" {{ old('colore') == 'Verde' ? 'selected' : '' }}>Verde</option>
                                    <option value="Giallo" {{ old('colore') == 'Giallo' ? 'selected' : '' }}>Giallo</option>
                                    <option value="Arancione" {{ old('colore') == 'Arancione' ? 'selected' : '' }}>Arancione</option>
                                    <option value="Rosa" {{ old('colore') == 'Rosa' ? 'selected' : '' }}>Rosa</option>
                                    <option value="Viola" {{ old('colore') == 'Viola' ? 'selected' : '' }}>Viola</option>
                                    <option value="Marrone" {{ old('colore') == 'Marrone' ? 'selected' : '' }}>Marrone</option>
                                    <option value="Beige" {{ old('colore') == 'Beige' ? 'selected' : '' }}>Beige</option>
                                    <option value="Bordeaux" {{ old('colore') == 'Bordeaux' ? 'selected' : '' }}>Bordeaux</option>
                                    <option value="Navy" {{ old('colore') == 'Navy' ? 'selected' : '' }}>Navy</option>
                                    <option value="Azzurro" {{ old('colore') == 'Azzurro' ? 'selected' : '' }}>Azzurro</option>
                                    <option value="Fucsia" {{ old('colore') == 'Fucsia' ? 'selected' : '' }}>Fucsia</option>
                                    <option value="Oro" {{ old('colore') == 'Oro' ? 'selected' : '' }}>Oro</option>
                                    <option value="Argento" {{ old('colore') == 'Argento' ? 'selected' : '' }}>Argento</option>
                                    <option value="Multicolore" {{ old('colore') == 'Multicolore' ? 'selected' : '' }}>Multicolore</option>
                                </select>
                                @error('colore')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="quantita" class="form-label">Quantità *</label>
                                <input type="number" class="form-control @error('quantita') is-invalid @enderror" 
                                       id="quantita" name="quantita" min="0" value="{{ old('quantita', 0) }}" required>
                                @error('quantita')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="scorta_minima" class="form-label">Scorta Minima *</label>
                                <input type="number" class="form-control @error('scorta_minima') is-invalid @enderror" 
                                       id="scorta_minima" name="scorta_minima" min="0" value="{{ old('scorta_minima', 5) }}" required>
                                @error('scorta_minima')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Quando la quantità scende sotto questo valore, riceverai un avviso.</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Nota:</strong> Assicurati che la combinazione prodotto + taglia + colore non esista già nel magazzino.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('magazzino.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Annulla
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Salva Scorta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-lightbulb"></i> Suggerimenti</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><i class="bi bi-check text-success"></i> Imposta una scorta minima per ricevere avvisi</li>
                    <li><i class="bi bi-check text-success"></i> Ogni combinazione prodotto/taglia/colore è unica</li>
                    <li><i class="bi bi-check text-success"></i> Puoi aggiornare le quantità in seguito</li>
                    <li><i class="bi bi-check text-success"></i> Le vendite aggiorneranno automaticamente le scorte</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection