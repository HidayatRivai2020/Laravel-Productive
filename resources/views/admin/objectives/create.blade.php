@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Objectives</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('objectives.index') }}" class="btn btn-outline-secondary">Back</a>
                <button type="button" class="btn btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#createObjectiveModal">Create Objective</button>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createObjectiveModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Create Objective</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('objectives.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select id="select-category" class="form-select">
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ ($cat->id == $firstCategoryId) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content</label>
                                <select name="content_id" id="select-content" class="form-select">
                                    @foreach($contents as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('content_id') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                                @error('name') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                                @error('description') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control">
                                @error('image') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <input type="number" name="status" class="form-control" value="{{ old('status', 0) }}">
                                @error('status') <div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-primary">Create</button>
                                <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cancel</button>
                            </div>
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
                    contentSelect.appendChild(opt);
                }
            } catch (e) {
                contentSelect.innerHTML = '';
            }
        }

        categorySelect.addEventListener('change', function () {
            loadContents(this.value);
        });

        // Auto-open modal if there are validation errors or old input
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        const hasOld = {{ count(session()->getOldInput()) ? 'true' : 'false' }};
        if (hasErrors || hasOld) {
            const modalEl = document.getElementById('createObjectiveModal');
            const bs = new bootstrap.Modal(modalEl);
            bs.show();
        }
    });
</script>
@endpush

@endsection
