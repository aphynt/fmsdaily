<?php

use App\Http\Controllers\Api\LaporanHarianController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\FuelServiceURLController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/user', [LaporanHarianController::class, 'laporanPengawas'])->name('api.laporan-pengawas');


Route::get('/fuel/serviceurl/{token}', [FuelServiceURLController::class, 'serviceURL'])->name('fuel.serviceURL');
Route::get('/fuel/operator', [FuelServiceURLController::class, 'operator'])->name('fuel.operator');
Route::get('/fuel/location', [FuelServiceURLController::class, 'location'])->name('fuel.location');
Route::get('/fuel/shift', [FuelServiceURLController::class, 'shift'])->name('fuel.shift');
Route::get('/fuel/type', [FuelServiceURLController::class, 'type'])->name('fuel.type');
Route::get('/fuel/unit', [FuelServiceURLController::class, 'unit'])->name('fuel.unit');
Route::get('/fuel/transfrom', [FuelServiceURLController::class, 'transfrom'])->name('fuel.transfrom');
Route::get('/fuel/transto', [FuelServiceURLController::class, 'transto'])->name('fuel.transto');

//Incoming
Route::post('/fuel/sendPostFuel/Incoming', [FuelServiceURLController::class, 'sendPostFuelIncoming'])->name('fuel.post.incoming');
Route::get('/fuel/getDataFuel/Incoming', [FuelServiceURLController::class, 'getDataFuelIncoming'])->name('fuel.get.incoming');

//Outgoing
Route::post('/fuel/sendPostFuel/Outgoing', [FuelServiceURLController::class, 'sendPostFuelOutgoing'])->name('fuel.post.outgoing');
Route::get('/fuel/getDataFuel/Outgoing', [FuelServiceURLController::class, 'getDataFuelOutgoing'])->name('fuel.get.outgoing');

//Transfer
Route::post('/fuel/sendPostFuel/Transfer', [FuelServiceURLController::class, 'sendPostFuelTransfer'])->name('fuel.post.transfer');
Route::get('/fuel/getDataFuel/Transfer', [FuelServiceURLController::class, 'getDataFuelTransfer'])->name('fuel.get.transfer');


// Route::get('/laporan-pengawas', [APIController::class, 'laporanPengawas'])->name('api.laporan-pengawas');
