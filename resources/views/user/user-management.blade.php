@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User Management') }}</div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning">
                        {{ session('warning') }}
                    </div>
                @endif

                <div class="card m-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="contain-header">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="contain">{{ $user->name }}</td>
                                    <td class="contain">{{ $user->email }}</td>
                                    <td class="contain">{{ $user->role }}</td>
                                    <td class="contain-header">
                                        <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this account?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger del">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('user-management.edit') }}" class="btn btn-primary save">Edit Role</a>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@endpush

@push('styles')
    <style>
        table {
            width: 100%;
        }

        .card-header {
            font-weight: bold;
            font-size: 17px;
        }
        
        th {
            font-weight: bold;
        }

        .contain-header {
            text-align: center;
        }

        .contain {
            vertical-align: middle;
        }
        
        .del {
            margin-top: 10%;
        }

        .save {
            width: 14%;
            margin-left: 43%;
            margin-right: 43%;
            margin-bottom: 15px;
        }

        .alert {
            margin: 10px 0;
            padding: 15px;
            border-radius: 3px;
            font-size: 15px;
        }
        
    </style>