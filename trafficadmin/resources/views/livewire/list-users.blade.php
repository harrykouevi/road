<div>
 
    <!-- DataTales Example -->
    <!-- Content Row -->
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
                                <a href="{{-- route('users.show', $user->id) --}}" class="btn btn-sm btn-info">Voir</a>
                                <a href="{{-- route('users.edit', $user->id) --}}" class="btn btn-sm btn-warning">Modifier</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
