
<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $pokemon->id }}">edit</button>

<div class="modal" id="editModal{{ $pokemon->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pokemon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pokemon.update', $pokemon->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    
                    <div class="form-floating row mb-3">
                        <input type="text" class="form-control" id="name" name="name" value={{ $pokemon->name }} autofocus>
                        <label for="name">Pokemon name</label>
                    </div>
                    <div class="form-floating row mb-3">
                        <input type="number" class="form-control" id="hp" name="hp" value={{ $pokemon->hp }}>
                        <label for="hp">Hp</label>
                    </div>
                    <div class="form-floating row mb-3">
                        <input type="number" class="form-control" id="attack" name="attack" value={{ $pokemon->attack }}>
                        <label for="attack">Attack</label>
                    </div>
                    <div class="form-floating row mb-3">
                        <input type="number" class="form-control" id="defense" name="defense" value={{ $pokemon->defense }}>
                        <label for="defense">Defense</label>
                    </div>
                    <div class="form-floating row mb-3">
                        <input type="number" class="form-control" id="speed" name="speed" value={{ $pokemon->speed }}>
                        <label for="speed">Speed</label>
                    </div>

                    @php
                        $pokemonTypeIds = collect(json_decode(json_encode($pokemon->types), true))->pluck('id')->toArray();
                    @endphp
                    <div>
                        @foreach($types as $type)
                            <input type="checkbox" class="btn-check" id="type-{{ $type->id }}-pokemon-{{ $pokemon->id }}" name="types[]" value="{{ $type->id }}"
                            @if(in_array($type->id, $pokemonTypeIds)) checked @endif>
                            <label class="btn btn-outline-primary" for="type-{{ $type->id }}-pokemon-{{ $pokemon->id }}">{{ $type->name }}</label>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>
