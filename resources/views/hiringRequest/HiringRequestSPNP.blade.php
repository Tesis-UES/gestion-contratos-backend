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
            margin-left: 1.5cm;
            margin-right: 1.5cm;
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

        .despedida {
            display: block;
            text-align: center;
        }

        .demo {
            border: 1px sólido #000000;

            padding: 5px;
        }

        .demo th {
            border: 1px sólido #000000;

            background-color: rgba(243, 55, 55, 0.5);
        }

        .demo td {
            border: 1px sólido #000000;

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
                <p>{{ mb_strtoupper($header->escuela) }}</p>
            </div>
        </div>
    </header>
    <footer>
        
    </footer>
    <main style="page-break-after: auto; margin-top:10px;">
        <span class="fecha">{{ $header->fecha }}</span>

        <div class="saludo-container">
            <span class="saludo">SEÑORES MIEMBROS DE JUNTA DIRECTIVA</span>
            <span class="saludo">FACULTAD DE INGENIERÍA Y ARQUITECTURA</span>
            <span class="saludo">UNIVERSIDAD DE EL SALVADOR</span>
            <span class="saludo">PRESENTE.</span>

            {!! $hiringRequest->message !!}
        </div>

        <div>
            <table class="demo" width="100%">
                <caption><b>Personas a Contratar en {{$hiringRequest->contractType->name}}</b></caption>
                <caption><b>en {{$hiringRequest->modality}}</b></caption>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Monto Periodo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hiringRequest->details as $item)
                        <tr>
                            <td>{{ $item->person->first_name . ' ' . $item->person->middle_name . ' ' . $item->person->last_name }}
                            </td>
                            <td style="text-align: center;">$ {{ number_format($item->subtotal,2) }}</td>
                        </tr>
                    @endforeach

                <tfoot>
                    <tr>
                        <td style="font-weight: bold; background-color: rgba(243, 55, 55, 0.5); text-align: center;"><strong>Total: </strong></td>
                        <td style="font-weight: bold; background-color: rgba(243, 55, 55, 0.5); text-align: center;">$ {{ number_format($hiringRequest->total,2) }}</td>
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
                <span class="despedida">{{$hiringRequest->school->SchoolAuthority->where('position', 'DIRECTOR')->first()->name;}}</span>
                <span class="despedida">Director</span>
                <span class="despedida">{{ ucfirst($header->escuela) }}</span>
            </div>
        </div>

    </main>
</body>

</html>
