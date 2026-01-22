<div class="modal fade" id="tambahStagingPlan" tabindex="-1" aria-labelledby="modalSupportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSupportLabel">Tambah Staging Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('stagingplan.post') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label>Start Date</label>
                        <input type="text" id="startDate" class="form-control" value="" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label>End Date</label>
                        <input type="text" id="endDate" class="form-control" value="" name="end_date" required>
                    </div>
                    <div class="mb-3">
                        <label>Shift</label>
                        <select class="form-select" id="shiftSupport" name="shift_id">
                            <option selected disabled>Pilih shift</option>
                            @foreach ($shift as $shh)
                            <option value="{{ $shh->id }}">{{ $shh->keterangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Shift</label>
                        <select class="form-select" id="pitSupport" name="pit_id" required>
                            <option selected disabled>Pilih pit</option>
                            @foreach ($pit as $ptt)
                            <option value="{{ $ptt->id }}">{{ $ptt->keterangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Upload Gambar</label>
                        <input type="file" id="image" class="form-control" accept="image/*" name="image" required>
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const startDate = document.getElementById("startDate");
        const endDate = document.getElementById("endDate");
        const today = new Date();

        // Format tanggal menjadi YYYY-MM-DD
        const formattedDate = `${String(today.getMonth() + 1).padStart(2, '0')}/${String(today.getDate()).padStart(2,
                '0')}/${today.getFullYear()}`;
        // Set nilai default input tanggal
        startDate.value = formattedDate;
        endDate.value = formattedDate;
    });

</script>
