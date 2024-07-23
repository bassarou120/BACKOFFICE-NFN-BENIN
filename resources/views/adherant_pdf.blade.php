<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>

<h1>Liste des membre de NFN </h1>

<table  class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Nom et prénom</th>
        <th>Téléphone</th>
        <th>Email</th>
        <th>commune</th>
        <th>Cartier</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($records as $record)
        <tr>
            <td>{{ $record->nom }} {{ $record->prenom }}</td>
            <td>{{ $record->telephone }}</td>
            <td>{{ $record->email }}</td>
            <td>{{ $record->commune->libelle }}</td>
            <td>{{ $record->quartier->libelle }}</td>
            <td>{{ $record->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>


</body>
</html>
