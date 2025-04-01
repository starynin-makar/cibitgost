@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Доступные организации</h1>
    
    <div class="row">
        @foreach($organizations as $organization)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $organization->name }}</h5>
                        <p class="card-text">{{ $organization->description }}</p>
                        <a href="{{ route('organizations.show', $organization) }}" class="btn btn-primary">Просмотр</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection 