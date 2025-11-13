<!-- Modal: Create Category -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary">Create</button>
                        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hasErrors = {{ $errors->any() ? 'true' : 'false' }};
        const hasOld = {{ count(session()->getOldInput()) ? 'true' : 'false' }};
        const openRequested = {{ request()->query('create') ? 'true' : 'false' }};
        if (hasErrors || hasOld || openRequested) {
            const modalEl = document.getElementById('createCategoryModal');
            const bs = new bootstrap.Modal(modalEl);
            bs.show();
        }
    });
</script>
@endpush
