@include('layout.head', ['title' => 'Staging Plan'])
@include('layout.sidebar')
@include('layout.header')

<div class="pc-container">
    <div class="pc-content">
       <div class="page-header">
    <div class="page-block">
        <div class="row align-items-center gy-3">

            {{-- Action Buttons --}}
            <div class="col-md-4">
                <div class="d-flex gap-2">

                    <a href="{{ url()->previous() }}"
                       class="btn btn-light border d-flex align-items-center gap-2 px-3">
                        <i class="bi bi-arrow-left"></i>
                        <span>Kembali</span>
                    </a>

                    <a href="{{ $pdfUrl }}"
                       target="_blank"
                       class="btn btn-primary d-flex align-items-center gap-2 px-3">
                        <i class="bi bi-download"></i>
                        <span>Download</span>
                    </a>

                </div>
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
