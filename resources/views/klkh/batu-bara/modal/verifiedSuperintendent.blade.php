<div class="modal fade" id="verifiedSuperintendent{{ $bb->uuid }}" tabindex="-1" aria-labelledby="modalSupportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSupportLabel">Catatan Verifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('klkh.batubara.verified.superintendent', $bb->uuid) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Masukkan Catatan</label>
                        <textarea type="text" class="form-control" name="catatan_verified_superintendent"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" data-bs-dismiss="modal">Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>
