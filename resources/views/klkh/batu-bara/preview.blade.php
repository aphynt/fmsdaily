@include('layout.head', ['title' => 'KLKH Batubara'])
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
                            <h5 style="text-align: center;">Pemeriksaan Kesiapan Kerja Harian & Kelayakan Lingkungan Kerja Harian (KLKH) Area Batubara</h5>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Pit:</h6>
                                    <h5>{{ $bb->pit }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Shift:</h6>
                                    <h5>{{ $bb->shift }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Hari/ Tanggal:</h6>
                                    <h5>{{ Carbon::parse($bb->date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Jam:</h6>
                                    <h5>{{ Carbon::parse($bb->time)->locale('id')->isoFormat('HH:mm') }}</h5>
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
                                                <td colspan="6"><b>A. Coal Loading Point</b></td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Lokasi loading point tidak dibawah batuan menggantung</td>
                                                <td>{{ $bb->loading_point_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->loading_point_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->loading_point_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->loading_point_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Permukaan front aman dari bahaya terjatuh atau terperosok</td>
                                                <td>{{ $bb->permukaan_front_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->permukaan_front_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->permukaan_front_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->permukaan_front_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Tinggi dan lebar bench kerja sesuai dengan standar</td>
                                                <td>{{ $bb->tinggi_bench_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->tinggi_bench_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->tinggi_bench_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->tinggi_bench_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Lebar loading point sesuai dengan standar pada spesifikasi unit loading</td>
                                                <td>{{ $bb->lebar_loading_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_loading_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_loading_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_loading_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Terdapat drainase atau paritan ke arah sump</td>
                                                <td>{{ $bb->drainase_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->drainase_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->drainase_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->drainase_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Penempatan unit loading sesuai dengan volume Batubara</td>
                                                <td>{{ $bb->penempatan_unit_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->penempatan_unit_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->penempatan_unit_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->penempatan_unit_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Terdapat pelabelan seam batubara di unit (hauler dan loader)</td>
                                                <td>{{ $bb->pelabelan_seam_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->pelabelan_seam_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->pelabelan_seam_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->pelabelan_seam_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>8</td>
                                                <td>Unit yang bekerja memiliki lampu dengan intensitas cahaya yang tinggi</td>
                                                <td>{{ $bb->lampu_unit_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lampu_unit_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lampu_unit_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lampu_unit_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>9</td>
                                                <td>Unit yang bekerja bersih dan sudah dicuci</td>
                                                <td>{{ $bb->unit_bersih_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->unit_bersih_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->unit_bersih_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->unit_bersih_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>10</td>
                                                <td>Penerangan area kerja mencukupi dan terarah untuk pekerjaan malam hari (20-50 lux)</td>
                                                <td>{{ $bb->penerangan_area_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->penerangan_area_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->penerangan_area_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->penerangan_area_note }}</td>
                                            </tr>
                                            <tr>
                                                <td>11</td>
                                                <td>Housekeeping baik (bebas sampah)</td>
                                               <td>{{ $bb->housekeeping_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->housekeeping_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->housekeeping_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->housekeeping_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>12</td>
                                                <td>Telah dilakukan pengukuran roof Batubara oleh survey</td>
                                               <td>{{ $bb->pengukuran_roof_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->pengukuran_roof_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->pengukuran_roof_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->pengukuran_roof_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>13</td>
                                                <td>Telah dilakukan cleaning pada Batubara dan Batubara bebas kontaminan</td>
                                               <td>{{ $bb->cleaning_batubara_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->cleaning_batubara_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->cleaning_batubara_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->cleaning_batubara_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>14</td>
                                                <td>Tidak terdapat genangan air pada Batubara</td>
                                               <td>{{ $bb->genangan_air_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->genangan_air_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->genangan_air_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->genangan_air_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>15</td>
                                                <td>Tidak terdapat big coal</td>
                                               <td>{{ $bb->big_coal_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->big_coal_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->big_coal_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->big_coal_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>16</td>
                                                <td>Stock material cukup</td>
                                               <td>{{ $bb->stock_material_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->stock_material_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->stock_material_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->stock_material_note }}</td>

                                            </tr>
                                            <tr>
                                                <td colspan="6"><b>B. Jalan Tambang</b></td>
                                            </tr>
                                            <tr>
                                                <td>17</td>
                                                <td>Lebar jalan angkut 3.5 x lebar unit terbesar</td>
                                               <td>{{ $bb->lebar_jalan_angkut_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_jalan_angkut_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_jalan_angkut_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_jalan_angkut_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>18</td>
                                                <td>Lebar jalan tikungan 4 x lebar unit terbesar</td>
                                               <td>{{ $bb->lebar_jalan_tikungan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_jalan_tikungan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_jalan_tikungan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->lebar_jalan_tikungan_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>19</td>
                                                <td>Super elevasi sesuai standar</td>
                                               <td>{{ $bb->super_elevasi_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->super_elevasi_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->super_elevasi_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->super_elevasi_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>20</td>
                                                <td>Tersedia safety berm pada areal yang mempunyai beda tinggi lebih dari 1 meter</td>
                                               <td>{{ $bb->safety_berm_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->safety_berm_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->safety_berm_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->safety_berm_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>21</td>
                                                <td>Tinggi tanggul minimal 2/3 tinggi ban unit terbesar</td>
                                               <td>{{ $bb->tinggi_tanggul_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->tinggi_tanggul_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->tinggi_tanggul_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->tinggi_tanggul_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>22</td>
                                                <td>Terdapat safety post pada tanggul jalan</td>
                                               <td>{{ $bb->safety_post_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->safety_post_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->safety_post_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->safety_post_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>23</td>
                                                <td>Tersedia drainase dan tidak ada genangan air di jalan angkut</td>
                                               <td>{{ $bb->drainase_genangan_air_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->drainase_genangan_air_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->drainase_genangan_air_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->drainase_genangan_air_note }}</td>

                                            </tr>
                                            <tr>
                                                <td>24</td>
                                                <td>Terdapat median jalan pada tikungan yang sudutnya lebih besar dari 60°</td>
                                               <td>{{ $bb->median_jalan_check == 'true' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->median_jalan_check == 'false' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->median_jalan_check == 'n/a' ? "✔️" : "" }}</td>
                                                <td>{{ $bb->median_jalan_note }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                                <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div>
                            </div>
                            <div class="col-12"><label class="form-label">Catatan:</label>
                                <p class="mb-0">{{ $bb->additional_notes }}</p>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Foreman</h6>

                                    @if ($bb->verified_foreman)
                                        <h5>
                                            <img src="{{ $bb->verified_foreman }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $bb->nama_foreman ?? '.......................' }}</h5>

                                    @if ($bb->catatan_verified_foreman)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $bb->catatan_verified_foreman }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Supervisor</h6>

                                    @if ($bb->verified_supervisor)
                                        <h5>
                                            <img src="{{ $bb->verified_supervisor }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $bb->nama_supervisor ?? '.......................' }}</h5>

                                    @if ($bb->catatan_verified_supervisor)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $bb->catatan_verified_supervisor }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>Superintendent</h6>

                                    @if ($bb->verified_superintendent)
                                        <h5>
                                            <img src="{{ $bb->verified_superintendent }}" style="max-width: 70px;">
                                        </h5>
                                    @endif

                                    <h5>{{ $bb->nama_superintendent ?? '.......................' }}</h5>

                                    @if ($bb->catatan_verified_superintendent)
                                        <p>
                                            <img src="{{ asset('dashboard/assets/images/widget/writing.png') }}" alt="">
                                            : {{ $bb->catatan_verified_superintendent }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body p-3">
                                @if (Auth::user()->role == 'ADMIN')
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedAll{{ $bb->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Semua</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $bb->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $bb->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $bb->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @if (Auth::user()->nik == $bb->foreman && $bb->verified_foreman == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedForeman{{ $bb->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Foreman</span></a>
                                @endif
                                @if (Auth::user()->nik == $bb->supervisor && $bb->verified_supervisor == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSupervisor{{ $bb->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Supervisor</span></a>
                                @endif
                                @if (Auth::user()->nik == $bb->superintendent && $bb->verified_superintendent == null)
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#verifiedSuperintendent{{ $bb->uuid }}"><span class="badge bg-success" style="font-size:14px">Verifikasi Superintendent</span></a>
                                @endif
                                @include('klkh.batu-bara.modal.verifiedAll')
                                @include('klkh.batu-bara.modal.verifiedForeman')
                                @include('klkh.batu-bara.modal.verifiedSupervisor')
                                @include('klkh.batu-bara.modal.verifiedSuperintendent')
                                <ul class="list-inline ms-auto mb-0 d-flex justify-content-end flex-wrap">
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.batubara') }}" class="avtar avtar-s btn-link-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><defs><path id="stashArrowReplyDuotone0" fill="currentColor" d="M10.296 6.889L4.833 11.18a.5.5 0 0 0 0 .786l5.463 4.292a.5.5 0 0 0 .801-.482l-.355-1.955c5.016-1.204 7.108 1.494 7.914 3.235c.118.254.614.205.64-.073c.645-7.201-4.082-8.244-8.57-7.567l.371-2.046a.5.5 0 0 0-.8-.482"/></defs><use href="#stashArrowReplyDuotone0" opacity="0.5"/><use href="#stashArrowReplyDuotone0" fill-opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"/><path fill="currentColor" d="m4.833 11.18l-.308-.392zm5.463-4.291l.31.393zm-5.463 5.078l-.308.393zm5.463 4.292l-.309.394zm.801-.482l.492-.09zm-.355-1.955l-.492.09a.5.5 0 0 1 .375-.576zm7.914 3.235l-.453.21zm.64-.073l-.498-.045zm-8.57-7.567l.074.494a.5.5 0 0 1-.567-.583zm.371-2.046l.492.09zm-6.572 3.417l5.462-4.293l.618.787l-5.463 4.292zm0 1.572a1 1 0 0 1 0-1.572l.617.786zm5.462 4.293L4.525 12.36l.617-.786l5.463 4.292zm1.602-.966c.165.906-.878 1.534-1.602.966l.618-.787zm-.355-1.954l.355 1.954l-.984.18l-.355-1.955zm-.609-.397c2.614-.627 4.528-.249 5.908.57c1.367.81 2.148 2.016 2.577 2.941l-.907.42c-.378-.815-1.046-1.829-2.18-2.501c-1.122-.665-2.762-1.034-5.164-.457zm8.485 3.511a.23.23 0 0 0-.114-.116c-.024-.01-.037-.008-.04-.008a.1.1 0 0 0-.058.028a.27.27 0 0 0-.1.188l.996.09c-.044.486-.481.661-.73.688c-.252.027-.676-.049-.861-.45zm-.312.092c.312-3.488-.68-5.332-2.134-6.273c-1.506-.975-3.657-1.087-5.864-.755l-.15-.988c2.282-.344 4.739-.274 6.557.903c1.87 1.211 2.92 3.489 2.587 7.202zm-7.209-9.478l-.372 2.046l-.984-.18l.372-2.045zm-1.602-.966c.724-.568 1.767.06 1.602.966l-.984-.18z"/></svg>
                                        </a>
                                    </li>
                                    {{-- <li class="list-inline-item align-bottom me-2">
                                        <a href="#" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-pencil-simple-line f-22"></i>
                                        </a>
                                    </li> --}}
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.batubara.download', $bb->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('klkh.batubara.cetak', $bb->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
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


