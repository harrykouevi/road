<div>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Carte interactive</h6>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <div class="card mb-4">
                    
                    <div class="card-body">
                        <!-- Choix du mode -->
                        <div class="mb-4">
                            <label class="form-label">Mode de recherche</label>
                            <select wire:model.lazy="mode" class="form-control">
                                <option value="incident">Recherche d'incidents</option>
                                <option value="itineraire">Recherche d'itinéraire</option>
                            </select>
                        </div>
                        
                        @if($mode === 'incident')
                            <hr class="my-4">
                             <h1 class="h5 mb-4 text-gray-800">Recherche d'incidents</h1>
                            <!-- Filtres généraux incidents -->
                            <div class="row g-3"  >
                                <div class="col-md-6">
                                    <label class="form-label">Type d'incident</label>
                                    <select wire:model="filterType" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="admin">Admin</option>
                                        <option value="client">Client</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Statut</label>
                                    <select wire:model="filterStatus" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="en_attente">En attente</option>
                                        <option value="valide">Validé</option>
                                        <option value="rejete">Rejeté</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filtres géographiques incidents -->
                            <hr class="my-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" wire:model="incidentLatitude" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" wire:model="incidentLongitude" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Rayon (km)</label>
                                    <input type="number" wire:model="incidentRadius" class="form-control" />
                                </div>
                            </div>
                        @elseif ($mode === 'itineraire')
                            <!-- Recherche itinéraire -->
                            <hr class="my-4">
                            <h1 class="h5 mb-4 text-gray-800">Recherche d'itinéraire</h1>
                            <h5>Départ</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" wire:model="departureLatitude" class="form-control" placeholder="Latitude" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" wire:model="departureLongitude" class="form-control" placeholder="Longitude" />
                                </div>
                            </div>

                            <h5 class="mt-3">Arrivée</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" wire:model="arrivalLatitude" class="form-control" placeholder="Latitude" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" wire:model="arrivalLongitude" class="form-control" placeholder="Longitude" />
                                </div>
                            </div>
                        @endif

                        <!-- Bouton -->
                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary" wire:click="applyFilters">Filtrer</button>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-12">
                <iframe src="{{ $iframeUrl }}" width="100%" height="600" style="border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
