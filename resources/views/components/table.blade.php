

@extends('index')

@section('content')

    <h1>hi from table</h1>

  
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>HP</th>
                        <th>Attack</th>
                        <th>Defense</th>
                        <th>Speed</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pokemons as $pokemon)
                        <tr>
                            <td>{{ $pokemon->id }}</td>
                            <td>{{ $pokemon->name }}</td>
                            <td>{{ $pokemon->hp }}</td>
                            <td>{{ $pokemon->attack }}</td>
                            <td>{{ $pokemon->defense }}</td>
                            <td>{{ $pokemon->speed }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

@endsection
