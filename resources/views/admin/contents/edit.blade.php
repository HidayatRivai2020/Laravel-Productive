@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Edit Content</h4>
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
                                <form action="{{ route('contents.update', $content->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label">ID (readonly)</label>
                                        <input type="text" value="{{ $content->id }}" class="form-control" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" value="{{ old('name', $content->name) }}" class="form-control" required>
                                        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <select name="category_id" class="form-select" required>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id', $content->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="desc" class="form-control">{{ old('desc', $content->desc) }}</textarea>
                                        @error('desc')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>

                                    <button class="btn btn-primary">Save</button>
                                    <a href="{{ route('contents.index') }}" class="btn btn-secondary ms-2">Cancel</a>
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
