@include('layout.head', ['title' => 'SOP Produksi'])
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
                        <li class="breadcrumb-item"><a href="javascript: void(0)">SOP Produksi Perawatan dan Penimbunan Jalan</a></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-2 mb-2 d-flex align-items-end">
                        <a href="{{ asset('sop/perawatan_dan_penimbunan_jalan.pdf') }}" class="btn btn-primary w-100" style="padding-top:10px;padding-bottom:10px;">Download</a>
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
                    <iframe src="https://docs.google.com/gview?url={{ asset('sop/perawatan_dan_penimbunan_jalan.pdf') }}&embedded=true" width="100%" frameborder="0" height="800px" style="border:none;"></iframe>
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
