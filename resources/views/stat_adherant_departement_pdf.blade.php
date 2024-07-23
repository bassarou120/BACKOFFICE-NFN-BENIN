<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>

<h1>Statistique des membres du NFN par départements  </h1>

<table  class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Départements</th>
        <th> Total adhérants</th>

        <th> Hommes</th>

        <th> Femmes</th>

        <th>  Diplôme CEP</th>

        <th>  Diplôme BEPC</th>

        <th>  Diplôme BAC</th>

        <th>  Licence</th>

        <th>   Master</th>
        <th>   Doctorat</th>

        <th>  Autre Diplôme</th>

    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr>
            <td>{{ $record->departement }}  </td>
            <td>{{ $record->total_adherant }}</td>
            <td>{{ $record->homme }}</td>
            <td>{{ $record->femme }}</td>
            <td>{{ $record->cep }}</td>
            <td>{{ $record->bepc }}</td>
            <td>{{ $record->bac }}</td>
            <td>{{ $record->licence }}</td>
            <td>{{ $record->master }}</td>
            <td>{{ $record->doctorat }}</td>
            <td>{{ $record->autre }}</td>

        </tr>
    @endforeach
    </tbody>
</table>


</body>
</html>
