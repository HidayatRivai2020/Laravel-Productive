@extends('admin.admin_master')

@section('admin')
<div class="content">

    <!-- Start Content-->
    <div class="container-xxl">

        <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
            <div class="flex-grow-1">
                <h4 class="fs-18 fw-semibold m-0">Speed Run</h4>
            </div>
        </div>

        <!-- start row -->
        <div class="row">
            <div class="col-md-12 col-xl-12">

                <div class="card">
                    <div class="card-body text-center">
                            <!-- Title input above timer -->
                            <div class="mb-3">
                                <input id="sessionTitle" type="text" class="form-control w-50 mx-auto" placeholder="Enter session title (used in export filename)">
                            </div>

                            <!-- Big Timer -->
                            <div class="mb-4">
                                <div id="stopwatch" class="fw-bold" style="font-size:64px; letter-spacing:2px;">00:00:00</div>
                                <div class="mt-2">
                                    <button id="startBtn" class="btn btn-primary btn-sm">Start</button>
                                    <button id="pauseBtn" class="btn btn-warning btn-sm ms-2">Pause</button>
                                    <button id="stopBtn" class="btn btn-danger btn-sm ms-2">Stop</button>
                                    <button id="resetBtn" class="btn btn-secondary btn-sm ms-2">Reset</button>
                                </div>
                            </div>

                            <!-- Input and record button -->
                            <div class="d-flex justify-content-center align-items-center gap-2 mb-3">
                                <input id="noteInput" type="text" class="form-control w-50" placeholder="Enter note here">
                                <button id="recordBtn" class="btn btn-success">Record</button>
                            </div>

                            <!-- Results table (hidden until first record) -->
                            <div class="table-responsive">
                                <table id="recordsTable" class="table table-striped" style="display:none;">
                                    <thead>
                                        <tr>
                                            <th style="width:80px;">#</th>
                                            <th>Time</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- rows appended here -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination and export controls -->
                            <div id="tableControls" class="d-flex justify-content-between align-items-center mt-2" style="display:none;">
                                <div>
                                    <button id="prevPage" class="btn btn-sm btn-outline-primary" disabled>Prev</button>
                                    <span id="pageInfo" class="mx-2">0 / 0</span>
                                    <button id="nextPage" class="btn btn-sm btn-outline-primary" disabled>Next</button>
                                </div>
                                <div>
                                    <button id="exportBtn" class="btn btn-sm btn-dark">Export</button>
                                </div>
                            </div>
                    </div>
                </div>

            </div> <!-- end timer -->
        </div> <!-- end row -->

    </div> <!-- container-fluid -->
</div>

@endsection

@push('scripts')
<script>
    // Enhanced stopwatch with Pause button, pagination and CSV export
    (function(){
    const titleInput = document.getElementById('sessionTitle');
    const display = document.getElementById('stopwatch');
    const startBtn = document.getElementById('startBtn');
    const pauseBtn = document.getElementById('pauseBtn');
    const stopBtn = document.getElementById('stopBtn');
    const resetBtn = document.getElementById('resetBtn');
        const recordBtn = document.getElementById('recordBtn');
        const noteInput = document.getElementById('noteInput');
        const recordsTable = document.getElementById('recordsTable');
        const recordsBody = recordsTable.querySelector('tbody');
        const tableControls = document.getElementById('tableControls');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const pageInfo = document.getElementById('pageInfo');
        const exportBtn = document.getElementById('exportBtn');

        let startTime = 0;
        let elapsed = 0;
        let timerId = null;
        let running = false;

        const pageSize = 10;
        let records = []; // store {timeMs, note}
        let currentPage = 1;

        function formatTime(ms) {
            const hours = Math.floor(ms / 3600000);
            const minutes = Math.floor((ms % 3600000) / 60000);
            const seconds = Math.floor((ms % 60000) / 1000);
            const hh = String(hours).padStart(2,'0');
            const mm = String(minutes).padStart(2,'0');
            const ss = String(seconds).padStart(2,'0');
            // Return without milliseconds per request
            return `${hh}:${mm}:${ss}`;
        }

        function update() {
            const now = Date.now();
            const diff = elapsed + (now - startTime);
            display.textContent = formatTime(diff);
            timerId = requestAnimationFrame(update);
        }

        function setButtonsState(states) {
            // states: { start, pause, stop, reset, export, record }
            startBtn.disabled = !states.start;
            pauseBtn.disabled = !states.pause;
            stopBtn.disabled = !states.stop;
            resetBtn.disabled = !states.reset;
            exportBtn.disabled = !states.export;
            // record button should be enabled only when running
            if (typeof states.record !== 'undefined') {
                recordBtn.disabled = !states.record;
            }
        }

        function startTimer(){
            if (!running) {
                startTime = Date.now();
                timerId = requestAnimationFrame(update);
                running = true;
                // when running, set button states accordingly
                // start disabled, pause & stop enabled, reset & export disabled
                setButtonsState({ start: false, pause: true, stop: true, reset: false, export: false, record: true });
            }
        }

        function pauseTimer(addRecordOnPause = true, pauseNote = 'pause'){
            if (running) {
                cancelAnimationFrame(timerId);
                elapsed = elapsed + (Date.now() - startTime);
                running = false;

                // when paused (not stopped), enable start and stop, disable pause
                setButtonsState({ start: true, pause: false, stop: true, reset: false, export: false, record: false });

                if (addRecordOnPause) {
                    // add a record (e.g., 'pause') to the end of the records
                    const currentMs = elapsed;
                    records.push({ timeMs: currentMs, note: pauseNote });
                    showTableIfNeeded();
                    renderPage(Math.ceil(records.length / pageSize));
                }
            }
        }

        function resetTimer(){
            cancelAnimationFrame(timerId);
            startTime = 0;
            elapsed = 0;
            running = false;
            display.textContent = '00:00:00';
        }

        function showTableIfNeeded(){
            if (records.length > 0) {
                recordsTable.style.display = '';
                tableControls.style.display = '';
            } else {
                recordsTable.style.display = 'none';
                tableControls.style.display = 'none';
            }
        }

        function renderPage(page){
            currentPage = page;
            const total = records.length;
            const totalPages = Math.max(1, Math.ceil(total / pageSize));
            if (currentPage < 1) currentPage = 1;
            if (currentPage > totalPages) currentPage = totalPages;

            // update table body
            recordsBody.innerHTML = '';
            const start = (currentPage - 1) * pageSize;
            const end = Math.min(start + pageSize, total);
            for (let i = start; i < end; i++){
                const item = records[i];
                const tr = document.createElement('tr');
                const tdNum = document.createElement('td'); tdNum.textContent = i + 1;
                const tdTime = document.createElement('td'); tdTime.textContent = formatTime(item.timeMs);
                const tdNote = document.createElement('td'); tdNote.textContent = item.note;
                tr.appendChild(tdNum); tr.appendChild(tdTime); tr.appendChild(tdNote);
                recordsBody.appendChild(tr);
            }

            // update pagination controls
            pageInfo.textContent = `${currentPage} / ${totalPages}`;
            prevPageBtn.disabled = currentPage <= 1;
            nextPageBtn.disabled = currentPage >= totalPages;
        }

        // start/resume uses startBtn, pause uses pauseBtn
        startBtn.addEventListener('click', function(){
            if (!running) {
                startTimer();
                // add a 'start' record at current time
                let currentMs = elapsed;
                if (running) currentMs = elapsed + (Date.now() - startTime); // just in case
                records.push({ timeMs: currentMs, note: 'start' });
                showTableIfNeeded();
                renderPage(Math.ceil(records.length / pageSize));
            }
        });

        pauseBtn.addEventListener('click', function(){
            if (running) {
                pauseTimer(true, 'pause');
            }
        });

        stopBtn.addEventListener('click', function(){
            if (running) {
                // stopping: pause and add 'stop' record
                pauseTimer(true, 'stop');
                // when stopped, set buttons: start false, pause & stop disabled, reset & export enabled
                setButtonsState({ start: false, pause: false, stop: false, reset: true, export: true, record: false });
            }
        });

        resetBtn.addEventListener('click', function(){
            // reset only if stopped
            if (!running) {
                resetTimer();
                // clear records and reset UI
                records = [];
                showTableIfNeeded();
                renderPage(1);
                // only start enabled after reset
                setButtonsState({ start: true, pause: false, stop: false, reset: false, export: false, record: false });
            }
        });

        recordBtn.addEventListener('click', function(){
            // capture current time
            let currentMs = elapsed;
            if (running) currentMs = elapsed + (Date.now() - startTime);
            const note = noteInput.value || '';
            records.push({ timeMs: currentMs, note: note });

            showTableIfNeeded();
            renderPage(Math.ceil(records.length / pageSize)); // jump to last page where new row is

            // clear input for convenience
            noteInput.value = '';
            noteInput.focus();
        });

        prevPageBtn.addEventListener('click', function(){
            renderPage(currentPage - 1);
        });

        nextPageBtn.addEventListener('click', function(){
            renderPage(currentPage + 1);
        });

        exportBtn.addEventListener('click', async function(){
            // stop the timer (do NOT add a 'pause' record here to avoid duplicates)
            pauseTimer(false);

            // add an 'export' record at current time
            let exportMs = elapsed;
            if (running) exportMs = elapsed + (Date.now() - startTime);
            records.push({ timeMs: exportMs, note: 'export' });
            showTableIfNeeded();
            renderPage(Math.ceil(records.length / pageSize));

            if (records.length === 0) {
                alert('No records to export.');
                return;
            }

            // Build TXT content where each row is: [time] note
            const lines = [];
            for (let i = 0; i < records.length; i++){
                const r = records[i];
                const timeStr = formatTime(r.timeMs);
                lines.push(`[${timeStr}] ${r.note}`);
            }

            const content = lines.join('\n');

            const title = (titleInput.value || 'untitled').trim();
            const safeTitle = title.replace(/[^a-z0-9-_]/gi, '_');
            const filename = `${Date.now()}_${safeTitle}.txt`;

            // get csrf token from meta
            const meta = document.querySelector('meta[name="csrf-token"]');
            const csrf = meta ? meta.getAttribute('content') : '';

            try {
                const resp = await fetch('/admin/speedrun/export', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ title: title, filename: filename, content: content })
                });

                if (!resp.ok) {
                    const body = await resp.text();
                    throw new Error(body || 'Server error');
                }

                const json = await resp.json();
                alert('Export saved to server: ' + (json.url || json.path || 'unknown'));
                // after export, only start enabled per rules
                setButtonsState({ start: true, pause: false, stop: false, reset: false, export: false, record: false });
            } catch (err) {
                console.error(err);
                alert('Export failed: ' + err.message);
            }
        });

        // allow Enter key in noteInput to trigger Record
        noteInput.addEventListener('keydown', function(e){
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();
                recordBtn.click();
            }
        });

        // initial state - render last page (if records exist)
        showTableIfNeeded();
        const lastPage = Math.max(1, Math.ceil(records.length / pageSize));
        renderPage(lastPage);
        // initial buttons: only start enabled
    setButtonsState({ start: true, pause: false, stop: false, reset: false, export: false, record: false });
    })();
</script>
@endpush