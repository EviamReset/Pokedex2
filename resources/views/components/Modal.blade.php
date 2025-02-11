@section('modal')
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPokemon">Add Pokemon</button>

    <div class="modal" id="addPokemon" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New Pokemon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ url()}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        
                        <div class="form-floating row mb-3">
                            <input type="text" class="form-control" id="name" name="name" autofocus>
                            <label for="name">Pokemon name</label>
                        </div>
                        <div class="form-floating row mb-3">
                            <input type="number" class="form-control" id="hp" name="hp" v-model="pokemon.hp">
                            <label for="hp">Hp</label>
                        </div>
                        <div class="form-floating row mb-3">
                            <input type="number" class="form-control" id="attack" name="attack"
                                v-model="pokemon.attack">
                            <label for="attack">Attack</label>
                        </div>
                        <div class="form-floating row mb-3">
                            <input type="number" class="form-control" id="defense" name="defense"
                                v-model="pokemon.defense">
                            <label for="defense">Defense</label>
                        </div>
                        <div class="form-floating row mb-3">
                            <input type="number" class="form-control" id="speed" name="speed" v-model="pokemon.speed">
                            <label for="speed">Speed</label>
                        </div>
                        <div>
                            @foreach($types as $type)
                                <input type="checkbox" class="btn-check" id="{{ $type->id}}" >
                                <label class="btn btn-outline-primary" for="{{ $type->id}}">{{ $type->name }}</label>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="insert">Save Pokemon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection