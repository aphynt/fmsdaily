<div class="modal fade" id="tambahSupportModal" tabindex="-1" aria-labelledby="modalSupportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSupportLabel">Unit Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSupport">
                    <div class="mb-3">
                        <label>Jenis</label>
                        <select class="form-select" id="jenisSupport" name="jenisSupport[]">
                            <option selected disabled>Pilih Jenis</option>
                            @foreach ($data['jenisSupport'] as $je)
                                <option value="{{ $je->id }}">{{ $je->keterangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Subcont</label>
                        <select class="form-select" id="subcontSupport" name="subcontSupport[]">
                            <option selected disabled>Pilih Subcont</option>
                            @foreach ($data['subcontSupport'] as $scs)
                                <option value="{{ $scs->id }}">{{ $scs->keterangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>No. Unit</label>
                        <input type="text" id="noUnitSupport" class="form-control text-uppercase" name="noUnitSupport[]">
                    </div>
                    <div class="mb-3">
                        <label>Area</label>
                        <select class="form-select" id="areaSupport" name="areaSupport[]">
                            <option selected disabled>Pilih area</option>
                            @foreach ($data['area'] as $as)
                                <option value="{{ $as->id }}">{{ $as->keterangan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Keterangan</label>
                        <input type="text" id="keteranganSupport" class="form-control" name="keteranganSupport[]">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveSupport" data-bs-dismiss="modal">Tambah</button>
            </div>
        </div>
    </div>
</div>
