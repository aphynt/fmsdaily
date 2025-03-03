<div class="modal fade" id="tambahSupportModal" tabindex="-1" aria-labelledby="modalSupportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSupportLabel">Alat Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSupport">
                    {{-- <div class="mb-3">
                        <label>Jenis</label>
                        <select class="form-select" id="jenisSupport" name="jenis_unit[]">
                            <option selected disabled>Pilih jenis support</option>
                            <option value="BD">BD</option>
                            <option value="MG">MG</option>
                            <option value="EX">EX</option>
                            <option value="HD">HD</option>
                            <option value="WT">WT</option>
                        </select>
                    </div> --}}
                    <div class="mb-3">
                        <label>Nomor Unit</label>
                        <select class="form-select" data-trigger id="unitSupport" name="alat_unit[]">
                            <option selected disabled></option>
                            @foreach ($data['nomor_unit'] as $nu)
                            <option value="{{ $nu->VHC_ID }}">{{ $nu->VHC_ID }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- <div class="mb-3">
                        <label>NIK Operator</label>
                        <input type="text" id="nikSupport" class="form-control" name="nik_operator[]" onchange="">
                    </div> --}}
                    <div class="mb-3">
                        <label>Nama Operator</label>
                        <select class="form-select"  data-trigger id="namaSupport" name="nama_operator[]">
                            <option selected disabled></option>
                            @foreach ($data['operator'] as $op)
                                <option value="{{ $op->NRP }}|{{ $op->PERSONALNAME }}">{{ $op->NRP }}|{{ $op->PERSONALNAME }}</option>
                            @endforeach
                        </select>
                        {{-- <input type="text" id="namaSupport" class="form-control" name="nama_operator[]" readonly> --}}
                    </div>
                    <div class="mb-3">
                        <label>Tanggal</label>
                        <input type="text" id="tanggalSupport" class="form-control" value="" name="tanggal_operator[]">
                    </div>
                    <div class="mb-3">
                        <label>Shift</label>
                        <select class="form-select" id="shiftSupport" name="shift_operator[]">
                            <option selected disabled>Pilih shift</option>
                            @foreach ($data['shift'] as $shh)
                                <option value="{{ $shh->id }}">{{ $shh->keterangan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>HM Awal</label>
                        <input type="number" id="hmAwalSupport" class="form-control" name="hm_awal[]">
                    </div>
                    <div class="mb-3">
                        <label>HM Akhir</label>
                        <input type="number" id="hmAkhirSupport" class="form-control" name="hm_akhir[]">
                    </div>
                    <div class="mb-3">
                        <label>Total</label>
                        <input type="number" id="totalSupport" class="form-control" name="hm_total[]" readonly>
                    </div>
                    <div class="mb-3">
                        <label>HM Cash</label>
                        <input type="number" id="hmCashSupport" class="form-control" name="hm_cash[]">
                    </div>

                    <div class="mb-3">
                        <label>Keterangan</label>
                        <input type="text" id="keteranganSupport" class="form-control" name="keterangan[]">
                        {{-- <select id="materialSupport" class="form-select" name="material[]">
                            <option selected disabled>Pilih material</option>
                            @foreach ($data['material'] as $mat)
                            <option value="{{ $mat->MAT_ID }}">{{ $mat->MAT_DESC }}</option>
                            @endforeach
                        </select> --}}
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
<script>
    // document.getElementById("jenisSupport").addEventListener("change", function () {
    //     // console.log("terpanggil")
    //     const selectedJenis = this.value; // Ambil nilai jenis yang dipilih
    //     const unitSelect = document.getElementById("unitSupport");
    //     const options = unitSelect.querySelectorAll("option"); // Ambil semua opsi unit

    //     // Tampilkan hanya unit yang sesuai dengan jenis support
    //     options.forEach(option => {
    //         if (option.dataset.matId && option.dataset.matId.startsWith(selectedJenis)) {
    //             option.style.display = ""; // Tampilkan opsi yang sesuai
    //         } else if (option.dataset.matId) {
    //             option.style.display = "none"; // Sembunyikan opsi yang tidak sesuai
    //         }
    //     });

    //     // Reset pilihan unit
    //     unitSelect.value = "";
    // });
        document.addEventListener("DOMContentLoaded", function () {
            const inputTanggal = document.getElementById("tanggalSupport");
            const today = new Date();

            // Format tanggal menjadi YYYY-MM-DD
            const formattedDate = `${String(today.getMonth() + 1).padStart(2, '0')}/${String(today.getDate()).padStart(2,
                '0')}/${today.getFullYear()}`;
            // Set nilai default input tanggal
            inputTanggal.value = formattedDate;
        });

    // document.getElementById('nikSupport').addEventListener('change', function () {
    //     const nik = this.value;
    //     const namaSupport = document.getElementById('namaSupport');

    //     if (nik) {
    //         // Panggil API untuk mendapatkan nama operator
    //         fetch(`/operator/${nik}`)
    //             .then(response => response.json())
    //             .then(data => {
    //                 if (data.success) {
    //                     namaSupport.value = data.nama; // Isi nama operator
    //                 } else {
    //                     namaSupport.value = ''; // Kosongkan jika tidak ditemukan
    //                     alert(data.message); // Tampilkan pesan kesalahan
    //                 }
    //             })
    //             .catch(error => {
    //                 console.error('Error:', error);
    //                 alert('Terjadi kesalahan saat memuat data operator');
    //             });
    //     } else {
    //         namaSupport.value = ''; // Kosongkan jika input NIK dihapus
    //     }
    // });
</script>
