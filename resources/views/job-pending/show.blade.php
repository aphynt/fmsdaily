@include('layout.head', ['title' => 'Job Pending Pengawas'])
@include('layout.sidebar')
@include('layout.header')
@php
    use Carbon\Carbon;
@endphp
<section class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="row align-items-center g-3">
                                    <div class="col-sm-3">
                                        <div class="d-flex align-items-center mb-2"><img
                                                src="{{ asset('dashboard/assets') }}/images/logo-full.png" class="img-fluid" alt="images" width="200px">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <h2 style="text-align: center;">JOB PENDING PENGAWAS</h2>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="border rounded p-3 d-flex">
                                    <div class="me-3">
                                        <strong>Dibuat oleh:</strong><br>
                                        <span>{{ $data[0]->nik_pic }} | {{ $data[0]->pic }}</span>
                                    </div>
                                    <div style="border-left: 1px solid #ccc; padding-left: 15px;">
                                        <strong>Section:</strong><br>
                                        <span>{{ $data[0]->section }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Hari/ Tanggal:</h6>
                                    <h5>{{ Carbon::parse($data[0]->tanggal_pending)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Shift:</h6>
                                    <h5>{{ $data[0]->shift }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Lokasi:</h6>
                                    <h5>{{ $data[0]->lokasi }}</h5>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="text-center">
                                            <tr>
                                                <th>No</th>
                                                <th>Aktivitas / Pekerjaan</th>
                                                <th>Unit Support</th>
                                                <th>Elevasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $item)
                                                <tr>
                                                    <td style="padding:12px 16px; text-align:center;">{{ $loop->iteration }}</td>
                                                    <td style="padding:12px 16px;">{{ $item->aktivitas }}</td>
                                                    <td style="padding:12px 16px; text-align:center;">{{ $item->unit }}</td>
                                                    <td style="padding:12px 16px; text-align:center;">{{ $item->elevasi }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label"><b>Issue:</b></label>
                                <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;">{!! nl2br(e($data[0]->issue)) !!}</p>
                            </div>
                            @if ($data[0]->catatan_verified_diterima != null)
                            <hr style="border: none; border-top: 2px dashed #000; height: 0; background: transparent;">
                            <div class="col-12">
                                <label class="form-label"><b>Catatan Penerima:</b></label>
                                <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;">{!! nl2br(e($data[0]->catatan_verified_diterima)) !!}</p>
                            </div>
                            @endif
                            @if ($data[0]->catatan_verified_diterima == null && $data[0]->verified_diterima == Auth::user()->nik)
                            <form action="{{ route('jobpending.catatanPenerima', $data[0]->uuid) }}" method="post">
                                @csrf
                                <hr style="border: none; border-top: 2px dashed #000; height: 0; background: transparent;">
                                <div class="col-md-12 col-12 px-2 py-2">
                                    <label for="shift"><b>Catatan Penerima (optional):</b></label>
                                    <input type="text" class="form-control" name="catatan_verified_diterima" placeholder="Tulis catatan di sini">
                                </div>
                                <div class="col-md-12 col-12 px-2 py-2">
                                <button class="btn btn-primary mb-3 big-btn" type="submit">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim
                                </button>
                                </div>

                            </form>

                            @endif

                            <div class="col-sm-6">
                                <div class="border rounded p-3">
                                    <h6>Dibuat oleh:</h6>
                                    @if ($data[0]->jabatan_dibuat)
                                        <h5>
                                            <img src="{{ $data[0]->verified_dibuat_qr }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $data[0]->nama_dibuat ?? '.......................' }}</h5>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="border rounded p-3">
                                    <h6>Diterima oleh:</h6>
                                    @if ($data[0]->jabatan_diterima)
                                        <h5>
                                            <img src="{{ $data[0]->verified_diterima_qr }}" style="max-width: 70px;">
                                        </h5>
                                    @endif
                                    <h5>{{ $data[0]->nama_diterima ?? '.......................' }}</h5>

                                </div>
                            </div>

                            <div class="card-body p-3">
                                @if ($data[0]->verified_diterima == null)
                                    <a href="#" data-bs-toggle="modal" ><span class="badge bg-success btn-verifikasi" style="font-size:14px"data-url="{{ route('jobpending.verifikasi', $data[0]->uuid) }}">
                                                        <i class="fas fa-check-circle me-1"></i>Verifikasi</span></a>
                                @endif
                                <ul class="list-inline ms-auto mb-0 d-flex justify-content-end flex-wrap">
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="#" onclick="window.history.back()" class="avtar avtar-s btn-link-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><defs><path id="stashArrowReplyDuotone0" fill="currentColor" d="M10.296 6.889L4.833 11.18a.5.5 0 0 0 0 .786l5.463 4.292a.5.5 0 0 0 .801-.482l-.355-1.955c5.016-1.204 7.108 1.494 7.914 3.235c.118.254.614.205.64-.073c.645-7.201-4.082-8.244-8.57-7.567l.371-2.046a.5.5 0 0 0-.8-.482"/></defs><use href="#stashArrowReplyDuotone0" opacity="0.5"/><use href="#stashArrowReplyDuotone0" fill-opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"/><path fill="currentColor" d="m4.833 11.18l-.308-.392zm5.463-4.291l.31.393zm-5.463 5.078l-.308.393zm5.463 4.292l-.309.394zm.801-.482l.492-.09zm-.355-1.955l-.492.09a.5.5 0 0 1 .375-.576zm7.914 3.235l-.453.21zm.64-.073l-.498-.045zm-8.57-7.567l.074.494a.5.5 0 0 1-.567-.583zm.371-2.046l.492.09zm-6.572 3.417l5.462-4.293l.618.787l-5.463 4.292zm0 1.572a1 1 0 0 1 0-1.572l.617.786zm5.462 4.293L4.525 12.36l.617-.786l5.463 4.292zm1.602-.966c.165.906-.878 1.534-1.602.966l.618-.787zm-.355-1.954l.355 1.954l-.984.18l-.355-1.955zm-.609-.397c2.614-.627 4.528-.249 5.908.57c1.367.81 2.148 2.016 2.577 2.941l-.907.42c-.378-.815-1.046-1.829-2.18-2.501c-1.122-.665-2.762-1.034-5.164-.457zm8.485 3.511a.23.23 0 0 0-.114-.116c-.024-.01-.037-.008-.04-.008a.1.1 0 0 0-.058.028a.27.27 0 0 0-.1.188l.996.09c-.044.486-.481.661-.73.688c-.252.027-.676-.049-.861-.45zm-.312.092c.312-3.488-.68-5.332-2.134-6.273c-1.506-.975-3.657-1.087-5.864-.755l-.15-.988c2.282-.344 4.739-.274 6.557.903c1.87 1.211 2.92 3.489 2.587 7.202zm-7.209-9.478l-.372 2.046l-.984-.18l.372-2.045zm-1.602-.966c.724-.568 1.767.06 1.602.966l-.984-.18z"/></svg>
                                        </a>
                                    </li>
                                    {{-- <li class="list-inline-item align-bottom me-2">
                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-pencil-simple-line f-22"></i>
                                        </a>
                                    </li> --}}
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('jobpending.download', $data[0]->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('jobpending.cetak', $data[0]->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-printer f-22"></i>
                                        </a>
                                    </li>

                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".btn-verifikasi");

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                let url = this.dataset.url;

                Swal.fire({
                    title: 'Yakin verifikasi?',
                    text: "Data ini akan diverifikasi!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, verifikasi',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim request ke server (GET/POST)
                        fetch(url, {
                            method: 'GET', // ganti ke 'POST' jika route post
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data berhasil diverifikasi',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); // reload halaman
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error', 'Terjadi kesalahan', 'error');
                        });
                    }
                });
            });
        });
    });
</script>


