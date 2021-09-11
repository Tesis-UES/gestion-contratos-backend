@component('mail::message')
<h1 style="text-align: center;">Universidad de El Salvador</h1>
<h1 style="text-align: center;">Facultad de Ingenieria y Arquitectura</h1>
<h1 style="text-align: center;">Procesos Administrativos</h1>
<h2 style="text-align: center;"></h2>
<br>
<br>
Buen Dia.

Se le comunica que ha sido enrolado en el Sistema de Gestión de Contratos
de la Facultad de Ingenieria y Arquitectura, para poder ingresar al sistema
sus credenciales son las siguiente.

- __Usuario:__      {{$user}}
- __Contraseña:__   {{$password}}

Al ingresar por primera vez se le sugiere cambiar su contraseña, en el menu de acciones.


@component('mail::button', ['url' => 'https://gestion-contratos.vercel.app/'])
Ir al Sitio
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
