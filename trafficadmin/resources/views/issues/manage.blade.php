@extends('layouts.app')

@section('title', 'Les utilisateurs')



@section('content') 
    <h3>Validation de l'incident</h3>
    <p class="text-muted">
        Veuillez remplir les champs ci-dessous pour {{ (isset($id) && $id) ? "mettre à jour" : "enregistrer" }} un incident routier.
        Assurez-vous que les informations sont exactes avant de valider.
    </p>
    
    @livewire('road-issue-manage-view', isset($id) ? ['roadissueId' => $id] : [])

@endsection