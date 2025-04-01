<!-- Модальное окно для добавления свидетельств -->
<div class="modal fade" id="evidenceModal" tabindex="-1" aria-labelledby="evidenceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="evidenceModalLabel">Добавить свидетельство</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="evidenceForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="evidence">Свидетельство</label>
                        <textarea class="form-control" id="evidence" name="evidence" rows="3" required></textarea>
                    </div>
                    <div class="form-group mt-3">
                        <label for="notes">Примечания</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentNormId = null;

function openEvidenceModal(normId) {
    currentNormId = normId;
    $('#evidenceModal').modal('show');
}

$('#evidenceForm').on('submit', function(e) {
    e.preventDefault();
    
    axios.post(`/assessments/${currentNormId}/evidence`, {
        evidence: $('#evidence').val(),
        notes: $('#notes').val(),
        audit_id: {{ $audit->id }}
    }).then(response => {
        $('#evidenceModal').modal('hide');
        location.reload();
    }).catch(error => {
        console.error('Error:', error);
        alert('Произошла ошибка при сохранении свидетельства');
    });
});

$('#evidenceModal').on('hidden.bs.modal', function () {
    $('#evidence').val('');
    $('#notes').val('');
    currentNormId = null;
});
</script>
@endpush 