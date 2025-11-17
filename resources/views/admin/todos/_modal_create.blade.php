<div class="modal fade" id="createTodoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('todos.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">New Todo_List</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="date" name="deadline" class="form-control" value="{{ old('deadline') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
