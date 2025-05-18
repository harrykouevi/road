<div>
    <h2>Validation de l'incident</h2>

    <p><strong>Description :</strong> {{ $incident->description }}</p>
    <p><strong>Date :</strong> {{ $incident->created_at }}</p>

    <hr>

    <div>
        <label for="comment">Commentaire (obligatoire si refus)</label>
        <textarea wire:model="comment" class="border p-2 w-full"></textarea>
        @error('comment') <span class="text-red-500">{{ $message }}</span> @enderror
    </div>

    <div class="mt-4 space-x-2">
        <button wire:click="validateIncident" class="bg-green-500 text-white px-4 py-2">Valider</button>
        <button wire:click="refuseIncident" class="bg-red-500 text-white px-4 py-2">Refuser</button>
    </div>
</div>
