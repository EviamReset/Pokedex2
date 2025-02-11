@extends('index')

@extends('components.modal')

@section('content')


    <h1>Pokédex</h1>
  
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>HP</th>
                        <th>Attack</th>
                        <th>Defense</th>
                        <th>Speed</th>
                        <th>Types</th>
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
                            <td>
                                @foreach($pokemon->types as $type)
                                    {{ $type->type_name }}
                                @endforeach
                            </td>
                            <td>
                                <button class="btn btn-danger">delete</button>
                            </td>
                            <td>
                                <button class="btn btn-warning">edit</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        
@endsection
