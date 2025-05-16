@extends('layouts.app')

@section('title', 'Les utilisateurs')



@section('content') 
    <h3>{{ $id ? 'Mis à jour d\'un utilisateurs' : 'Creation d\'un utilisateur' }}</h3>
    
    @livewire('user-form', ['id' => $id])
  
    
    

@endsection