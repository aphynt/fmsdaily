@include('layout.head', ['title' => 'SOP Top Soil'])
@include('layout.sidebar')
@include('layout.header')

<div class="pc-container">
    <div class="pc-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-12">
                        <ul class="breadcrumb">
                        {{-- <li class="breadcrumb-item"><a href="javascript: void(0)">Home</a></li> --}}
                        <li class="breadcrumb-item"><a href="javascript: void(0)">SOP Top Soil</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                        <a href="{{ asset('sop/top_soil.pdf') }}" class="btn btn-primary w-100" style="padding-top:10px;padding-bottom:10px;">Download</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="shadow-5-strong p-2">
                                <div class="embed-responsive embed-responsive-16by9 leb">
                                    <iframe id="myIframe" src="" width="100%" frameborder="0" height="800px" style="border:none;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function reloadAndLoadIframe() {
        // Memeriksa apakah iframe sudah dimuat
        if (!sessionStorage.getItem('iframeLoaded')) {
            // Jika belum, reload halaman
            sessionStorage.setItem('iframeLoaded', 'true');
            location.reload();
        } else {
            // Jika sudah, muat iframe
            document.getElementById('myIframe').src = 'https://docs.google.com/gview?url={{ asset('sop/top_soil.pdf') }}&embedded=true';
        }
    }

    window.onload = reloadAndLoadIframe;
</script>

@include('layout.footer')
