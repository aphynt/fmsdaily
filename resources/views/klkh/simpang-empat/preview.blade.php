@include('layout.head', ['title' => 'KLKH Intersection/Simpang Empat'])
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
                                        <h6>FM-PRD-73/00/10/06/24</h6>
                                    </div>
                                </div>
                            </div>
                            <h5 style="text-align: center;">Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) INTERSECTION (Simpang Empat)</h5>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Pit:</h6>
                                    <h5>{{ $se->pit }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Shift:</h6>
                                    <h5>{{ $se->shift }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Hari/ Tanggal:</h6>
                                    <h5>{{ Carbon::parse($se->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Jam:</h6>
                                    <h5>{{ Carbon::parse($se->time)->locale('id')->isoFormat('HH:mm') }}</h5>
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
                                                <td colspan="6"><b>A. Rambu</b></td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Papan informasi nama intersection</td>
                                                <td>{{ $se->intersection_name_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_name_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_name_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_name_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Rambu batas kecepatan </td>
                                                <td>{{ $se->speed_limit_sign_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->speed_limit_sign_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->speed_limit_sign_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->speed_limit_sign_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Rambu simpang 4 </td>
                                                <td>{{ $se->intersection_sign_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_sign_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_sign_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_sign_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Rambu hati- hati </td>
                                                <td>{{ $se->caution_sign_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->caution_sign_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->caution_sign_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->caution_sign_note }}</td>
                                            </tr>

                                            <tr>
                                                <td>5</td>
                                                <td>Rambu batas berhenti </td>
                                                <td>{{ $se->stop_sign_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->stop_sign_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->stop_sign_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->stop_sign_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Rambu mulai & berhenti klakson</td>
                                                <td>{{ $se->horn_sign_unit_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->horn_sign_unit_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->horn_sign_unit_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->horn_sign_unit_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Rambu Ganda (stop dan penunjuk ararah)</td>
                                                <td>{{ $se->double_sign_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->double_sign_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->double_sign_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->double_sign_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Rambu larangan belok kanan</td>
                                                <td>{{ $se->right_turn_prohibited_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->right_turn_prohibited_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->right_turn_prohibited_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->right_turn_prohibited_note }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"><b>B. Lokasi Kerja</b></td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Lampu Trafic berfungsi dengan baik</td>
                                                <td>{{ $se->traffic_light_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->traffic_light_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->traffic_light_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->traffic_light_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Terdapat petugas Intersection yang memiliki kartu petugas intersection</td>
                                                <td>{{ $se->intersection_officer_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_officer_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_officer_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_officer_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Terdapat radio komunikasi dengan chanel yang sesuai</td>
                                                <td>{{ $se->radio_communication_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->radio_communication_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->radio_communication_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->radio_communication_note }}</td>
                                            </tr>

                                            <tr>
                                                <td>12</td>
                                                <td>Posisi pondok intersection memungkinkan petugas Intersection memantau lalulintas dengan baik diarea intersection</td>
                                                <td>{{ $se->intersection_monitoring_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_monitoring_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_monitoring_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->intersection_monitoring_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Terdapat median jalan standar dengan rambu ganda</td>
                                                <td>{{ $se->standard_road_medium_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->standard_road_medium_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->standard_road_medium_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->standard_road_medium_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>Lebar jalan 3,5 x unit terbesar</td>
                                                <td>{{ $se->road_width_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->road_width_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->road_width_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->road_width_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td>Jalur angkut rata, tidak bergelombang, dan bebas dari tumpahan material dan spoil-spoil</td>
                                                <td>{{ $se->smooth_transport_path_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->smooth_transport_path_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->smooth_transport_path_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->smooth_transport_path_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>16</td>
                                                <td>Tidak terdapat blind spot</td>
                                                <td>{{ $se->blind_spot_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->blind_spot_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->blind_spot_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->blind_spot_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>17</td>
                                                <td>Pada radius 75 m sebelum intersection, tinggi bund wall / tanggul jalan wall adalah 75 cm</td>
                                                <td>{{ $se->radius_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->radius_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->radius_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->radius_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>18</td>
                                                <td>Terdapat tempat sampah</td>
                                                <td>{{ $se->trash_bin_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->trash_bin_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->trash_bin_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->trash_bin_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>19</td>
                                                <td>Terdapat fasilitas toilet</td>
                                                <td>{{ $se->toilet_facility_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->toilet_facility_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->toilet_facility_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->toilet_facility_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>20</td>
                                                <td>Tingkat pencahayaan minimal 20 Lux</td>
                                                <td>{{ $se->lighting_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->lighting_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->lighting_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->lighting_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>21</td>
                                                <td>Terdapat Kotak P3K di </td>
                                                <td>{{ $se->first_aid_box_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->first_aid_box_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->first_aid_box_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->first_aid_box_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>22</td>
                                                <td>Terdapat APAR</td>
                                                <td>{{ $se->fire_extinguisher_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->fire_extinguisher_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->fire_extinguisher_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->fire_extinguisher_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>23</td>
                                                <td>Terdapat Parkir area sarana beserta rambu parkir</td>
                                                <td>{{ $se->parking_area_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->parking_area_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->parking_area_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->parking_area_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>24</td>
                                                <td>Terdapat Penyalur Petir</td>
                                                <td>{{ $se->lightning_rod_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->lightning_rod_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->lightning_rod_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->lightning_rod_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>25</td>
                                                <td>Terdapat SOP intersection dalam pondok</td>
                                                <td>{{ $se->sop_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $se->sop_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $se->sop_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $se->sop_note }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div>
                            </div>
                            <div class="col-12"><label class="form-label">Catatan:</label>
                                <p class="mb-0">{{ $se->additional_notes }}</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Foreman</h6>

                                    @if ($se->verified_foreman)
                                        <h5>
                                            <img src="{{ $se->verified_foreman }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $se->nama_foreman ?? '.......................' }}</h5>

                                    @if ($se->catatan_verified_foreman)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $se->catatan_verified_foreman }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Supervisor</h6>

                                    @if ($se->verified_supervisor)
                                        <h5>
                                            <img src="{{ $se->verified_supervisor }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $se->nama_supervisor ?? '.......................' }}</h5>

                                    @if ($se->catatan_verified_supervisor)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $se->catatan_verified_supervisor }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Superintendent</h6>

                                    @if ($se->verified_superintendent)
                                        <h5>
                                            <img src="{{ $se->verified_superintendent }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $se->nama_superintendent ?? '.......................' }}</h5>

                                    @if ($se->catatan_verified_superintendent)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $se->catatan_verified_superintendent }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @if (Auth::user()->role == 'ADMIN')
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedAll{{ $se->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Semua</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $se->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $se->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $se->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @if (Auth::user()->nik == $se->foreman && $se->verified_foreman == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $se->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                @endif
                                @if (Auth::user()->nik == $se->supervisor && $se->verified_supervisor == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $se->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                @endif
                                @if (Auth::user()->nik == $se->superintendent && $se->verified_superintendent == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $se->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @include('klkh.simpang-empat.modal.verifiedAll')
                                @include('klkh.simpang-empat.modal.verifiedForeman')
                                @include('klkh.simpang-empat.modal.verifiedSupervisor')
                                @include('klkh.simpang-empat.modal.verifiedSuperintendent')
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
                                        <a href="{{ route('klkh.simpangempat.download', $se->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.simpangempat.cetak', $se->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
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


