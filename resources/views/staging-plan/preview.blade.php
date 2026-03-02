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

                    <a href="{{ $fileUrl }}"
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

    @if ($data->pit != null)
        <h4>PIT: {{ $data->pit }}</h4>
    @endif
        <div class="col-xl-12 col-md-6">
            <div class="card">
                <div style="height:90vh">

                    {{-- Jika Gambar --}}
                    @if(Str::startsWith($contentType, 'image/'))

                        <div class="text-center p-3">
                            <img src="{{ $fileUrl }}"
                                class="img-fluid rounded shadow"
                                style="max-height:85vh">
                        </div>

                    {{-- Jika PDF --}}
                    @elseif($contentType === 'application/pdf')

                        <iframe
                            id="pdfViewer"
                            width="100%"
                            height="100%"
                            style="border:0"
                            allowfullscreen>
                        </iframe>

                    {{-- Fallback --}}
                    @else

                        <iframe
                            src="{{ $fileUrl }}"
                            width="100%"
                            height="100%"
                            style="border:0"
                            allowfullscreen>
                        </iframe>

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

@if($contentType === 'application/pdf')
<script>
(function () {
    const base = "{{ url('/pdfjs/web/viewer.html') }}?file={{ urlencode($fileUrl) }}";

    function setSrc() {
        const isMobile = window.matchMedia('(max-width: 768px)').matches ||
                        /android|iphone|ipad|ipod/i.test(navigator.userAgent);
        const zoom = isMobile ? 'page-width' : 'page-fit';
        document.getElementById('pdfViewer').src =
            base + '#zoom=' + zoom + '&page=1';
    }

    setSrc();

    let t;
    window.addEventListener('resize', () => {
        clearTimeout(t);
        t = setTimeout(setSrc, 200);
    });
})();
</script>
@endif

@include('layout.footer')
