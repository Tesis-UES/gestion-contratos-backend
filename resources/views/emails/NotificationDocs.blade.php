@component('mail::message')
<h1 style="text-align: center;">Universidad de El Salvador</h1>
<h1 style="text-align: center;">Facultad de Ingeniería y Arquitectura</h1>
<h1 style="text-align: center;">Gestión de Contratos De Personal Docente - FIA-UES</h1>
<h2 style="text-align: center;"></h2>
<br>
<br>
Buen día:
<br>
<br>
<p style='text-align: justify;'>
{!!$mensaje!!}
</p>

Para verificar dichos datos ingrese al sistema.

Gracias,<br>
{{ config('app.name') }}
@endcomponent
