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


        .despedida {
            display: block;
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
                <p>FACULTAD DE INGENIERÍA Y ARQUITECTURA</p>
                <p>{{ mb_strtoupper($escuela) }}</p>
            </div>
        </div>
    </header>
    <footer>
        Facultad de Ingeniería y Arquitectura - Universidad de El Salvador
    </footer>
    <main style="page-break-after: auto; margin-top:10px;">
        @php
            $n = 0;
        @endphp
        @foreach ($hiringRequest->details as $detail)
            @php
                $n++;
            @endphp
            <div @if (count($hiringRequest->details) != $n)
                style="page-break-after: always;"
        @endif >
        <span class="despedida"><b>ANEXO {{ $n }}<b></span>
        <span class="despedida"><b>FUNCIONES QUE REALIZARA {{ Str::upper($detail->fullName) }}</b>
        </span>
        <ol>
            @foreach ($detail->mappedActivities as $activity)
                <li>{{ $activity }}</li>
            @endforeach
        </ol>
        </div>
        @endforeach
    </main>
</body>

</html>
