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
                            @foreach ($role as $rl)
                                <option value="{{ $rl->id }}|{{ $rl->name }}">{{ $rl->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" >Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
