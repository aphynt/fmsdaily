<?php

use App\Http\Controllers\AlatSupportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BBCatatanPengawasController;
use App\Http\Controllers\BBLoadingPointController;
use App\Http\Controllers\BBUnitSupportController;
use App\Http\Controllers\CatatanPengawasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormPengawasBatuBaraController;
use App\Http\Controllers\FormPengawasController;
use App\Http\Controllers\FormPengawasNewController;
use App\Http\Controllers\FormPengawasOldController;
use App\Http\Controllers\FormPengawasSAPController;
use App\Http\Controllers\FrontLoadingController;
use App\Http\Controllers\KLKHBatuBaraController;
use App\Http\Controllers\KLKHDisposalController;
use App\Http\Controllers\KLKHHaulRoadController;
use App\Http\Controllers\KLKHLoadingPointController;
use App\Http\Controllers\KLKHLumpurController;
use App\Http\Controllers\KLKHOGSController;
use App\Http\Controllers\KLKHSimpangEmpatController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MonitoringLaporanKerjaKLKHController;
use App\Http\Controllers\MonitoringPayloadController;
use App\Http\Controllers\OprAssigntmentController;
use App\Http\Controllers\PayloadRitationController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RosterKerjaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifikasiKLKHBatubaraController;
use App\Http\Controllers\VerifikasiKLKHController;
use App\Http\Controllers\VerifikasiKLKHDisposalController;
use App\Http\Controllers\VerifikasiKLKHHaulRoadController;
use App\Http\Controllers\VerifikasiKLKHLoadingPointController;
use App\Http\Controllers\VerifikasiKLKHLumpurController;
use App\Http\Controllers\VerifikasiKLKHOGSController;
use App\Http\Controllers\VerifikasiKLKHSimpangEmpatController;
use App\Http\Controllers\VerifikasiLaporanKerja;
use App\Http\Controllers\VerifikasiLaporanKerjaController;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\isAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login/post', [AuthController::class, 'login_post'])->name('login.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

//Payload & Ritation API
Route::get('/payloadritation/api', [PayloadRitationController::class, 'api'])->name('payloadritation.api');

//Operator Assignment
Route::get('/OprAssignment/B1', [OprAssigntmentController::class, 'b1'])->name('opr.b1');
Route::get('/OprAssignment/B1/api', [OprAssigntmentController::class, 'b1_api'])->name('opr.b1.api');
Route::get('/OprAssignment/B2', [OprAssigntmentController::class, 'b2'])->name('opr.b2');
Route::get('/OprAssignment/B2/api', [OprAssigntmentController::class, 'b2_api'])->name('opr.b2.api');
Route::get('/OprAssignment/A3', [OprAssigntmentController::class, 'a3'])->name('opr.a3');
Route::get('/OprAssignment/A3/api', [OprAssigntmentController::class, 'a3_api'])->name('opr.a3.api');


Route::group(['middleware' => ['auth']], function(){
    //dashboard
    Route::get('/dashboards/index', [DashboardController::class, 'index'])->name('dashboard.index');


    Route::get('/operator/{nik}', [FormPengawasController::class, 'getOperatorByNIK']);

    Route::get('/production/index', [ProductionController::class, 'index'])->name('production.index');

    //Form Pengawas Lama
    // Route::get('/form-pengawas-old/show', [FormPengawasOldController::class, 'show'])->name('form-pengawas-old.show');
    // Route::get('/form-pengawas-old/index', [FormPengawasOldController::class, 'index'])->name('form-pengawas-old.index')->middleware('checkRole'.':FOREMAN,SUPERVISOR');
    // Route::get('/form-pengawas-old/download/{uuid}', [FormPengawasOldController::class, 'download'])->name('form-pengawas-old.download');
    // Route::get('/form-pengawas-old/bundlepdf', [FormPengawasOldController::class, 'bundlepdf'])->name('form-pengawas-old.bundlepdf');
    // Route::get('/form-pengawas-old/download/pdf/{uuid}', [FormPengawasOldController::class, 'pdf'])->name('form-pengawas-old.pdf');
    // Route::get('/form-pengawas-old/preview/{uuid}', [FormPengawasOldController::class, 'preview'])->name('form-pengawas-old.preview');
    // Route::get('/form-pengawas-old/delete/{uuid}', [FormPengawasOldController::class, 'delete'])->name('form-pengawas-old.delete');
    // Route::post('/form-pengawas-old/post', [FormPengawasOldController::class, 'post'])->name('form-pengawas-old.post');
    // Route::post('/form-pengawas-old/auto-save', [FormPengawasOldController::class, 'autoSave'])->name('form-pengawas-old.auto-save');

    //Verifikasi Form Pengawas
    // Route::get('/form-pengawas/verified/all/{uuid}', [FormPengawasController::class, 'verifiedAll'])->name('form-pengawas.verified.all');
    // Route::get('/form-pengawas/verified/foreman/{uuid}', [FormPengawasController::class, 'verifiedForeman'])->name('form-pengawas.verified.foreman');
    // Route::get('/form-pengawas/verified/supervisor/{uuid}', [FormPengawasController::class, 'verifiedSupervisor'])->name('form-pengawas.verified.supervisor');
    // Route::get('/form-pengawas/verified/superintendent/{uuid}', [FormPengawasController::class, 'verifiedSuperintendent'])->name('form-pengawas.verified.superintendent');

    //Form Pengawas
    //Route::get('/form-pengawas/search-users', [FormPengawasController::class, 'users'])->name('cariUsers');
    // Route::get('/form-pengawas/show', [FormPengawasController::class, 'show'])->name('form-pengawas.show');
    // Route::get('/form-pengawas/index', [FormPengawasController::class, 'index'])->name('form-pengawas.index');

    // Route::get('/form-pengawas/download/{uuid}', [FormPengawasController::class, 'download'])->name('form-pengawas.download');
    // Route::get('/form-pengawas/preview/{uuid}', [FormPengawasController::class, 'preview'])->name('form-pengawas.preview');
    // Route::post('/form-pengawas/post', [FormPengawasController::class, 'post'])->name('form-pengawas.post');
    // Route::post('/form-pengawas/auto-save', [FormPengawasController::class, 'autoSave'])->name('form-pengawas.auto-save');

    Route::get('/form-pengawas/index', function () {
        return redirect()->route('form-pengawas-new.index');
    });

    //Form Pengawas Baru
    Route::get('/form-pengawas-new/search-users', [FormPengawasNewController::class, 'users'])->name('cariUsers');
    Route::get('/form-pengawas-new/show', [FormPengawasNewController::class, 'show'])->name('form-pengawas-new.show');
    Route::get('/form-pengawas-new/index', [FormPengawasNewController::class, 'index'])->name('form-pengawas-new.index');
    Route::get('/form-pengawas-new/preview/{uuid}', [FormPengawasNewController::class, 'preview'])->name('form-pengawas-new.preview');
    Route::post('/save-draft', [FormPengawasNewController::class, 'saveAsDraft'])->name('daily-report.saveAsDraft');
    Route::get('/form-pengawas-new/get-draft/{uuid}', [FormPengawasNewController::class, 'getDraft'])->name('get-draft');
    Route::get('/form-pengawas-new/post', [FormPengawasNewController::class, 'post'])->name('form-pengawas-new.post');
    Route::get('/form-pengawas-new/download/pdf/{uuid}', [FormPengawasNewController::class, 'pdf'])->name('form-pengawas-new.pdf');
    Route::get('/form-pengawas-new/download/{uuid}', [FormPengawasNewController::class, 'download'])->name('form-pengawas-new.download');
    Route::get('/form-pengawas-new/verified/all/{uuid}', [FormPengawasNewController::class, 'verifiedAll'])->name('form-pengawas-new.verified.all');
    Route::get('/form-pengawas-new/verified/foreman/{uuid}', [FormPengawasNewController::class, 'verifiedForeman'])->name('form-pengawas-new.verified.foreman');
    Route::get('/form-pengawas-new/verified/supervisor/{uuid}', [FormPengawasNewController::class, 'verifiedSupervisor'])->name('form-pengawas-new.verified.supervisor');
    Route::get('/form-pengawas-new/verified/superintendent/{uuid}', [FormPengawasNewController::class, 'verifiedSuperintendent'])->name('form-pengawas-new.verified.superintendent');
    Route::get('/form-pengawas-new/delete/{uuid}', [FormPengawasNewController::class, 'delete'])->name('form-pengawas-new.delete');
    Route::get('/form-pengawas-new/bundlepdf', [FormPengawasNewController::class, 'bundlepdf'])->name('form-pengawas-new.bundlepdf');

    //Form Pengawas Batu Bara
    Route::get('/form-pengawas-batubara/show', [FormPengawasBatuBaraController::class, 'show'])->name('form-pengawas-batubara.show');
    Route::get('/form-pengawas-batubara/index', [FormPengawasBatuBaraController::class, 'index'])->name('form-pengawas-batubara.index');
    Route::post('/form-pengawas-batubara/post', [FormPengawasBatuBaraController::class, 'post'])->name('form-pengawas-batubara.post');
    Route::post('/save-draft-form-pengawas-batubara', [FormPengawasBatuBaraController::class, 'saveAsDraft'])->name('form-pengawas-batubara.saveAsDraft');
    Route::get('/form-pengawas-batubara/preview/{uuid}', [FormPengawasBatuBaraController::class, 'preview'])->name('form-pengawas-batubara.preview');
    Route::get('/form-pengawas-batubara/delete/{uuid}', [FormPengawasBatuBaraController::class, 'delete'])->name('form-pengawas-batubara.delete');
    Route::get('/form-pengawas-batubara/verified/all/{uuid}', [FormPengawasBatuBaraController::class, 'verifiedAll'])->name('form-pengawas-batubara.verified.all');
    Route::get('/form-pengawas-batubara/verified/foreman/{uuid}', [FormPengawasBatuBaraController::class, 'verifiedForeman'])->name('form-pengawas-batubara.verified.foreman');
    Route::get('/form-pengawas-batubara/verified/supervisor/{uuid}', [FormPengawasBatuBaraController::class, 'verifiedSupervisor'])->name('form-pengawas-batubara.verified.supervisor');
    Route::get('/form-pengawas-batubara/verified/superintendent/{uuid}', [FormPengawasBatuBaraController::class, 'verifiedSuperintendent'])->name('form-pengawas-batubara.verified.superintendent');
    Route::get('/form-pengawas-batubara/download/pdf/{uuid}', [FormPengawasBatuBaraController::class, 'pdf'])->name('form-pengawas-batubara.pdf');
    Route::get('/form-pengawas-batubara/download/{uuid}', [FormPengawasBatuBaraController::class, 'download'])->name('form-pengawas-batubara.download');
    Route::get('/form-pengawas-batubara/bundlepdf', [FormPengawasBatuBaraController::class, 'bundlepdf'])->name('form-pengawas-batubara.bundlepdf');

    //Form Pengawas SAP
    Route::get('/form-pengawas-sap/index', [FormPengawasSAPController::class, 'index'])->name('form-pengawas-sap.index');
    Route::post('/form-pengawas-sap/post', [FormPengawasSAPController::class, 'post'])->name('form-pengawas-sap.post');
    Route::get('/form-pengawas-sap/show', [FormPengawasSAPController::class, 'show'])->name('form-pengawas-sap.show');
    Route::get('/form-pengawas-sap/delete/{uuid}', [FormPengawasSAPController::class, 'delete'])->name('form-pengawas-sap.delete');
    Route::get('/form-pengawas-sap/rincian/{uuid}', [FormPengawasSAPController::class, 'rincian'])->name('form-pengawas-sap.rincian');
    Route::post('/form-pengawas-sap/update/{uuid}', [FormPengawasSAPController::class, 'update'])->name('form-pengawas-sap.update');

    //Front Loading
    Route::get('/front-loading/index', [FrontLoadingController::class, 'index'])->name('front-loading.index');
    Route::get('/front-loading/export/excel', [FrontLoadingController::class, 'excel'])->name('front-loading.excel');
    Route::delete('/delete-front-loading/{uuid}', [FrontLoadingController::class, 'destroy'])->name('front-loading.destroy');

    //BB Loading Point
    Route::get('/batu-bara/loading-point/index', [BBLoadingPointController::class, 'index'])->name('bb.loading-point.index');
    Route::get('/batu-bara/loading-point/export/excel', [BBLoadingPointController::class, 'excel'])->name('bb.loading-point.excel');
    Route::delete('/batu-bara/delete-loading-point/{uuid}', [BBLoadingPointController::class, 'destroy'])->name('bb.loading-point.destroy');

    //Alat Support
    Route::get('/alat-support/index', [AlatSupportController::class, 'index'])->name('alat-support.index');
    Route::get('/alat-support/api', [AlatSupportController::class, 'api'])->name('alat-support.api');
    Route::get('/alat-support/excel', [AlatSupportController::class, 'excel'])->name('alat-support.excel');
    Route::post('/alat-support/update/{uuid}', [AlatSupportController::class, 'update'])->name('alat-support.update');
    Route::delete('/alat-support/{id}', [AlatSupportController::class, 'destroy']);
    Route::delete('/delete-support/{id}', [AlatSupportController::class, 'destroy']);


    //BB Unit Support
    Route::get('/batu-bara/unit-support/index', [BBUnitSupportController::class, 'index'])->name('bb.unit-support.index');
    Route::get('/batu-bara/unit-support/api', [BBUnitSupportController::class, 'api'])->name('bb.unit-support.api');
    Route::get('/batu-bara/unit-support/excel', [BBUnitSupportController::class, 'excel'])->name('bb.unit-support.excel');
    Route::post('/batu-bara/unit-support/update/{uuid}', [BBUnitSupportController::class, 'update'])->name('bb.unit-support.update');
    Route::delete('/batu-bara/unit-support/{id}', [BBUnitSupportController::class, 'destroy']);
    Route::delete('/batu-bara/delete-support/{id}', [BBUnitSupportController::class, 'destroy']);

    //Catatan Pengawas
    Route::get('/catatan-pengawas/index', [CatatanPengawasController::class, 'index'])->name('catatan-pengawas.index');
    Route::delete('/delete/catatan-pengawas/{id}', [CatatanPengawasController::class, 'destroy'])->name('catatan-pengawas.destroy');

    //BB Catatan Pengawas
    Route::get('/batu-bara/catatan-pengawas/index', [BBCatatanPengawasController::class, 'index'])->name('bb.catatan-pengawas.index');
    Route::delete('/batu-bara/delete/catatan-pengawas/{id}', [BBCatatanPengawasController::class, 'destroy'])->name('bb.catatan-pengawas.destroy');

    //KLKH Loading Point
    Route::get('/klkh/loading-point', [KLKHLoadingPointController::class, 'index'])->name('klkh.loading-point');
    Route::get('/klkh/loading-point/insert', [KLKHLoadingPointController::class, 'insert'])->name('klkh.loading-point.insert')->middleware('checkRole'.':FOREMAN,SUPERVISOR');
    Route::post('/klkh/loading-point/post', [KLKHLoadingPointController::class, 'post'])->name('klkh.loading-point.post');
    Route::get('/klkh/loading-point/delete/{id}', [KLKHLoadingPointController::class, 'delete'])->name('klkh.loading-point.delete');
    Route::get('/klkh/loading-point/preview/{uuid}', [KLKHLoadingPointController::class, 'preview'])->name('klkh.loading-point.preview');
    Route::get('/klkh/loading-point/bundlepdf', [KLKHLoadingPointController::class, 'bundlepdf'])->name('klkh.loading-point.bundlepdf');
    Route::get('/klkh/loading-point/cetak/{uuid}', [KLKHLoadingPointController::class, 'cetak'])->name('klkh.loading-point.cetak');
    Route::get('/klkh/loading-point/download/{uuid}', [KLKHLoadingPointController::class, 'download'])->name('klkh.loading-point.download');
    Route::get('/klkh/loading-point/verified/all/{uuid}', [KLKHLoadingPointController::class, 'verifiedAll'])->name('klkh.loading-point.verified.all');
    Route::get('/klkh/loading-point/verified/foreman/{uuid}', [KLKHLoadingPointController::class, 'verifiedForeman'])->name('klkh.loading-point.verified.foreman');
    Route::get('/klkh/loading-point/verified/supervisor/{uuid}', [KLKHLoadingPointController::class, 'verifiedSupervisor'])->name('klkh.loading-point.verified.supervisor');
    Route::get('/klkh/loading-point/verified/superintendent/{uuid}', [KLKHLoadingPointController::class, 'verifiedSuperintendent'])->name('klkh.loading-point.verified.superintendent');

    //KLKH Haul Road
    Route::get('/klkh/haul-road', [KLKHHaulRoadController::class, 'index'])->name('klkh.haul-road');
    Route::get('/klkh/haul-road/insert', [KLKHHaulRoadController::class, 'insert'])->name('klkh.haul-road.insert')->middleware('checkRole'.':FOREMAN,SUPERVISOR');
    Route::post('/klkh/haul-road/post', [KLKHHaulRoadController::class, 'post'])->name('klkh.haul-road.post');
    Route::get('/klkh/haul-road/delete/{id}', [KLKHHaulRoadController::class, 'delete'])->name('klkh.haul-road.delete');
    Route::get('/klkh/haul-road/preview/{uuid}', [KLKHHaulRoadController::class, 'preview'])->name('klkh.haul-road.preview');
    Route::get('/klkh/haul-road/bundlepdf', [KLKHHaulRoadController::class, 'bundlepdf'])->name('klkh.haul-road.bundlepdf');
    Route::get('/klkh/haul-road/cetak/{uuid}', [KLKHHaulRoadController::class, 'cetak'])->name('klkh.haul-road.cetak');
    Route::get('/klkh/haul-road/download/{uuid}', [KLKHHaulRoadController::class, 'download'])->name('klkh.haul-road.download');
    Route::get('/klkh/haul-road/verified/all/{uuid}', [KLKHHaulRoadController::class, 'verifiedAll'])->name('klkh.haul-road.verified.all');
    Route::get('/klkh/haul-road/verified/foreman/{uuid}', [KLKHHaulRoadController::class, 'verifiedForeman'])->name('klkh.haul-road.verified.foreman');
    Route::get('/klkh/haul-road/verified/supervisor/{uuid}', [KLKHHaulRoadController::class, 'verifiedSupervisor'])->name('klkh.haul-road.verified.supervisor');
    Route::get('/klkh/haul-road/verified/superintendent/{uuid}', [KLKHHaulRoadController::class, 'verifiedSuperintendent'])->name('klkh.haul-road.verified.superintendent');

    //KLKH Disposal
    Route::get('/klkh/disposal', [KLKHDisposalController::class, 'index'])->name('klkh.disposal');
    Route::get('/klkh/disposal/insert', [KLKHDisposalController::class, 'insert'])->name('klkh.disposal.insert')->middleware('checkRole'.':FOREMAN,SUPERVISOR');
    Route::post('/klkh/disposal/post', [KLKHDisposalController::class, 'post'])->name('klkh.disposal.post');
    Route::get('/klkh/disposal/delete/{id}', [KLKHDisposalController::class, 'delete'])->name('klkh.disposal.delete');
    Route::get('/klkh/disposal/preview/{uuid}', [KLKHDisposalController::class, 'preview'])->name('klkh.disposal.preview');
    Route::get('/klkh/disposal/bundlepdf', [KLKHDisposalController::class, 'bundlepdf'])->name('klkh.disposal.bundlepdf');
    Route::get('/klkh/disposal/cetak/{uuid}', [KLKHDisposalController::class, 'cetak'])->name('klkh.disposal.cetak');
    Route::get('/klkh/disposal/download/{uuid}', [KLKHDisposalController::class, 'download'])->name('klkh.disposal.download');
    Route::get('/klkh/disposal/verified/all/{uuid}', [KLKHDisposalController::class, 'verifiedAll'])->name('klkh.disposal.verified.all');
    Route::get('/klkh/disposal/verified/foreman/{uuid}', [KLKHDisposalController::class, 'verifiedForeman'])->name('klkh.disposal.verified.foreman');
    Route::get('/klkh/disposal/verified/supervisor/{uuid}', [KLKHDisposalController::class, 'verifiedSupervisor'])->name('klkh.disposal.verified.supervisor');
    Route::get('/klkh/disposal/verified/superintendent/{uuid}', [KLKHDisposalController::class, 'verifiedSuperintendent'])->name('klkh.disposal.verified.superintendent');

    //KLKH Lumpur
    Route::get('/klkh/lumpur', [KLKHLumpurController::class, 'index'])->name('klkh.lumpur');
    Route::get('/klkh/lumpur/insert', [KLKHLumpurController::class, 'insert'])->name('klkh.lumpur.insert')->middleware('checkRole'.':FOREMAN,SUPERVISOR');
    Route::post('/klkh/lumpur/post', [KLKHLumpurController::class, 'post'])->name('klkh.lumpur.post');
    Route::get('/klkh/lumpur/delete/{id}', [KLKHLumpurController::class, 'delete'])->name('klkh.lumpur.delete');
    Route::get('/klkh/lumpur/preview/{uuid}', [KLKHLumpurController::class, 'preview'])->name('klkh.lumpur.preview');
    Route::get('/klkh/lumpur/bundlepdf', [KLKHLumpurController::class, 'bundlepdf'])->name('klkh.lumpur.bundlepdf');
    Route::get('/klkh/lumpur/cetak/{uuid}', [KLKHLumpurController::class, 'cetak'])->name('klkh.lumpur.cetak');
    Route::get('/klkh/lumpur/download/{uuid}', [KLKHLumpurController::class, 'download'])->name('klkh.lumpur.download');
    Route::get('/klkh/lumpur/verified/all/{uuid}', [KLKHLumpurController::class, 'verifiedAll'])->name('klkh.lumpur.verified.all');
    Route::get('/klkh/lumpur/verified/foreman/{uuid}', [KLKHLumpurController::class, 'verifiedForeman'])->name('klkh.lumpur.verified.foreman');
    Route::get('/klkh/lumpur/verified/supervisor/{uuid}', [KLKHLumpurController::class, 'verifiedSupervisor'])->name('klkh.lumpur.verified.supervisor');
    Route::get('/klkh/lumpur/verified/superintendent/{uuid}', [KLKHLumpurController::class, 'verifiedSuperintendent'])->name('klkh.lumpur.verified.superintendent');

    //KLKH OGS
    Route::get('/klkh/ogs', [KLKHOGSController::class, 'index'])->name('klkh.ogs');
    Route::get('/klkh/ogs/insert', [KLKHOGSController::class, 'insert'])->name('klkh.ogs.insert')->middleware('checkRole'.':FOREMAN,SUPERVISOR');
    Route::post('/klkh/ogs/post', [KLKHOGSController::class, 'post'])->name('klkh.ogs.post');
    Route::get('/klkh/ogs/delete/{id}', [KLKHOGSController::class, 'delete'])->name('klkh.ogs.delete');
    Route::get('/klkh/ogs/preview/{uuid}', [KLKHOGSController::class, 'preview'])->name('klkh.ogs.preview');
    Route::get('/klkh/ogs/bundlepdf', [KLKHOGSController::class, 'bundlepdf'])->name('klkh.ogs.bundlepdf');
    Route::get('/klkh/ogs/cetak/{uuid}', [KLKHOGSController::class, 'cetak'])->name('klkh.ogs.cetak');
    Route::get('/klkh/ogs/download/{uuid}', [KLKHOGSController::class, 'download'])->name('klkh.ogs.download');
    Route::get('/klkh/ogs/verified/all/{uuid}', [KLKHOGSController::class, 'verifiedAll'])->name('klkh.ogs.verified.all');
    Route::get('/klkh/ogs/verified/foreman/{uuid}', [KLKHOGSController::class, 'verifiedForeman'])->name('klkh.ogs.verified.foreman');
    Route::get('/klkh/ogs/verified/supervisor/{uuid}', [KLKHOGSController::class, 'verifiedSupervisor'])->name('klkh.ogs.verified.supervisor');
    Route::get('/klkh/ogs/verified/superintendent/{uuid}', [KLKHOGSController::class, 'verifiedSuperintendent'])->name('klkh.ogs.verified.superintendent');

    //KLKH Batu Bara
    Route::get('/klkh/batubara', [KLKHBatuBaraController::class, 'index'])->name('klkh.batubara');
    Route::get('/klkh/batubara/insert', [KLKHBatuBaraController::class, 'insert'])->name('klkh.batubara.insert')->middleware('checkRole'.':FOREMAN,SUPERVISOR');
    Route::post('/klkh/batubara/post', [KLKHBatuBaraController::class, 'post'])->name('klkh.batubara.post');
    Route::get('/klkh/batubara/delete/{id}', [KLKHBatuBaraController::class, 'delete'])->name('klkh.batubara.delete');
    Route::get('/klkh/batubara/preview/{uuid}', [KLKHBatuBaraController::class, 'preview'])->name('klkh.batubara.preview');
    Route::get('/klkh/batubara/bundlepdf', [KLKHBatuBaraController::class, 'bundlepdf'])->name('klkh.batubara.bundlepdf');
    Route::get('/klkh/batubara/cetak/{uuid}', [KLKHBatuBaraController::class, 'cetak'])->name('klkh.batubara.cetak');
    Route::get('/klkh/batubara/download/{uuid}', [KLKHBatuBaraController::class, 'download'])->name('klkh.batubara.download');
    Route::get('/klkh/batubara/verified/all/{uuid}', [KLKHBatuBaraController::class, 'verifiedAll'])->name('klkh.batubara.verified.all');
    Route::get('/klkh/batubara/verified/foreman/{uuid}', [KLKHBatuBaraController::class, 'verifiedForeman'])->name('klkh.batubara.verified.foreman');
    Route::get('/klkh/batubara/verified/supervisor/{uuid}', [KLKHBatuBaraController::class, 'verifiedSupervisor'])->name('klkh.batubara.verified.supervisor');
    Route::get('/klkh/batubara/verified/superintendent/{uuid}', [KLKHBatuBaraController::class, 'verifiedSuperintendent'])->name('klkh.batubara.verified.superintendent');

    //KLKH Simpang Empat
    Route::get('/klkh/simpangempat', [KLKHSimpangEmpatController::class, 'index'])->name('klkh.simpangempat');
    Route::get('/klkh/simpangempat/insert', [KLKHSimpangEmpatController::class, 'insert'])->name('klkh.simpangempat.insert')->middleware('checkRole'.':FOREMAN,SUPERVISOR');
    Route::post('/klkh/simpangempat/post', [KLKHSimpangEmpatController::class, 'post'])->name('klkh.simpangempat.post');
    Route::get('/klkh/simpangempat/delete/{id}', [KLKHSimpangEmpatController::class, 'delete'])->name('klkh.simpangempat.delete');
    Route::get('/klkh/simpangempat/preview/{uuid}', [KLKHSimpangEmpatController::class, 'preview'])->name('klkh.simpangempat.preview');
    Route::get('/klkh/simpangempat/bundlepdf', [KLKHSimpangEmpatController::class, 'bundlepdf'])->name('klkh.simpangempat.bundlepdf');
    Route::get('/klkh/simpangempat/cetak/{uuid}', [KLKHSimpangEmpatController::class, 'cetak'])->name('klkh.simpangempat.cetak');
    Route::get('/klkh/simpangempat/download/{uuid}', [KLKHSimpangEmpatController::class, 'download'])->name('klkh.simpangempat.download');
    Route::get('/klkh/simpangempat/verified/all/{uuid}', [KLKHSimpangEmpatController::class, 'verifiedAll'])->name('klkh.simpangempat.verified.all');
    Route::get('/klkh/simpangempat/verified/foreman/{uuid}', [KLKHSimpangEmpatController::class, 'verifiedForeman'])->name('klkh.simpangempat.verified.foreman');
    Route::get('/klkh/simpangempat/verified/supervisor/{uuid}', [KLKHSimpangEmpatController::class, 'verifiedSupervisor'])->name('klkh.simpangempat.verified.supervisor');
    Route::get('/klkh/simpangempat/verified/superintendent/{uuid}', [KLKHSimpangEmpatController::class, 'verifiedSuperintendent'])->name('klkh.simpangempat.verified.superintendent');

    //Paylaod & Ritation
    Route::get('/payloadritation/all', [PayloadRitationController::class, 'index'])->name('payloadritation.index');
    Route::get('/payloadritation/exa', [PayloadRitationController::class, 'exa_new'])->name('payloadritation.exa');

    // Profile
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');

    //Verifikasi Laporan Kerja
    Route::get('/verifikasi/laporan-kerja', [VerifikasiLaporanKerjaController::class, 'index'])->name('verifikasi.laporankerja');

    //Verifikasi Semua KLKH
    Route::get('/verifikasi/klkh', [VerifikasiKLKHController::class, 'index'])->name('verifikasi.klkh');
    Route::get('/verifikasi/klkh/preview/{uuid}', [VerifikasiKLKHController::class, 'preview'])->name('verifikasi.klkh.preview');
    Route::get('/verifikasi/klkh/all', [VerifikasiKLKHController::class, 'all'])->name('verifikasi.klkh.all');

    //Verifikasi KLKH Loading Point
    Route::get('/verifikasi/klkh/loading-point', [VerifikasiKLKHLoadingPointController::class, 'index'])->name('verifikasi.klkh.loadingpoint');
    Route::get('/verifikasi/klkh/loading-point/all', [VerifikasiKLKHLoadingPointController::class, 'all'])->name('verifikasi.klkh.loadingpoint.all');

    //Verifikasi KLKH Haul Road
    Route::get('/verifikasi/klkh/haul-road', [VerifikasiKLKHHaulRoadController::class, 'index'])->name('verifikasi.klkh.haulroad');

    //Verifikasi KLKH Disposal/Dumping Point
    Route::get('/verifikasi/klkh/disposal', [VerifikasiKLKHDisposalController::class, 'index'])->name('verifikasi.klkh.disposal');

    //Verifikasi KLKH Dumping di Lumpur
    Route::get('/verifikasi/klkh/lumpur', [VerifikasiKLKHLumpurController::class, 'index'])->name('verifikasi.klkh.lumpur');

    //Verifikasi KLKH OGS
    Route::get('/verifikasi/klkh/ogs', [VerifikasiKLKHOGSController::class, 'index'])->name('verifikasi.klkh.ogs');

    //Verifikasi KLKH Batu Bara
    Route::get('/verifikasi/klkh/batu-bara', [VerifikasiKLKHBatubaraController::class, 'index'])->name('verifikasi.klkh.batubara');

    //Verifikasi KLKH Intersection/Simpang Empat
    Route::get('/verifikasi/klkh/simpang-empat', [VerifikasiKLKHSimpangEmpatController::class, 'index'])->name('verifikasi.klkh.simpangempat');

    //Monitoring Laporan Kerja & KLKH
    Route::get('/monitoring-laporan-kerja-klkh', [MonitoringLaporanKerjaKLKHController::class, 'index'])->name('monitoringlaporankerjaklkh');

    //Roster Kerja
    Route::get('/roster-kerja', [RosterKerjaController::class, 'index'])->name('rosterkerja');
    Route::post('/roster-kerja/import', [RosterKerjaController::class, 'import'])->name('rosterkerja.import');
    Route::get('/roster-kerja/export', [RosterKerjaController::class, 'export'])->name('rosterkerja.export');

    //Monitoring Payload
    Route::get('/monitoring-payload', [MonitoringPayloadController::class, 'index'])->name('monitoringpayload');

    // User
    Route::get('/user/index', [UserController::class, 'index'])->name('user.index')->middleware('checkRole'.':ADMIN');
    Route::post('/user/insert', [UserController::class, 'insert'])->name('user.insert');
    Route::post('/user/change-role/{id}', [UserController::class, 'changeRole'])->name('user.change-role');
    Route::get('/user/reset-password/{id}', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::get('/user/status-enabled/{id}', [UserController::class, 'statusEnabled'])->name('user.status-enabled');

    // Log
    Route::get('/log/index', [LogController::class, 'index'])->name('log.index')->middleware('checkRole'.':ADMIN');
});


