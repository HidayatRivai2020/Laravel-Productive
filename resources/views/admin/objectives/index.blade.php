@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Objectives</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('objectives.create') }}" class="btn btn-primary">New Objective</a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable-objectives" class="table table-striped table-bordered dt-responsive nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Content</th>
                                        <th>Description</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($objectives as $objective)
                                        <tr>
                                            <td>{{ $objective->id }}</td>
                                            <td>{{ $objective->name }}</td>
                                            <td>{{ $objective->content?->name }}</td>
                                            <td>{{ Str::limit($objective->description, 80) }}</td>
                                            <td>{{ $objective->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <a href="{{ route('objectives.show', $objective->id) }}" class="btn btn-sm btn-info">View</a>
                                                <a href="{{ route('objectives.edit', $objective->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('objectives.destroy', $objective->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this objective?');">
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
@push('scripts')
    <!-- Datatables CSS (placed here to keep this page self-contained) -->
    <link href="{{ asset('backend/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('backend/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Datatables JS -->
    <script src="{{ asset('backend/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#datatable-objectives').DataTable({
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
                pageLength: {{ request('per_page', 10) }},
            });
        });
    </script>
@endpush

</div>
@endsection
