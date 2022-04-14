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

        .header-text p:last-of-type {}

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
                <p>DETALLE DE MONTOS TOTALES POR SOLICITUD DE ESCUELA</p>
            </div>
        </div>
    </header>
    <footer>

    </footer>
    <main style="page-break-after: auto; margin-top:10px;">
        <div>
            <span>
                <p style="text-align: center;">
                    <strong>
                        <u>
                           Reporte generado segun las fechas: {{date('d/m/Y',strtotime($fechaInicio))}} al {{date('d/m/Y',strtotime($fechaFin))}}
                        </u>
                    </strong>
                </p>
                
            </span>

            <table class="demo" width="100%">
                <caption><b>
                        <h3>{{ $escuela }}</h3>
                    </b></caption>
                <thead>
                    <tr>
                        <th>Modalidad de contratación</th>
                        <th>Monto Total</th>





                    </tr>
                </thead>
                <tbody>

                    <tr>
                        <td style="text-align: center;"><b>Servicios Profesionales No Personales</b></td>
                        <td style="text-align: center;">$ {{ number_format($spnpTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><b>Tiempo Adicional</b></td>
                        <td style="text-align: center;">$ {{ number_format($taTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><b>Tiempo Integral</b></td>
                        <td style="text-align: center;">$ {{ number_format($tiTotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><b>Total </b></td>
                        <td style="text-align: center;">$ {{ number_format($spnpTotal + $taTotal + $tiTotal, 2) }}</td>
                    </tr>

                <tfoot>


                </tfoot>

                </tbody>
            </table>
            <br>


            <div class="despedida">


            </div>
        </div>

    </main>
</body>

</html>
