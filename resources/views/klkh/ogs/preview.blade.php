@include('layout.head', ['title' => 'KLKH OGS'])
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
                                        <h6>FM-PRD-71/00/08/03/24</h6>
                                    </div>
                                </div>
                            </div>
                            <h5 style="text-align: center;">Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Area OGS</h5>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Pit:</h6>
                                    <h5>{{ $ogs->pit }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Shift:</h6>
                                    <h5>{{ $ogs->shift }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Hari/ Tanggal:</h6>
                                    <h5>{{ Carbon::parse($ogs->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Jam:</h6>
                                    <h5>{{ Carbon::parse($ogs->time)->locale('id')->isoFormat('HH:mm') }}</h5>
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
                                                <td colspan="6"><b>A. Tempat Parkir</b></td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Rata dan padat</td>
                                                <td>{{ $ogs->rata_padat_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rata_padat_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rata_padat_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rata_padat_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Parkir kendaraan sarana LV/Support/Daily Check terpisah</td>
                                                <td>{{ $ogs->parkir_terpisah_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->parkir_terpisah_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->parkir_terpisah_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->parkir_terpisah_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Tidak ada ceceran oli</td>
                                                <td>{{ $ogs->ceceran_oli_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->ceceran_oli_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->ceceran_oli_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->ceceran_oli_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Tidak ada genangan air</td>
                                                <td>{{ $ogs->genangan_air_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->genangan_air_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->genangan_air_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->genangan_air_note }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"><b>B. Rambu</b></td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Terdapat rambu informasi berkumpul darurat</td>
                                                <td>{{ $ogs->rambu_darurat_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_darurat_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_darurat_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_darurat_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Terdapat rambu-rambu lalulintas sesuai standar (Larangan, petunjuk, batas kecepatan)</td>
                                                <td>{{ $ogs->rambu_lalulintas_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_lalulintas_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_lalulintas_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_lalulintas_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Terdapat rambu tanda batas berhenti atau antri masing-masing unit</td>
                                                <td>{{ $ogs->rambu_berhenti_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_berhenti_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_berhenti_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_berhenti_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Terdapat rambu petunjuk/tanda masuk dan keluar</td>
                                                <td>{{ $ogs->rambu_masuk_keluar_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_masuk_keluar_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_masuk_keluar_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_masuk_keluar_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Terdapat rambu kapasitas OGS</td>
                                                <td>{{ $ogs->rambu_ogs_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_ogs_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_ogs_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->rambu_ogs_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Terdapat papan nama dibagian tanggul luar menghadap akses jalan yang berisi nama OGS, penanggung jawab area dan No kontak</td>
                                                <td>{{ $ogs->papan_nama_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->papan_nama_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->papan_nama_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->papan_nama_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Terdapat informasi emergency call</td>
                                                <td>{{ $ogs->emergency_call_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->emergency_call_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->emergency_call_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->emergency_call_note }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"><b>C. Lokasi Kerja</b></td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Tersedia tempat sampah</td>
                                                <td>{{ $ogs->tempat_sampah_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tempat_sampah_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tempat_sampah_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tempat_sampah_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Terdapat penyalur petir dengan nilai tahanan grounding max 5 Ohm dan mencakup seluruh area</td>
                                                <td>{{ $ogs->penyalur_petir_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->penyalur_petir_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->penyalur_petir_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->penyalur_petir_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>Tersedia tempat istirahat yang memadai</td>
                                                <td>{{ $ogs->tempat_istirahat_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tempat_istirahat_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tempat_istirahat_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tempat_istirahat_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td>Tersedia APAR </td>
                                                <td>{{ $ogs->apar_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->apar_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->apar_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->apar_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>16</td>
                                                <td>Tersedia  kotak P3K</td>
                                                <td>{{ $ogs->kotak_p3k_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->kotak_p3k_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->kotak_p3k_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->kotak_p3k_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>17</td>
                                                <td>Penerangan 20 Lux</td>
                                                <td>{{ $ogs->penerangan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->penerangan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->penerangan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->penerangan_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>18</td>
                                                <td>Terdapat kamar mandi dengan fasilitas air bersih</td>
                                                <td>{{ $ogs->kamar_mandi_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->kamar_mandi_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->kamar_mandi_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->kamar_mandi_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>19</td>
                                                <td>Permukaan tanah rata atau maksimal kemiringan 2%</td>
                                                <td>{{ $ogs->permukaan_tanah_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->permukaan_tanah_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->permukaan_tanah_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->permukaan_tanah_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>20</td>
                                                <td>Terdapat akses jalan keluar dan masuk dengan dilengkapi rambu</td>
                                                <td>{{ $ogs->akses_jalan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->akses_jalan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->akses_jalan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->akses_jalan_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>21</td>
                                                <td>Tinggi tanggul 1/3 diameter roda terbesar dan lebar tanggul 2 meter</td>
                                                <td>{{ $ogs->tinggi_tanggul_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tinggi_tanggul_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tinggi_tanggul_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->tinggi_tanggul_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>22</td>
                                                <td>Lebar jalur Bus 5 meter</td>
                                                <td>{{ $ogs->lebar_bus_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->lebar_bus_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->lebar_bus_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->lebar_bus_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>23</td>
                                                <td>Lebar jalur HD 24 meter (jalur HD dan emergency)</td>
                                                <td>{{ $ogs->lebar_hd_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->lebar_hd_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->lebar_hd_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->lebar_hd_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>24</td>
                                                <td>Terdapat Jalur emergency HD kosongan dan muatan</td>
                                                <td>{{ $ogs->jalur_hd_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->jalur_hd_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->jalur_hd_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $ogs->jalur_hd_note }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div>
                            </div>
                            <div class="col-12"><label class="form-label">Catatan:</label>
                                <p class="mb-0">{{ $ogs->additional_notes }}</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Foreman</h6>
                                    @if ($ogs->verified_foreman != null)
                                        <h5>{!! $ogs->verified_foreman !!}</h5>
                                        <h5>{{ $ogs->nama_foreman ? $ogs->nama_foreman : '.......................' }}</h5>
                                        <p>
                                            {!! $ogs->catatan_verified_foreman
                                                ? '<img src="' . asset('dashboard/assets/images/widget/writing.png') . '" alt=""> : ' . e($ogs->catatan_verified_foreman)
                                                : '' !!}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Supervisor</h6>
                                    @if ($ogs->verified_supervisor != null)
                                        <h5>{!! $ogs->verified_supervisor !!}</h5>
                                        <h5>{{ $ogs->nama_supervisor ? $ogs->nama_supervisor : '.......................' }}</h5>
                                        <p>
                                            {!! $ogs->catatan_verified_supervisor
                                                ? '<img src="' . asset('dashboard/assets/images/widget/writing.png') . '" alt=""> : ' . e($ogs->catatan_verified_supervisor)
                                                : '' !!}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Superintendent</h6>
                                    @if ($ogs->verified_superintendent != null)
                                        <h5>{!! $ogs->verified_superintendent !!}</h5>
                                        <h5>{{ $ogs->nama_superintendent ? $ogs->nama_superintendent : '.......................' }}</h5>
                                        <p>
                                            {!! $ogs->catatan_verified_superintendent
                                                ? '<img src="' . asset('dashboard/assets/images/widget/writing.png') . '" alt=""> : ' . e($ogs->catatan_verified_superintendent)
                                                : '' !!}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @if (Auth::user()->role == 'ADMIN')
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedAll{{ $ogs->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Semua</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $ogs->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $ogs->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $ogs->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @if (Auth::user()->nik == $ogs->foreman && $ogs->verified_foreman == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $ogs->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                @endif
                                @if (Auth::user()->nik == $ogs->supervisor && $ogs->verified_supervisor == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $ogs->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                @endif
                                @if (Auth::user()->nik == $ogs->superintendent && $ogs->verified_superintendent == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $ogs->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @include('klkh.ogs.modal.verifiedAll')
                                @include('klkh.ogs.modal.verifiedForeman')
                                @include('klkh.ogs.modal.verifiedSupervisor')
                                @include('klkh.ogs.modal.verifiedSuperintendent')
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
                                        <a href="{{ route('klkh.ogs.download', $ogs->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.ogs.cetak', $ogs->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
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


