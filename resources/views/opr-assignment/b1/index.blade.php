<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operator Assignment B1</title>
    <link rel="shortcut icon" href="{{ asset('oprAssignment') }}/icon/sims.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('oprAssignment') }}/css/bootstrap.min.css">

</head>

@php
    use Illuminate\Support\Str;
@endphp

<style>
    p{
        font-size:14px;
    }
    p.anymore{
        font-size:14px;
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

    .badge{
        --bs-badge-font-weight: 500;
    }
</style>


<body>
    <div id="spinner" class="spinner"></div>
    <section class="bg-light py-1 py-xl-8">
        <div class="container">
            <!-- Badge Section -->
            <hr>
            <div>
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


            <!-- Divider -->
            <hr class="mt-2 mb-0" style="height: 1px; border: none; background-color: #ddd;">

            <!-- Centered Content Section -->
            <div class="row justify-content-center">
                <div class="col-lg-8 col-xl-7 text-center">
                    <!-- Title -->
                    <h2 class="text-white py-2" style="background-color: #001831;">SM-B1</h2>
                </div>
            </div>
        </div>


        <div class="container overflow-hidden">
            <div class="row">
                @foreach($data as $loaderId => $assignments)
                    <!-- Kolom Loader ID -->
                    <div class="col">
                        <div class="card border-0">
                            @if ($assignments['0']->IS_SETTING_LOADR == 0) <img src="{{ asset('oprAssignment/icon/belum-disetting.png') }}" width="15px"> @endif
                            @if ($assignments['0']->IS_LOGIN_LOADER == 0) <img src="{{ asset('oprAssignment/icon/belum-login.png') }}" width="15px"> @endif
                            @if ($assignments['0']->IS_LOGIN_LOADER > 0 && $assignments['0']->IS_SETTING_LOADR > 0) <img src="{{ asset('oprAssignment/icon/setting-sesuai.png') }}" width="15px"> @endif
                            @if ($assignments['0']->IS_LOGIN_LOADER > 0 && $assignments['0']->IS_SETTING_LOADR == 0) <img src="{{ asset('oprAssignment/icon/setting-berbeda.png') }}" width="15px"> @endif
                            <div class="text-center text-white"
                                    @if ($assignments['0']->NIK_FINGER_LOADER_ORI == null) style="background-color:#6495ed;" @endif
                                    @if ($assignments['0']->NAMA_FGR_LOADER != null) style="background-color:#0000ff;" @endif
                                    @if ($assignments['0']->NAMA_FGR_LOADER == null) style="background-color:#6495ed;" @endif
                                        data-bs-toggle="tooltip"
                                        data-bs-placement="top"
                                        data-bs-html="true"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="Status: {{ $assignments['0']->STATUSDESCLOADER }}">
                                    <h5 class="mb-0 text-white">{{ $loaderId }}</h5>
                                    <p class="mb-0">{{ Str::limit($assignments['0']->NAMA_FGR_LOADER, 13) ? Str::limit($assignments['0']->NAMA_FGR_LOADER, 13) : '______'  }}</p>
                                    <p class="mb-0 anymore">{{ Str::limit($assignments['0']->NIK_FGR_LOADER, 13) ? Str::limit($assignments['0']->NIK_FGR_LOADER, 13) : '______'  }}</p>
                            </div>
                        </div>
                        <!-- Data Assignments -->
                        <div class="mt-2">
                            @foreach($assignments as $assignment)
                            <div class="card mb-3 border-0 shadow-sm text-white"
                            @if ($assignment->NIK_FGR_ORI == null) style="background-color:#afeeee;" @endif
                            @if ($assignment->NAMA_FGR != null) style="background-color:#00ff00;" @endif
                            @if ($assignment->NAMA_FGR == null)style="background-color:#00ff00;" @endif
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            data-bs-html="true"
                            data-bs-custom-class="custom-tooltip"
                            data-bs-title="Assignment: {{ date('d-m-Y H:i', strtotime($assignments['0']->ASG_TIMESTAMP)) }}
                            <br>Material: {{ $assignments['0']->ASG_MAT_ID }}
                            <br>Status: {{ $assignments['0']->STATUSDESCTRUCK }}">
                            <div class="text-center">
                                @if ($assignment->IS_SETTING == 0) <img src="{{ asset('oprAssignment/icon/belum-disetting.png') }}" width="15px"> @endif
                                @if ($assignment->IS_LOGIN == 0) <img src="{{ asset('oprAssignment/icon/belum-login.png') }}" width="15px"> @endif
                                @if ($assignment->IS_LOGIN > 0 && $assignment->IS_SETTING > 0) <img src="{{ asset('oprAssignment/icon/setting-sesuai.png') }}" width="15px"> @endif
                                @if ($assignment->IS_LOGIN > 0 && $assignment->IS_SETTING == 0) <img src="{{ asset('oprAssignment/icon/setting-berbeda.png') }}" width="15px"> @endif
                                <p class="fw-bold text-black mb-1">{{ $assignment->VHC_ID }}</p>
                                <p class="mb-0 text-black">{{ Str::limit($assignment->NAMA_FGR, 13) ? Str::limit($assignment->NAMA_FGR, 13) : '______' }}</p>
                                <p class="mb-0 anymore text-black">{{ $assignment->NIK_FGR ? $assignment->NIK_FGR : '_____' }}</p>
                            </div>
                        </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </section>
</body>
<script src="{{ asset('oprAssignment') }}/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi semua elemen dengan atribut data-bs-toggle="tooltip"
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<script>
    setInterval(function() {
        location.reload();
    }, 10000); //10 detik
</script>
</html>
