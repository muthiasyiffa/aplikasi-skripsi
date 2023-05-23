@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">{{ __('Upload Data') }}</div>
                <div class="card m-3">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    <form id="uploadForm" action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                        <input type="file" name="excel_file" id="excel_file" class="hidden" accept=".xlsx, .xls" onchange="updateFileName()">
                        <br>

                        <label for="file_name" class="file_name">Choose File</label>
                        <input type="text" id="selected_file_name" class="input" readonly>

                        <label for="excel_file" class="btn btn-outline-primary">Browse</label>
                        @csrf
                        <button id="uploadButton" class="btn btn-primary">Upload</button>

                    </form>

                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
    function updateFileName() {
        var fileInput = document.getElementById('excel_file');
        var fileNameElement = document.getElementById('selected_file_name');

        if (fileInput.files.length > 0) {
            var fileName = fileInput.files[0].name;
            fileNameElement.value = fileName;
        } else {
            fileNameElement.value = "";
        }
    }
</script>

@endsection



@push('styles')
    <style>
        form {
            margin-left: 2%;
            margin-bottom: 5%;
        }

        .hidden {
            display: none;
        }

        table {
            width: 100%;
        }

        .card-header {
            font-weight: bold;
            font-size: 17px;
        }

        .btn {
            vertical-align: middle;
        }

        .input {
            padding-top: 3px;
            padding-bottom: 4px;
            background-color: #e0e0e0;
            cursor: default;
            border-radius: 3px;
        }

        .file_name {
            font-weight: bold;
            font-size: 15px;
        }

        .alert {
            margin: 10px 0;
            padding: 15px;
            border-radius: 3px;
            font-size: 14px;
        }

        .alert ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

    </style>