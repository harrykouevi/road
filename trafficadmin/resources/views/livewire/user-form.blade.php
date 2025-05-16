<div>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form wire:submit.prevent="save">
      


        @csrf @method('PUT')

        <div class="form-group">
            <label>Name</label>
            <input wire:model="name"  class="form-control" required />
             @error('name') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="form-group mt-2">
            <label>Email</label>
            <input wire:model="email"  class="form-control" required />
             @error('email') <span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <button type="submit" class="btn btn-success">
            {{ $userId ? 'Mettre à jour' : 'Enregistrer' }}
        </button>
    </form>
</div>
