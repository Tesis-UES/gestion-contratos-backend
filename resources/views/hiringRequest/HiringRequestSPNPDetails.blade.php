<html>

<head>
    <style>
        html {
            font-size: 62.5%;
            /*10 px */
        }

        body {
            font-size: 1.3rem;
            margin: 3cm 0.01cm 0.5cm 0.01cm;

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

        footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0%;
            height: 20px;
            /** Extra personal styles **/
            text-align: center;
            font-size: 1.3rem;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-text {
            position: relative;
            border-bottom: 0.6rem solid red;
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

        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #000000;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: rgb(192, 192, 192, 0.5);
            text-align: center;
        }

    </style>
</head>

<body>

    <header>
        <div class="header-container">
            <img src="iconos/Minerva-01.png" alt="ueslogo" width="55" height="75" class="ueslogo" />
            <div class="header-text">
                <p>UNIVERSIDAD DE EL SALVADOR</p>
                <p>FACULTAD DE INGENIERÍA Y ARQUITECTURA </p>
                <p> FORMATO CONTRATACIONES PARA SERVICIOS PROFESIONALES</p>
            </div>
        </div>
        <div class="title-details">
            <span style="position:absolute; top:1rem;left:10rem; text-align:right;">Escuela o Unidad: Escuela de
                Sistemas Informaticos</span>
            <span style="position:absolute; top:1rem;right:1rem;">Fecha: 10 de febrero del 2022</span>
        </div>
    </header>
    <footer>
        FIA-UES 2022
    </footer>
    <main>
        <table style="page-break-inside: auto !important; margin-top:10px;" class="demo" width="100%">
            <!-- <table style ="page-break-inside: avoid !important; margin-top:10px;" class="demo" width="100%"> -->
            <thead>
                <tr>
                  {{--   <th>Nombre del Docente</th> --}}
                    <th>Asignatura</th>
                    <th>Numero de Grupo</th>
                    <th>Dias a Impartir</th>
                    <th>Horario</th>
                    <th>Periodo de Contratación</th>
                    <th>Funciones</th>
                    <th>Valor a Pagar por Hora</th>
                    <th>Semanas a Pagar</th>
                    <th>Horas por semana</th>
                    <th>Total de Horas </th>
                    <th>Total a pagar</th>
                </tr>
                <thead>
                <tbody>
                    @foreach ($hiringRequest->details as $detail)
                    <tr>
                        <td style="font-weight: bold; background-color: rgba(190, 100, 100, 0.5); text-align: center;" colspan="11">Nombre del Docente:<b> {{ $detail->fullName }}</b>  </td>
                      </tr>
                        <tr style="page-break-inside: avoid !important;">
                           {{-- <td rowspan={{ count($detail->mappedGroups) + 1 }}>{{ $detail->fullName }}</td> --}} 
                            <td>{{ $detail->mappedGroups[0]->name }}</td>
                            <td>{{ $detail->mappedGroups[0]->groupType }}</td>
                            <td>{{ $detail->mappedGroups[0]->days }}</td>
                            <td>{{ $detail->mappedGroups[0]->time }}</td>
                            <td>{{ $detail->period }}</td>
                            <td>Anexo 2</td>
                            <td>$ {{ $detail->mappedGroups[0]->hourly_rate }}</td>
                            <td>{{ $detail->mappedGroups[0]->work_weeks }}</td>
                            <td>{{ $detail->mappedGroups[0]->weekly_hours }}</td>
                            <td>{{ $detail->mappedGroups[0]->work_weeks * $detail->mappedGroups[0]->weekly_hours }}</td>
                            <td>$ {{ $detail->mappedGroups[0]->work_weeks *$detail->mappedGroups[0]->weekly_hours *$detail->mappedGroups[0]->hourly_rate }}</td>
                        </tr>
                       
                        @for ($i = 1; $i < count($detail->mappedGroups); $i++)
                            <tr>
                                <td>{{ $detail->mappedGroups[$i]->name }}</td>
                                <td>{{ $detail->mappedGroups[$i]->groupType }}</td>
                                <td>{{ $detail->mappedGroups[$i]->days }}</td>
                                <td>{{ $detail->mappedGroups[$i]->time }}</td>
                                <td>{{ $detail->period }}</td>
                                <td>Anexo n</td>
                                <td>$ {{ $detail->mappedGroups[$i]->hourly_rate }}</td>
                                <td>{{ $detail->mappedGroups[$i]->work_weeks }}</td>
                                <td>{{ $detail->mappedGroups[$i]->weekly_hours }}</td>
                                <td>{{ $detail->mappedGroups[$i]->work_weeks * $detail->mappedGroups[$i]->weekly_hours }}</td>
                                <td>$ {{ $detail->mappedGroups[$i]->work_weeks *$detail->mappedGroups[$i]->weekly_hours *$detail->mappedGroups[$i]->hourly_rate }}</td>
                            </tr>
                           
                           
                        @endfor
                        <tr>
                            <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5); text-align: center;" colspan="9">Sub Total</td>
                            <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5);">{{$detail->subtotalHoras}}</td> 
                            <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5);"> ${{$detail->subtotal}}</td>
                            
                          </tr>
                    @endforeach
                </tbody>
        </table>

    </main>
</body>

</html>
