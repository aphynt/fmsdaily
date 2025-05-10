@include('layout.head', ['title' => 'KLKH Disposal'])
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
                                        <h6>FM-PRD-52/01/18/10/22</h6>
                                    </div>
                                </div>
                            </div>
                            <h5 style="text-align: center;">Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Departemen Produksi Area Disposal/Dumping Point</h5>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Pit:</h6>
                                    <h5>{{ $dp->pit }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Shift:</h6>
                                    <h5>{{ $dp->shift }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Hari/ Tanggal:</h6>
                                    <h5>{{ Carbon::parse($dp->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Jam:</h6>
                                    <h5>{{ Carbon::parse($dp->time)->locale('id')->isoFormat('HH:mm') }}</h5>
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
                                                <td>Lebar dumping point 2x (lebar unit terbesar + turn radius) x N Load</td>
                                                <td>{{ $dp->dumping_point_1 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_1 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_1 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_1_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Adanya patok cek elevasi</td>
                                                <td>{{ $dp->dumping_point_2 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_2 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_2 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_2_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Tinggi tanggul dumpingan atau dump/bud wall 3/4 tinggi ban unit terbesar</td>
                                                <td>{{ $dp->dumping_point_3 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_3 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_3 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_3_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Kondisi permukaan lantai dumping rata dan permukaan tanah tidak lembek dan tidak bergelombang</td>
                                                <td>{{ $dp->dumping_point_4 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_4 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_4 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_4_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Tidak ada genangan air di lokasi dumping</td>
                                                <td>{{ $dp->dumping_point_5 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_5 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_5 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_5_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Terdapat unit support bulldozer di lokasi dumping</td>
                                                <td>{{ $dp->dumping_point_6 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_6 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_6 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_6_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Rambu atau papan informasi memadai</td>
                                                <td>{{ $dp->dumping_point_7 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_7 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_7 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_7_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Tersedia lampu penerangan untuk pekerjaan malam hari</td>
                                                <td>{{ $dp->dumping_point_8 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_8 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_8 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_8_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Pengendalian debu sudah dilakukan dengan baik (penyiraman terjadwal dan jumlahnya mencukupi)</td>
                                                <td>{{ $dp->dumping_point_9 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_9 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_9 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_9_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Frame final disposal rapi dan sesuai desain (dimensi slope sesuai dengan standar)</td>
                                                <td>{{ $dp->dumping_point_10 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_10 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_10 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_10_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Terdapat pondok dump man</td>
                                                <td>{{ $dp->dumping_point_11 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_11 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_11 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_11_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Terdapat bendera merah dan hijau untuk penunjuk dumping dan informasi lokasi bahaya untuk dumping</td>
                                                <td>{{ $dp->dumping_point_12 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_12 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_12 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_12_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Housekeeping terjaga (disposal rapi dari tumpukan material yang belum di- spreading)</td>
                                                <td>{{ $dp->dumping_point_13 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_13 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_13 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_13_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>Alokasi material di disposal sesuai dengan rencana</td>
                                                <td>{{ $dp->dumping_point_14 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_14 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_14 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_14_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td>Operator melakukan metode dumping sesuai dengan prosedur</td>
                                                <td>{{ $dp->dumping_point_15 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_15 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_15 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_15_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>16</td>
                                                <td>Terdapat petugas pemandu HD untuk mundur (Stopper/Pengawas)</td>
                                                <td>{{ $dp->dumping_point_16 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_16 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_16 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_16_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>17</td>
                                                <td>Petugas memiliki radio komunikasi (HT)</td>
                                                <td>{{ $dp->dumping_point_17 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_17 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_17 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_17_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>18</td>
                                                <td>Terdapat median pemisah ruas jalan akses masuk & keluar area pembuangan</td>
                                                <td>{{ $dp->dumping_point_18 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_18 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_18 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_18_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>19</td>
                                                <td>Tersedia tanggul  (pipa Gorong-gorong) untuk dumping lumpur cair</td>
                                                <td>{{ $dp->dumping_point_19 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_19 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_19 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_19_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>20</td>
                                                <td>Kondisi pasak penahan gorong-gorong kuat tidak goyah</td>
                                                <td>{{ $dp->dumping_point_20 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_20 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_20 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_20_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>21</td>
                                                <td>Kondisi apron masih baik tidak tergerus lumpur cair</td>
                                                <td>{{ $dp->dumping_point_21 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_21 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_21 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_21_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>22</td>
                                                <td>Material Top Soil di tempatkan khusus dan tidak tercampur material OB</td>
                                                <td>{{ $dp->dumping_point_22 == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_22 == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_22 == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $dp->dumping_point_22_note }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div>
                            </div>
                            <div class="col-12"><label class="form-label">Catatan:</label>
                                <p class="mb-0">{{ $dp->additional_notes }}</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Foreman</h6>
                                    @if ($dp->verified_foreman != null)
                                        <h5>{!! $dp->verified_foreman !!}</h5>
                                        <h5>{{ $dp->nama_foreman ? $dp->nama_foreman : '.......................' }}</h5>
                                        <p>
                                            {!! $dp->catatan_verified_foreman
                                                ? '<img src="' . asset('dashboard/assets/images/widget/writing.png') . '" alt=""> : ' . e($dp->catatan_verified_foreman)
                                                : '' !!}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Supervisor</h6>
                                    @if ($dp->verified_supervisor != null)
                                        <h5>{!! $dp->verified_supervisor !!}</h5>
                                        <h5>{{ $dp->nama_supervisor ? $dp->nama_supervisor : '.......................' }}</h5>
                                        <p>
                                            {!! $dp->catatan_verified_supervisor
                                                ? '<img src="' . asset('dashboard/assets/images/widget/writing.png') . '" alt=""> : ' . e($dp->catatan_verified_supervisor)
                                                : '' !!}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Superintendent</h6>
                                    @if ($dp->verified_superintendent != null)
                                        <h5>{!! $dp->verified_superintendent !!}</h5>
                                        <h5>{{ $dp->nama_superintendent ? $dp->nama_superintendent : '.......................' }}</h5>
                                        <p>
                                            {!! $dp->catatan_verified_superintendent
                                                ? '<img src="' . asset('dashboard/assets/images/widget/writing.png') . '" alt=""> : ' . e($dp->catatan_verified_superintendent)
                                                : '' !!}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @if (Auth::user()->role == 'ADMIN')
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedAll{{ $dp->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Semua</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $dp->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $dp->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $dp->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @if (Auth::user()->nik == $dp->foreman && $dp->verified_foreman == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $dp->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                @endif
                                @if (Auth::user()->nik == $dp->supervisor && $dp->verified_supervisor == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $dp->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                @endif
                                @if (Auth::user()->nik == $dp->superintendent && $dp->verified_superintendent == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $dp->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @include('klkh.disposal.modal.verifiedAll')
                                @include('klkh.disposal.modal.verifiedForeman')
                                @include('klkh.disposal.modal.verifiedSupervisor')
                                @include('klkh.disposal.modal.verifiedSuperintendent')
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
                                        <a href="{{ route('klkh.disposal.download', $dp->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.disposal.cetak', $dp->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
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


