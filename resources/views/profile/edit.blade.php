@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">{{ __('Edit Profile') }}</div>
                <div class="card m-3">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your new password">
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">New Password Confirmation</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your new password">
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter your current password" required>
                            @error('current_password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
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

        .card-header {
            font-weight: bold;
            font-size: 17px;
        }

        .form-group {
            padding: 5px;
            margin-left: 15px;
            margin-right: 20px;
        }

        .btn {
            width: 10%;
            margin-left: 45%;
            margin-right: 45%;
            margin-bottom: 10px;
            margin-top: 18px;
        }
    </style>