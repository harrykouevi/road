<div>
    <div>
    <!-- Content Row -->
    <!-- Filtres de recherche -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <strong>Filtres de recherche</strong>
                </div>
                <div class="card-body">
                    <!-- Filtres généraux -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="keyword" class="form-label">Mot Clé</label>
                            <input id="keyword" type="text" wire:model="filteKkeyword" class="form-control" placeholder="Ex: 6.17" />
                        </div>

                        <div class="col-md-6">
                            <label for="type" class="form-label">Type</label>
                            <select id="type" wire:model="filterType" class="form-control">
                                <option value="">Tous</option>
                                <option value="admin">Admin</option>
                                <option value="client">Client</option>
                            </select>
                        </div>
               

                        <div class="col-md-6">
                            <label for="status" class="form-label">Statut</label>
                            <select id="status" wire:model="filterStatus" class="form-control">
                                <option value="">Tous</option>
                                <option value="en_attente">En attente</option>
                                <option value="valide">Validé</option>
                                <option value="rejete">Rejeté</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="date" class="form-label">Date de création</label>
                            <input id="date" wire:model="filterDate" type="date" class="form-control" />
                        </div>
                    </div>

                    <!-- Filtres géographiques -->
                    <hr class="my-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input id="latitude" type="text" wire:model="filterLatitude" class="form-control" />
                        </div>

                        <div class="col-md-4">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input id="longitude" type="text" wire:model="filterLongitude" class="form-control" />
                        </div>

                        <div class="col-md-4">
                            <label for="radius" class="form-label">Rayon (km)</label>
                            <input id="radius" type="number" wire:model="filterRadius" class="form-control" />
                        </div>
                    </div>

                    <!-- Bouton Filtrer -->
                    <div class="d-flex justify-content-end mt-4">
                        <button class="btn btn-primary" wire:click="applyFilters">Filtrer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mb-3">
        <!-- Bouton Ajouter -->
        <div class="col-md-12 d-flex justify-content-end">
            <a href="{{ route('roadissues.create') }}" class="btn btn-success">
                + Ajouter un nouvel incident
            </a>
        </div>
    </div>

    <div class="row">
        <div class="@if ($selectedIssue) col-md-4 @else d-none @endif">
            @if ($selectedIssue)
                <div class="card shadow">
                    <div class="card-body">
                        <p class="card-text"><strong>Auteur :</strong> {{ ($selectedIssue['user'] !== Null && array_key_exists('name',$selectedIssue['user'] )) ?$selectedIssue['user']['name'] : '____' }}</p>
                        <p class="card-text"><strong>Adresse :</strong> {{ $selectedIssue["addresse"]}}</p>
                        <p class="card-text"><strong>Coordonnée :</strong> {{ $selectedIssue["latitude"] .'  -   '. $selectedIssue["longitude"]}}</p>
                        <p class="card-text"><strong>Description :</strong> {{ $selectedIssue["description"] }}</p>
                        <p class="card-text"><strong>Type d'incident :</strong> {{ $selectedIssue['reporttype']['name']  }}</p>
                        <p class="card-text"><strong>Posté le :</strong> {{ $selectedIssue['created_at'] }}</p>

                        <div class="col-12 mb-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <iframe src="{{ $iframeUrl }}" width="100%" height="600" style="border: none;"></iframe>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('roadissues.edit', $selectedIssue['id']) }}" class="btn btn-sm btn-primary mt-2">Modifier</a>
                        <button wire:click="delete({{ $selectedIssue['id'] }})" class="btn btn-sm btn-danger mt-2"
                                onclick="return confirm('Vous ête sur?')">Supprimer</button>
                    </div>
                </div>
            @else
                <p>Sélectionnez un utilisateur dans la liste.</p>
            @endif
        </div>

        <div class="@if ($selectedIssue) col-md-8 @else col-md-12 @endif">
            <div class="card  mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Addresse</th>
                                    <th>Coordonnée</th>
                                    <th>Description</th>
                                    <th>Type d'incident</th>
                                    <th>User ID</th>
                                    <th>status</th>
                                    <th>Créé le</th>
                                    <th>Mise à jour</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roadissues as $roadissue)
                                <tr>
                                    <td>{{ $roadissue["id"] }}</td>
                                    <td>{{ $roadissue["addresse"] }}</td>
                                    <td>{{ $roadissue["latitude"] .' - '. $roadissue["longitude"] }}</td>
                                    <td>{{ $roadissue["description"] }}</td>
                                    <td>{{ $roadissue['reporttype']['name'] }}</td>
                                    <td>{{ $roadissue["user_id"] }}</td>
                                    <td>{{ $roadissue["user_id"] }}</td>
                                    <td>{{ $roadissue["updated_at"] }}</td>
                                    <td>{{ $roadissue["created_at"] }}</td>
                                    <td>
                                        <button wire:click="selectIssue({{ $roadissue['id'] }})" class="btn btn-sm btn-info">Voir</button>
                                        <a href="{{ route('roadissues.edit', $roadissue['id']) }}" class="btn btn-sm btn-primary">Modifier</a>
                                        <button wire:click="delete({{ $roadissue['id'] }})" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Vous ête sur?')">Supprimer</button>
                                        <a class="btn btn-sm btn-warning" href="{{ route('incident.validation', $roadissue['id']) }}" style="color:black !important">Valider / Refuser</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-2">
                        {{ $roadissues->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
