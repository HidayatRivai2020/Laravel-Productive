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
                                        <div class="input-group">
                                            <input type="hidden" name="category_id" id="category_id_input" value="{{ old('category_id', $content->category_id) }}">
                                            <input type="text" id="category_selected_name" class="form-control" placeholder="No category selected" readonly value="{{ optional($content->category)->name }}">
                                            <button type="button" class="btn btn-outline-secondary" id="openCategorySelector">Select</button>
                                        </div>
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
                            @push('scripts')
                            <script>
                            // Reuse the same category selector injected for create page; if it isn't present, insert it and wire up behavior
                            document.addEventListener('DOMContentLoaded', function () {
                                const openBtn = document.getElementById('openCategorySelector');
                                if (!openBtn) return;

                                // If modal doesn't exist (create page already added it), create same modal here
                                if (!document.getElementById('categorySelectorModal')) {
                                    const modalHtml = `
                                    <div class="modal fade" id="categorySelectorModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Select Category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-2 d-flex gap-2">
                                                        <input type="search" id="catSearch" class="form-control" placeholder="Search categories...">
                                                        <select id="catPerPage" class="form-select" style="width:120px;">
                                                            <option value="5">5</option>
                                                            <option value="10" selected>10</option>
                                                            <option value="25">25</option>
                                                        </select>
                                                    </div>
                                                    <div id="catSelectorTable"></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                                    document.body.insertAdjacentHTML('beforeend', modalHtml);
                                }

                                const modalEl = document.getElementById('categorySelectorModal');
                                const bsModal = new bootstrap.Modal(modalEl);
                                const tableContainer = document.getElementById('catSelectorTable');
                                const searchInput = document.getElementById('catSearch');
                                const perPageSelect = document.getElementById('catPerPage');
                                const selectedIdInput = document.getElementById('category_id_input');
                                const selectedNameInput = document.getElementById('category_selected_name');

                                const url = "{{ route('categories.paginated') }}";

                                function fetchPage(page = 1) {
                                    const params = new URLSearchParams();
                                    params.set('page', page);
                                    params.set('per_page', perPageSelect.value);
                                    params.set('search', searchInput.value || '');

                                    fetch(url + '?' + params.toString())
                                        .then(r => r.json())
                                        .then(renderTable);
                                }

                                function renderTable(json) {
                                    const meta = json.meta;
                                    let html = '<table class="table"><thead><tr><th>Name</th><th></th></tr></thead><tbody>';
                                    json.data.forEach(c => {
                                        html += `<tr><td>${c.name}</td><td><button type="button" class="btn btn-sm btn-primary select-cat" data-id="${c.id}" data-name="${c.name}">Select</button></td></tr>`;
                                    });
                                    html += '</tbody></table>';

                                    // pager
                                    html += '<nav><ul class="pagination">';
                                    for (let i = 1; i <= meta.last_page; i++) {
                                        html += `<li class="page-item ${i===meta.current_page? 'active':''}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
                                    }
                                    html += '</ul></nav>';

                                    tableContainer.innerHTML = html;

                                    tableContainer.querySelectorAll('.select-cat').forEach(btn => {
                                        btn.addEventListener('click', () => {
                                            selectedIdInput.value = btn.dataset.id;
                                            selectedNameInput.value = btn.dataset.name;
                                            bsModal.hide();
                                        });
                                    });

                                    tableContainer.querySelectorAll('.page-link').forEach(link => {
                                        link.addEventListener('click', (e) => {
                                            e.preventDefault();
                                            const p = Number(e.target.dataset.page);
                                            fetchPage(p);
                                        });
                                    });
                                }

                                openBtn.addEventListener('click', () => {
                                    bsModal.show();
                                    fetchPage(1);
                                });
                            });
                            </script>
                            @endpush
    </div>
</div>
@endsection
