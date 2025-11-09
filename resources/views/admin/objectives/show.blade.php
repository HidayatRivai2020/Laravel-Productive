@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Objective Details</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('objectives.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th class="col-sm-3">ID</th>
                                        <td class="col-sm-9">{{ $objective->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Name</th>
                                        <td class="col-sm-9">{{ $objective->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Content</th>
                                        <td class="col-sm-9">{{ $objective->content?->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Description</th>
                                        <td class="col-sm-9">{{ $objective->description }}</td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Image</th>
                                        <td class="col-sm-9">
                                            @if($objective->image)
                                                <img src="{{ asset('storage/' . $objective->image) }}" alt="img" style="max-height:160px;">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Status</th>
                                        <td class="col-sm-9">{{ $objective->status }}</td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Created</th>
                                        <td class="col-sm-9">{{ $objective->created_at }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <a href="{{ route('objectives.edit', $objective->id) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('objectives.index') }}" class="btn btn-secondary ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
