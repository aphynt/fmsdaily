<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SOPProduksiController extends Controller
{
    //
    public function perawatanPenimbunanJalan()
    {
        return view('sop.produksi.perawatanPenimbunanJalan');
    }
    public function coalGetting()
    {
        return view('sop.produksi.coalGetting');
    }

    public function penimbunanMaterialKolamLumpurBullDozer()
    {
        return view('sop.produksi.penimbunanMaterialKolamLumpurBullDozer');
    }

    public function pemuatanPengangkutanLumpur()
    {
        return view('sop.produksi.pemuatanPengangkutanLumpur');
    }

    public function kegiatanSlippery()
    {
        return view('sop.produksi.kegiatanSlippery');
    }

    public function pengoperasianEXDigger()
    {
        return view('sop.produksi.pengoperasianEXDigger');
    }

    public function pengoperasianLampuTambang()
    {
        return view('sop.produksi.pengoperasianLampuTambang');
    }

    public function landClearing()
    {
        return view('sop.produksi.landClearing');
    }

    public function pengecekanPerbaikanWeakpoint()
    {
        return view('sop.produksi.pengecekanPerbaikanWeakpoint');
    }

    public function topSoil()
    {
        return view('sop.produksi.topSoil');
    }
}
