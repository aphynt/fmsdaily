@include('layout.head', ['title' => 'SOP Produksi'])
@include('layout.sidebar')
@include('layout.header')

<div class="pc-container">
    <div class="pc-content">
        <div class="col-xl-12 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="shadow-5-strong p-2">
                                <!-- Toolbar -->
                                <div class="mb-2">
                                    <button id="prev" class="btn btn-sm btn-primary">Prev</button>
                                    <button id="next" class="btn btn-sm btn-primary">Next</button>
                                    <span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
                                    <button id="zoomIn" class="btn btn-sm btn-success">+</button>
                                    <button id="zoomOut" class="btn btn-sm btn-danger">-</button>
                                </div>

                                <!-- PDF Viewer -->
                                <div id="pdfContainer" style="width: 100%; height: 800px; overflow: auto; border:1px solid #ccc; text-align:center;">
                                    <canvas id="pdfCanvas"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layout.footer')

<!-- PDF.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js"></script>

<script>
    const url = "{{ url('sop/perawatan_dan_penimbunan_jalan.pdf') }}";

    let pdfDoc = null,
        pageNum = 1,
        scale = 1.2,
        canvas = document.getElementById('pdfCanvas'),
        ctx = canvas.getContext('2d');

    pdfjsLib.GlobalWorkerOptions.workerSrc =
        "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.14.305/pdf.worker.min.js";

    // Render page
    function renderPage(num) {
        pdfDoc.getPage(num).then(function(page) {
            let viewport = page.getViewport({ scale: scale });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            let renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            page.render(renderContext);
        });

        document.getElementById('page_num').textContent = num;
    }

    // Load document
    pdfjsLib.getDocument(url).promise.then(function(pdf) {
        pdfDoc = pdf;
        document.getElementById('page_count').textContent = pdf.numPages;
        renderPage(pageNum);
    });

    // Events
    document.getElementById('prev').addEventListener('click', () => {
        if (pageNum <= 1) return;
        pageNum--;
        renderPage(pageNum);
    });

    document.getElementById('next').addEventListener('click', () => {
        if (pageNum >= pdfDoc.numPages) return;
        pageNum++;
        renderPage(pageNum);
    });

    document.getElementById('zoomIn').addEventListener('click', () => {
        scale += 0.2;
        renderPage(pageNum);
    });

    document.getElementById('zoomOut').addEventListener('click', () => {
        if (scale <= 0.4) return;
        scale -= 0.2;
        renderPage(pageNum);
    });
</script>
