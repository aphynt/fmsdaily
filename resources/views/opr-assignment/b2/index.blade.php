<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Assignment SM-B2</title>
    <link rel="shortcut icon" href="{{ asset('oprAssignment') }}/icon/sims.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('oprAssignment') }}/css/bootstrap.min.css">
</head>

@php
    use Illuminate\Support\Str;
@endphp

<style>
    body{
        background-image: url('/oprAssignment/background.jpg');
        background-repeat: no-repeat;
        background-position: center;
    }
    p{
        font-size:11px;
        margin-top:-3px;
        margin-bottom:-3px;
    }
    p.anymore{
        font-size:11px;
    }
    .custom-tooltip {
    font-size: 11px;
    }
    #spinner {
        display: none;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #content {
        display: none;
    }
    .card {
    position: relative;
    }

    .card img {
        position: absolute;
        top: 0;
        left: 0;
        margin: 5px;
    }
    span{
        font-size: 9px;
    }
</style>

<body>
    <!-- Spinner -->
    <div id="spinner" class="spinner" style="display:none;">Loading...</div>

    <section class="py-1 py-xl-8">
        <div class="container">
            <!-- Badge Section -->
            <div style="background-color: #F9ECD9;border-radius: 8px;">
                <span class="badge" style="color: black; font-size:8pt; padding-left: 4px; padding-right: 4px;">
                    <img src="{{ asset('oprAssignment/icon/belum-disetting.png') }}" width="15px"> Belum Disetting
                </span>
                <span class="badge" style="color: black; font-size:8pt; padding-left: 4px; padding-right: 4px;">
                    <img src="{{ asset('oprAssignment/icon/belum-login.png') }}" width="15px"> Belum Login
                </span>
                <span class="badge" style="color: black; font-size:8pt; padding-left: 4px; padding-right: 4px;">
                    <img src="{{ asset('oprAssignment/icon/setting-berbeda.png') }}" width="15px"> Login & Setting Berbeda
                </span>
                <span class="badge" style="color: black; font-size:8pt; padding-left: 4px; padding-right: 4px;">
                    <img src="{{ asset('oprAssignment/icon/setting-sesuai.png') }}" width="15px"> Login & Setting Sesuai
                </span>
                <span class="badge" style="background-color: #0000ff; font-size:8pt; padding-left: 4px; padding-right: 4px;">
                    EX-Sudah Finger
                </span>
                <span class="badge" style="background-color: #00ff00; font-size:8pt; color:black; padding-left: 4px; padding-right: 4px;">
                    HD-Sudah Finger
                </span>
            </div>

            <hr class="mt-2 mb-0" style="height: 1px; border: none; background-color: #ddd;">

            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7 text-center">
                    <h2 class="text-black py-2" style="background-color: #FFFAE6;border-radius: 8px;">SM-B2</h2>
                </div>
            </div>
        </div>

        <div class="container overflow-hidden">
            <div id="assignmentsContainer" class="row">
                <!-- Data assignments akan dimuat di sini melalui AJAX -->
            </div>
        </div>
    </section>

    <script src="{{ asset('oprAssignment') }}/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('dashboard/assets') }}/cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        let lastData = null;

        function loadAssignments() {
            $.ajax({
                url: '{{ route('opr.b2.api') }}',
                method: 'GET',
                beforeSend: function() {
                    // $('#spinner').show();
                    $('#content').hide();
                },
                success: function(response) {
                    $('#spinner').hide();

                    if(response.html) {
                        lastData = response.html;
                        $('#assignmentsContainer').html(response.html);
                    } else {
                        // Jika data kosong, gunakan data terakhir
                        if (lastData) {
                            $('#assignmentsContainer').html(lastData);
                        } else {
                            $('#assignmentsContainer').html('<p class="text-center text-warning">Tidak ada data untuk ditampilkan.</p>');
                        }
                    }
                    $('#content').show();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $('#spinner').hide();

                    if (lastData) {
                        $('#assignmentsContainer').html(lastData);
                    } else {
                        $('#assignmentsContainer').html('<p class="text-center text-warning">Tidak ada data untuk ditampilkan.</p>');
                    }
                    $('#content').show();

                    console.error('Error: ' + textStatus + ', ' + errorThrown);  // Log error ke konsol untuk debugging
                },
                complete: function() {
                    setTimeout(loadAssignments, 10000);
                }
            });
        }

        $(document).ready(function() {
            loadAssignments();
        });
    </script>
</body>

</html>
