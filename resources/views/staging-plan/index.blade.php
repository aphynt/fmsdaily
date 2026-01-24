@include('layout.head', ['title' => 'Staging Plan'])
@include('layout.sidebar')
@include('layout.header')

<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                            {{-- <li class="breadcrumb-item"><a href="javascript: void(0)">Home</a></li> --}}
                            <li class="breadcrumb-item"><a href="javascript: void(0)">Staging Plan</a></li>
                        </ul>
                    </div>
                    <div class="col-12">
                        <div class="mb-3 row d-flex align-items-center">
                            <div class="col-12 col-md-10">
                                <form action="" method="get">
                                    <div class="row">
                                        <div class="col-6 col-md-3 mb-2">
                                            <label for="startStagingPlan">Start Date</label>
                                            <input type="text" id="startStagingPlan" class="form-control" name="startStagingPlan" value="{{ request('startStagingPlan') ? \Carbon\Carbon::parse(request('startStagingPlan'))->format('m/d/Y') : '' }}">
                                        </div>
                                        <div class="col-6 col-md-3 mb-2">
                                            <label for="endStagingPlan">End Date</label>
                                            <input type="text" id="endStagingPlan" class="form-control" name="endStagingPlan" value="{{ request('endStagingPlan') ? \Carbon\Carbon::parse(request('endStagingPlan'))->format('m/d/Y') : '' }}">
                                        </div>
                                        {{-- <div class="col-6 col-md-2 mb-2">
                                            <label for="shift">Shift</label>
                                            <select class="form-select" name="shift" id="shift">
                                                <option value="Semua" {{ (request('shift') == 'Semua' || !request('shift')) ? 'selected' : '' }}>Semua</option>
                                                @foreach ($shift as $shi)
                                                    <option value="{{ $shi->id }}" {{ request('shift') == $shi->id ? 'selected' : '' }}>
                                                        {{ $shi->keterangan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 col-md-2 mb-2">
                                            <label for="pit">Pit</label>
                                            <select class="form-select" name="pit" id="pit">
                                                @foreach ($pit as $ptt)
                                                    <option value="{{ $ptt->id }}" {{ request('pit') == $ptt->id ? 'selected' : '' }}>
                                                        {{ $ptt->keterangan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}

                                        <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary w-100" style="padding-top:10px;padding-bottom:10px;">Tampilkan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Tombol buat job pending -->
                            @if (in_array(Auth::user()->role, ['ADMIN', 'PIT CONTROL']))
                            <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                                <a href="{{ route('stagingplan.insert') }}" class="btn btn-success w-100" style="padding-top:10px;padding-bottom:10px;" data-bs-toggle="modal" data-bs-target="#tambahStagingPlan">
                                    <i class="fas fa-plus"></i> Tambah
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @include('staging-plan.modal.insert')
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="example" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                   <tr>
                                        <th>No</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        {{-- <th>Shift</th> --}}
                                        <th>Pit</th>
                                        <th>Document</th>
                                        <th>Aksi</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach($staging as $stg)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $stg->start_date }}</td>
                                            <td>{{ $stg->end_date }}</td>
                                            {{-- <td>{{ $stg->shift }}</td> --}}
                                            <td>{{ $stg->pit }}</td>
                                            <td>
                                                <a href="{{ route('stagingplan.preview', $stg->uuid) }}"
                                                    class="badge text-center me-1"
                                                    style="font-size:14px; background-color:#001932; color:white; display:inline-block;">Lihat Stage
                                                </a>
                                            </td>
                                            @if (in_array(Auth::user()->role, ['ADMIN', 'PIT CONTROL']))
                                            <td class="d-flex">
                                                <a href="{{ route('jobpending.show', $stg->uuid) }}" class="text-danger me-1" style="font-size:16px;" data-bs-toggle="modal" data-bs-target="#deleteStagingPlan{{ $stg->uuid }}"
                                                    title="Hapus"> <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                            @else
                                            <td>
                                                -
                                            </td>
                                            @endif
                                        </tr>
                                        @include('staging-plan.modal.delete')
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="modal fade" id="imagePreviewModal" tabindex="-1">
                                <div class="modal-dialog modal-xl modal-dialog-centered">
                                    <div class="modal-content" style="height:800px">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Preview Gambar</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body text-center" style="overflow:auto;">
                                            <img id="previewImage">
                                        </div>

                                    </div>
                                </div>
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
(function () {
    const startInput = document.querySelector('#startStagingPlan');
    const endInput   = document.querySelector('#endStagingPlan');

    if (startInput) {
        new Datepicker(startInput, {
            buttonClass: 'btn',
            autohide: true,
        });
    }

    if (endInput) {
        new Datepicker(endInput, {
            buttonClass: 'btn',
            autohide: true,
        });
    }
})();

document.addEventListener("DOMContentLoaded", function () {
    const startInput = document.getElementById("startStagingPlan");
    const endInput   = document.getElementById("endStagingPlan");

    const today = new Date();
    const formattedDate = `${String(today.getMonth() + 1).padStart(2, '0')}/${String(today.getDate()).padStart(2, '0')}/${today.getFullYear()}`;

    if (startInput && !startInput.value) {
        startInput.value = formattedDate;
    }

    if (endInput && !endInput.value) {
        endInput.value = formattedDate;
    }
});
</script>

<script>
    var groupColumn = 3;
    var table = $('#example').DataTable({
    columnDefs: [{ visible: false, targets: groupColumn }],
    order: [[groupColumn, 'asc']],
    displayLength: 25,
    drawCallback: function (settings) {
        var api = this.api();
        var rows = api.rows({ page: 'current' }).nodes();
        var last = null;

        api.column(groupColumn, { page: 'current' })
            .data()
            .each(function (group, i) {
                if (last !== group) {
                    $(rows)
                        .eq(i)
                        .before(
                            '<tr class="group"><td colspan="10"><strong>' +
                                group +
                                '</strong></td></tr>'
                        );

                    last = group;
                }
            });
    }
});

// Order by the grouping
$('#example tbody').on('click', 'tr.group', function () {
    var currentOrder = table.order()[0];
    if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
        table.order([groupColumn, 'desc']).draw();
    }
    else {
        table.order([groupColumn, 'asc']).draw();
    }
});
</script>

