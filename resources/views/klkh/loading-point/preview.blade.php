@include('layout.head', ['title' => 'KLKH Loading Point'])
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
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center mb-2"><img
                                                src="{{ asset('dashboard/assets') }}/images/logo-full.png" class="img-fluid" alt="images" width="200px">
                                        </div>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <h6>FM-PRD-50/01/06/09/24</h6>
                                    </div>
                                </div>
                            </div>
                            <h5 style="text-align: center;">Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Departemen Produksi Area Loading Point</h5>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Pit:</h6>
                                    <h5>{{ $ld->pit }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Shift:</h6>
                                    <h5>{{ $ld->shift }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Hari/ Tanggal:</h6>
                                    <h5>{{ Carbon::parse($ld->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Jam:</h6>
                                    <h5>{{ Carbon::parse($ld->time)->locale('id')->isoFormat('HH:mm') }}</h5>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="text-center">
                                            <tr>
                                                <th rowspan="2">No</th>
                                                <th rowspan="2">Point Yang Diperiksa</th>
                                                <th colspan="3">Cek</th>
                                                <th rowspan="2">Keterangan</th>
                                            </tr>
                                            <tr>
                                                <th>Ya</th>
                                                <th>Tidak</th>
                                                <th>N/A</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Lokasi loading point tidak dibawah batuan menggantung</td>
                                                <td>{{ $ld->loading_point_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_point_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_point_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_point_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Permukaan front aman dari bahaya terjatuh atau terperosok</td>
                                                <td>{{ $ld->front_surface_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->front_surface_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->front_surface_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->front_surface_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Tinggi dan lebar bench kerja sesuai dengan standar parameter (Buku Panduan Foreman/Supervisor Lapangan)</td>
                                                <td>{{ $ld->bench_work_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->bench_work_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->bench_work_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->bench_work_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Tinggi tanggul akses jalan masuk loading point 3/4 tinggi roda terbesar</td>
                                                <td>{{ $ld->access_dike_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->access_dike_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->access_dike_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->access_dike_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Lebar loading point sesuai dengan standar pada spesifikasi unit loading</td>
                                                <td>{{ $ld->loading_point_width_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_point_width_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_point_width_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_point_width_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Terdapat drainage atau paritan kearah sump</td>
                                                <td>{{ $ld->drainage_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->drainage_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->drainage_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->drainage_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Loading point tidak bergelombang, tidak berair, dan bebas batuan lepas</td>
                                                <td>{{ $ld->no_waves_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->no_waves_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->no_waves_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->no_waves_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Penempatan unit loading sesuai dengan volume material pada area tersebut</td>
                                                <td>{{ $ld->unit_placement_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->unit_placement_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->unit_placement_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->unit_placement_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Stok material cukup</td>
                                                <td>{{ $ld->material_stock_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->material_stock_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->material_stock_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->material_stock_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Kombinasi unit loading dan unit hauling sesuai</td>
                                                <td>{{ $ld->loading_hauling_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_hauling_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_hauling_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->loading_hauling_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Pengendalian debu sudah dilakukan dengan baik (penyiraman terjadwal dan jumlahnya mencukupi)</td>
                                                <td>{{ $ld->dust_control_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->dust_control_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->dust_control_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->dust_control_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Penerangan areal kerja mencukupi dan terarah untuk pekerjaan malam hari</td>
                                                <td>{{ $ld->lighting_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->lighting_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->lighting_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->lighting_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Kebersihan sekitar area pembuangan & Housekeeping baik (bebas sampah)</td>
                                                <td>{{ $ld->housekeeping_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->housekeeping_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->housekeeping_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ld->housekeeping_note }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div>
                            </div>
                            <div class="col-12"><label class="form-label">Catatan:</label>
                                <p class="mb-0">{{ $ld->additional_notes }}</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Foreman</h6>

                                    @if ($ld->verified_foreman)
                                        <h5>
                                            <img src="{{ $ld->verified_foreman }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $ld->nama_foreman ?? '.......................' }}</h5>

                                    @if ($ld->catatan_verified_foreman)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $ld->catatan_verified_foreman }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Supervisor</h6>

                                    @if ($ld->verified_supervisor)
                                        <h5>
                                            <img src="{{ $ld->verified_supervisor }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $ld->nama_supervisor ?? '.......................' }}</h5>

                                    @if ($ld->catatan_verified_supervisor)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $ld->catatan_verified_supervisor }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Superintendent</h6>

                                    @if ($ld->verified_superintendent)
                                        <h5>
                                            <img src="{{ $ld->verified_superintendent }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $ld->nama_superintendent ?? '.......................' }}</h5>

                                    @if ($ld->catatan_verified_superintendent)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $ld->catatan_verified_superintendent }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @if (Auth::user()->role == 'ADMIN')
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedAll{{ $ld->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Semua</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $ld->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $ld->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $ld->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @if (Auth::user()->nik == $ld->foreman && $ld->verified_foreman == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $ld->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                @endif
                                @if (Auth::user()->nik == $ld->supervisor && $ld->verified_supervisor == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $ld->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                @endif
                                @if (Auth::user()->nik == $ld->superintendent && $ld->verified_superintendent == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $ld->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @include('klkh.loading-point.modal.verifiedAll')
                                @include('klkh.loading-point.modal.verifiedForeman')
                                @include('klkh.loading-point.modal.verifiedSupervisor')
                                @include('klkh.loading-point.modal.verifiedSuperintendent')
                                <ul class="list-inline ms-auto mb-0 d-flex justify-content-end flex-wrap">
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="#" onclick="window.history.back()" class="avtar avtar-s btn-link-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><defs><path id="stashArrowReplyDuotone0" fill="currentColor" d="M10.296 6.889L4.833 11.18a.5.5 0 0 0 0 .786l5.463 4.292a.5.5 0 0 0 .801-.482l-.355-1.955c5.016-1.204 7.108 1.494 7.914 3.235c.118.254.614.205.64-.073c.645-7.201-4.082-8.244-8.57-7.567l.371-2.046a.5.5 0 0 0-.8-.482"/></defs><use href="#stashArrowReplyDuotone0" opacity="0.5"/><use href="#stashArrowReplyDuotone0" fill-opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"/><path fill="currentColor" d="m4.833 11.18l-.308-.392zm5.463-4.291l.31.393zm-5.463 5.078l-.308.393zm5.463 4.292l-.309.394zm.801-.482l.492-.09zm-.355-1.955l-.492.09a.5.5 0 0 1 .375-.576zm7.914 3.235l-.453.21zm.64-.073l-.498-.045zm-8.57-7.567l.074.494a.5.5 0 0 1-.567-.583zm.371-2.046l.492.09zm-6.572 3.417l5.462-4.293l.618.787l-5.463 4.292zm0 1.572a1 1 0 0 1 0-1.572l.617.786zm5.462 4.293L4.525 12.36l.617-.786l5.463 4.292zm1.602-.966c.165.906-.878 1.534-1.602.966l.618-.787zm-.355-1.954l.355 1.954l-.984.18l-.355-1.955zm-.609-.397c2.614-.627 4.528-.249 5.908.57c1.367.81 2.148 2.016 2.577 2.941l-.907.42c-.378-.815-1.046-1.829-2.18-2.501c-1.122-.665-2.762-1.034-5.164-.457zm8.485 3.511a.23.23 0 0 0-.114-.116c-.024-.01-.037-.008-.04-.008a.1.1 0 0 0-.058.028a.27.27 0 0 0-.1.188l.996.09c-.044.486-.481.661-.73.688c-.252.027-.676-.049-.861-.45zm-.312.092c.312-3.488-.68-5.332-2.134-6.273c-1.506-.975-3.657-1.087-5.864-.755l-.15-.988c2.282-.344 4.739-.274 6.557.903c1.87 1.211 2.92 3.489 2.587 7.202zm-7.209-9.478l-.372 2.046l-.984-.18l.372-2.045zm-1.602-.966c.724-.568 1.767.06 1.602.966l-.984-.18z"/></svg>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-pencil-simple-line f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.loading-point.download', $ld->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.loading-point.cetak', $ld->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-printer f-22"></i>
                                        </a>
                                    </li>

                                </ul>
                            </div>

                            {{-- <div class="col-12 text-end d-print-none">
                                <button class="btn btn-outline-secondary btn-print-invoice">Download</button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')


