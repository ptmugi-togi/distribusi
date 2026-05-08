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
use App\Http\Controllers\BbkController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\BpbController;
use App\Http\Controllers\TaController;
use App\Http\Controllers\StockStatusController;
use App\Http\Controllers\OsrController;
use App\Http\Controllers\WoController;
use App\Http\Controllers\MstmasController;
use App\Http\Controllers\OcSbController;
use App\Http\Controllers\MktController;
use App\Http\Controllers\DoController;
use App\Http\Controllers\ScController;
use App\Http\Controllers\DpInvRelController;
use App\Http\Controllers\ProjectInvRelController;
use App\Http\Controllers\RetailInvRelController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\WoffController;
use App\Http\Controllers\PaymentListController;
use App\Http\Controllers\ArWoffListController;
use App\Http\Controllers\AgingArByInvoiceController;

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
Route::get('/get-warco-bbm', [BbmController::class, 'getWarco'])->name('bbm.get-warco');
Route::get('/generate-trano-bbm', [BbmController::class,'generateTrano'])->name('generate-trano-bbm');
Route::get('/get-invoice/{rinum}', [BbmController::class, 'getInvoice'])->name('bbm.getInvoice');
Route::get('/get-barang/{invno}', [BbmController::class, 'getBarang'])->name('bbm.getBarang');
Route::get('/get-supplier-items/{supno}', [BbmController::class, 'getSupplierItems']);
Route::get('/get-locco/{warco}', [BbmController::class, 'getLocco'])->name('bbm.getLocco');
Route::get('/get-po-list', [BbmController::class, 'getPoList'])->name('bbm.getPoList');
Route::get('/get-po-supplier/{pono}', [BbmController::class, 'getPoSupplier'])->name('bbm.getPoSupplier');
Route::get('/get-ta', [BbmController::class, 'getTa'])->name('get.ta');
Route::get('/get-opron-ta', [BbmController::class, 'getOpronByTa'])->name('get.opron.by.ta');
Route::get('/get-oc', [BbmController::class, 'getOc'])->name('get.oc');
Route::get('/get-ob', [BbmController::class, 'getOb'])->name('get.ob');
Route::get('/get-opron-by-ob', [BbmController::class, 'getOpronByOb'])->name('get.opron.by.ob');
Route::get('/get-oa', [BbmController::class, 'getOa'])->name('get.oa');
Route::get('/get-opron-by-oa', [BbmController::class, 'getOpronByOa'])->name('get.opron.by.oa');
Route::get('/get-oe', [BbmController::class, 'getOe'])->name('get.oe');
Route::get('/get-opron-by-oe', [BbmController::class, 'getOpronByOe'])->name('get.opron.by.oe');
Route::get('/get-wo', [BbmController::class, 'getWo'])->name('get.wo');
Route::get('/get-opron-by-wo', [BbmController::class, 'getOpronByWo'])->name('get.opron.by.wo');
Route::post('/bbm/store', [BbmController::class,'store'])->middleware('auth')->name('bbm.store');
Route::get('/bbm/{id}/detail', [BbmController::class,'show'])->middleware('auth')->name('bbm.detail');
Route::get('/bbm/{id}/edit', [BbmController::class,'edit'])->middleware('auth')->name('bbm.edit');
Route::put('/bbm/{bbmid}', [BbmController::class,'update'])->middleware('auth')->name('bbm.update');
Route::delete('/bbm/{id}/delete', [BbmController::class,'destroy'])->middleware('auth')->name('bbm.delete');
Route::get('/bbm/previewBbm/{id}', [PdfController::class, 'previewBbm'])->name('bbm.previewBbm');
Route::get('/bbm/printBbm/{id}', [PdfController::class, 'printBbm'])->name('bbm.printBbm');

Route::get('/bbk', [BbkController::class,'index'])->middleware('auth')->name('bbk.index');
Route::get('/bbk/create', [BbkController::class,'create'])->middleware('auth')->name('bbk.create');
Route::get('/get-warco-bbk', [BbkController::class, 'getWarco'])->name('bbk.get-warco');
Route::get('/generate-trano-bbk', [BbkController::class,'generateTrano'])->name('generate-trano-bbk');
Route::get('/get-barang/{braco}/{warco}/{locco}', [BbkController::class, 'getBarangOF']); //get barang untuk OF
Route::get('/get-stobl/{braco}/{warco}/{opron}', [BbkController::class, 'getStobl'])->where('opron', '.*');
Route::get('/get-stobl-ob/{braco}/{warco}/{opron}', [BbkController::class, 'getStoblOb'])->where('opron', '.*');
Route::post('/reduce-stock', [BbkController::class, 'reduceStock']); //mengurangi stok jika OF
Route::post('/bbk/store', [BbkController::class,'store'])->middleware('auth')->name('bbk.store');
Route::get('/bbk/{id}/detail', [BbkController::class,'show'])->middleware('auth')->name('bbk.detail');
Route::get('/bbk/{id}/edit', [BbkController::class,'edit'])->middleware('auth')->name('bbk.edit');
Route::put('/bbk/{bbkid}', [BbkController::class,'update'])->middleware('auth')->name('bbk.update');
Route::delete('/bbk/{id}/delete', [BbkController::class,'destroy'])->middleware('auth')->name('bbk.delete');
Route::get('/bbk/previewBbk/{id}', [PdfController::class, 'previewBbk'])->name('bbk.previewBbk');
Route::get('/bbk/printBbk/{id}', [PdfController::class, 'printBbk'])->name('bbk.printBbk');

Route::get('/sms/create', [SmsController::class,'create'])->middleware('auth')->name('sms.create');
Route::get('/stock/preview', [SmsController::class, 'preview'])->name('sms.preview');

Route::get('/bpb', [BpbController::class,'index'])->middleware('auth')->name('bpb.index');
Route::get('/bpb/create', [BpbController::class,'create'])->middleware('auth')->name('bpb.create');
Route::get('/generate-reqno-bpb', [BpbController::class,'generateReqno'])->name('generate-reqno-bpb');
Route::get('/get-warco-detail/{code}', [BpbController::class, 'getWarcoDetail']);
Route::post('/bpb/store', [BpbController::class,'store'])->middleware('auth')->name('bpb.store');
Route::get('/bpb/{id}/detail', [BpbController::class,'show'])->middleware('auth')->name('bpb.detail');
Route::get('/bpb/{id}/edit', [BpbController::class,'edit'])->middleware('auth')->name('bpb.edit');
Route::put('/bpb/{bpbid}', [BpbController::class,'update'])->middleware('auth')->name('bpb.update');
Route::delete('/bpb/{id}/delete', [BpbController::class,'destroy'])->middleware('auth')->name('bpb.delete');
Route::get('/bpb/previewBpb/{id}', [PdfController::class, 'previewBpb'])->name('bpb.previewBpb');
Route::get('/bpb/printBpb/{id}', [PdfController::class, 'printBpb'])->name('bpb.printBpb');

Route::get('/ta', [TaController::class,'index'])->middleware('auth')->name('ta.index');
Route::get('/ta/create', [TaController::class,'create'])->middleware('auth')->name('ta.create');
Route::post('/ta/store', [TaController::class,'store'])->middleware('auth')->name('ta.store');
Route::get('/generate-trano-ta', [TaController::class,'generateTrano'])->name('generate-trano-ta');
Route::get('/get-sa', [TaController::class,'getSa'])->name('get-sa');
Route::get('/get-barang-ra/{ra_id}', [TaController::class, 'getBarangByRA']);
Route::get('/get-lotno/{ra_id}/{opron}', [TaController::class, 'getLotByRA'])->where('opron', '.*');
Route::get('/ta/{id}/detail', [TaController::class,'show'])->middleware('auth')->name('ta.detail');
Route::get('/ta/{id}/edit', [TaController::class,'edit'])->middleware('auth')->name('ta.edit');
Route::get('/get-rqqty/{sano}/{opron}', [TaController::class, 'getRqqty']);
Route::put('/ta/{id}', [TaController::class,'update'])->middleware('auth')->name('ta.update');
Route::delete('/ta/{id}/delete', [TaController::class,'destroy'])->middleware('auth')->name('ta.delete');
Route::get('/ta/previewTa/{id}', [PdfController::class, 'previewTa'])->name('ta.previewTa');
Route::get('/ta/printTa/{id}', [PdfController::class, 'printTa'])->name('ta.printTa');

Route::get('/stock-status', [StockStatusController::class,'index'])->middleware('auth')->name('ss.index');
Route::get('/stock-status/lot/{opron}', [StockStatusController::class, 'getLot'])->where('opron', '.*');

Route::get('/outstanding-stock-requisition', [OsrController::class,'index'])->middleware('auth')->name('osr.create');
Route::get('/osr/print', [OsrController::class, 'print'])->name('osr.print');

Route::get('/wo', [WoController::class,'index'])->middleware('auth')->name('wo.index');
Route::get('/wo/create', [WoController::class,'create'])->middleware('auth')->name('wo.create');
Route::get('/generate-wonum', [WoController::class,'generateWonum'])->name('generate-wonum');
Route::get('/get-ra-wo/{braco}', [WoController::class, 'getRa']);
Route::get('/get-barang-ra/{bpbid}', [WoController::class, 'getBarangByRA']);
Route::post('/wo/store', [WoController::class,'store'])->middleware('auth')->name('wo.store');
Route::get('/wo/{id}/detail', [WoController::class,'show'])->middleware('auth')->name('wo.detail');
Route::get('/wo/{id}/edit', [WoController::class,'edit'])->middleware('auth')->name('wo.edit');
Route::put('/wo/{id}', [WoController::class,'update'])->middleware('auth')->name('wo.update');
Route::delete('/wo/{id}/delete', [WoController::class,'destroy'])->middleware('auth')->name('wo.delete');
Route::get('/wo/previewWo/{id}', [PdfController::class, 'previewWo'])->name('wo.previewWo');
Route::get('/wo/printWo/{id}', [PdfController::class, 'printWo'])->name('wo.printWo');

Route::get('/api/mstmas', [MstmasController::class, 'getMstmas'])->name('api.mstmas');

Route::get('/oc', [OcController::class,'index'])->middleware('auth')->name('oc.index');
Route::get('/oc/create', [OcController::class,'create'])->middleware('auth')->name('oc.create');
Route::get('/generate-ocnum', [OcController::class,'generateOcnum'])->name('generate-ocnum');
Route::get('/get-currency-rate/{curco}', [OcController::class, 'getCurrencyRate'])->name('get-currency-rate');
Route::get('/get-sales-split/{sqtbr}', [OcController::class, 'getSalesSplit'])->name('get-sales-split');
Route::get('/get-mstmas-delto', [OcController::class, 'getMstmasDelto'])->name('get-mstmas-delto');
Route::get('/get-mstmas-detail', [OcController::class, 'getMstmasDetail'])->name('get-mstmas-detail');
route::post('/oc/store', [OcController::class,'store'])->middleware('auth')->name('oc.store');
Route::get('/oc/{id}/detail', [OcController::class,'show'])->middleware('auth')->name('oc.detail');
Route::get('/oc/{id}/edit', [OcController::class,'edit'])->middleware('auth')->name('oc.edit');
Route::put('/oc/{id}', [OcController::class,'update'])->middleware('auth')->name('oc.update');
Route::put('/oc/{id}/cancel', [OcController::class,'cancel'])->middleware('auth')->name('oc.cancel');
Route::delete('/oc/{id}/delete', [OcController::class,'destroy'])->middleware('auth')->name('oc.delete');
Route::get('/oc/previewOc/{id}', [PdfController::class, 'previewOc'])->name('oc.previewOc');
Route::get('/oc/printOc/{id}', [PdfController::class, 'printOc'])->name('oc.printOc');

Route::get('/oc-sb', [OcSbController::class,'index'])->middleware('auth')->name('oc_sb.index');
Route::get('/oc-sb/create', [OcSbController::class,'create'])->middleware('auth')->name('oc_sb.create');
Route::get('/get-sub-product', [OcSbController::class, 'getSubProduct'])->name('get-sub-product');
Route::get('/get-sales-by-branch', [OcSbController::class, 'getSalesByBranch']);
Route::post('/oc-sb/store', [OcSbController::class,'store'])->middleware('auth')->name('oc_sb.store');
Route::get('/oc-sb/{id}/detail', [OcSbController::class,'show'])->middleware('auth')->name('oc_sb.detail');
Route::get('/oc-sb/{id}/edit', [OcSbController::class,'edit'])->middleware('auth')->name('oc_sb.edit');
Route::get('/get-bom-by-oc', [OcSbController::class, 'getBomByOc'])->middleware('auth')->name('get-bom-by-oc');
Route::put('/oc-sb/{id}', [OcSbController::class,'update'])->middleware('auth')->name('oc_sb.update');
Route::put('/oc-sb/{id}/cancel', [OcSbController::class,'cancel'])->middleware('auth')->name('oc_sb.cancel');
Route::delete('/oc-sb/{id}/delete', [OcSbController::class,'destroy'])->middleware('auth')->name('oc_sb.delete');
Route::get('/oc/previewOcSb/{id}', [PdfController::class, 'previewOcSb'])->name('oc.previewOcSb');
Route::get('/oc/printOcSb/{id}', [PdfController::class, 'printOcSb'])->name('oc.printOcSb');

Route::get('/mkt', [MktController::class, 'create'])->middleware('auth')->name('mkt.createMkt');
Route::get('/mkt/preview', [MktController::class, 'previewMkt'])->name('mkt.previewMkt');
Route::get('/mkt-ss', [MktController::class, 'createSs'])->middleware('auth')->name('mkt.createMktSs');
Route::get('/mkt-ss/preview', [MktController::class, 'previewMktSs'])->name('mkt.previewMktSs');

Route::get('/do', [DoController::class,'index'])->middleware('auth')->name('do.index');
Route::get('/do/create', [DoController::class,'create'])->middleware('auth')->name('do.create');
Route::post('/do/store', [DoController::class,'store'])->middleware('auth')->name('do.store');
Route::get('/generate-trano-do', [DoController::class,'generateTrano'])->name('generate-trano-do');
Route::get('/get-shipto', [DoController::class, 'getShiptoByCusno'])->name('get-shipto-by-cusno');
Route::get('/get-oc/do', [DoController::class, 'getOc'])->name('get-oc');
Route::get('/get-barang-oc', [DoController::class, 'getBarangByOC'])->name('get-barang-oc');
Route::get('/get-lot-oc', [DoController::class, 'getLotByOC'])->name('get-lot-oc');
Route::get('/do/{id}/detail', [DoController::class,'show'])->middleware('auth')->name('do.detail');
Route::get('/do/{id}/edit', [DoController::class,'edit'])->middleware('auth')->name('do.edit');
Route::put('/do/{id}', [DoController::class,'update'])->middleware('auth')->name('do.update');
Route::delete('/do/{id}/delete', [DoController::class,'destroy'])->middleware('auth')->name('do.delete');
Route::get('/do/previewDo/{id}', [DoController::class, 'previewDo'])->name('do.previewDo');
Route::get('/do/printDo/{id}', [DoController::class, 'printDo'])->name('do.printDo');

Route::get('/sc/create', [ScController::class,'create'])->middleware('auth')->name('sc.create');
Route::get('/sc/preview', [ScController::class, 'preview'])->name('sc.preview');

Route::get('/dp-inv-rel/index', [DpInvRelController::class,'index'])->middleware('auth')->name('dp_inv_rel.index');
Route::get('/dp-inv-rel/create', [DpInvRelController::class,'create'])->middleware('auth')->name('dp_inv_rel.create');
Route::get('/generate-invno-sc-dp', [DpInvRelController::class,'generateInvno'])->name('generate-invno-sc-dp');
Route::get('/get-oc/sc-sa', [DpInvRelController::class, 'getOcSa'])->name('get-oc-sa');
Route::get('/get-main-address', [DpInvRelController::class, 'getMainAddress'])->name('get-main-address');
Route::get('/get-opron-by-oc-sa', [DpInvRelController::class, 'getOpronByOcSa'])->name('get-opron-by-oc-sa');
Route::post('/dp-inv-rel/store', [DpInvRelController::class,'store'])->middleware('auth')->name('dp_inv_rel.store');
Route::put('/dp-inv-rel/release/{invid}', [DpInvRelController::class,'release'])->middleware('auth')->name('dp_inv_rel.release');
Route::get('/dp-inv-rel/preview/{invid}', [DpInvRelController::class,'preview'])->middleware('auth')->name('dp_inv_rel.preview');
Route::get('/dp-inv-rel/print/{invid}', [DpInvRelController::class,'print'])->middleware('auth')->name('dp_inv_rel.print');

Route::get('/project-inv-rel/index', [ProjectInvRelController::class,'index'])->middleware('auth')->name('project_inv_rel.index');
Route::get('/project-inv-rel/create', [ProjectInvRelController::class,'create'])->middleware('auth')->name('project_inv_rel.create');
Route::get('/generate-invno-sc-project', [ProjectInvRelController::class,'generateInvno'])->name('generate-invno-sc-project');
Route::get('/get-oc/sc-sb', [ProjectInvRelController::class, 'getOcSb'])->name('get-oc-sb');
Route::get('/get-phase-by-oc', [ProjectInvRelController::class, 'getPhaseByOc'])->name('get-phase-by-oc');
Route::get('/get-main-address', [ProjectInvRelController::class, 'getMainAddress'])->name('get-main-address');
Route::get('/get-opron-by-oc-sb', [ProjectInvRelController::class, 'getOpronByOcSb'])->name('get-opron-by-oc-sb');
Route::post('/project-inv-rel/store', [ProjectInvRelController::class,'store'])->middleware('auth')->name('project_inv_rel.store');
Route::put('/project-inv-rel/release/{invid}', [ProjectInvRelController::class,'release'])->middleware('auth')->name('project_inv_rel.release');
Route::get('/project-inv-rel/preview/{invid}', [ProjectInvRelController::class,'preview'])->middleware('auth')->name('project_inv_rel.preview');
Route::get('/project-inv-rel/print/{invid}', [ProjectInvRelController::class,'print'])->middleware('auth')->name('project_inv_rel.print');

Route::get('/retail-inv-rel/index', [RetailInvRelController::class,'index'])->middleware('auth')->name('retail_inv_rel.index');
Route::get('/retail-inv-rel/create', [RetailInvRelController::class,'create'])->middleware('auth')->name('retail_inv_rel.create');
Route::get('/generate-invno-sc-retail', [RetailInvRelController::class,'generateInvno'])->name('generate-invno-sc-retail');
Route::get('/get-oc/sc-do', [RetailInvRelController::class, 'getDo'])->name('get-do');
Route::get('/get-main-address', [RetailInvRelController::class, 'getMainAddress'])->name('get-main-address');
Route::get('/get-opron-by-do-sa', [RetailInvRelController::class, 'getOpronByDoSa'])->name('get-opron-by-do-sa');
Route::post('/retail-inv-rel/store', [RetailInvRelController::class,'store'])->middleware('auth')->name('retail_inv_rel.store');
Route::put('/retail-inv-rel/release/{invid}', [RetailInvRelController::class,'release'])->middleware('auth')->name('retail_inv_rel.release');
Route::get('/retail-inv-rel/preview/{invid}', [RetailInvRelController::class,'preview'])->middleware('auth')->name('retail_inv_rel.preview');
Route::get('/retail-inv-rel/print/{invid}', [RetailInvRelController::class,'print'])->middleware('auth')->name('retail_inv_rel.print');

Route::get('/invoice-payment/index', [InvoicePaymentController::class,'index'])->middleware('auth')->name('invoice_payment.index');
Route::get('/invoice-payment/create', [InvoicePaymentController::class,'create'])->middleware('auth')->name('invoice_payment.create');
Route::get('/generate-invoice-payment-no', [InvoicePaymentController::class,'generateInvoicePaymentNo'])->name('generate-invoice-payment-no');
Route::get('/get-invoice', [InvoicePaymentController::class, 'getInvoice'])->name('get-invoice');
Route::post('/invoice-payment/store', [InvoicePaymentController::class,'store'])->middleware('auth')->name('invoice_payment.store');
Route::get('/invoice-payment/detail/{id}', [InvoicePaymentController::class,'show'])->where('id', '.*')->middleware('auth')->name('invoice_payment.detail');
Route::get('/invoice-payment/edit/{id}', [InvoicePaymentController::class,'edit'])->where('id', '.*')->middleware('auth')->name('invoice_payment.edit');
Route::put('/invoice-payment/update/{id}', [InvoicePaymentController::class,'update'])->where('id', '.*')->middleware('auth')->name('invoice_payment.update');

Route::get('/writeoff-ar/index', [WoffController::class,'index'])->middleware('auth')->name('writeoff_ar.index');
Route::get('/writeoff-ar/create', [WoffController::class,'create'])->middleware('auth')->name('writeoff_ar.create');
Route::get('/generate-writeoff-ar-no', [WoffController::class,'generateWoffNo'])->name('generate-writeoff-ar-no');
Route::get('/get-invoice-writeoff', [WoffController::class, 'getInvoice'])->name('get-invoice-writeoff');
Route::post('/writeoff-ar/store', [WoffController::class,'store'])->middleware('auth')->name('writeoff_ar.store');
Route::get('/writeoff-ar/detail/{id}', [WoffController::class,'show'])->middleware('auth')->name('writeoff_ar.detail');
Route::get('/writeoff-ar/edit/{id}', [WoffController::class,'edit'])->middleware('auth')->name('writeoff_ar.edit');
Route::put('/writeoff-ar/update/{id}', [WoffController::class,'update'])->middleware('auth')->name('writeoff_ar.update');

Route::get('/payment-list/create', [PaymentListController::class,'create'])->middleware('auth')->name('payment_list.create');
Route::get('/payment-list/preview', [PaymentListController::class, 'previewPaymentList'])->middleware('auth')->name('payment_list.preview');

Route::get('/ar-woff-list/create', [ArWoffListController::class,'create'])->middleware('auth')->name('ar_woff_list.create');
Route::get('/ar-woff-list/preview', [ArWoffListController::class, 'previewArWoffList'])->middleware('auth')->name('ar_woff_list.preview');

Route::get('/aging-ar-by-invoice/create', [AgingArByInvoiceController::class,'create'])->middleware('auth')->name('aging_ar_by_invoice.create');
Route::get('/aging-ar-by-invoice/customer', [AgingArByInvoiceController::class,'getCustomer'])->middleware('auth')->name('aging_ar_by_invoice.customer');
Route::get('/aging-ar-by-invoice/preview', [AgingArByInvoiceController::class, 'previewAgingArByInvoiceList'])->middleware('auth')->name('aging_ar_by_invoice.preview');