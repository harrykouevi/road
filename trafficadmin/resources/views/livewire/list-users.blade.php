<div>
 <!-- Content Row -->
    <div class="row">

    
    <div class=" @if ($selectedUser) col-md-4 @else d-none @endif">
        @if ($selectedUser)
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title"><strong>Email :</strong>{{ $selectedUser['email'] }}</h5>
                    <p class="card-text"><strong>Nom :</strong> {{ $selectedUser['name'] }}  </p>
                    <p class="card-text"><strong>S'est inscrit le :</strong> {{ $selectedUser['created_at'] }}</p>
                    <p class="card-text"><strong>Nombre d'incidents signalés :</strong> ____</p>
                    <p class="card-text"><strong>incidents signalés avérés :</strong> ____</p>

                    <!-- Boutons d'action -->
                    <a href="{{ route('users.edit', $selectedUser['id']) }}" class="btn btn-sm btn-primary mt-2">Modifier</a>
                    @if ( $selectedUser['id'] > 1)
                        <button  class="btn btn-sm btn-danger"
                            onclick="return confirm('Vous ête sur?')">Supprimer</button>
                
                    @endif
                </div>
            </div>
        @else
            <p>Sélectionnez un utilisateur dans la liste.</p>
        @endif
    </div>
    <!-- Content Row -->
    <div class=" @if ($selectedUser) col-md-8  @else col-md-12 @endif">
        <div class="card shadow mb-4">
        
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Date d'inscription</th>
                                {{-- <th>Rôle</th> --}}
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user["name"] }}</td>
                                <td>{{ $user["email"] }}</td>
                                <td>{{ $user['created_at']}}</td>
                                {{-- <td>{{ $user['created_at']->format('d/m/Y') }}</td>
                                <td>{{ $user->role ?? 'N/A' }}</td> --}}
                                <td>
                                    <button   wire:click="selectUser({{ $user['id'] }})" class="btn btn-sm btn-info">Voir</button>
                                    <a href="{{ route('users.edit', $user['id']) }}" class="btn btn-sm btn-primary">Modifier</a>

                                    @if ( $user['id'] > 1)
                                    {{-- wire:click="delete({{ $user['id'] }})" --}}
                                        <button  class="btn btn-sm btn-danger"
                                            onclick="return confirm('Vous ête sur?')">Supprimer</button>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>Protegé</button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
