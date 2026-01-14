@include('layout.head', ['title' => 'KLKH Dumping di Kolam Air/Lumpur'])
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
                                        <h6>FM-PRD-66/00/13/01/23</h6>
                                    </div>
                                </div>
                            </div>
                            <h5 style="text-align: center;">Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Dumping di Kolam Air/Lumpur</h5>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Pit:</h6>
                                    <h5>{{ $lpr->pit }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Shift:</h6>
                                    <h5>{{ $lpr->shift }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Hari/ Tanggal:</h6>
                                    <h5>{{ Carbon::parse($lpr->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Jam:</h6>
                                    <h5>{{ Carbon::parse($lpr->time)->locale('id')->isoFormat('HH:mm') }}</h5>
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
                                                <td colspan="6"><b>A. Jalan</b></td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Apakah terdapat unit breakdown di jalan</td>
                                                <td>{{ $lpr->unit_breakdown_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->unit_breakdown_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->unit_breakdown_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->unit_breakdown_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Terdapat rambu rambu jalan</td>
                                                <td>{{ $lpr->rambu_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->rambu_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->rambu_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->rambu_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Terdapat pelaporan grade jalan Max 12 %</td>
                                                <td>{{ $lpr->grade_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->grade_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->grade_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->grade_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Terdapat Unit Maintenance Jalan (MG, BD, EXC)</td>
                                                <td>{{ $lpr->unit_maintenance_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->unit_maintenance_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->unit_maintenance_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->unit_maintenance_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Terdapat unit pengendalian Debu (WT)</td>
                                                <td>{{ $lpr->debu_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->debu_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->debu_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->debu_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Lebar jalan min 21 meter</td>
                                                <td>{{ $lpr->lebar_jalan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->lebar_jalan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->lebar_jalan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->lebar_jalan_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Terdapat area blind spot</td>
                                                <td>{{ $lpr->blind_spot_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->blind_spot_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->blind_spot_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->blind_spot_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Kondisi jalan bergelombang (andulating)</td>
                                                <td>{{ $lpr->kondisi_jalan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->kondisi_jalan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->kondisi_jalan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->kondisi_jalan_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Terdapat Tanggul jalan dengan tinggi 3/4 dari diameter  tyre HD terbesar</td>
                                                <td>{{ $lpr->tanggul_jalan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tanggul_jalan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tanggul_jalan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tanggul_jalan_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Terdapat pengelolaan air di jalan saat Hujan (sodetan, drainase)</td>
                                                <td>{{ $lpr->pengelolaan_air_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->pengelolaan_air_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->pengelolaan_air_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->pengelolaan_air_note }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"><b>B. Dumpingan</b></td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Apakah terdapat crack, patahan penurunan dumpingan</td>
                                                <td>{{ $lpr->crack_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->crack_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->crack_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->crack_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Apakah luas area dumpingan mencukupi untuk manuver HD (min 30 meter)</td>
                                                <td>{{ $lpr->luas_area_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->luas_area_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->luas_area_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->luas_area_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Apakah terdapat tanggul dumpingan (bundwall) dengan tinggi 3/4 dari diameter tyre HD terbesar</td>
                                                <td>{{ $lpr->tanggul_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tanggul_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tanggul_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tanggul_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>Apakah terdapat free dump di area dumpingan</td>
                                                <td>{{ $lpr->free_dump_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->free_dump_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->free_dump_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->free_dump_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td>Apakah terdapat pengelolaan alokasi  material kurang bagus </td>
                                                <td>{{ $lpr->alokasi_material_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->alokasi_material_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->alokasi_material_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->alokasi_material_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>16</td>
                                                <td>Apakah terdapat beda level area dumpingan</td>
                                                <td>{{ $lpr->beda_level_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->beda_level_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->beda_level_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->beda_level_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>17</td>
                                                <td>Apakah tinggi dumpingan max 2.5 meter dari permukaan air/lumpur</td>
                                                <td>{{ $lpr->tinggi_dumpingan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tinggi_dumpingan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tinggi_dumpingan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tinggi_dumpingan_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>18</td>
                                                <td>Apakah terdapat genangan air di area dumpingan</td>
                                                <td>{{ $lpr->genangan_air_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->genangan_air_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->genangan_air_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->genangan_air_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>19</td>
                                                <td>Apakah dumpingan bergelombang</td>
                                                <td>{{ $lpr->dumpingan_bergelombang_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->dumpingan_bergelombang_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->dumpingan_bergelombang_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->dumpingan_bergelombang_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>20</td>
                                                <td>Apakah terdapat bendera acuan dumpingan</td>
                                                <td>{{ $lpr->bendera_acuan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->bendera_acuan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->bendera_acuan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->bendera_acuan_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>21</td>
                                                <td>Apakah terdapat rambu jarak dumping 7,5 m</td>
                                                <td>{{ $lpr->rambu_jarak_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->rambu_jarak_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->rambu_jarak_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->rambu_jarak_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>22</td>
                                                <td>Apakah terdapat tower lamp (Penerangan cukup saat gelap/malam hari)</td>
                                                <td>{{ $lpr->tower_lamp_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tower_lamp_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tower_lamp_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->tower_lamp_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>23</td>
                                                <td>Apakah terdapat penyalur petir (penangkal Petir)</td>
                                                <td>{{ $lpr->penyalur_petir_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->penyalur_petir_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->penyalur_petir_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->penyalur_petir_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>24</td>
                                                <td>Apakah terdapat area tempat berkumpul saat terjadi emergency (Muster Point)</td>
                                                <td>{{ $lpr->muster_point_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->muster_point_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->muster_point_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->muster_point_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>25</td>
                                                <td>Apakah terdapat area parkir sarana dengan safety bund wall</td>
                                                <td>{{ $lpr->safety_bundwall_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->safety_bundwall_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->safety_bundwall_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->safety_bundwall_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>26</td>
                                                <td>Apakah terdapat Ring buoy dengan tali panjang 15 m</td>
                                                <td>{{ $lpr->ring_buoy_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->ring_buoy_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->ring_buoy_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->ring_buoy_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>27</td>
                                                <td>Apakah terdapat sling ware</td>
                                                <td>{{ $lpr->sling_ware_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->sling_ware_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->sling_ware_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->sling_ware_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>28</td>
                                                <td>Apakah terdapat pondok pengawas</td>
                                                <td>{{ $lpr->pondok_pengawas_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->pondok_pengawas_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->pondok_pengawas_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->pondok_pengawas_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>29</td>
                                                <td>Apakah terdapat struktur pengawas</td>
                                                <td>{{ $lpr->struktur_pengawas_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->struktur_pengawas_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->struktur_pengawas_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->struktur_pengawas_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>30</td>
                                                <td>Apakah terdapat Life Jacket untuk Unit Bulldozer</td>
                                                <td>{{ $lpr->life_jacket_bulldozer_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->life_jacket_bulldozer_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->life_jacket_bulldozer_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->life_jacket_bulldozer_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>31</td>
                                                <td>Apakah terdapat nomor Emergenchy di area disposal</td>
                                                <td>{{ $lpr->emergency_number_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->emergency_number_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->emergency_number_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->emergency_number_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>32</td>
                                                <td>Apakah terdapat life jacket untuk Spotter</td>
                                                <td>{{ $lpr->life_jacket_spotter_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->life_jacket_spotter_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->life_jacket_spotter_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $lpr->life_jacket_spotter_note }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div>
                            </div>
                            <div class="col-12"><label class="form-label">Catatan:</label>
                                <p class="mb-0">{{ $lpr->additional_notes }}</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Foreman</h6>

                                    @if ($lpr->verified_foreman)
                                        <h5>
                                            <img src="{{ $lpr->verified_foreman }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $lpr->nama_foreman ?? '.......................' }}</h5>

                                    @if ($lpr->catatan_verified_foreman)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $lpr->catatan_verified_foreman }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Supervisor</h6>

                                    @if ($lpr->verified_supervisor)
                                        <h5>
                                            <img src="{{ $lpr->verified_supervisor }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $lpr->nama_supervisor ?? '.......................' }}</h5>

                                    @if ($lpr->catatan_verified_supervisor)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $lpr->catatan_verified_supervisor }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Superintendent</h6>

                                    @if ($lpr->verified_superintendent)
                                        <h5>
                                            <img src="{{ $lpr->verified_superintendent }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $lpr->nama_superintendent ?? '.......................' }}</h5>

                                    @if ($lpr->catatan_verified_superintendent)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $lpr->catatan_verified_superintendent }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @if (Auth::user()->roleRel?->name === 'ADMIN')
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedAll{{ $lpr->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Semua</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $lpr->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $lpr->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $lpr->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @if (Auth::user()->nik == $lpr->foreman && $lpr->verified_foreman == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $lpr->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                @endif
                                @if (Auth::user()->nik == $lpr->supervisor && $lpr->verified_supervisor == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $lpr->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                @endif
                                @if (Auth::user()->nik == $lpr->superintendent && $lpr->verified_superintendent == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $lpr->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @include('klkh.lumpur.modal.verifiedAll')
                                @include('klkh.lumpur.modal.verifiedForeman')
                                @include('klkh.lumpur.modal.verifiedSupervisor')
                                @include('klkh.lumpur.modal.verifiedSuperintendent')
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
                                        <a href="{{ route('klkh.lumpur.download', $lpr->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.lumpur.cetak', $lpr->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
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


