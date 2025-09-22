<div class="modal fade" id="tambahPitstopModal" tabindex="-1" aria-labelledby="modalPitstopLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPitstopLabel">Unit Pitstop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPitstop">
                    <div class="mb-3">
                        <label>Nomor Unit</label>
                        <select class="form-select" data-trigger id="no_unitPitstop" name="no_unitPitstop[]">
                            <option selected disabled></option>
                            @foreach ($data['unit'] as $nu)
                            <option value="{{ $nu->VHC_ID }}">{{ $nu->VHC_ID }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Operator Settingan</label>
                        <select class="form-select"  data-trigger id="opr_settinganPitstop" name="opr_settinganPitstop[]">
                            <option selected disabled></option>
                            @foreach ($data['operator'] as $op)
                                <option value="{{ $op->NRP }}|{{ $op->PERSONALNAME }}">{{ $op->NRP }}|{{ $op->PERSONALNAME }}</option>
                            @endforeach
                        </select>
                    </div>
                    <h5>Status</h5>
                    <div class="mb-3">
                        <label for="start">Unit Breakdown</label>
                        <input type="datetime-local" class="form-control" id="status_unit_breakdownPitstop" name="status_unit_breakdownPitstop[]">
                    </div>
                    <div class="mb-3">
                        <label for="start">Unit Ready</label>
                        <input type="datetime-local" class="form-control" id="status_unit_readyPitstop" name="status_unit_readyPitstop[]">
                    </div>
                    <div class="mb-3">
                        <label for="start">Operator Ready</label>
                        <input type="datetime-local" class="form-control" id="status_opr_readyPitstop" name="status_opr_readyPitstop[]">
                    </div>
                    <h5>Operator</h5>
                    <div class="mb-3">
                        <label>Operator (Ready)</label>
                        <select class="form-select"  data-trigger id="opr_readyPitstop" name="opr_readyPitstop[]">
                            <option selected disabled></option>
                            @foreach ($data['operator'] as $opready)
                                <option value="{{ $opready->NRP }}|{{ $opready->PERSONALNAME }}">{{ $opready->NRP }}|{{ $opready->PERSONALNAME }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Keterangan</label>
                        <input type="text" id="keteranganPitstop" class="form-control" name="keteranganPitstop[]">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="savePitstop" data-bs-dismiss="modal">Tambah</button>
            </div>
        </div>
    </div>
</div>
