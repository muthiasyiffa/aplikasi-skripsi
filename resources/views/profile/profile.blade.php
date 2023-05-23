@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">{{ __('Profile') }}</div>
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
                        <tbody>
                            <tr>
                                <th>Name</th>
                                <td><span>:</span> {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><span>:</span> {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Password</th>
                                <td><span>:</span> **********</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td><span>:</span> {{ $user->role }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        table {
            width: 100%;
        }
        
        th {
            text-align: right;
            width: 25%;
            font-weight: bold;
            padding-right: 10px;
        }
        
        td {
            width: 75%;
        }

        span {
            font-weight: bold;
        }

        .card-header {
            font-weight: bold;
            font-size: 17px;
        }

        .btn {
            width: 16%;
            margin-left: 42%;
            margin-right: 42%;
            margin-bottom: 15px;
        }

        .alert {
            margin: 10px 0;
            padding: 15px;
            border-radius: 3px;
            font-size: 14px;
        }
    </style>
