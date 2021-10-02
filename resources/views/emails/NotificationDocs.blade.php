@component('mail::message')
<h1 style="text-align: center;">Universidad de El Salvador</h1>
<h1 style="text-align: center;">Facultad de Ingenieria y Arquitectura</h1>
<h1 style="text-align: center;">Procesos Administrativos</h1>
<h2 style="text-align: center;"></h2>
<br>
<br>
Buen Dia.

{{$mensaje}}

Para verificar dichos datos ingrese al sistema dando click al boton.


@component('mail::button', ['url' => 'https://gestion-contratos.vercel.app/'])
Ir al Sitio
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
