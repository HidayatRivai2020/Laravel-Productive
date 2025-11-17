@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Todo_List</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTodoModal">New Todo</button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable-todos" class="table table-striped table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Deadline</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todos as $todo)
                                        <tr>
                                            <td>{{ $todo->id }}</td>
                                            <td>{{ $todo->title }}</td>
                                            <td>{{ $todo->deadline?->format('Y-m-d') ?? $todo->deadline }}</td>
                                            <td>
                                                <a href="{{ route('todos.show', $todo->id) }}" class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('todos.edit', $todo->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this todo?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- DataTables will handle pagination/search on the client side for this page -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <link href="{{ asset('backend/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#datatable-todos').DataTable({
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
                pageLength: {{ request('per_page', 15) }},
            });
        });
    </script>
    @include('admin.todos._modal_create')
@endpush

@endsection
