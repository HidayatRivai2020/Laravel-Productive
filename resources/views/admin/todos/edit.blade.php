@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Edit Todo_List</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('todos.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card mt-3">
                    <div class="card-body">
                        <form action="{{ route('todos.update', $todo->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $todo->title) }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deadline</label>
                                <input type="date" name="deadline" class="form-control" value="{{ old('deadline', $todo->deadline) }}">
                            </div>

                            <button class="btn btn-primary" type="submit">Update</button>
                            <a href="{{ route('todos.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
