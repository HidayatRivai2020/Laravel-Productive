@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Todo_List Details</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('todos.index') }}" class="btn btn-secondary">Back</a>
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
                                                <td class="col-sm-9">{{ $todo->id }}</td>
                                            </tr>
                                            <tr>
                                                <th class="col-sm-3">Title</th>
                                                <td class="col-sm-9">{{ $todo->title }}</td>
                                            </tr>
                                            <tr>
                                                <th class="col-sm-3">Deadline</th>
                                                <td class="col-sm-9">{{ $todo->deadline?->format('Y-m-d') ?? $todo->deadline }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <a href="{{ route('todos.edit', $todo->id) }}" class="btn btn-warning">Edit</a>
                                <a href="{{ route('todos.index') }}" class="btn btn-secondary ms-2">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
