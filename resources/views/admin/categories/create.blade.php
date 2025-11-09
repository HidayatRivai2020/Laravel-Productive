@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Create Category</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <!-- start row -->
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card mt-3">
                            <div class="card-body">
                                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">ID (optional)</label>
                                        <input type="text" name="id" value="{{ old('id') }}" class="form-control" placeholder="Leave empty to auto-generate UUID">
                                        @error('id')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="desc" class="form-control">{{ old('desc') }}</textarea>
                        @error('desc')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>

                    <button class="btn btn-primary">Create</button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
