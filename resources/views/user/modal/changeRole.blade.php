<div class="modal fade" id="changeRole{{ $us->id }}" tabindex="-1" aria-labelledby="modalSupportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSupportLabel">Ganti Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('user.change-role', $us->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" class="form-control" value="{{ $us->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label>NIK</label>
                        <input type="text" class="form-control" value="{{ $us->nik }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Pilih role</label>
                        <select class="form-select" name="role" required>
                            <option value="{{ $us->role_id }}|{{ $us->role }}" selected disabled>{{ $us->role }}</option>
                            @foreach ($role as $rl)
                                <option value="{{ $rl->id }}|{{ $rl->name }}">{{ $rl->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" >Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
