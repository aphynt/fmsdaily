<div class="modal fade" id="tambahLoadingModal" tabindex="-1" aria-labelledby="modalLoadingLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLoadingLabel">Loading Point</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formLoading">
                    <div class="mb-3">
                        <label>Subcont</label>
                        <select class="form-select" id="subcontLoading" name="subcontLoading[]">
                            <option selected disabled>Pilih Subcont</option>
                            @foreach ($data['subcontSupport'] as $scs)
                                <option value="{{ $scs->id }}">{{ $scs->keterangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>PIT</label>
                        <select class="form-select" id="areaLoading" name="areaLoading[]">
                            <option selected disabled>Pilih Area</option>
                            @foreach ($data['area'] as $ar)
                                <option value="{{ $ar->id }}">{{ $ar->keterangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Pengawas</label>
                        <input type="text" id="pengawasLoading" class="form-control text-uppercase" name="pengawasLoading[]">
                    </div>
                    <div class="mb-3">
                        <label>Fleet EX</label>
                        <input type="text" id="fleetLoading" class="form-control text-uppercase" name="fleetLoading[]">
                    </div>
                    <div class="mb-3">
                        <label>Jumlah DT</label>
                        <input type="number" id="jumlahDTLoading" class="form-control" name="jumlahDTLoading[]">
                    </div>
                    <div class="mb-3">
                        <label>Seam BB</label>
                        <input type="number" id="seamBBLoading" class="form-control" name="seamBBLoading[]">
                    </div>
                    <div class="mb-3">
                        <label>Jarak (km)</label>
                        <input type="text" id="jarakLoading" class="form-control" pattern="^[0-9.]+$" name="jarakLoading[]">
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <input type="text" id="keteranganLoading" class="form-control" name="keteranganLoading[]">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveLoading" data-bs-dismiss="modal">Tambah</button>
            </div>
        </div>
    </div>
</div>
