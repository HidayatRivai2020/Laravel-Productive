@extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Create New Task</h4>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Category, Content, Objective Selection -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Select Category</label>
                                <select id="categorySelect" class="form-select">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Second Row: Content and Objective Selection (inline) -->
                        <div class="row mb-3" id="contentObjectiveRow" style="display: none;">
                            <div class="col-md-6">
                                <label class="form-label">Select Content</label>
                                <select id="contentSelect" class="form-select">
                                    <option value="">-- Select Content --</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Objective</label>
                                <select id="objectiveSelect" class="form-select">
                                    <option value="">-- Select Objective --</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Task Creation Form -->
                        <form action="{{ route('tasks.store') }}" method="POST" enctype="multipart/form-data" id="taskForm" style="display: none;">
                            @csrf
                            
                            <input type="hidden" name="objective_id" id="selectedObjectiveId" value="{{ old('objective_id') }}">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Selected Objective</label>
                                        <input type="text" id="selectedObjectiveName" class="form-control" readonly>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="status" id="statusSwitch" value="1" {{ old('status', 0) == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label" for="statusSwitch">
                                                <span class="status-text">{{ old('status', 0) == 1 ? 'Done' : 'In Progress' }}</span>
                                            </label>
                                        </div>
                                        <input type="hidden" name="status" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Detail</label>
                                <textarea name="detail" class="form-control" rows="4">{{ old('detail') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Back to Tasks
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-content-save"></i> Create Task
                                </button>
                            </div>
                        </form>

                        <!-- Instructions when no objective selected -->
                        <div id="instructionsText" class="text-center text-muted p-4">
                            Select category, content, and objective to create a task
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // When category is selected, load related contents
    $('#categorySelect').change(function() {
        const categoryId = $(this).val();
        const contentSelect = $('#contentSelect');
        const objectiveSelect = $('#objectiveSelect');
        const contentObjectiveRow = $('#contentObjectiveRow');
        
        // Reset content and objective dropdowns
        contentSelect.html('<option value="">-- Select Content --</option>');
        objectiveSelect.html('<option value="">-- Select Objective --</option>');
        hideTaskForm();
        
        if (categoryId) {
            // Show the content/objective row
            contentObjectiveRow.show();
            
            // Fetch contents for the selected category
            $.get(`/admin/categories/${categoryId}/contents`, function(contents) {
                $.each(contents, function(index, content) {
                    contentSelect.append(`<option value="${content.id}">${content.name}</option>`);
                });
            }).fail(function() {
                // Fallback: load all contents and filter by category_id
                $.get('/admin/contents', function(data) {
                    const contents = data.data || data;
                    $.each(contents, function(index, content) {
                        if (content.category_id === categoryId) {
                            contentSelect.append(`<option value="${content.id}">${content.name}</option>`);
                        }
                    });
                });
            });
        } else {
            contentObjectiveRow.hide();
        }
    });
    
    // When content is selected, load related objectives
    $('#contentSelect').change(function() {
        const contentId = $(this).val();
        const objectiveSelect = $('#objectiveSelect');
        
        // Reset objective dropdown
        objectiveSelect.html('<option value="">-- Select Objective --</option>');
        hideTaskForm();
        
        if (contentId) {
            // Fetch objectives for the selected content
            $.get(`/admin/contents/${contentId}/objectives`, function(objectives) {
                $.each(objectives, function(index, objective) {
                    objectiveSelect.append(`<option value="${objective.id}">${objective.name}</option>`);
                });
            }).fail(function() {
                // Fallback: load all objectives and filter by content_id
                $.get('/admin/objectives', function(data) {
                    const objectives = data.data || data;
                    $.each(objectives, function(index, objective) {
                        if (objective.content_id === contentId) {
                            objectiveSelect.append(`<option value="${objective.id}">${objective.name}</option>`);
                        }
                    });
                });
            });
        }
    });
    
    // When objective is selected, show task form
    $('#objectiveSelect').change(function() {
        const objectiveId = $(this).val();
        const objectiveName = $(this).find('option:selected').text();
        
        if (objectiveId) {
            showTaskForm(objectiveId, objectiveName);
        } else {
            hideTaskForm();
        }
    });
    
    function showTaskForm(objectiveId, objectiveName) {
        $('#selectedObjectiveId').val(objectiveId);
        $('#selectedObjectiveName').val(`[${objectiveId}] ${objectiveName}`);
        $('#taskForm').show();
        $('#instructionsText').hide();
    }
    
    function hideTaskForm() {
        $('#taskForm').hide();
        $('#instructionsText').show();
        $('#selectedObjectiveId').val('');
        $('#selectedObjectiveName').val('');
    }

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
                }).appendTo('#taskForm');
            }
        }
    });
});
</script>
@endpush
@endsection