<div>
   

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($successMessage)
                <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                    {{ $successMessage }}
                </div>
            @endif
            @error('_mess_') 
                <span class="text-danger">{{ $message }}</span>
            @enderror
            
            
            {{-- Affichage de l'image --}}
            @if(!empty($roadissue['image']))
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $roadissue['image']) }}" 
                        alt="Image du problème signalé" 
                        class="img-fluid rounded shadow-sm" 
                        style="max-height: 400px;">
                </div>
            @endif

            {{-- Détails de l'incident --}}
            <div class="mb-3">
                <p><strong>Adresse :</strong> {{ $roadissue['addresse'] }}</p>
                <p><strong>Description :</strong> {{ $roadissue['description'] }}</p>
                <p><strong>Date :</strong> {{ $roadissue['created_at'] }}</p>
            </div>

            <hr>

            {{-- Champ de commentaire --}}
            <div class="mb-3">
                <label for="comment" class="form-label">Commentaire (obligatoire si refus)</label>
                <textarea wire:model="comment" id="comment" rows="3" class="form-control"></textarea>
                @error('comment') 
                    <div class="text-danger mt-1">{{ $message }}</div> 
                @enderror
            </div>
            @error('general') 
            <div class="mb-4">
                <hr>
                <span class="text-danger">{{ $message }}</span>
            </div>
            @enderror

            {{-- Boutons de validation --}}
            <div class="d-flex gap-2">
                
                @if( $roadissue['validated_at'] == Null  &&   $successMessage == ""  )
                
                    <button wire:click="validateRoadissue" class="btn btn-success">
                        Valider
                    </button>
                    <button wire:click="refuseRoadissue" class="btn btn-danger mx-2">
                        Refuser
                    </button>
                @else
                    <button wire:click="deleteRoadissue" class="btn btn-danger">
                        Supprimer
                    </button>
                @endif
            </div>
            
        </div>
    </div>

</div>
