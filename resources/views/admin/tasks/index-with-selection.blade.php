 @extends('admin.admin_master')

@section('admin')
<div class="content">
    <div class="container-xxl">
        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Tasks</h4>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                    <i class="mdi mdi-plus"></i> Add Task
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- First Row: Category Selection -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Select Category</label>
                                <select id="categorySelect" class="form-select">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $selectedCategory && $selectedCategory->id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Second Row: Content and Objective Selection (inline) -->
                        <div class="row mb-3" id="contentObjectiveRow" style="{{ $selectedCategory ? '' : 'display: none;' }}">
                            <div class="col-md-6">
                                <label class="form-label">Select Content</label>
                                <select id="contentSelect" class="form-select">
                                    <option value="">-- Select Content --</option>
                                    @foreach($contents as $content)
                                        <option value="{{ $content->id }}" {{ $selectedContent && $selectedContent->id == $content->id ? 'selected' : '' }}>
                                            {{ $content->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Objective</label>
                                <select id="objectiveSelect" class="form-select">
                                    <option value="">-- Select Objective --</option>
                                    @foreach($objectives as $objective)
                                        <option value="{{ $objective->id }}" {{ $selectedObjective && $selectedObjective->id == $objective->id ? 'selected' : '' }}>
                                            {{ $objective->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Display Row -->
        <div class="row">
            <div class="col-12">
                <div id="tasksContainer">
                    @if($tasks->count() > 0)
                        @foreach($tasks as $task)
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <!-- Left: Image -->
                                                <div class="col-md-2 text-center">
                                                    @if($task->image)
                                                        <img src="{{ asset('storage/' . $task->image) }}" alt="Task Image" class="img-fluid" style="max-width: 100px; height: 100px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                                            <i class="mdi mdi-image-off text-muted fs-1"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Center: Task Info -->
                                                <div class="col-md-8 task-info-clickable" style="cursor: pointer;" 
                                                     data-objective-id="{{ $task->objective_id }}" 
                                                     data-task-id="{{ $task->id }}"
                                                     data-objective-name="{{ $task->objective ? $task->objective->name : 'No Objective' }}"
                                                     data-task-detail="{{ $task->detail ?: 'No description available' }}"
                                                     data-task-image="{{ $task->image ?: '' }}">
                                                    <div class="mb-2">
                                                        <h5 class="mb-1">
                                                            <span class="text-decoration-none">
                                                                [{{ $task->objective_id }}-{{ $task->id }}] {{ $task->objective ? $task->objective->name : 'No Objective' }}
                                                            </span>
                                                        </h5>
                                                    </div>
                                                    <div>
                                                        <div class="task-description">
                                                            <p class="text-muted mb-0 task-detail-line" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                                {{ $task->detail ?: 'No description available' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Right: Checkbox -->
                                                <div class="col-md-2 text-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" {{ $task->status ? 'checked' : '' }} disabled style="width: 2rem; height: 2rem;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted p-4">
                            @if($selectedObjective)
                                No tasks found for this objective
                            @else
                                Select category, content, and objective to view tasks
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Detail Modal -->
<div class="modal fade" id="taskDetailModal" tabindex="-1" aria-labelledby="taskDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskDetailModalLabel">Task Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3" id="modalTaskImage">
                        <!-- Task image will be inserted here -->
                    </div>
                    <div class="col-md-8">
                        <h6 class="fw-bold mb-2">Task ID & Name:</h6>
                        <p id="modalTaskTitle" class="mb-3"></p>
                        
                        <h6 class="fw-bold mb-2">Description:</h6>
                        <p id="modalTaskDescription" class="text-muted"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="modalEditLink" class="btn btn-primary">
                    <i class="mdi mdi-pencil"></i> Edit Task
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Add event listeners for clickable task info areas (for server-rendered tasks)
    $('.task-info-clickable').off('click').on('click', function() {
        const objectiveId = $(this).data('objective-id');
        const taskId = $(this).data('task-id');
        const objectiveName = $(this).data('objective-name');
        const taskDetail = $(this).data('task-detail');
        const taskImage = $(this).data('task-image');
        
        showTaskModal(objectiveId, taskId, objectiveName, taskDetail, taskImage);
    });

    // When category is selected, load related contents
    $('#categorySelect').change(function() {
        const categoryId = $(this).val();
        const contentSelect = $('#contentSelect');
        const objectiveSelect = $('#objectiveSelect');
        const contentObjectiveRow = $('#contentObjectiveRow');
        
        // Reset content and objective dropdowns
        contentSelect.html('<option value="">-- Select Content --</option>');
        objectiveSelect.html('<option value="">-- Select Objective --</option>');
        
        if (categoryId) {
            // Show the content/objective row
            contentObjectiveRow.show();
            
            // Clear tasks display when category changes
            clearTasksTable();
            
            // Fetch contents for the selected category
            $.get(`/admin/categories/${categoryId}/contents`, function(contents) {
                $.each(contents, function(index, content) {
                    contentSelect.append(`<option value="${content.id}">${content.name}</option>`);
                });
            }).fail(function() {
                // Fallback: load all contents and filter by category_id
                $.get('/admin/contents', function(data) {
                    const contents = data.data || data; // Handle different response formats
                    $.each(contents, function(index, content) {
                        if (content.category_id === categoryId) {
                            contentSelect.append(`<option value="${content.id}">${content.name}</option>`);
                        }
                    });
                });
            });
        } else {
            contentObjectiveRow.hide();
            clearTasksTable();
        }
    });
    
    // When content is selected, load related objectives
    $('#contentSelect').change(function() {
        const contentId = $(this).val();
        const objectiveSelect = $('#objectiveSelect');
        
        // Reset objective dropdown
        objectiveSelect.html('<option value="">-- Select Objective --</option>');
        
        if (contentId) {
            // Fetch objectives for the selected content
            $.get(`/admin/contents/${contentId}/objectives`, function(objectives) {
                $.each(objectives, function(index, objective) {
                    objectiveSelect.append(`<option value="${objective.id}">${objective.name}</option>`);
                });
            }).fail(function() {
                // Fallback: load all objectives and filter by content_id
                $.get('/admin/objectives', function(data) {
                    const objectives = data.data || data; // Handle different response formats
                    $.each(objectives, function(index, objective) {
                        if (objective.content_id === contentId) {
                            objectiveSelect.append(`<option value="${objective.id}">${objective.name}</option>`);
                        }
                    });
                });
            });
        } else {
            clearTasksTable();
        }
    });
    
    // When objective is selected, load related tasks
    $('#objectiveSelect').change(function() {
        const objectiveId = $(this).val();
        
        if (objectiveId) {
            loadTasksForObjective(objectiveId);
        } else {
            clearTasksTable();
        }
    });
    
    function loadTasksForObjective(objectiveId) {
        $.get(`/admin/objectives/${objectiveId}/tasks`, function(tasks) {
            displayTasks(tasks);
        }).fail(function() {
            // Fallback: load all tasks and filter by objective_id
            $.get('/admin/tasks/all', function(data) {
                const tasks = data.data || data;
                const filteredTasks = tasks.filter(task => task.objective_id == objectiveId);
                displayTasks(filteredTasks);
            }).fail(function() {
                $('#tasksContainer').html('<div class="text-center text-muted p-4">Error loading tasks</div>');
            });
        });
    }
    
    function displayTasks(tasks) {
        const container = $('#tasksContainer');
        
        if (tasks.length === 0) {
            container.html('<div class="text-center text-muted p-4">No tasks found for this objective</div>');
            return;
        }
        
        // Show all tasks
        let html = '';
        $.each(tasks, function(index, task) {
            const imageHtml = task.image 
                ? `<img src="/storage/${task.image}" alt="Task Image" class="img-fluid" style="max-width: 100px; height: 100px; object-fit: cover;">` 
                : '<div class="bg-light d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;"><i class="mdi mdi-image-off text-muted fs-1"></i></div>';
                
            const isChecked = task.status !== 0;
            
            html += `
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Left: Image -->
                                    <div class="col-md-2 text-center">
                                        ${imageHtml}
                                    </div>
                                    
                                    <!-- Center: Task Info -->
                                    <div class="col-md-8 task-info-clickable" style="cursor: pointer;" 
                                         data-objective-id="${task.objective_id}" 
                                         data-task-id="${task.id}">
                                        <div class="mb-2">
                                            <h5 class="mb-1">
                                                <span class="text-decoration-none">
                                                    [${task.objective_id}-${task.id}] ${task.objective ? task.objective.name : 'No Objective'}
                                                </span>
                                            </h5>
                                        </div>
                                        <div>
                                            <div class="task-description">
                                                <p class="text-muted mb-0 task-detail-line" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${task.detail || 'No description available'}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Right: Checkbox -->
                                    <div class="col-md-2 text-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" ${isChecked ? 'checked' : ''} disabled style="width: 2rem; height: 2rem;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.html(html);
        
        // Add event listeners for clickable task info areas
        $('.task-info-clickable').off('click').on('click', function() {
            const objectiveId = $(this).data('objective-id');
            const taskId = $(this).data('task-id');
            
            // Find the task data from the original tasks array
            const currentTask = tasks.find(t => t.objective_id == objectiveId && t.id == taskId);
            
            if (currentTask) {
                const objectiveName = currentTask.objective ? currentTask.objective.name : 'No Objective';
                const taskDetail = currentTask.detail || 'No description available';
                const taskImage = currentTask.image || '';
                
                showTaskModal(objectiveId, taskId, objectiveName, taskDetail, taskImage);
            }
        });
    }
    
    function clearTasksTable() {
        $('#tasksContainer').html('<div class="text-center text-muted p-4">Select category, content, and objective to view tasks</div>');
    }
});

// Function to show task details in modal
function showTaskModal(objectiveId, taskId, objectiveName, taskDetail, taskImage) {
    // Set modal content
    document.getElementById('modalTaskTitle').textContent = `[${objectiveId}-${taskId}] ${objectiveName}`;
    document.getElementById('modalTaskDescription').textContent = taskDetail;
    
    // Handle task image
    const modalImageContainer = document.getElementById('modalTaskImage');
    if (taskImage) {
        modalImageContainer.innerHTML = `<img src="/storage/${taskImage}" alt="Task Image" class="img-fluid" style="max-width: 200px; height: 200px; object-fit: cover;">`;
    } else {
        modalImageContainer.innerHTML = '<div class="bg-light d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; margin: 0 auto;"><i class="mdi mdi-image-off text-muted" style="font-size: 3rem;"></i></div>';
    }
    
    // Set edit link
    document.getElementById('modalEditLink').href = `/admin/tasks/${objectiveId}/${taskId}/edit`;
    
    // Show modal with proper focus management
    const modalElement = document.getElementById('taskDetailModal');
    const modal = new bootstrap.Modal(modalElement, {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    
    // Handle modal events for better accessibility
    modalElement.addEventListener('hidden.bs.modal', function () {
        // Remove any lingering aria-hidden attributes after modal is fully closed
        this.removeAttribute('aria-hidden');
    }, { once: true });
    
    modal.show();
}
</script>
@endpush
@endsection