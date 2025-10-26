@include('layout.head', ['title' => 'SOP Pengoperasioan Excavator Big Digger'])
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
                        <li class="breadcrumb-item"><a href="javascript: void(0)">SOP Pengoperasioan Excavator Big Digger</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                        <a href="{{ asset('sop/'. $name) }}" target="_blank" class="btn btn-primary w-100" style="padding-top:10px;padding-bottom:10px;">Download</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div style="height:90vh">
                        <iframe id="pdfViewer" width="100%" height="100%" style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
    const base = "{{ url('/pdfjs/web/viewer.html') }}?file={{ urlencode($pdfUrl) }}";

    function setSrc() {
        const isMobile = window.matchMedia('(max-width: 768px)').matches ||
                        /android|iphone|ipad|ipod/i.test(navigator.userAgent);
        const zoom = isMobile ? 'page-width' : 'page-fit';
        document.getElementById('pdfViewer').src = base + '#zoom=' + zoom + '&page=1';
    }

    setSrc();

    let t;
    window.addEventListener('resize', () => { clearTimeout(t); t = setTimeout(setSrc, 200); });
    })();
</script>
@include('layout.footer')
