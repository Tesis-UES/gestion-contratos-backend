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
        
    </footer>
    <main style="page-break-after: auto; margin-top:5px;">
        @php
            $n = 0;
        @endphp
        @foreach ($hiringRequest->details as $detail)
            @php
                $n++;
            @endphp
            <div
                @if (count($hiringRequest->details) != $n) style="page-break-after: always; text-align: center;"
            @else
                style="text-align: center;" @endif>
                <span style="float: left;"><b>{{ $header->escuela }}</b></span>
                <span><b>Docente:</b> {{ $detail->fullName }}</span>
                <span style="float:right "><b>Escalafon: {{ $detail->person->employee->escalafon->code }}</b></span>
                <table style="margin-top:10px; " class="demo" width="100%">
                    <tbody>
                        <tr>
                            <td style="font-weight: bold; background-color: rgba(190, 100, 100, 0.5); text-align: center;"
                                colspan="12">Actividades en Horario normal</b> </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5); text-align: center;"
                                colspan="6"><b>Funciones en Jornada Normal</b> </td>
                            <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5); text-align: center;"
                                colspan="6"><b>Horario de Permanencia en Jornada Normal</b> </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; background-color: rgb(255, 255, 255); text-align: center;"
                                colspan="6">
                                Ver Anexo
                            </td>
                            <td style="font-weight: bold; background-color: rgb(255, 255, 255); text-align: center;"
                                colspan="6">
                                Ver Anexo
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

                        @foreach ($detail->mappedGroups as $group)
                            <tr>
                                <td style="font-weight: bold;  text-align: center;" colspan="3">
                                    <b>{{ $group->name }}</b> </td>
                                <td style="font-weight: bold; text-align: center;" colspan="3">
                                    <b>{{ $group->groupType }}</b> </td>
                                <td style="font-weight: bold; text-align: center;" colspan="3">
                                    <b>{{ $group->number }}</b> </td>
                                <td style="font-weight: bold; text-align: center;" colspan="3">
                                    <b>{{ $group->days . ' ' . $group->time }}</b>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5); text-align: Left;"
                                colspan="3"><b>Funciones en tiempo Integral:</b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="9"><b>Ver
                                    Anexo</b>
                            </td>

                        </tr>
                        <tr>
                            <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5);  text-align: Left;"
                                colspan="3"><b>Justifiacion:</b></td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="9">
                                <b>{{ $detail->justification }}</b>
                            </td>

                        </tr>
                        <tr>
                            <td style="font-weight: bold; background-color: rgb(192, 192, 192, 0.5);  text-align: Left;"
                                colspan="3"><b>Meta</b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="9">
                                <b>{{ $detail->goal }}</b>
                            </td>

                        </tr>
                        <tr>
                            <td style="font-weight: bold;  text-align: Left; background-color: rgb(192, 192, 192, 0.5);"
                                colspan="3"><b>Periodo de Contratación</b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="9">
                                <b>{{ 'Del ' . date('d/m/Y', strtotime($detail->start_date)) . ' Hasta ' . date('d/m/Y', strtotime($detail->finish_date)) }}</b>
                            </td>

                        </tr>
                        <tr>
                            <td style="font-weight: bold;  text-align: Left; background-color: rgb(192, 192, 192, 0.5);"
                                colspan="3"><b>Periodo de Contratación en meses</b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="9">
                                <b>{{ $detail->work_months }}</b>
                            </td>

                        </tr>
                        <tr>
                            <td style="font-weight: bold;  text-align: Left; background-color: rgb(192, 192, 192, 0.5);"
                                colspan="3"><b>Salario Mensual en Jornada Normal</b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="9"><b>$
                                    {{ number_format($detail->monthly_salary, 2) }}</b>
                            </td>

                        </tr>
                        <tr>
                            <td style="font-weight: bold;  text-align: Left; background-color:rgb(192, 192, 192, 0.5);"
                                colspan="3"><b>Indicador de Procentaje a pagar</b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="9">
                                <b>{{ sprintf('%.2f', $detail->salary_percentage * 100) }}%</b>
                            </td>

                        </tr>
                        <tr>
                            <td style="font-weight: bold;  text-align: Left; background-color:rgba(190, 100, 100, 0.5);"
                                colspan="3"><b>Valor a pagar por mes </b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="3">
                                <b>${{ number_format($detail->monthly_salary * $detail->salary_percentage, 2) }}</b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; background-color: rgba(190, 100, 100, 0.5);"
                                colspan="3"><b>Total a pagar</b>
                            </td>
                            <td style="font-weight: bold;  text-align: Left; word-wrap: break-word" colspan="3">
                                <b>${{ number_format($detail->work_months * $detail->monthly_salary * $detail->salary_percentage, 2) }}</b>
                            </td>

                        </tr>
                    </tbody>
                </table>
                <div style="page-break-before: always;">
                    <span><b>Anexo de Funciones y horario de permanencia del docente: </b>
                        {{ $detail->fullName }}</span>
                    {{-- make a list --}}
                    <div>
                        <table class="table table-bordered" align="center" width="50%">
                            <thead>
                                <tr>
                                    <th style="font-weight: bold; text-align: center; background-color: rgba(190, 100, 100, 0.5);"
                                        colspan="2"><b>Horario de Permanencia en Jornada Normal</b>
                                    </th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($detail->staySchedule as $stay)
                                    <tr>
                                        <td style="font-weight: bold; text-align: center;" colspan="2">
                                            <b>{{ $stay }}</b></td>
                                    </tr>
                                @endforeach



                            </tbody>
                        </table>
                        <br>
                        <table class="table table-bordered" align="center" width="75%">
                            <thead>
                                <tr>
                                    <th style="font-weight: bold; text-align: center; background-color: rgba(190, 100, 100, 0.5);"
                                        colspan="2"><b>Funciones en Jornada Normal</b>
                                    </th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($detail->stayActivities as $act)
                                    <tr>
                                        <td style="font-weight: bold; text-align: left;" colspan="2">
                                            <b>{{ $act }}</b></td>
                                    </tr>
                                @endforeach



                            </tbody>
                        </table>
                        <br>
                        <div style="page-break-after: always;"> </div>
                        <span class="despedida"><b>CARGOS Y FUNCIONES QUE REALIZARA
                                {{ Str::upper($detail->fullName) }} EN TIEMPO INTEGRAL</b> </span>

                            @foreach ($detail->positionActivities as $activity)
                            <table class="table table-bordered" align="center" width="75%">
                                <thead>
                                    <tr>
                                        <th style="font-weight: bold; text-align: center; background-color: rgba(190, 100, 100, 0.5);"
                                            colspan="2"><b>Funciones del cargo {{ $activity['position'] }}</b>
                                        </th>
                                    </tr>
            
                                </thead>
                                <tbody>
                              
                                    @foreach ($activity['activities'] as $act)
                                       <tr>
                                    <td style="font-weight: bold; text-align: left;" colspan="2"><b>{{ $act }}</b></td>
                                </tr>
                                    @endforeach

                                
                            </tbody>
                            </table>
                            @endforeach
                        
                    </div>

                </div>
            </div>
        @endforeach

        <br>
        <br>
        <div class="despedida">
            <br>
            <br>
            <br>
            <span class="despedida">___________________________________</span>
            <span
                class="despedida">{{ $hiringRequest->school->SchoolAuthority->where('position', 'DIRECTOR')->first()->name }}</span>
            <span class="despedida">Director</span>
            <span class="despedida">{{ ucfirst($header->escuela) }}</span>
        </div>
    </main>
</body>

</html>
