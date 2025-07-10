@extends('layouts.app')

@section('title', __('app.edit_stock') . ' - Gestionale Negozio')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3><i class="bi bi-pencil"></i> {{ __('app.edit_stock') }}</h3>
                <p class="mb-0 text-muted">{{ $magazzino->prodotto->nome }} - {{ $magazzino->taglia }} - {{ $magazzino->colore }}</p>
            </div>
            <div class="card-body">
                <form action="{{ route('magazzino.update', $magazzino) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="prodotto_id" class="form-label">{{ __('app.product') }} *</label>
                        <select class="form-select @error('prodotto_id') is-invalid @enderror"
                                id="prodotto_id" name="prodotto_id" required>
                            <option value="">{{ __('app.select_product') }}...</option>
                            @foreach($prodotti as $prodotto)
                            <option value="{{ $prodotto->id }}" {{ old('prodotto_id', $magazzino->prodotto_id) == $prodotto->id ? 'selected' : '' }}>
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
                                <label for="taglia" class="form-label">{{ __('app.size') }} *</label>
                                <select class="form-select @error('taglia') is-invalid @enderror"
                                        id="taglia" name="taglia" required>
                                    <option value="">{{ __('app.select_size') }}...</option>
                                    <option value="XXS" {{ old('taglia', $magazzino->taglia) == 'XXS' ? 'selected' : '' }}>XXS</option>
                                    <option value="XS" {{ old('taglia', $magazzino->taglia) == 'XS' ? 'selected' : '' }}>XS</option>
                                    <option value="S" {{ old('taglia', $magazzino->taglia) == 'S' ? 'selected' : '' }}>S</option>
                                    <option value="M" {{ old('taglia', $magazzino->taglia) == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="L" {{ old('taglia', $magazzino->taglia) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="XL" {{ old('taglia', $magazzino->taglia) == 'XL' ? 'selected' : '' }}>XL</option>
                                    <option value="XXL" {{ old('taglia', $magazzino->taglia) == 'XXL' ? 'selected' : '' }}>XXL</option>
                                    <option value="XXXL" {{ old('taglia', $magazzino->taglia) == 'XXXL' ? 'selected' : '' }}>XXXL</option>
                                    <option value="4XL" {{ old('taglia', $magazzino->taglia) == '4XL' ? 'selected' : '' }}>4XL</option>
                                    <option value="5XL" {{ old('taglia', $magazzino->taglia) == '5XL' ? 'selected' : '' }}>5XL</option>
                                    <!-- Taglie numeriche -->
                                    <option value="38" {{ old('taglia', $magazzino->taglia) == '38' ? 'selected' : '' }}>38</option>
                                    <option value="40" {{ old('taglia', $magazzino->taglia) == '40' ? 'selected' : '' }}>40</option>
                                    <option value="42" {{ old('taglia', $magazzino->taglia) == '42' ? 'selected' : '' }}>42</option>
                                    <option value="44" {{ old('taglia', $magazzino->taglia) == '44' ? 'selected' : '' }}>44</option>
                                    <option value="46" {{ old('taglia', $magazzino->taglia) == '46' ? 'selected' : '' }}>46</option>
                                    <option value="48" {{ old('taglia', $magazzino->taglia) == '48' ? 'selected' : '' }}>48</option>
                                    <option value="50" {{ old('taglia', $magazzino->taglia) == '50' ? 'selected' : '' }}>50</option>
                                    <option value="52" {{ old('taglia', $magazzino->taglia) == '52' ? 'selected' : '' }}>52</option>
                                    <option value="54" {{ old('taglia', $magazzino->taglia) == '54' ? 'selected' : '' }}>54</option>
                                    <option value="56" {{ old('taglia', $magazzino->taglia) == '56' ? 'selected' : '' }}>56</option>
                                </select>
                                @error('taglia')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="colore" class="form-label">{{ __('app.color') }} *</label>
                                <select class="form-select @error('colore') is-invalid @enderror"
                                        id="colore" name="colore" required>
                                    <option value="">{{ __('app.select_color') }}...</option>
                                    <option value="Nero" {{ old('colore', $magazzino->colore) == 'Nero' ? 'selected' : '' }}>{{ __('app.black') }}</option>
                                    <option value="Bianco" {{ old('colore', $magazzino->colore) == 'Bianco' ? 'selected' : '' }}>{{ __('app.white') }}</option>
                                    <option value="Grigio" {{ old('colore', $magazzino->colore) == 'Grigio' ? 'selected' : '' }}>{{ __('app.gray') }}</option>
                                    <option value="Rosso" {{ old('colore', $magazzino->colore) == 'Rosso' ? 'selected' : '' }}>{{ __('app.red') }}</option>
                                    <option value="Blu" {{ old('colore', $magazzino->colore) == 'Blu' ? 'selected' : '' }}>{{ __('app.blue') }}</option>
                                    <option value="Verde" {{ old('colore', $magazzino->colore) == 'Verde' ? 'selected' : '' }}>{{ __('app.green') }}</option>
                                    <option value="Giallo" {{ old('colore', $magazzino->colore) == 'Giallo' ? 'selected' : '' }}>{{ __('app.yellow') }}</option>
                                    <option value="Arancione" {{ old('colore', $magazzino->colore) == 'Arancione' ? 'selected' : '' }}>{{ __('app.orange') }}</option>
                                    <option value="Rosa" {{ old('colore', $magazzino->colore) == 'Rosa' ? 'selected' : '' }}>{{ __('app.pink') }}</option>
                                    <option value="Viola" {{ old('colore', $magazzino->colore) == 'Viola' ? 'selected' : '' }}>{{ __('app.purple') }}</option>
                                    <option value="Marrone" {{ old('colore', $magazzino->colore) == 'Marrone' ? 'selected' : '' }}>{{ __('app.brown') }}</option>
                                    <option value="Beige" {{ old('colore', $magazzino->colore) == 'Beige' ? 'selected' : '' }}>{{ __('app.beige') }}</option>
                                    <option value="Bordeaux" {{ old('colore', $magazzino->colore) == 'Bordeaux' ? 'selected' : '' }}>{{ __('app.bordeaux') }}</option>
                                    <option value="Navy" {{ old('colore', $magazzino->colore) == 'Navy' ? 'selected' : '' }}>{{ __('app.navy') }}</option>
                                    <option value="Azzurro" {{ old('colore', $magazzino->colore) == 'Azzurro' ? 'selected' : '' }}>{{ __('app.light_blue') }}</option>
                                    <option value="Fucsia" {{ old('colore', $magazzino->colore) == 'Fucsia' ? 'selected' : '' }}>{{ __('app.fuchsia') }}</option>
                                    <option value="Oro" {{ old('colore', $magazzino->colore) == 'Oro' ? 'selected' : '' }}>{{ __('app.gold') }}</option>
                                    <option value="Argento" {{ old('colore', $magazzino->colore) == 'Argento' ? 'selected' : '' }}>{{ __('app.silver') }}</option>
                                    <option value="Multicolore" {{ old('colore', $magazzino->colore) == 'Multicolore' ? 'selected' : '' }}>{{ __('app.multicolor') }}</option>
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
                                <label for="quantita" class="form-label">{{ __('app.quantity') }} *</label>
                                <input type="number" class="form-control @error('quantita') is-invalid @enderror"
                                       id="quantita" name="quantita" min="0" value="{{ old('quantita', $magazzino->quantita) }}" required>
                                @error('quantita')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="scorta_minima" class="form-label">{{ __('app.minimum_stock') }} *</label>
                                <input type="number" class="form-control @error('scorta_minima') is-invalid @enderror"
                                       id="scorta_minima" name="scorta_minima" min="0" value="{{ old('scorta_minima', $magazzino->scorta_minima) }}" required>
                                @error('scorta_minima')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('app.low_stock_notification') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('magazzino.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> {{ __('app.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> {{ __('app.update_stock') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-info-circle"></i> {{ __('app.stock_information') }}</h5>
            </div>
            <div class="card-body">
                <p><strong>{{ __('app.product') }}:</strong> {{ $magazzino->prodotto->nome }}</p>
                <p><strong>{{ __('app.code') }}:</strong> {{ $magazzino->prodotto->codice_prodotto }}</p>
                <p><strong>{{ __('app.price') }}:</strong> €{{ number_format($magazzino->prodotto->prezzo, 2) }}</p>
                <p><strong>{{ __('app.category') }}:</strong> {{ $magazzino->prodotto->categoria }}</p>
                <p><strong>{{ __('app.created_on') }}:</strong> {{ $magazzino->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>{{ __('app.last_modified') }}:</strong> {{ $magazzino->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="bi bi-graph-up"></i> {{ __('app.quick_actions') }}</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success" onclick="document.getElementById('quantita').value = parseInt(document.getElementById('quantita').value) + 10">
                        <i class="bi bi-plus"></i> {{ __('app.add_10_pieces') }}
                    </button>
                    <button type="button" class="btn btn-warning" onclick="document.getElementById('quantita').value = Math.max(0, parseInt(document.getElementById('quantita').value) - 5)">
                        <i class="bi bi-dash"></i> {{ __('app.subtract_5_pieces') }}
                    </button>
                    <button type="button" class="btn btn-info" onclick="document.getElementById('quantita').value = 0">
                        <i class="bi bi-x-circle"></i> {{ __('app.clear_stock') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection