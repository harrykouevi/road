<div>
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header">
                <strong>{{ $roadissueId ? 'Modifier un incident' : 'Ajouter un nouvel incident' }}</strong>
            </div>

            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form wire:submit.prevent="save">
                    @csrf
                    @method('PUT')
                    @error('_mess_') 
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    @error('general') 
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <div class="row">
                        
                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label for="addresse" class="form-label">Addresse</label>
                            <textarea id="addresse" wire:model="addresse" class="form-control" rows="3" placeholder="Décrivez brièvement l'incident..."></textarea>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description de l'incident</label>
                            <textarea id="description" wire:model="description" class="form-control" rows="3" placeholder="Décrivez brièvement l'incident..."></textarea>
                        </div>

                        <!-- Type d'incident -->
                        <div class="col-md-6 mb-3">
                            <label for="report_type_id" class="form-label">Type d'incident</label>
                            <select id="report_type_id" wire:model="report_type_id" class="form-control">
                                <option value="">-- Sélectionnez --</option>
                                @foreach($incidentTypes as $type)
                                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Latitude -->
                        <div class="col-md-3 mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" id="latitude" wire:model="latitude" class="form-control" placeholder="Ex : 6.1712">
                        </div>

                        <!-- Longitude -->
                        <div class="col-md-3 mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" id="longitude" wire:model="longitude" class="form-control" placeholder="Ex : 1.2314">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            {{ $roadissueId ? 'Mettre à jour' : 'Créer l\'incident' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
