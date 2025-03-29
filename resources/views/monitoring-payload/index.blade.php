@include('layout.head', ['title' => 'Monitoring Payload'])
@include('layout.sidebar')
@include('layout.header')

<style>

    @media (max-width: 768px) {
        .datatable-table thead th, .table thead th {
            font-size: 7pt;
    }
    .datatable-table tbody td, .table tbody td {
            font-size: 9pt;
    }
        .pagination {
            --bs-pagination-padding-x: 8px;
            --bs-pagination-padding-y: 1px;
            --bs-pagination-font-size: 11pt;
        }

        .dt-buttons.btn-group.flex-wrap {
            display: none;
        }
    }
</style>

@php
// Variabel untuk menghitung total di footer
$total_LESS_THAN_100 = 0;
$total_BETWEEN_100_AND_115 = 0;
$total_GREATHER_THAN_115 = 0;
$total_MAX_PAYLOAD = 0;
$count = 0;
$sum_2 =  0;
$count_a = 0;
$count_b = 0;

$total_LESS_THAN_100_khusus = 0;
$total_BETWEEN_100_AND_115_khusus = 0;
$total_GREATHER_THAN_115_khusus = 0;
$total_MAX_PAYLOAD_khusus = 0;
$count_khusus = 0;
$sum_2_khusus =  0;
$count_a_khusus = 0;
$count_b_khusus = 0;

$total_LESS_THAN_100_2023 = 0;
$total_BETWEEN_100_AND_115_2023 = 0;
$total_GREATHER_THAN_115_2023 = 0;
$total_MAX_PAYLOAD_2023 = 0;
$count_2023 = 0;
$sum_2_2023 =  0;
$count_a_2023 = 0;
$count_b_2023 = 0;
$total_ritasi_2023 = 0;
$PAYLOAD_AVERAGE_2023 = 0;
$payload_max_2023 = 0;
$grand_total_ritasi_2023 = 0;
@endphp

<section class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="row">
                        <form action="" method="get" class="w-100">
                            <div class="col-12 col-md-4 d-flex align-items-center">
                                <!-- Select Input -->
                                <div class="mb-3 me-2 w-100">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select class="form-select" data-trigger name="unit" id="unit">
                                        <option selected disabled></option>
                                        @foreach ($data['unit'] as $u)
                                            <option value="{{ $u->VHC_ID }}">{{ $u->VHC_ID }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-success" style="font-size:16px;">Tampilkan</button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="cbtn-selectors" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th style="background-color:aquamarine;" rowspan="2">Unit</th>
                                        <th style="background-color:aquamarine;" colspan="3">Distribusi Payload</th>
                                        <th rowspan="2" style="word-wrap: break-word; white-space: normal;background-color:aquamarine;">
                                            Kejadian Payload
                                            > 115 T
                                          </th>
                                        <th rowspan="2" style="word-wrap: break-word; white-space: normal;background-color:aquamarine;">Maksimal Payload</th>
                                    </tr>
                                    <tr>
                                        <th style="background-color:aquamarine;">< 100</th>
                                        <th style="background-color:aquamarine;">100 - 115</th>
                                        <th style="background-color:aquamarine;">> 115</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($data['payload'] as $py)
                                    @php
                                        $count++;
                                        if ($py['GREATHER_THAN_115'] > 0) {
                                            $count_a++;
                                        }
                                        if ($py['MAX_PAYLOAD'] > 0) {
                                            $count_b++;
                                        }
                                        $sum_2 += $py['LESS_THAN_100'] + $py['BETWEEN_100_AND_115'] + $py['GREATHER_THAN_115'];

                                        // Menambahkan nilai ke total
                                        $total_LESS_THAN_100 += $py['LESS_THAN_100'];
                                        $total_BETWEEN_100_AND_115 += $py['BETWEEN_100_AND_115'];
                                        $total_GREATHER_THAN_115 += $py['GREATHER_THAN_115'];
                                        $total_MAX_PAYLOAD += $py['MAX_PAYLOAD'];
                                    @endphp
                                        <tr>
                                            <td>{{ $py['VHC_ID'] }}</td>
                                            <td style="text-align: center">{{ round($py['LESS_THAN_100'], 1) }}</td>
                                            <td style="text-align: center">{{ round($py['BETWEEN_100_AND_115'], 1) }}</td>
                                            <td style="text-align: center">{{ round($py['GREATHER_THAN_115'], 1) }}</td>
                                            <td style="text-align: center">{{ round($py['GREATHER_THAN_115'], 1) }}</td>
                                            <td style="text-align: center">{{ round($py['MAX_PAYLOAD'], 1) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background-color:moccasin">
                                    {{-- @dd($sum_2) --}}
                                    <tr>
                                        <td>%</td>
                                        <td style="text-align: center">{{ $sum_2 != 0 ? round($total_LESS_THAN_100 / $sum_2, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ $sum_2 != 0 ? round($total_BETWEEN_100_AND_115 / $sum_2, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ $sum_2 != 0 ? round($total_GREATHER_THAN_115 / $sum_2, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ $count_a != 0 ? round($total_GREATHER_THAN_115 / $count_a, 1) : 0 }}</td>
                                        <td style="text-align: center">{{ $count_b != 0 ? round($total_MAX_PAYLOAD / $count_b, 1) : 0 }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <h5>Monitoring Unit Khusus</h5>
                            <table id="cbtn-selectors-khusus" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th style="background-color:aquamarine;text-align: center" rowspan="2">Unit</th>
                                        <th style="background-color:aquamarine;text-align: center" colspan="3">Distribusi Payload</th>
                                        <th rowspan="2" style="word-wrap: break-word; white-space: normal;background-color:aquamarine;text-align: center">
                                            Kejadian Payload
                                            > 115 T
                                          </th>
                                        <th rowspan="2" style="word-wrap: break-word; white-space: normal;background-color:aquamarine;text-align: center">Maksimal Payload</th>
                                    </tr>
                                    <tr>
                                        <th style="background-color:aquamarine;text-align: center">< 100</th>
                                        <th style="background-color:aquamarine;text-align: center">100 - 115</th>
                                        <th style="background-color:aquamarine;text-align: center">> 115</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($data['payload_khusus'] as $pk)
                                    @php
                                        $count_khusus++;
                                        if ($pk->GREATHER_THAN_115 > 0) {
                                            $count_a_khusus++;
                                        }
                                        if ($pk->MAX_PAYLOAD > 0) {
                                            $count_b_khusus++;
                                        }
                                        $sum_2_khusus += $pk->LESS_THAN_100 + $pk->BETWEEN_100_AND_115 + $pk->GREATHER_THAN_115;

                                        // Menambahkan nilai ke total
                                        $total_LESS_THAN_100_khusus += $pk->LESS_THAN_100;
                                        $total_BETWEEN_100_AND_115_khusus += $pk->BETWEEN_100_AND_115;
                                        $total_GREATHER_THAN_115_khusus += $pk->GREATHER_THAN_115;
                                        $total_MAX_PAYLOAD_khusus += $pk->MAX_PAYLOAD;
                                    @endphp
                                        <tr>
                                            <td>{{ $pk->VHC_ID }}</td>
                                            <td style="text-align: center">{{ round($pk->LESS_THAN_100, 1) }}</td>
                                            <td style="text-align: center">{{ round($pk->BETWEEN_100_AND_115, 1) }}</td>
                                            <td style="text-align: center">{{ round($pk->GREATHER_THAN_115, 1) }}</td>
                                            <td style="text-align: center">{{ round($pk->GREATHER_THAN_115, 1) }}</td>
                                            <td style="text-align: center">{{ round($pk->MAX_PAYLOAD, 1) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background-color:moccasin">
                                    {{-- @dd($count_a_khusus) --}}
                                    <tr>
                                        <td>%</td>
                                        <td style="text-align: center">{{ $sum_2_khusus != 0 ? round($total_LESS_THAN_100_khusus / $sum_2_khusus, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ $sum_2_khusus != 0 ? round($total_BETWEEN_100_AND_115_khusus / $sum_2_khusus, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ $sum_2_khusus != 0 ? round($total_GREATHER_THAN_115_khusus / $sum_2_khusus, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ $count_a_khusus != 0 ? round($total_GREATHER_THAN_115_khusus / $count_a_khusus, 1) : 0 }}</td>
                                        <td style="text-align: center">{{ $count_b_khusus != 0 ? round($total_MAX_PAYLOAD_khusus / $count_b_khusus, 1) : 0 }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                @if (in_array(Auth::user()->role, ['ADMIN', 'MANAGER']))
                <div class="card">
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <h5>Distribusi Payload Tahun 2023 - 2024</h5>
                            <table id="cbtn-selectors-2023" class="table table-striped table-hover table-bordered nowrap">
                                <thead style="text-align: center; vertical-align: middle;">
                                    <tr>
                                        <th style="background-color:aquamarine;" rowspan="2">Unit</th>
                                        <th style="background-color:aquamarine;" colspan="3">Distribusi Payload</th>
                                        <th rowspan="2" style="word-wrap: break-word; white-space: normal;background-color:aquamarine;">Total Ritasi</th>
                                        <th rowspan="2" style="word-wrap: break-word; white-space: normal;background-color:aquamarine;">Payload Average</th>
                                        <th rowspan="2" style="word-wrap: break-word; white-space: normal;background-color:aquamarine;">Payload Max</th>
                                    </tr>
                                    <tr>
                                        <th style="background-color:aquamarine;">< 100</th>
                                        <th style="background-color:aquamarine;">100 - 115</th>
                                        <th style="background-color:aquamarine;">> 115</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($data['payload_2023'] as $p2023)

                                    @php
                                        $count_2023++;
                                        if ($p2023->GREATHER_THAN_115 > 0) {
                                            $count_a_2023++;
                                        }
                                        if ($p2023->MAX_PAYLOAD > 0) {
                                            $count_b_2023++;
                                        }
                                        $sum_2_2023 += $p2023->LESS_THAN_100 + $p2023->BETWEEN_100_AND_115 + $p2023->GREATHER_THAN_115;

                                        $total_ritasi_2023 = $p2023->LESS_THAN_100 + $p2023->BETWEEN_100_AND_115 + $p2023->GREATHER_THAN_115;
                                        $PAYLOAD_AVERAGE_2023 = $p2023->LESS_THAN_100 + $p2023->BETWEEN_100_AND_115 + $p2023->GREATHER_THAN_115;
                                        $payload_max_2023 = $p2023->LESS_THAN_100 + $p2023->BETWEEN_100_AND_115 + $p2023->GREATHER_THAN_115;

                                        // Menambahkan nilai ke total
                                        $total_LESS_THAN_100_2023 += $p2023->LESS_THAN_100;
                                        $total_BETWEEN_100_AND_115_2023 += $p2023->BETWEEN_100_AND_115;
                                        $total_GREATHER_THAN_115_2023 += $p2023->GREATHER_THAN_115;
                                        $grand_total_ritasi_2023 += $total_ritasi_2023;
                                    @endphp

                                        <tr>
                                            <td>{{ $p2023->VHC_ID }}</td>
                                            <td style="text-align: center">{{ number_format(round($p2023->LESS_THAN_100, 1), 0, ',', '.') }}</td>
                                            <td style="text-align: center">{{ number_format(round($p2023->BETWEEN_100_AND_115, 1), 0, ',', '.') }}</td>
                                            <td style="text-align: center">{{ number_format(round($p2023->GREATHER_THAN_115, 1), 0, ',', '.') }}</td>
                                            <td style="text-align: center">{{ number_format(round($total_ritasi_2023, 1), 0, ',', '.') }}</td>
                                            <td style="text-align: center">{{ number_format(round($p2023->PAYLOAD_AVERAGE, 1), 0, ',', '.') }}</td>
                                            <td style="text-align: center">{{ number_format(round($p2023->MAX_PAYLOAD, 1), 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                    {{-- @dd($total_LESS_THAN_100_2023) --}}
                                </tbody>
                                <tfoot style="background-color:moccasin">
                                    {{-- @dd($sum_2) --}}
                                    <tr>
                                        <td>%</td>
                                        <td style="text-align: center">{{ $sum_2_2023 != 0 ? round($total_LESS_THAN_100_2023 / $sum_2_2023, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ $sum_2_2023 != 0 ? round($total_BETWEEN_100_AND_115_2023 / $sum_2_2023, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ $sum_2_2023 != 0 ? round($total_GREATHER_THAN_115_2023 / $sum_2_2023, 2 ) * 100 : 0 }}</td>
                                        <td style="text-align: center">{{ number_format($grand_total_ritasi_2023, 0, ',', '.') }}</td>
                                        <td style="text-align: center"></td>
                                        <td style="text-align: center"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</section>

@include('layout.footer')
<script>
    setTimeout(function () {
        location.reload();
    }, 300000); // 300000 ms = 5 menit

</script>
<script>
    // range picker
    (function () {
        const datepicker_range = new DateRangePicker(document.querySelector('#pc-datepicker-5'), {
            buttonClass: 'btn'
        });
    })();

</script>
<script>
    // [ HTML5 Export Buttons ]
    $('#basic-btn').DataTable({
        dom: 'Bfrtip',
        buttons: ['excel', 'print']
    });

    // [ Column Selectors ]
    $('#cbtn-selectors').DataTable({
        dom: 'Bfrtip',
        buttons: [{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0, ':visible'],

                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'portrait', // Set orientation menjadi landscape
                pageSize: 'A4', // Ukuran halaman (opsional, default A4)
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, column, node) {
                            // Menghapus entitas HTML seperti &gt;
                            return data
                                .replace(/&lt;/g, '<')   // Mengganti &lt; dengan <
                                .replace(/&gt;/g, '>')   // Mengganti &gt; dengan >
                                .replace(/&amp;/g, '&');
                        }
                    }

                },
                customize: function (doc) {
                    // Menyesuaikan margin atau pengaturan tambahan
                    doc.content[1].margin = [10, 10, 10, 10]; // Atur margin [kiri, atas, kanan, bawah]
                }
            },
            'colvis'
        ]
    });

    $('#cbtn-selectors-khusus').DataTable({
        dom: 'Bfrtip',
        pageLength:15,
        buttons: [{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0, ':visible'],

                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'portrait', // Set orientation menjadi landscape
                pageSize: 'A4', // Ukuran halaman (opsional, default A4)
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, column, node) {
                            // Menghapus entitas HTML seperti &gt;
                            return data
                                .replace(/&lt;/g, '<')   // Mengganti &lt; dengan <
                                .replace(/&gt;/g, '>')   // Mengganti &gt; dengan >
                                .replace(/&amp;/g, '&');
                        }
                    }

                },
                customize: function (doc) {
                    // Menyesuaikan margin atau pengaturan tambahan
                    doc.content[1].margin = [10, 10, 10, 10]; // Atur margin [kiri, atas, kanan, bawah]
                }
            },
            'colvis'
        ]
    });
    $('#cbtn-selectors-2023').DataTable({
        dom: 'Bfrtip',
        pageLength:15,
        buttons: [{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [0, ':visible'],

                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                orientation: 'portrait', // Set orientation menjadi landscape
                pageSize: 'A4', // Ukuran halaman (opsional, default A4)
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5],
                    format: {
                        body: function(data, row, column, node) {
                            // Menghapus entitas HTML seperti &gt;
                            return data
                                .replace(/&lt;/g, '<')   // Mengganti &lt; dengan <
                                .replace(/&gt;/g, '>')   // Mengganti &gt; dengan >
                                .replace(/&amp;/g, '&');
                        }
                    }

                },
                customize: function (doc) {
                    // Menyesuaikan margin atau pengaturan tambahan
                    doc.content[1].margin = [10, 10, 10, 10]; // Atur margin [kiri, atas, kanan, bawah]
                }
            },
            'colvis'
        ]
    });

    // [ Excel - Cell Background ]
    $('#excel-bg').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excelHtml5',
            customize: function (xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="F"]', sheet).each(function () {
                    if ($('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000) {
                        $(this).attr('s', '20');
                    }
                });
            }
        }]
    });

    // [ Custom File (JSON) ]
    $('#pdf-json').DataTable({
        dom: 'Bfrtip',
        buttons: [{
            text: 'JSON',
            action: function (e, dt, button, config) {
                var data = dt.buttons.exportData();
                $.fn.dataTable.fileSave(new Blob([JSON.stringify(data)]), 'Export.json');
            }
        }]
    });

</script>

