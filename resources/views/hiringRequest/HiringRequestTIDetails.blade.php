<html>

<head>
    <style>
        html {
            font-size: 62.5%;
            /*10 px */
        }

        .despedida {
            display: block;
            text-align: center;
        }

        body {
            font-size: 1.3rem;
            margin: 2.5cm 0.01cm 0.5cm 0.01cm;

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

        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            table-layout: fixed;
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
                <p> FORMATO CONTRATACIONES PARA TIEMPO INTEGRAL</p>
            </div>
        </div>
    </header>
    <footer>
        FIA-UES 2022
    </footer>
    <main style="page-break-after: auto; margin-top:5px;">
        @php
            $n = 0;
        @endphp
        @foreach ($hiringRequest->details as $detail)
            @php
                $n++;
            @endphp
            <div @if (count($hiringRequest->details) != $n)
                style="page-break-after: always; text-align: center;"
            @else
                style="text-align: center;"
        @endif >
        <span style="float: left;"><b>{{ $escuela }}</b></span>
        <span><b>Docente:</b> {{ $detail->fullName }}</span>
        <span style="float:right "><b>Escalafon: {{ $detail->person->employee->escalafon->code }}</b></span>
        <table style="margin-top:10px; " class="demo" width="100%">
            <tbody >
                <tr>
                    <td style="font-weight: bold; background-color: rgba(190, 100, 100, 0.5); text-align: center;"
                        colspan="12">Actividades en Horario normal</b> </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background-color: rgb(255, 255, 255); text-align: center;"
                        colspan="6"><b>Funciones en Jornada Normal</b> </td>
                    <td style="font-weight: bold; background-color: rgb(255, 255, 255); text-align: center;"
                        colspan="6"><b>Horario de Permanencia en Jornada Normal</b> </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background-color: rgb(255, 255, 255); text-align: center;"
                        colspan="6">
                        <ol>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                        </ol>
                    </td>
                    <td style="font-weight: bold; background-color: rgb(255, 255, 255); text-align: center;"
                        colspan="6">
                        <ol>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                            <li>prueba</li>
                        </ol>
                    </td>

                </tr>
                <tr>
                    <td style="font-weight: bold; background-color: rgba(190, 100, 100, 0.5); text-align: center;"
                        colspan="12">Actividades en Tiempo Integral</b> </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background-color:  rgb(192, 192, 192, 0.5); text-align: center;"
                        colspan="3"><b>Asignatura</b> </td>
                    <td style="font-weight: bold; background-color:  rgb(192, 192, 192, 0.5); text-align: center;"
                        colspan="3"><b>Actividad</b> </td>
                    <td style="font-weight: bold; background-color:  rgb(192, 192, 192, 0.5); text-align: center;"
                        colspan="3"><b>Grupo</b> </td>
                    <td style="font-weight: bold; background-color:  rgb(192, 192, 192, 0.5); text-align: center;"
                        colspan="3"><b>Horario</b> </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: center;" colspan="3"><b>Diseño de Sistemas I</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Teorioco</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>1</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Lunes Y Viernes 6:30pm-8:05pm</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: center;" colspan="3"><b>Diseño de Sistemas I</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Teorioco</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>1</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Lunes Y Viernes 6:30pm-8:05pm</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: center;" colspan="3"><b>Diseño de Sistemas I</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Teorioco</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>1</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Lunes Y Viernes 6:30pm-8:05pm</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: center;" colspan="3"><b>Diseño de Sistemas I</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Teorioco</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>1</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Lunes Y Viernes 6:30pm-8:05pm</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: center;" colspan="3"><b>Diseño de Sistemas I</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Teorioco</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>1</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Lunes Y Viernes 6:30pm-8:05pm</b>
                    </td>
                </tr>
                
                <tr>
                    <td style="font-weight: bold;  text-align: center;" colspan="3"><b>Diseño de Sistemas I</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Teorioco</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>1</b> </td>
                    <td style="font-weight: bold; text-align: center;" colspan="3"><b>Lunes Y Viernes 6:30pm-8:05pm</b>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5); text-align: Left;" colspan="2"><b>Funciones en tiempo Integral:</b>
                        </td>
                    <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="10"><b>Ver Anexo</b>
                        </td>

                </tr>
                <tr>
                    <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5);  text-align: Left;" colspan="2"><b>Justifiacion:</b></td>
                    <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="10"><b>fghdfghgsfdfghdfghdfghdfghdfgdgfhgjfghfghjfghjfghjfghjfghjfghjfghjfghjfghjfghjfghjfghjfghjfghjjfghjffhgjghjsdfgsdfgsdfgdsfsdfgsdfsdfgsdfgsdfgsdfgsdfggsdfgsdfgsdfgsdfgsdfggñlkajh </td>

                </tr>
                <tr>
                    <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5);  text-align: Left;" colspan="2"><b>Meta</b>
                    </td>
                    <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="10"><b>fvgbfdgsdfgdfgdsfgsdfgsdfgsd</b>
                        </td>

                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: Left; background-color: rgba(148, 255, 134, 0.5);" colspan="2"><b>Periodo de Contratación</b>
                    </td>
                    <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="10"><b>fecha</b>
                        </td>

                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: Left; background-color: rgba(148, 255, 134, 0.5);" colspan="2"><b>Periodo de Contratación en meses</b>
                    </td>
                    <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="10"><b>fecha</b>
                        </td>

                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: Left; background-color: rgba(148, 255, 134, 0.5);" colspan="2"><b>Salario Mensual en Jornada Norma</b>
                    </td>
                    <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="10"><b>fecha</b>
                        </td>

                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: Left; background-color: rgba(148, 255, 134, 0.5);" colspan="2"><b>Indicador de Procentaje a pagar</b>
                    </td>
                    <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="10"><b>fecha</b>
                        </td>

                </tr>
                <tr>
                    <td style="font-weight: bold;  text-align: Left; background-color: rgba(2, 247, 255, 0.5);" colspan="3"><b>Indicador de Procentaje a pagar</b>
                    </td>
                    <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="3"><b>fecha</b>
                        </td>
                        <td style="font-weight: bold;  text-align: Left; background-color: rgba(2, 247, 255, 0.5);" colspan="3"><b>Indicador de Procentaje a pagar</b>
                        </td>
                        <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="3"><b>fecha</b>
                            </td>

                </tr>
            </tbody>
        </table>







        </div>

        @endforeach

        <br>
        <br>
        {{-- <div class="despedida">
            <br>
            <br>
            <br>
            <span class="despedida">___________________________________</span>
            <span
                class="despedida">{{ $hiringRequest->school->SchoolAuthority->where('position', 'DIRECTOR')->first()->name }}</span>
            <span class="despedida">Director</span>
            <span class="despedida">{{ ucfirst($escuela) }}</span>
        </div> --}}
    </main>
</body>

</html>
