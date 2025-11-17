@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Edit Task</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('tasks.update', [$task->objective_id, $task->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Objective</label>
                                        <select name="objective_id" class="form-select" required>
                                            <option value="">-- Select Objective --</option>
                                            @foreach($objectives as $objective)
                                                <option value="{{ $objective->id }}" {{ (old('objective_id', $task->objective_id) == $objective->id) ? 'selected' : '' }}>
                                                    {{ $objective->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1" {{ old('status', $task->status) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusSwitch">
                                                <span class="status-text">{{ old('status', $task->status) == 1 ? 'Done' : 'In Progress' }}</span>
                                            </label>
                                        </div>
                                        <input type="hidden" name="status" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Detail</label>
                                <textarea name="detail" class="form-control" rows="6">{{ old('detail', $task->detail) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                @if($task->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $task->image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                                        <p class="text-muted mt-1">Current image</p>
                                    </div>
                                @endif
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Leave empty to keep current image</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Back to Tasks
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Update Task
                                </button>
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
$(document).ready(function() {
    // Update status label dynamically
    $('#statusSwitch').change(function() {
        const isChecked = $(this).is(':checked');
        $('.status-text').text(isChecked ? 'Done' : 'In Progress');
        
        // Update hidden input value
        if (isChecked) {
            $('input[type="hidden"][name="status"]').remove();
        } else {
            if ($('input[type="hidden"][name="status"]').length === 0) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'status',
                    value: '0'
                }).appendTo('form');
            }
        }
    });
});
</script>
@endpush
@endsection