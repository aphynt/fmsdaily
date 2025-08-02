<div class="modal fade" id="insertUser" tabindex="-1" aria-labelledby="modalSupportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSupportLabel">Tambah User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.insert') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label>NIK</label>
                        <input type="text" class="form-control" name="nik" style="text-transform: uppercase;" required>
                    </div>
                    <div class="mb-3">
                        <label>Role</label>
                        <select class="form-select" name="role" required>
                            <option selected disabled>Pilih role</option>
                            <option value="FOREMAN">FOREMAN</option>
                            <option value="SUPERVISOR">SUPERVISOR</option>
                            <option value="SUPERINTENDENT">SUPERINTENDENT</option>
                            <option value="MANAGER">MANAGER</option>
                            <option value="FOREMAN MEKANIK">FOREMAN MEKANIK</option>
                            <option value="PJS FOREMAN MEKANIK">PJS FOREMAN MEKANIK</option>
                            <option value="JR FOREMAN MEKANIK">JR FOREMAN MEKANIK</option>
                            <option value="SUPERVISOR MEKANIK">SUPERVISOR MEKANIK</option>
                            <option value="LEADER MEKANIK">LEADER MEKANIK</option>
                            <option value="SUPERINTENDENT SAFETY">SUPERINTENDENT SAFETY</option>
                            <option value="SUPERVISOR SAFETY">SUPERVISOR SAFETY</option>
                            <option value="FOREMAN SAFETY">FOREMAN SAFETY</option>
                            <option value="TRAINING CENTER">TRAINING CENTER</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
