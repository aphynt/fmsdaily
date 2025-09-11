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

    public function penimbunanMaterialKolamLumpurBullDozer()
    {
        return view('sop.produksi.penimbunanMaterialKolamLumpurBullDozer');
    }

    public function pemuatanPengangkutanLumpur()
    {
        return view('sop.produksi.pemuatanPengangkutanLumpur');
    }
}
