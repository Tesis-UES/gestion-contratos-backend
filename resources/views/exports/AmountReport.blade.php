<table>
    <thead>
        <tr>
            <th><b>No.</b></th>
            <th><b>Nombre Candidato</b></th>
            <th><b>Monto Periodo</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($informacion->candidatos as $ct)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $ct['name']}}</td>
                <td>$ {{number_format($ct['subtotal'],2)}}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td> - </td>
            <td><b>Total</b></td>
            <td><b>$ {{number_format($informacion->total,2)}}</b></td>
        </tr>
    </tfoot>
</table>
