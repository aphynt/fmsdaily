@include('layout.head', ['title' => 'Laporan Kata Sandi'])
@include('layout.sidebar')
@include('layout.header')
@php
    use Carbon\Carbon;
@endphp

<style>
    @media (max-width: 768px) {
    h5 {
        font-size: 9pt; /* Ganti dengan ukuran yang sesuai */
    }
}
</style>
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
                                        <h6>FM-SHE-146/01/04/08/21</h6>
                                    </div>
                                </div>
                            </div>
                            <h5 style="text-align: center; margin: 0; padding: 0;">PENGAWASAN PADA JAM KRITIKAL - MENGANTUK</h5>
                            <h5 style="text-align: center; margin: 0; padding: 0;">1. PENGAWAS MENGHUBUNGI OPERATOR TIAP JAM MEMAKAI RADIO</h5>
                            <h5 style="text-align: center; margin: 0; padding: 0;">2. OPERATOR MENJAWAB DENGAN KATA SANDI</h5>

                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Kata Sandi:</h6>
                                    <h5>{{ $data['kataSandi']->kata_sandi }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Tgl/Bln/Thn:</h6>
                                    <h5>{{ Carbon::parse($data['kataSandi']->tanggal)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Disampaikan Oleh Pengawas:</h6>
                                    <h5>{{ $data['kataSandi']->nik_pic }} | {{ $data['kataSandi']->pic }}</h5>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="border rounded p-3">
                                    <h6 class="mb-0">Shift:</h6>
                                    <h5>{{ $data['kataSandi']->shift }}</h5>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead >
                                            <tr>
                                                <th>No</th>
                                                <th>No. Unit</th>
                                                <th>Shift</th>
                                                <th>Jam Monitor</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data['kataSandiUnit'] as $ksu)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $ksu->no_unit }}</td>
                                                <td>{{ $data['kataSandi']->shift }}</td>
                                                <td>{{ Carbon::parse($ksu->jam_monitor)->locale('id')->isoFormat('HH:mm') }}</td>
                                                <td>{{ $ksu->keterangan }}</td>
                                            </tr>
                                            @endforeach

                                        </tbody>

                                    </table>
                                </div>
                                {{-- <div class="text-start">
                                    <hr class="mb-2 mt-1 border-secondary border-opacity-50">
                                </div> --}}
                            </div>

                            {{-- <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>PIMPINAN DEPARTEMEN</h6>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>NAMA</h6>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="border rounded p-3">
                                    <h6>TANDA VERIFIKASI</h6>

                                </div>
                            </div> --}}

                            <div class="card-body p-3">

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
                                        <a href="{{ route('laporan-kata-sandi.pdf', $data['kataSandi']->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
                                            <i class="ph-duotone ph-download-simple f-22"></i>
                                        </a>
                                    </li>
                                    <li class="list-inline-item align-bottom me-2">
                                        <a href="{{ route('laporan-kata-sandi.cetak', $data['kataSandi']->uuid) }}" target="_blank" class="avtar avtar-s btn-link-secondary">
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


