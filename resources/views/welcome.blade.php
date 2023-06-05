@extends('layouts.app')

@section('content')

<div class="welcome-container">  
    <h1 class="welcome-text">Welcome to Monitoring Information System !</h1>
     <span class="welcome-text1">
        Log in to find out information about XL sales orders
    </span>
</div>

@endsection

@push('styles')
    <style>
        .welcome-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 80vh;
            color: #FFFFFF;
            position: relative;
        }

        .welcome-text {
            text-align: center;
            font-weight: 600;
            font-size: 30px;
        }

        .welcome-text1 {
            text-align: center;
            font-size: 20px;
        }
    </style>