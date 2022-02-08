<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css" />
    <title>Solicitud de Docente Asesor Para tesis Grupo</title>
</head>
<style type="text/css">
    .container-header {
        display: block;
        text-align: right;
        margin: 0 8%;
        height: 150px;
        font-family: Arial, Helvetica, sans-serif;
    }

    .container-images {
        display: flex;
        border: none;
        text-align: center;
        justify-items: center;
        font-family: Arial, Helvetica, sans-serif;
    }

    .header-solicitud {
        border-bottom: 4px solid #ff0000;
    }

    span {
        display: block;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 22px;
        color: #05317c;
        margin-top: none;
        margin-block-start: none;
    }

    img {
        display: left;
    }

    .fecha {
        display: block;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        color: #000000;
        margin-top: none;
        margin-block-start: none;

    }

    .container-cuerpo-JD {
        display: block;
        text-align: left;
        margin: 0 8%;
        height: 250px;
        font-size: 14px;
    }

    .container-saludo-JD {
        display: block;
        border: none;
        font-family: Arial, Helvetica, sans-serif;

    }

    p {
        display: block;
        text-align: justify;
        font-size: 14px;

    }


    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

<body>
    <div class="container-header">
        <div class="container-images">
            <img src="iconos/LOGO_MINERVA.jpg" alt="ueslogo" width="55" height="75" />
            <div class="header-solicitud">
                <div>UNIVERSIDAD DE EL SALVADOR</div>
                <div>FACULTAD DE INGENIERÍA Y ARQUITECTURA </div>
                <div>{{ mb_strtoupper($escuela) }}</div>
                <br>
            </div>

        </div>
        <br>
        <span class="fecha">{{ $fecha }}</span>
        <br>
        <br>
    </div>
    <div class="container-cuerpo-JD">
        <div class="container-saludo-JD">
            <div>SEÑORES MIEMBROS DE JUNTA DIRECTIVA</div>
            <div>FACULTAD DE INGENIERÍA Y ARQUITECTURA</div>
            <div>UNIVERSIDAD DE EL SALVADOR</div>
            <div>PRESENTE.</div>
            <br>
            <p>Estimados Señores:</p>
            <br>
            <p>{!! $hiringRequest->message !!}
                <br>
                <br>
                <style>
                    .demo {
                        border: 1px sólido #C0C0C0;
                        border-collapse: colapso;
                        padding: 5px;
                    }

                    .demo th {
                        border: 1px sólido #C0C0C0;
                        padding: 5px;
                        background: #F0F0F0;
                    }

                    .demo td {
                        border: 1px sólido #C0C0C0;
                        padding: 5px;
                    }
                </style>
            <div>
                <table class="demo">
                    <caption>Personas a Contratar</caption>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Monto Ciclo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hiringRequest->details as $item)
                        <tr>
                            <td>{{ $item->person->first_name . ' ' . $item->person->middle_name . ' ' . $item->person->last_name }}</td>
                            <td>{{($item->subtotal)}}</td>
                        </tr>

                        @endforeach

                    <tfoot>
                        <tr>
                            <td><strong>Total: </strong></td>
                            <td>{{$hiringRequest->total}}</td>
                        </tr>
                    </tfoot>

                    </tbody>
                </table>
            </div>


        </div>
    </div>
    <br>
</body>

</html>