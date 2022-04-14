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
                <p>NUMERO DE CONTRATACIONES DE ESCUELA POR CANDIDATO</p>
            </div>
        </div>
    </header>
    <footer>
        
    </footer>
    <main style="page-break-after: auto; margin-top:10px;">
        <div>
           
        
            <table class="demo" width="100%">
                <caption><b><h3>{{$escuela}}</h3></b></caption>
                <thead>
                    <tr>
                        <th>Nombre Candidato</th>
                        <th>Serivicios Profesionales No Personales</th>
                        <th>Tiempo Integral</th>
                        <th>Tiempo Adicional</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    
                     @foreach ($reportInfo as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            </td>
                            <td style="text-align: center;">{{ $item['spnp'] }}</td>
                            <td style="text-align: center;">{{ $item['ti'] }}</td>
                            <td style="text-align: center;">{{ $item['ta'] }}</td>
                            {{-- total --}}
                            @php
                                $total = $item['spnp'] + $item['ti'] + $item['ta'];
                            @endphp
                            <td style="text-align: center;"><b>{{ $total }}</b></td>

                        </tr>
                    @endforeach   

                <tfoot>
                   {{-- calculate each total --}}
                     @php
                            $totalSPNP = 0;
                            $totalTI = 0;
                            $totalTA = 0;
                            $total = 0;
                            foreach ($reportInfo as $item) {
                             $totalSPNP += $item['spnp'];
                             $totalTI += $item['ti'];
                             $totalTA += $item['ta'];
                             $total += $item['spnp'] + $item['ti'] + $item['ta'];
                            }
                      @endphp
                     
                </tfoot>
                <tr>
                    <td><b>TOTAL</b></td>
                    <td style="text-align: center;"><b>{{ $totalSPNP }}</b></td>
                    <td style="text-align: center;"><b>{{ $totalTI }}</b></td>
                    <td style="text-align: center;"><b>{{ $totalTA }}</b></td>
                    <td style="text-align: center;"><b>{{ $total }}</b></td>
              </tr>
                </tbody>
            </table>
            <br>
          
            
            <div class="despedida">
                
               
            </div>
        </div>

    </main>
</body>

</html>
