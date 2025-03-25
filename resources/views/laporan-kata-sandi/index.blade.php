@include('layout.head', ['title' => 'Laporan Kata Sandi'])
@include('layout.sidebar')
@include('layout.header')

<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-sm-12 col-md-6 col-xxl-12">
                        <h3>LAPORAN KATA SANDI</h3>
                        <h5>PENGAWASAN PADA JAM KRITIKAL - MENGANTUK</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container mt-0">
                            <form action="{{ route('laporan-kata-sandi.post') }}" method="POST" id="submitFormLaporanKataSandi">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-4 col-12 px-2 py-2">
                                        <label for="kataSandi">Kata Sandi Hari Ini</label>
                                        <input type="text" class="form-control form-control-sm pb-2" name="kataSandi" required oninput="makeUpperCase(this)">
                                    </div>
                                    <div class="col-md-4 col-12 px-2 py-2">
                                        <label for="date">Hari/ Tanggal</label>
                                        <input type="date" class="form-control form-control-sm pb-2" name="date" required>
                                    </div>
                                    <div class="col-md-4 col-12 px-2 py-2">
                                        <label for="shift">Shift</label>
                                        <select class="form-control form-control-sm pb-2" name="shift" required>
                                            <option selected disabled></option>
                                            @foreach ($shift as $sh)
                                                <option value="{{ $sh->id }}">{{ $sh->keterangan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <span class="badge bg-success" id="addCardBtn" style="cursor: pointer;">
                                    + Tambah Data
                                </span>
                                <br><br>

                                <!-- Form dinamis menggunakan Card Layout -->
                                <div class="row" id="cardContainer">
                                    <!-- Baris data akan ditambahkan di sini -->
                                </div>

                                <!-- Tombol Submit -->
                                <div class="text-center">
                                    <span class="badge bg-primary mt-3" id="submitBtn" style="cursor: pointer; font-size: 12pt;">
                                        Submit
                                    </span>
                                </div>
                            </form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
<script>
    function makeUpperCase(input) {
        input.value = input.value.toUpperCase();
    }

    document.getElementById('addCardBtn').addEventListener('click', function() {
    var cardContainer = document.getElementById('cardContainer');
    var newCard = document.createElement('div');
    newCard.classList.add('col-12', 'col-md-6', 'col-lg-4', 'mb-3');
    newCard.innerHTML = `
        <div class="card">
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="noUnit">No. Unit</label>
                    <select class="form-control form-control-sm" name="noUnit[]" required>
                        <option selected disabled>Pilih No. Unit</option>
                        @foreach ($unit as $un)
                            <option value="{{ $un->VHC_ID }}">{{ $un->VHC_ID }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="jamMonitor">Jam Monitor</label>
                    <input type="time" class="form-control form-control-sm" name="jamMonitor[]" required>
                </div>

                <div class="form-group mb-3">
                    <label for="keterangan">Keterangan</label>
                    <input type="text" class="form-control form-control-sm" name="keterangan[]" placeholder="Keterangan">
                </div>

                <span class="badge bg-danger btn-sm deleteCardBtn" style="cursor: pointer;">
                    <i class="fa-solid fa-trash"></i> Hapus
                </span>
            </div>
        </div>
    `;

    // Menambahkan card baru ke dalam container
    cardContainer.appendChild(newCard);

    // Menambahkan event listener untuk tombol Hapus
    newCard.querySelector('.deleteCardBtn').addEventListener('click', function() {
        newCard.remove(); // Menghapus card ketika tombol Hapus diklik
    });
});

// Fungsi untuk submit form (mengirim data)
document.getElementById('submitBtn').addEventListener('click', function(event) {
    event.preventDefault(); // Mencegah pengiriman form default agar kita bisa menangani sendiri

    var formElement = document.getElementById('submitFormLaporanKataSandi');
    if (!formElement) {
        console.error("Form tidak ditemukan!");
        return;
    }

    // Membuat FormData dari form
    var formData = new FormData(formElement);
    console.log(formData);


    //Validation
    if(!formData.get('kataSandi') || !formData.get('date') || !formData.get('shift')){
        Swal.fire({
            icon: 'warning',
            title: 'Upps!!!',
            text: 'Kata Sandi, Tanggal, dan Shift perlu diisi',
        });

        return;
    }

    if(!formData.get('noUnit[]') || !formData.get('jamMonitor[]')){
        Swal.fire({
            icon: 'warning',
            title: 'Upps!!!',
            text: 'Harap mengisi No. Unit dan Jam Monitor minimal 1',
        });

        return;
    }


    fetch("{{ route('laporan-kata-sandi.post') }}", {
    method: 'POST',
    body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Terjadi kesalahan');
        }
        return response.json();
    })
    .then(data => {
        if (data.message) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,  // Pesan yang diterima dari response
            }).then(() => {
                // Redirect ke halaman lain setelah sukses
                window.location.href = "{{ route('laporan-kata-sandi.show') }}";
            });

        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Pesan tidak ditemukan dalam respons.',
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: error.message || 'Tidak ada pesan error yang diberikan.',
        });
    });

});

</script>


<script>
    window.onload = function() {
        var currentDate = new Date();

        // Format tanggal Indonesia (DD-MM-YYYY)
        var dd = ("0" + currentDate.getDate()).slice(-2); // Menambahkan 0 jika tanggal < 10
        var mm = ("0" + (currentDate.getMonth() + 1)).slice(-2); // Menambahkan 0 jika bulan < 10
        var yyyy = currentDate.getFullYear();
        var formattedDate = yyyy + "-" + mm + "-" + dd; // Tanggal untuk input type="date" (YYYY-MM-DD)

        // Format waktu (HH:MM)
        var hours = ("0" + currentDate.getHours()).slice(-2); // Menambahkan 0 jika jam < 10
        var minutes = ("0" + currentDate.getMinutes()).slice(-2); // Menambahkan 0 jika menit < 10
        var formattedTime = hours + ":" + minutes;

        // Isi input dengan tanggal dan waktu saat ini
        document.getElementById("date").value = formattedDate;
        document.getElementById("time").value = formattedTime;
    }
    document.querySelector("form").addEventListener("submit", function(e) {
        const radioGroups = Array.from(new Set([...document.querySelectorAll("input[type='radio']")].map(r => r
            .name)));
        const incompleteGroups = radioGroups.filter(groupName => {
            return !document.querySelector(`input[name="${groupName}"]:checked`);
        });

        if (incompleteGroups.length > 0) {
            e.preventDefault();
            alert("Silakan isi semua pilihan True/False/N/A sebelum mengirimkan form!");
        }
    });
</script>
