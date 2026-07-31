<div class="modal fade" id="progressModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-4">
                <div id="progressIcon" class="progress-icon text-primary mb-3">
                    <i class="bi bi-cloud-download"></i>
                </div>
                <h5 id="progressTitle" class="mb-1">Menyiapkan...</h5>
                <p id="progressStatus" class="text-muted small mb-3">Menghubungi server Dapodik...</p>
                <div class="progress mb-2" style="height: 12px;">
                    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 0%"></div>
                </div>
                <div id="progressPercent" class="small text-muted mb-3">0%</div>
                <div id="progressLog" class="text-start small bg-light rounded-2 p-2 mb-3" style="max-height: 320px; overflow-y: auto; display: none;"></div>
                <div id="progressTips" class="text-muted small fst-italic">
                    <i class="bi bi-lightbulb me-1"></i><span id="progressTipText"></span>
                </div>
            </div>
            <div class="modal-footer justify-content-center py-2" id="progressFooter"></div>
        </div>
    </div>
</div>
