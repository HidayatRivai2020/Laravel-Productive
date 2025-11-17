@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Contents</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createContentModal">New Content</button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- start row -->
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable-contents" class="table table-striped table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Description</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($contents as $content)
                                                <tr>
                                                    <td>{{ $content->id }}</td>
                                                    <td>{{ $content->name }}</td>
                                                    <td>{{ $content->category?->name }}</td>
                                                    <td>{{ Str::limit($content->desc, 80) }}</td>
                                                    <td>
                                                        <a href="{{ route('contents.show', $content->id) }}" class="btn btn-sm btn-info">View</a>
                                                        <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                        <form action="{{ route('contents.destroy', $content->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this content?');">
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
            $('#datatable-contents').DataTable({
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: -1 }
                ],
                pageLength: {{ request('per_page', 10) }},
            });
        });
    </script>
    @include('admin.contents._modal_create')
@endpush

@endsection
