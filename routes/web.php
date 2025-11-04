<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClsController;
use App\Http\Controllers\DepoController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CinduController;
use App\Http\Controllers\ItypeController;
use App\Http\Controllers\PgrupController;
use App\Http\Controllers\MsgrupController;
use App\Http\Controllers\SrenoController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CusmasController;
use App\Http\Controllers\Cusmas;
use App\Http\Controllers\PromasController;
use App\Http\Controllers\MssgrupController;
use App\Http\Controllers\SsgrupController;
use App\Http\Controllers\TcorehController;
use App\Http\Controllers\SgrupController;
use App\Http\Controllers\StmasController;
use App\Http\Controllers\CusmasCabController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OcController;
use App\Http\Controllers\TpoController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\BlawbController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BbmController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () { return view('login/index'); });
Route::get('/', [LoginController::class,'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class,'auth']);
Route::post('/logout', [LoginController::class,'logout']);

Route::get('/dashboard', function () { return view('home'); })->middleware('auth')->name('dashboard');

Route::get('/register', [LoginController::class,'registerUser'])->middleware('auth');
Route::get('/cekUsername', [LoginController::class,'cekUsername']);
Route::post('/register', [LoginController::class,'insertUser'])->middleware('auth');
Route::post('/register/{id}', [LoginController::class,'updateUser'])->middleware('auth');
Route::delete('/register/{id}',[LoginController::class,'deleteUser'])->middleware('auth');


// Route::get('/mssgrup',[MssgrupController::class,'index']);
// Route::post('/mssgrup',[MssgrupController::class,'store']);
// Route::delete('/mssgrup/{ssgrup_id}',[MssgrupController::class,'destroy']);

Route::resource('/ssgrup', SsgrupController::class)->middleware('auth');
Route::resource('/msgrup',SgrupController::class)->middleware('auth');
Route::resource('/mpgrup',PgrupController::class)->middleware('auth');
Route::resource('/mbrand',BrandController::class)->middleware('auth');
Route::resource('/mitype',ItypeController::class)->middleware('auth');
Route::resource('/mcls',ClsController::class)->middleware('auth');

// Route::get('/mpromas',[PromasController::class,'index']);
// Route::post('/mpromas',[PromasController::class,'store']);
// Route::put('/mpromas/{mproma}',[PromasController::class,'update']);
// Route::delete('/mpromas/{mproma}',[PromasController::class,'destroy']);
Route::resource('/mpromas',PromasController::class)->middleware('auth');
Route::post('/mpromas/cekOpron',[PromasController::class,'cekOpron'])->middleware('auth');
Route::get('/mpromas/listJson',[PromasController::class,'listJson'])->middleware('auth');
Route::resource('/msreno',SrenoController::class)->middleware('auth');
Route::resource('/mcindu',CinduController::class)->middleware('auth');

Route::get('/mstmas/provinsii',[StmasController::class,'provinsii'])->middleware('auth');
Route::resource('/mstmas',StmasController::class)->middleware('auth');
Route::get('/mstmas/kabkot/{id}',[StmasController::class,'kabkot'])->middleware('auth');
Route::get('/mstmas/getProvinsi/{id}',[StmasController::class,'getProvinsi'])->middleware('auth');
Route::get('/mstmas/getKabKot/{id}',[StmasController::class,'getKabKot'])->middleware('auth');

Route::resource('/mbranch',BranchController::class)->middleware('auth');

Route::get('/mcusmas/provinsi',[CusmasController::class,'provinsi'])->middleware('auth');
Route::get('/mcusmas/grup',[CusmasController::class,'grup'])->middleware('auth');
Route::get('/mcusmas/customer',[CusmasController::class,'customer'])->middleware('auth');
Route::get('/mcusmas/getDepo/{id}',[CusmasController::class,'getDepo'])->middleware('auth');
Route::get('/mcusmas/getSite/{id}',[CusmasController::class,'getSite'])->middleware('auth');
Route::get('/mcusmas/getMesin/{id}',[CusmasController::class,'getMesin'])->middleware('auth');
Route::resource('/mcusmas', CusmasController::class)->middleware('auth');
Route::resource('/mcusmascab', CusmasCabController::class)->middleware('auth');

Route::get('/cusmas/titleCusmas',[Cusmas::class,'titleCusmas'])->middleware('auth');
Route::get('/cusmas/cinduCusmas',[Cusmas::class,'cinduCusmas'])->middleware('auth');
Route::get('/cusmas/czoneCusmas',[Cusmas::class,'czoneCusmas'])->middleware('auth');
Route::get('/cusmas/careaCusmas',[Cusmas::class,'careaCusmas'])->middleware('auth');
Route::resource('/cusmas', Cusmas::class)->middleware('auth');

Route::get('/mdepo/branche',[DepoController::class,'branche'])->middleware('auth');
Route::resource('/mdepo',DepoController::class)->middleware('auth');
Route::get('/mdepo/getBranch/{id}',[DepoController::class,'getBranch'])->middleware('auth');
Route::get('/order',[TcorehController::class,'index'])->middleware('auth');

Route::get('/roce/clsOc',[OcController::class,'clsOc'])->middleware('auth');
Route::get('/roce/pgrupOc',[OcController::class,'pgrupOc'])->middleware('auth');
Route::get('/roce/renoOc',[OcController::class,'renoOc'])->middleware('auth');
Route::get('/roce/customerOc',[OcController::class,'customerOc'])->middleware('auth');
Route::resource('/roce', OcController::class)->middleware('auth');

Route::get('/tpo', [TpoController::class,'index'])->middleware('auth')->name('tpo.index');
Route::get('/get-currency-rate/{curco}', [TpoController::class, 'getCurrencyRate']);
Route::get('/api/products', [ProductController::class, 'getProducts'])->name('api.products');
Route::get('/tpo/create', [TpoController::class, 'create'])->middleware('auth')->name('tpo.create');
Route::post('/tpo/store', [TpoController::class, 'store'])->middleware('auth')->name('tpo.store');
Route::get('/tpo/{id}/detail', [TpoController::class, 'show'])->middleware('auth')->name('tpo.detail');
Route::get('/tpo/{id}/edit', [TpoController::class, 'edit'])->middleware('auth')->name('tpo.edit');
Route::put('/tpo/{id}', [TpoController::class, 'update'])->middleware('auth')->name('tpo.update');
Route::delete('/tpo/{id}/delete', [TpoController::class, 'destroy'])->middleware('auth');

Route::get('/pdf/preview/{id}', [PdfController::class, 'preview'])->name('pdf.preview'); // sementara dinonaktifkan
Route::get('/pdf/print/{pono}', [PdfController::class, 'print'])->name('pdf.print');
Route::get('/pdf/previewPi/{id}', [PdfController::class, 'previewPi'])->name('pdf_pi.preview'); // sementara dinonaktifkan
Route::get('/pdf/printPi/{pono}', [PdfController::class, 'printPi'])->name('pdf_pi.print');

Route::get('/blawb', [BlawbController::class,'index'])->middleware('auth')->name('blawb.index');
Route::get('/blawb/create', [BlawbController::class,'create'])->middleware('auth')->name('blawb.create');
Route::post('/blawb/store', [BlawbController::class,'store'])->middleware('auth')->name('blawb.store');
Route::get('/blawb/{id}/detail', [BlawbController::class,'show'])->middleware('auth')->name('blawb.detail');
Route::get('/blawb/{id}/edit', [BlawbController::class,'edit'])->middleware('auth')->name('blawb.edit');
Route::put('/blawb/{id}', [BlawbController::class,'update'])->middleware('auth')->name('blawb.update');
Route::delete('/blawb/{id}/delete', [BlawbController::class,'destroy'])->middleware('auth')->name('blawb.delete');

Route::get('/invoice', [InvoiceController::class,'index'])->middleware('auth')->name('invoice.index');
Route::get('/invoice/create', [InvoiceController::class,'create'])->middleware('auth')->name('invoice.create');
Route::get('/get-rinum-by-supplier/{supno}', [InvoiceController::class, 'getRinumBySupplier']);
Route::get('/get-po-by-supplier/{supno}', [InvoiceController::class, 'getPoBySupplier']);
Route::get('/get-items-by-po/{pono}', [InvoiceController::class, 'getItemsByPo']);
Route::post('/invoice/store', [InvoiceController::class,'store'])->middleware('auth')->name('invoice.store');
Route::get('/invoice/{id}/detail', [InvoiceController::class,'show'])->middleware('auth')->name('invoice.detail');
Route::get('/invoice/{id}/edit', [InvoiceController::class,'edit'])->middleware('auth')->name('invoice.edit');
Route::put('/invoice/{id}', [InvoiceController::class,'update'])->middleware('auth')->name('invoice.update');
Route::delete('/invoice/{id}/delete', [InvoiceController::class,'destroy'])->middleware('auth')->name('invoice.delete');

Route::get('/bbm', [BbmController::class,'index'])->middleware('auth')->name('bbm.index');
Route::get('/bbm/create', [BbmController::class,'create'])->middleware('auth')->name('bbm.create');
Route::get('/get-invoice/{rinum}', [BbmController::class, 'getInvoice'])->name('bbm.getInvoice');
Route::get('/get-barang/{invno}', [BbmController::class, 'getBarang'])->name('bbm.getBarang');
Route::get('/get-locco/{warco}', [BbmController::class, 'getLocco'])->name('bbm.getLocco');
Route::get('/get-po-list', [BbmController::class, 'getPoList'])->name('bbm.getPoList');
Route::get('/get-po-supplier/{pono}', [BbmController::class, 'getPoSupplier'])->name('bbm.getPoSupplier');
Route::post('/bbm/store', [BbmController::class,'store'])->middleware('auth')->name('bbm.store');
Route::get('/bbm/{id}/detail', [BbmController::class,'show'])->middleware('auth')->name('bbm.detail');
Route::get('/bbm/{id}/edit', [BbmController::class,'edit'])->middleware('auth')->name('bbm.edit');
Route::put('/bbm/{bbmid}', [BbmController::class,'update'])->middleware('auth')->name('bbm.update');
Route::delete('/bbm/{id}/delete', [BbmController::class,'destroy'])->middleware('auth')->name('bbm.delete');
Route::get('/bbm/previewBbm/{id}', [PdfController::class, 'previewBbm'])->name('bbm.previewBbm');