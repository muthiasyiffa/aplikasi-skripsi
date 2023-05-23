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
                                        <button type="button" class="btn btn-danger delete-btn" data-toggle="modal" data-target="#confirmDeleteModal" data-user-id="{{ $user->id }}">Delete</button>
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

<!-- Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this account?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('user-management.destroy', $user) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let deleteBtns = document.getElementsByClassName('delete-btn');

            Array.prototype.forEach.call(deleteBtns, function(btn) {
                btn.addEventListener('click', function() {
                    let userId = this.getAttribute('data-user-id');
                    let deleteForm = document.getElementById('deleteForm');
                    let deleteFormAction = deleteForm.action.replace(':user_id', userId);
                    deleteForm.action = deleteFormAction;
                    $('#confirmDeleteModal').modal('show');
                });
            });
        });
    </script>
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
            margin-top: 15%;
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