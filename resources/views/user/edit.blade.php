@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Role') }}</div>
                <div class="card m-3">
                <form action="{{ route('user-management.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th class="contain-header">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="contain">{{ $user->name }}</td>
                                        <td class="contain">{{ $user->email }}</td>
                                        <td class="contain-header">
                                            <select name="users[{{ $user->id }}][role]role">
                                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                <option value="operator" {{ $user->role === 'operator' ? 'selected' : '' }}>Operator</option>
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
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
            font-weight: bold;
        }

        .card-header {
            font-weight: bold;
            font-size: 17px;
        }

        .contain-header {
            text-align: center;
        }

        .contain {
            vertical-align: middle;
        }

        select {
            color: #333333;
            background-color: #f5f5f5;
            border: 1px solid #cccccc;
            padding: 5px;
            border-radius: 4px;
        }

        select:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        option[selected] {
            font-weight: bold;
        }

        .btn {
            width: 10%;
            margin-left: 45%;
            margin-right: 45%;
            margin-bottom: 15px;
        }
    </style>
