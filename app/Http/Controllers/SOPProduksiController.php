<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SOPProduksiController extends Controller
{
    //
    public function perawatanPenimbunanJalan(Request $request)
    {
        $name = $request->query('name', 'perawatan_dan_penimbunan_jalan.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.perawatanPenimbunanJalan', compact('pdfUrl', 'name'));
    }

    public function coalGetting(Request $request)
    {
        $name = $request->query('name', 'coal_getting.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.coalGetting', compact('pdfUrl', 'name'));
    }

    public function penimbunanMaterialKolamLumpurBullDozer(Request $request)
    {
        $name = $request->query('name', 'penimbunan_material_lumpur_bulldozer.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.penimbunanMaterialKolamLumpurBullDozer', compact('pdfUrl', 'name'));
    }

    public function pemuatanPengangkutanLumpur(Request $request)
    {
        $name = $request->query('name', 'pemuatan_dan_pengangkutan_lumpur.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.pemuatanPengangkutanLumpur', compact('pdfUrl', 'name'));
    }

    public function kegiatanSlippery(Request $request)
    {
        $name = $request->query('name', 'kegiatan_slippery.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.kegiatanSlippery', compact('pdfUrl', 'name'));
    }

    public function pengoperasianEXDigger(Request $request)
    {
        $name = $request->query('name', 'pengoperasian_ex_digger.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.pengoperasianEXDigger', compact('pdfUrl', 'name'));
    }

    public function pengoperasianLampuTambang(Request $request)
    {
        $name = $request->query('name', 'pengoperasian_lampu_tambang.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.pengoperasianLampuTambang', compact('pdfUrl', 'name'));
    }

    public function landClearing(Request $request)
    {
        $name = $request->query('name', 'land_clearing.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.landClearing', compact('pdfUrl', 'name'));
    }

    public function pengecekanPerbaikanWeakpoint(Request $request)
    {
        $name = $request->query('name', 'pengecekan_perbaikan_weakpoint.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.pengecekanPerbaikanWeakpoint', compact('pdfUrl', 'name'));
    }

    public function topSoil(Request $request)
    {
        $name = $request->query('name', 'top_soil.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.topSoil', compact('pdfUrl', 'name'));
    }

    public function optimalisasiGantiShift(Request $request)
    {
        $name = $request->query('name', 'optimalisasi_ganti_shift.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.optimalisasiGantiShift', compact('pdfUrl', 'name'));
    }

    public function penangananUnitHDAmblas(Request $request)
    {
        $name = $request->query('name', 'penanganan_unit_hd_amblas.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.penangananUnitHDAmblas', compact('pdfUrl', 'name'));
    }

    public function piketJagaTambang(Request $request)
    {
        $name = $request->query('name', 'piket_jaga_tambang.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.piketJagaTambang', compact('pdfUrl', 'name'));
    }

    public function kegiatanHaulRoad(Request $request)
    {
        $name = $request->query('name', 'kegiatan_haul_road.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.kegiatanHaulRoad', compact('pdfUrl', 'name'));
    }

    public function kegiatanDropCut(Request $request)
    {
        $name = $request->query('name', 'kegiatan_drop_cut.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.kegiatanDropCut', compact('pdfUrl', 'name'));
    }

    public function pengelolaanWasteDump(Request $request)
    {
        $name = $request->query('name', 'pengelolaan_waste_dump.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.pengelolaanWasteDump', compact('pdfUrl', 'name'));
    }

    public function dumpingAreaWasteDump(Request $request)
    {
        $name = $request->query('name', 'dumping_area_waste_dump.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.dumpingAreaWasteDump', compact('pdfUrl', 'name'));
    }

    public function perbaikanTanggulJalan(Request $request)
    {
        $name = $request->query('name', 'perbaikan_tanggul_jalan.pdf');
        $pdfUrl = route('files.show', ['name' => $name]);

        return view('sop.produksi.perbaikanTanggulJalan', compact('pdfUrl', 'name'));
    }
}
