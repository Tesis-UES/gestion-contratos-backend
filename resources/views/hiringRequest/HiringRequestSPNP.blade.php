<html>

<head>
    <style>
        html {
            font-size: 62.5%;
            /*10 px */
        }

        body {
            font-size: 1.3rem;
            margin-top: 3cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 3cm;
            font-family: Arial, Helvetica, sans-serif;
        }

        header {
            font-size: 1.4rem;
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 100px;
            /** Extra personal styles **/
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            margin-top: 20px;
            padding: 0px 7.2rem;

        }

        header::after {
            content: '';
            position: absolute;
            bottom: 3rem;
            left: 0;
            height: 0.2rem;
            background: red;
            width: 75vw;
            margin: 0 auto;

        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0%;
            height: 20px;
            /** Extra personal styles **/
            text-align: center;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-text {
            position: relative;
        }

        .header-text p {
            display: block;
            margin: 0 auto;
        }

        .header-text p:last-of-type {
            margin-bottom: 1.75rem
        }

        .title-details {
            width: 100%;
            position: absolute;
            bottom: 2.5rem;
            margin: 0 auto;
            clear: both;
        }

        .title-details span {
            transform: translate(-50%, -50%);
            display: inline-block;
        }

        .fecha {
            display: inline-block;
            margin: 0 auto;
            text-align: right;
            width: 100%;
            position: absolute;
            top: 10rem;
            right: 1rem;
        }

        .saludo-container {
            margin-top: 4.5rem;
            margin-bottom: 2.5rem;
        }

        .saludo {
            text-transform: uppercase;
            display: block;
            text-align: left;

        }
        .despedida{
            display: block;
            text-align: center;
        }

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

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

    </style>
</head>

<body>

    <header>
        <div class="header-container">
            <img src="iconos/Minerva-01.png" alt="ueslogo" width="55" height="75" class="ueslogo" />
            <div class="header-text">
                <p>UNIVERSIDAD DE EL SALVADOR</p>
                <p>FACULTAD DE INGENIERÍA Y ARQUITECTURA</p>
                <p>{{ mb_strtoupper($escuela) }}</p>
            </div>
        </div>
    </header>
    <footer>
       Facultad de Ingeniería y Arquitectura - Universidad de El Salvador
    </footer>
    <main style="page-break-after: auto; margin-top:10px;">
        <span class="fecha">{{ $fecha }}</span>

        <div class="saludo-container">
            <span class="saludo">SEÑORES MIEMBROS DE JUNTA DIRECTIVA</span>
            <span class="saludo">FACULTAD DE INGENIERÍA Y ARQUITECTURA</span>
            <span class="saludo">UNIVERSIDAD DE EL SALVADOR</span>
            <span class="saludo">PRESENTE.</span>

            {!! $hiringRequest->message !!}
        </div>

        <div>
            <table class="demo" width="100%">
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
                            <td>{{ $item->person->first_name . ' ' . $item->person->middle_name . ' ' . $item->person->last_name }}
                            </td>
                            <td>$ {{ $item->subtotal }}</td>
                        </tr>
                    @endforeach

                <tfoot>
                    <tr>
                        <td><strong>Total: </strong></td>
                        <td>$ {{ $hiringRequest->total }}</td>
                    </tr>
                </tfoot>

                </tbody>
            </table>
            <br>
            <br>
            <div class="despedida">
                <span class="despedida">Atentamente,</span>
                <span class="despedida">HACIA LA LIBERTAD POR LA CULTURA</span>
                <br>
                <br>
                <br>
                <span class="despedida">Ing. Rudy Wilfredo Chicas Villegas</span>
                <span class="despedida">Director</span>
                <span class="despedida">{{ ucfirst($escuela) }}</span>
            </div>
        </div>

    </main>
</body>
</html>

