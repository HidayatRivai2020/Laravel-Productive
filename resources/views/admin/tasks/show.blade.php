@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Task Details</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('tasks.edit', [$task->objective_id, $task->id]) }}" class="btn btn-warning">
                    <i class="mdi mdi-pencil"></i> Edit
                </a>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Back to Tasks
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="col-sm-3">Objective ID</th>
                                        <td class="col-sm-9">{{ $task->objective_id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Task ID</th>
                                        <td class="col-sm-9">{{ $task->id }}</td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Objective</th>
                                        <td class="col-sm-9">
                                            <a href="{{ route('objectives.show', $task->objective_id) }}">
                                                {{ $task->objective?->name }}
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Detail</th>
                                        <td class="col-sm-9">{{ $task->detail }}</td>
                                    </tr>
                                    <tr>
                                        <th class="col-sm-3">Status</th>
                                        <td class="col-sm-9">{{ $task->status }}</td>
                                    </tr>
                                    @if($task->image)
                                        <tr>
                                            <th class="col-sm-3">Image</th>
                                            <td class="col-sm-9">
                                                <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" class="img-fluid" style="max-width: 300px;">
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection