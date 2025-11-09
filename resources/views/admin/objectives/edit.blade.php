@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Edit Objective</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('objectives.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card mt-3">
                    <div class="card-body">
                        <form action="{{ route('objectives.update', $objective->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select id="select-category" class="form-select">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ ($cat->id == ($selectedCategory ?? null)) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <select name="content_id" id="select-content" class="form-select">
                                    @foreach($contents as $c)
                                        <option value="{{ $c->id }}" {{ ($c->id == $objective->content_id) ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('content_id') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $objective->name) }}">
                                @error('name') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ old('description', $objective->description) }}</textarea>
                                @error('description') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                @if($objective->image)
                                    <div class="mb-2"><img src="{{ asset('storage/' . $objective->image) }}" alt="img" style="max-height:120px;"></div>
                                @endif
                                <input type="file" name="image" class="form-control">
                                @error('image') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <input type="number" name="status" class="form-control" value="{{ old('status', $objective->status) }}">
                                @error('status') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <button class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const categorySelect = document.getElementById('select-category');
        const contentSelect = document.getElementById('select-content');

        async function loadContents(categoryId) {
            contentSelect.innerHTML = '<option>Loading...</option>';
            try {
                const res = await fetch(`{{ url('admin/contents/by-category') }}/${categoryId}`);
                const data = await res.json();
                contentSelect.innerHTML = '';
                for (const item of data) {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.name;
                    if (item.id === '{{ $objective->content_id }}') opt.selected = true;
                    contentSelect.appendChild(opt);
                }
            } catch (e) {
                contentSelect.innerHTML = '';
            }
        }

        categorySelect.addEventListener('change', function () {
            loadContents(this.value);
        });
    });
</script>
@endpush

@endsection
