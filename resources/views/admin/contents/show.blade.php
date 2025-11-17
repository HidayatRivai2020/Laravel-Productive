@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Content Details</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('contents.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <th class="col-sm-3">ID</th>
                                                <td class="col-sm-9">{{ $content->id }}</td>
                                            </tr>
                                            <tr>
                                                <th class="col-sm-3">Name</th>
                                                <td class="col-sm-9">{{ $content->name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="col-sm-3">Category</th>
                                                <td class="col-sm-9">{{ $content->category?->name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="col-sm-3">Description</th>
                                                <td class="col-sm-9">{{ $content->desc }}</td>
                                            </tr>
                                            <tr>
                                                <th class="col-sm-3">Created</th>
                                                <td class="col-sm-9">{{ $content->created_at }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <a href="{{ route('contents.edit', $content->id) }}" class="btn btn-warning">Edit</a>
                                <a href="{{ route('contents.index') }}" class="btn btn-secondary ms-2">Back</a>
                            </div>
                        </div>

                        <!-- Child objectives list -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <h5 class="card-title">Objectives for this Content</h5>
                                <div class="table-responsive">
                                    <table id="datatable-content-objectives" class="table table-striped table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $contentObjectives = \App\Models\Objective::where('content_id', $content->id)->get();
                                            @endphp
                                            @foreach($contentObjectives as $objective)
                                                <tr>
                                                    <td><a href="{{ route('objectives.show', $objective->id) }}">{{ $objective->id }}</a></td>
                                                    <td>{{ $objective->name }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
            $('#datatable-content-objectives').DataTable({
                responsive: true,
                columnDefs: [
                    { orderable: false, targets: [] }
                ],
                pageLength: {{ request('per_page', 10) }},
            });
        });
    </script>
@endpush

@endsection
