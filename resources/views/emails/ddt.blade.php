<x-mail::message>
# DDT {{ $ddt->numero_ddt }}

Gentile {{ $ddt->cliente->nome_completo }},

Le inviamo in allegato il Documento di Trasporto relativo alla sua vendita.

**Dettagli DDT:**
- **Numero:** {{ $ddt->numero_ddt }}
- **Data:** {{ $ddt->data_ddt->format('d/m/Y') }}
- **Causale:** {{ $ddt->causale }}
- **Destinatario:** {{ $ddt->destinatario_completo }}

@if($ddt->trasportatore)
**Trasportatore:** {{ $ddt->trasportatore }}
@endif

@if($ddt->note)
**Note:** {{ $ddt->note }}
@endif

Il documento PDF è allegato a questa email.

Grazie per la fiducia,<br>
{{ config('app.name') }}
</x-mail::message>