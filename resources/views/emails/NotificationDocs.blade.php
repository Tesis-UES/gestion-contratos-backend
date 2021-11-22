@component('mail::message')
<h1 style="text-align: center;">Universidad de El Salvador</h1>
<h1 style="text-align: center;">Facultad de Ingeniería y Arquitectura</h1>
<h1 style="text-align: center;">Procesos Administrativos</h1>
<h2 style="text-align: center;"></h2>
<br>
<br>
Buen Día:
<br>
<br>
<p style='text-align: justify;'>
{!!$mensaje!!}
</p>

Para verificar dichos datos ingrese al sistema dando click al botón.


@component('mail::button', ['url' => 'https://gestion-contratos.vercel.app/'])
Ir al Sitio
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
