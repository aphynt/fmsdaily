@include('layout.head', ['title' => 'KLKH OGS'])
@include('layout.sidebar')
@include('layout.header')

<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-sm-12 col-md-6 col-xxl-4">
                        <h3>KLKH OGS</h3>
                    </div>
                    {{-- <div class="col-12">
                        <div class="mb-3 row d-flex align-items-center">
                            <div class="col-sm-12 col-md-10 mb-2">
                                <div class="input-group" id="pc-datepicker-5">
                                    <input type="text" class="form-control form-control-sm" placeholder="Start date" name="range-start" style="max-width: 200px;" id="range-start">
                                    <span class="input-group-text">s/d</span>
                                    <input type="text" class="form-control form-control-sm" placeholder="End date" name="range-end" style="max-width: 200px;" id="range-end">
                                    <button type="button" class="btn btn-primary btn-sm">View Report</button>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-2 mb-2 text-md-end text-center">
                                <button type="button" class="btn btn-success w-100 w-md-auto">
                                    <i class="fas fa-download"></i> Download
                                </button>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="container mt-3">
                            <form action="{{ route('klkh.ogs.post') }}" method="POST" id="submitFormKLKHOGS">
                                @csrf
                                <!-- Inputan di atas tabel -->
                                <div class="row mb-3">
                                    <!-- Kolom 1: PIT dan Shift -->
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <label for="pit">PIT</label>
                                        <select class="form-control form-control-sm pb-2" id="exampleFormControlSelect2"
                                                                name="pit" required>
                                                                <option selected disabled></option>
                                                                @foreach ($users['pit'] as $pit)
                                                                    <option value="{{ $pit->id }}">{{ $pit->keterangan }}</option>
                                                                @endforeach
                                                            </select>
                                    </div>
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <label for="shift">Shift</label>
                                        <select class="form-control form-control-sm pb-2" id="exampleFormControlSelect1"
                                                                name="shift" required>
                                                                <option selected disabled></option>
                                                                @foreach ($users['shift'] as $sh)
                                                                    <option value="{{ $sh->id }}">{{ $sh->keterangan }}</option>
                                                                @endforeach
                                                            </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <!-- Kolom 2: Hari/Tanggal dan Jam -->
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <label for="date">Hari/ Tanggal</label>
                                        <input type="date" class="form-control form-control-sm pb-2" id="date" name="date" required>
                                    </div>
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <label for="time">Jam</label>
                                        <input type="time" class="form-control form-control-sm pb-2" id="time" name="time" required>
                                    </div>
                                </div>
                                <hr>
                                <h4>A. Tempat Parkir</h4>
                                <hr>
                                <!-- Form dengan radio button -->
                                <div class="mb-3">
                                    <label for="rata_padat_check">1. Rata dan padat:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="rata_padat_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="rata_padat_true" name="rata_padat_check" value="true" required /> Ya
                                        </label>
                                        <label for="rata_padat_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="rata_padat_false" name="rata_padat_check" value="false" /> Tidak
                                        </label>
                                        <label for="rata_padat_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="rata_padat_na" name="rata_padat_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="rata_padat_note" id="rata_padat_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="parkir_terpisah_check">2. Parkir kendaraan sarana LV/Support/Daily Check terpisah:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="parkir_terpisah_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="parkir_terpisah_true" name="parkir_terpisah_check" value="true" required /> Ya
                                        </label>
                                        <label for="parkir_terpisah_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="parkir_terpisah_false" name="parkir_terpisah_check" value="false" /> Tidak
                                        </label>
                                        <label for="parkir_terpisah_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="parkir_terpisah_na" name="parkir_terpisah_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="parkir_terpisah_note" id="parkir_terpisah_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="ceceran_oli_check">3. Tidak ada ceceran oli:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="ceceran_oli_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="ceceran_oli_true" name="ceceran_oli_check" value="true" required /> Ya
                                        </label>
                                        <label for="ceceran_oli_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="ceceran_oli_false" name="ceceran_oli_check" value="false" /> Tidak
                                        </label>
                                        <label for="ceceran_oli_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="ceceran_oli_na" name="ceceran_oli_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="ceceran_oli_note" id="ceceran_oli_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="genangan_air_check">4. Tidak ada genangan air:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="genangan_air_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="genangan_air_true" name="genangan_air_check" value="true" required /> Ya
                                        </label>
                                        <label for="genangan_air_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="genangan_air_false" name="genangan_air_check" value="false" /> Tidak
                                        </label>
                                        <label for="genangan_air_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="genangan_air_na" name="genangan_air_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="genangan_air_note" id="genangan_air_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <h4>B. Rambu</h4>
                                <hr>
                                <div class="mb-3">
                                    <label for="rambu_darurat_check">5. Terdapat rambu informasi berkumpul darurat:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="rambu_darurat_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_darurat_true" name="rambu_darurat_check" value="true" required /> Ya
                                        </label>
                                        <label for="rambu_darurat_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_darurat_false" name="rambu_darurat_check" value="false" /> Tidak
                                        </label>
                                        <label for="rambu_darurat_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_darurat_na" name="rambu_darurat_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="rambu_darurat_note" id="rambu_darurat_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="rambu_lalulintas_check">6. Terdapat rambu-rambu lalulintas sesuai standar (Larangan, petunjuk, batas kecepatan):</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="rambu_lalulintas_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_lalulintas_true" name="rambu_lalulintas_check" value="true" required /> Ya
                                        </label>
                                        <label for="rambu_lalulintas_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_lalulintas_false" name="rambu_lalulintas_check" value="false" /> Tidak
                                        </label>
                                        <label for="rambu_lalulintas_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_lalulintas_na" name="rambu_lalulintas_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="rambu_lalulintas_note" id="rambu_lalulintas_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="rambu_berhenti_check">7. Terdapat rambu tanda batas berhenti atau antri masing-masing unit:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="rambu_berhenti_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_berhenti_true" name="rambu_berhenti_check" value="true" required /> Ya
                                        </label>
                                        <label for="rambu_berhenti_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_berhenti_false" name="rambu_berhenti_check" value="false" /> Tidak
                                        </label>
                                        <label for="rambu_berhenti_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_berhenti_na" name="rambu_berhenti_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="rambu_berhenti_note" id="rambu_berhenti_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="rambu_masuk_keluar_check">8. Terdapat rambu petunjuk/tanda masuk dan keluar:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="rambu_masuk_keluar_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_masuk_keluar_true" name="rambu_masuk_keluar_check" value="true" required /> Ya
                                        </label>
                                        <label for="rambu_masuk_keluar_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_masuk_keluar_false" name="rambu_masuk_keluar_check" value="false" /> Tidak
                                        </label>
                                        <label for="rambu_masuk_keluar_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_masuk_keluar_na" name="rambu_masuk_keluar_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="rambu_masuk_keluar_note" id="rambu_masuk_keluar_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="rambu_ogs_check">9. Terdapat rambu kapasitas OGS:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="rambu_ogs_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_ogs_true" name="rambu_ogs_check" value="true" required /> Ya
                                        </label>
                                        <label for="rambu_ogs_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_ogs_false" name="rambu_ogs_check" value="false" /> Tidak
                                        </label>
                                        <label for="rambu_ogs_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="rambu_ogs_na" name="rambu_ogs_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="rambu_ogs_note" id="rambu_ogs_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="papan_nama_check">10. Terdapat papan nama di bagian tanggul luar menghadap akses jalan yang berisi nama OGS, penanggung jawab area, dan No kontak:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="papan_nama_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="papan_nama_true" name="papan_nama_check" value="true" required /> Ya
                                        </label>
                                        <label for="papan_nama_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="papan_nama_false" name="papan_nama_check" value="false" /> Tidak
                                        </label>
                                        <label for="papan_nama_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="papan_nama_na" name="papan_nama_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="papan_nama_note" id="papan_nama_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="emergency_call_check">11. Terdapat informasi emergency call:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="emergency_call_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="emergency_call_true" name="emergency_call_check" value="true" required /> Ya
                                        </label>
                                        <label for="emergency_call_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="emergency_call_false" name="emergency_call_check" value="false" /> Tidak
                                        </label>
                                        <label for="emergency_call_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="emergency_call_na" name="emergency_call_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="emergency_call_note" id="emergency_call_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <h4>C. Lokasi Kerja</h4>
                                <hr>
                                <div class="mb-3">
                                    <label for="tempat_sampah_check">12. Tersedia tempat sampah:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="tempat_sampah_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="tempat_sampah_true" name="tempat_sampah_check" value="true" required /> Ya
                                        </label>
                                        <label for="tempat_sampah_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="tempat_sampah_false" name="tempat_sampah_check" value="false" /> Tidak
                                        </label>
                                        <label for="tempat_sampah_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="tempat_sampah_na" name="tempat_sampah_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="tempat_sampah_note" id="tempat_sampah_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="penyalur_petir_check">13. Terdapat penyalur petir dengan nilai tahanan grounding max 5 Ohm dan mencakup seluruh area:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="penyalur_petir_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="penyalur_petir_true" name="penyalur_petir_check" value="true" required /> Ya
                                        </label>
                                        <label for="penyalur_petir_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="penyalur_petir_false" name="penyalur_petir_check" value="false" /> Tidak
                                        </label>
                                        <label for="penyalur_petir_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="penyalur_petir_na" name="penyalur_petir_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="penyalur_petir_note" id="penyalur_petir_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="tempat_istirahat_check">14. Tersedia tempat istirahat yang memadai:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="tempat_istirahat_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="tempat_istirahat_true" name="tempat_istirahat_check" value="true" required /> Ya
                                        </label>
                                        <label for="tempat_istirahat_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="tempat_istirahat_false" name="tempat_istirahat_check" value="false" /> Tidak
                                        </label>
                                        <label for="tempat_istirahat_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="tempat_istirahat_na" name="tempat_istirahat_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="tempat_istirahat_note" id="tempat_istirahat_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="apar_check">15. Tersedia APAR:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="apar_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="apar_true" name="apar_check" value="true" required /> Ya
                                        </label>
                                        <label for="apar_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="apar_false" name="apar_check" value="false" /> Tidak
                                        </label>
                                        <label for="apar_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="apar_na" name="apar_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="apar_note" id="apar_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="kotak_p3k_check">16. Tersedia kotak P3K:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="kotak_p3k_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="kotak_p3k_true" name="kotak_p3k_check" value="true" required /> Ya
                                        </label>
                                        <label for="kotak_p3k_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="kotak_p3k_false" name="kotak_p3k_check" value="false" /> Tidak
                                        </label>
                                        <label for="kotak_p3k_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="kotak_p3k_na" name="kotak_p3k_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="kotak_p3k_note" id="kotak_p3k_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="penerangan_check">17. Penerangan 20 Lux:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="penerangan_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="penerangan_true" name="penerangan_check" value="true" required /> Ya
                                        </label>
                                        <label for="penerangan_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="penerangan_false" name="penerangan_check" value="false" /> Tidak
                                        </label>
                                        <label for="penerangan_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="penerangan_na" name="penerangan_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="penerangan_note" id="penerangan_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="kamar_mandi_check">18. Terdapat kamar mandi dengan fasilitas air bersih:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="kamar_mandi_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="kamar_mandi_true" name="kamar_mandi_check" value="true" required /> Ya
                                        </label>
                                        <label for="kamar_mandi_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="kamar_mandi_false" name="kamar_mandi_check" value="false" /> Tidak
                                        </label>
                                        <label for="kamar_mandi_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="kamar_mandi_na" name="kamar_mandi_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="kamar_mandi_note" id="kamar_mandi_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="permukaan_tanah_check">19. Permukaan tanah rata atau maksimal kemiringan 2%:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="permukaan_tanah_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="permukaan_tanah_true" name="permukaan_tanah_check" value="true" required /> Ya
                                        </label>
                                        <label for="permukaan_tanah_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="permukaan_tanah_false" name="permukaan_tanah_check" value="false" /> Tidak
                                        </label>
                                        <label for="permukaan_tanah_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="permukaan_tanah_na" name="permukaan_tanah_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="permukaan_tanah_note" id="permukaan_tanah_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="akses_jalan_check">20. Terdapat akses jalan keluar dan masuk dengan dilengkapi rambu:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="akses_jalan_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="akses_jalan_true" name="akses_jalan_check" value="true" required /> Ya
                                        </label>
                                        <label for="akses_jalan_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="akses_jalan_false" name="akses_jalan_check" value="false" /> Tidak
                                        </label>
                                        <label for="akses_jalan_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="akses_jalan_na" name="akses_jalan_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="akses_jalan_note" id="akses_jalan_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="tinggi_tanggul_check">21. tinggi tanggul 3/4 diameter roda terbesar dan lebar tanggul 2 meter:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="tinggi_tanggul_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="tinggi_tanggul_true" name="tinggi_tanggul_check" value="true" required /> Ya
                                        </label>
                                        <label for="tinggi_tanggul_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="tinggi_tanggul_false" name="tinggi_tanggul_check" value="false" /> Tidak
                                        </label>
                                        <label for="tinggi_tanggul_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="tinggi_tanggul_na" name="tinggi_tanggul_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="tinggi_tanggul_note" id="tinggi_tanggul_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="lebar_bus_check">22. Lebar jalur Bus 5 meter:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="lebar_bus_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="lebar_bus_true" name="lebar_bus_check" value="true" required /> Ya
                                        </label>
                                        <label for="lebar_bus_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="lebar_bus_false" name="lebar_bus_check" value="false" /> Tidak
                                        </label>
                                        <label for="lebar_bus_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="lebar_bus_na" name="lebar_bus_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="lebar_bus_note" id="lebar_bus_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="lebar_hd_check">23. Lebar jalur HD 24 meter (jalur HD dan emergency):</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="lebar_hd_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="lebar_hd_true" name="lebar_hd_check" value="true" required /> Ya
                                        </label>
                                        <label for="lebar_hd_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="lebar_hd_false" name="lebar_hd_check" value="false" /> Tidak
                                        </label>
                                        <label for="lebar_hd_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="lebar_hd_na" name="lebar_hd_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="lebar_hd_note" id="lebar_hd_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="jalur_hd_check">24. Terdapat Jalur emergency HD kosongan dan muatan:</label>
                                    <div class="d-flex justify-content-start">
                                        <label for="jalur_hd_true" class="me-3 px-2 py-2">
                                            <input type="radio" id="jalur_hd_true" name="jalur_hd_check" value="true" required /> Ya
                                        </label>
                                        <label for="jalur_hd_false" class="me-3 px-2 py-2">
                                            <input type="radio" id="jalur_hd_false" name="jalur_hd_check" value="false" /> Tidak
                                        </label>
                                        <label for="jalur_hd_na" class="me-3 px-2 py-2">
                                            <input type="radio" id="jalur_hd_na" name="jalur_hd_check" value="n/a" /> N/A
                                        </label>
                                    </div>
                                    <input type="text" name="jalur_hd_note" id="jalur_hd_note" class="form-control form-control-sm pb-2 mt-2" placeholder="Keterangan" />
                                </div>
                                <hr>

                                <!-- Catatan -->
                                <div class="form-group mt-3">
                                    <label for="notes">Catatan:</label>
                                    <textarea id="notes" name="additional_notes" class="form-control form-control-sm pb-2" rows="3"
                                        placeholder="Tambahkan catatan..."></textarea>
                                </div>

                                <hr>
                                <div class="row mb-3">
                                    <!-- Kolom 1: PIT dan Shift -->
                                    @if (Auth::user()->role != 'SUPERVISOR')
                                        <div class="col-md-6 col-12 px-2 py-2">
                                            <label for="supervisor">Supervisor</label>
                                            <select class="form-control form-control-sm pb-2" id="exampleFormControlSelect2"
                                                                    name="supervisor">
                                                                    <option selected disabled></option>
                                                                    @foreach ($users['supervisor'] as $sv)
                                                                        <option value="{{ $sv->NRP }}">{{ $sv->PERSONALNAME }}</option>
                                                                    @endforeach
                                                                </select>
                                        </div>
                                    @endif
                                    <div class="col-md-6 col-12 px-2 py-2">
                                        <label for="superintendent">Superintendent</label>
                                        <select class="form-control form-control-sm pb-2" id="exampleFormControlSelect1"
                                                                name="superintendent">
                                                                <option selected disabled></option>
                                                                @foreach ($users['superintendent'] as $si)
                                                                    <option value="{{ $si->NRP }}">{{ $si->PERSONALNAME }} ({{ $si->JABATAN }})</option>
                                                                @endforeach
                                                            </select>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="text-center mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm" id="submitButtonKLKHOGS">Submit</button>
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

    const formKLKHOGS = document.getElementById('submitFormKLKHOGS');
    const submitButtonKLKHOGS = document.getElementById('submitButtonKLKHOGS');

    formKLKHOGS.addEventListener('submit', function() {
        // Nonaktifkan tombol submit ketika form sedang diproses
        submitButtonKLKHOGS.disabled = true;
        submitButtonKLKHOGS.innerText = 'Processing...';
        setTimeout(function() {
            submitButtonKLKHOGS.disabled = false;
            submitButtonKLKHOGS.innerText = 'Submit';
        }, 7000);
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
