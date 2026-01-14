@include('layout.head', ['title' => 'Users'])
@include('layout.sidebar')
@include('layout.header')

<section class="pc-container">
    <div class="pc-content">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="mb-3 row d-flex align-items-center">
                    <div class="col-sm-12 col-md-10 mb-2"></div>
                    {{-- @if (Auth::user()->role != 'ADMIN') --}}
                        <div class="col-sm-12 col-md-2 mb-2 text-md-end">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#insertUser"><span class="badge bg-success" style="font-size: 16px"><i class="fas fa-plus"></i> Tambah User</span></a>
                        </div>
                        @include('user.modal.insert')
                    {{-- @endif --}}
                </div>
                <div class="card">
                    <div class="col-12">

                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-hover" id="pc-dt-simple">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($user as $us)
                                    <tr>
                                        <td>
                                            <div class="row align-items-center">
                                                <div class="col-auto pe-0"><img src="{{ asset('dashboard/assets') }}/images/user/avatar-1.png" alt="user-image" class="wid-40 hei-40 rounded"></div>
                                                <div class="col">
                                                    <h6 class="mb-2"><span class="text-truncate w-100">{{ $us->name }}</span></h6>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-left f-w-600">{{ $us->nik }}</td>
                                        <td class="text-left f-w-600">{{ $us->role }}</td>
                                        <td>
                                            @if ($us->statusenabled == true)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-danger">Non Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-left f-w-600">
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#resetPassword{{ $us->id }}"><span class="badge bg-secondary">Reset Password</span></a>
                                            {{-- <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#resetPassword{{ $us->id }}">Reset Password</a> --}}
                                            @if ($us->statusenabled == true)
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#statusEnabled{{ $us->id }}"><span class="badge bg-warning">Nonaktifkan</span></a>
                                            @else
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#statusEnabled{{ $us->id }}"><span class="badge bg-success">Aktifkan</span></a>
                                            @endif
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#changeRole{{ $us->id }}"><span class="badge bg-info">Ganti Role</span></a>
                                        </td>
                                    </tr>
                                    @include('user.modal.statusEnabled')
                                    @include('user.modal.resetPassword')
                                    @include('user.modal.changeRole')
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('layout.footer')
<script src="{{ asset('dashboard/assets') }}/js/plugins/simple-datatables.js"></script>
<script>
    const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
        sortable: false,
        perPage: 10
    });
</script>

