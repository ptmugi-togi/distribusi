<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\DnHdr;
use App\Models\DnDtl;

class DeliveryNoteController extends Controller
{
    public function index()
    {
        $userBraco = Auth::user()->cabang;
        
        $dn = DnHdr::with('mbranch', 'mcusmas', 'mformcode')
            ->where('braco', $userBraco)
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('teknik.delivery_note.delivery_note_index', compact('dn', 'userBraco', 'periodClosed'));
    }

    public function create()
    {
        $userBraco = Auth::user()->cabang;
        
        $dn = DnHdr::with('mbranch', 'mcusmas', 'mformcode')
            ->where('braco', $userBraco)
            ->get();

        $minDate = null;

        $periodeAktif = DB::table('tperiode')
            ->where('braco', auth()->user()->cabang)
            ->where('status', 'O')
            ->orderBy('periode', 'desc')
            ->first();

        if ($periodeAktif) {
            $priod = $periodeAktif->periode;
            $year = substr($periodeAktif->periode, 0, 4);
            $month = substr($periodeAktif->periode, 4, 2);
            $minDate = "$year-$month-01";
        }

        $depo = DB::table('mdepos')
            ->where('braco', auth()->user()->cabang)
            ->get();
        
        $customers = DB::table('mcusmas')
            ->where('braco', auth()->user()->cabang)
            ->get();

        $serviceType = DB::table('msertyp')
            ->get();

        $tax = DB::table('mtaxes')
            ->where('braco', auth()->user()->cabang)
            ->first();

        return view('teknik.delivery_note.delivery_note_create', compact('dn', 'minDate', 'depo', 'customers', 'serviceType', 'tax'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $dnid = $request->braco . $request->depo .  $request->formc . $request->dnnum;

            $bracoformc = $request->braco . $request->formc;

            $gramt = $request->totalservice + $request->totalsparepart;

            DnHdr::create([
                'dnid'      => $dnid,
                'bracoformc' => $bracoformc,
                'braco'      => $request->braco,
                'depo'       => $request->depo,
                'formc'      => $request->formc,
                'cusno'      => $request->cusno,
                'dnnum'      => $request->dnnum,
                'dndat'      => $request->dndat,
                'priod'      => $request->priod,
                'delto'      => $request->shpto,
                'quote'      => $request->quote,
                'curco'      => $request->curco,
                'crate'      => $request->crate,
                'vatax'      => $request->vatax,
                'gramt'      => $gramt,
                'odisa'      => $request->odisa ?? 0,
                'ntamt'      => $request->ntamt,
                'dpamt'      => $request->dpamt ?? 0,
                'txamt'      => $request->txamt,
                'blamt'      => $request->blamt,
                'total_service' => $request->totalservice,
                'total_sparepart' => $request->totalsparepart,
                'cuspo'      => $request->cuspo,
                'intxt'      => $request->intxt,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
                'deleted_at' => null,
                'deleted_by' => null,
            ]);

            foreach ($request->opron as $i => $opron) {

                $dnlin = $i + 1;
                $gramt_service = array_sum($request->fee[$i] ?? []);
                $odisa_service = (float) ($request->odisa_service[$i] ?? 0);
                $ntamt_service = $gramt_service - $odisa_service;
                $odisp_service = $gramt_service > 0 ? ($odisa_service / $gramt_service) * 100 : 0;

                $tdna = DnDtl::create([
                    'dnid'      => $dnid,
                    'braco'     => $request->braco,
                    'formc'     => $request->formc,
                    'dnnum'     => $request->dnnum,
                    'dnlin'     => $dnlin,
                    'tofee'     => 'SERVICE',
                    'opron'     => $opron,
                    'trqty'     => $request->quantity_service[$i] ?? 0,
                    'stdqu'     => $request->stdqu[$i],
                    'lotno'     => $request->lotno[$i],
                    'gramt'     => $gramt_service,
                    'odisa'     => $odisa_service,
                    'odisp'     => round($odisp_service, 2),
                    'netbe' => $ntamt_service,
                ]);

                if(isset($request->tofee[$i])){
                    foreach($request->tofee[$i] as $j => $service){
                        $feeService = $request->fee[$i][$j] ?? 0;

                        DB::table('tdnb')->insert([
                            'dnid'  => $dnid,
                            'braco' => $request->braco,
                            'dnnum' => $request->dnnum,
                            'formc' => $request->formc,
                            'dnlin' => $dnlin,

                            'serty' => $request->serty[$i][$j] ?? null,
                            'tofee' => $service,
                            'descr' => $request->descr[$i][$j] ?? null,

                            'gramt' => $feeService,
                            'odisp' => 0,
                            'odisa' => 0,
                            'net'   => $feeService,
                        ]);
                    }
                }
            }
                
            if($request->filled('sparepart')){
                foreach($request->sparepart as $i => $sparepart){
                    if(empty($sparepart)){
                        continue;
                    }

                    $qty = (float) ($request->quantity_sparepart[$i] ?? 0);
                    $price = (float) ($request->price[$i] ?? 0);

                    if($qty <= 0 && $price <= 0){
                        continue;
                    }

                    $gramt_sparepart = $qty * $price;
                    $odisa_sparepart = (float) ($request->odisa_sparepart[$i] ?? 0);
                    $odisp_sparepart = $gramt_sparepart > 0 ? ($odisa_sparepart / $gramt_sparepart) * 100 : 0;
                    $ntamt_sparepart = $gramt_sparepart - $odisa_sparepart;

                    DB::table('tdnc')->insert([
                        'dnid'   => $dnid,
                        'braco'  => $request->braco,
                        'dnnum'  => $request->dnnum,
                        'formc'  => $request->formc,
                        'opron'  => $sparepart,
                        'lotno'  => $request->lotnos[$i] ?? null,
                        'warco'  => $request->warco[$i] ?? null,
                        'locco'  => $request->locco[$i] ?? null,
                        'trqty'  => $qty,
                        'qunit'  => $request->qunit[$i] ?? null,
                        'price'  => $price,
                        'gramt'  => $gramt_sparepart,
                        'odisa'  => $odisa_sparepart,
                        'odisp'  => round($odisp_sparepart, 2),
                        'netbe'  => $ntamt_sparepart,
                        'descr'  => $request->descr_sparepart[$i] ?? null,
                    ]);

                    DB::table('stobw_tbl')
                        ->where('braco', $request->braco)
                        ->where('opron', $sparepart)
                        ->update([
                            'toqoh' => DB::raw("toqoh - {$qty}")
                        ]);

                    DB::table('stobl_tbl')
                        ->where('braco', $request->braco)
                        ->where('opron', $sparepart)
                        ->where('lotno', $request->lotnos[$i])
                        ->update([
                            'toqoh' => DB::raw("toqoh - {$qty}")
                        ]);
                }
            }

            DB::commit();
            return redirect()->route('delivery_note.index')->with('success', "DN \"$request->dnnum\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan DN:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($dnid)
    {
        $dn = DnHdr::with(['mcusmas'])->where('dnid', $dnid)->firstOrFail();

        $services = DB::table('tdna')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tdna.opron')
            ->where('tdna.dnid', $dnid)
            ->select(
                'tdna.*',
                'mpromas.prona'
            )
            ->orderBy('tdna.dnlin')
            ->get();

        $serviceFees = DB::table('tdnb')
            ->where('dnid', $dnid)
            ->orderBy('dnlin')
            ->get()
            ->groupBy('dnlin');

        $spareparts = DB::table('tdnc')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tdnc.opron')
            ->where('tdnc.dnid', $dnid)
            ->select(
                'tdnc.*',
                'mpromas.prona'
            )
            ->get();

        return view('teknik.delivery_note.delivery_note_detail', compact(
            'dn',
            'services',
            'serviceFees',
            'spareparts'
        ));
    }

    public function edit($dnid)
    {
        $dn = DnHdr::where('dnid', $dnid)->firstOrFail();

        $services = DB::table('tdna')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tdna.opron')
            ->where('tdna.dnid', $dnid)
            ->select('tdna.*', 'mpromas.prona')
            ->orderBy('tdna.dnlin')
            ->get();

        $serviceFees = DB::table('tdnb')
            ->where('dnid', $dnid)
            ->orderBy('dnlin')
            ->get()
            ->groupBy('dnlin');

        $spareparts = DB::table('tdnc')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tdnc.opron')
            ->where('tdnc.dnid', $dnid)
            ->select('tdnc.*', 'mpromas.prona')
            ->get();

        $depo = DB::table('mdepos')
            ->where('braco', auth()->user()->cabang)
            ->get();
        $customers = DB::table('mcusmas')
            ->where('braco', auth()->user()->cabang)
            ->get();

        $customer_detail = DB::table('mcusmas')
            ->where('cusno', $dn->cusno)
            ->first();

        $billAddress = collect([
            $customer_detail->address ?? null,
            $customer_detail->opost ?? null,
        ])
        ->filter()
        ->implode("\n");

        $shiptos = DB::table('mstmas')
            ->where('cusno', $dn->cusno)
            ->get();

        $selectedShipto = $shiptos
            ->where('shpto', $dn->delto)
            ->first();

        $serviceType = DB::table('msertyp')->get();
        $tax = DB::table('mtaxes')->first();

        $sparepartLotnos = [];

        foreach($spareparts as $sp){

            $lotnos = DB::table('stobl_tbl')
                ->where('braco', $dn->braco)
                ->where('opron', $sp->opron)
                ->where(function($q) use ($sp){
                    $q->where('toqoh', '>', 0)
                    ->orWhere('lotno', $sp->lotno);
                })
                ->get();

            $sparepartLotnos[$sp->opron] = $lotnos;
        }

        return view('teknik.delivery_note.delivery_note_edit', compact(
            'dn',
            'services',
            'serviceFees',
            'spareparts',
            'depo',
            'customers',
            'customer_detail',
            'billAddress',
            'shiptos',
            'selectedShipto',
            'serviceType',
            'tax',
            'sparepartLotnos'
        ));
    }

    public function update(Request $request, $dnid)
    {
        DB::beginTransaction();

        try {
            $dn = DnHdr::where('dnid', $dnid)->firstOrFail();

            // BALIKIN STOCK LAMA DULU
            $oldSpareparts = DB::table('tdnc')
                ->where('dnid', $dnid)
                ->get();

            foreach($oldSpareparts as $old){
                DB::table('stobw_tbl')
                    ->where('braco', $old->braco)
                    ->where('opron', $old->opron)
                    ->update([
                        'toqoh' => DB::raw("toqoh + {$old->trqty}")
                    ]);

                DB::table('stobl_tbl')
                    ->where('braco', $old->braco)
                    ->where('opron', $old->opron)
                    ->where('lotno', $old->lotno)
                    ->update([
                        'toqoh' => DB::raw("toqoh + {$old->trqty}")
                    ]);
            }

            // DELETE DETAIL LAMA
            DB::table('tdnb')->where('dnid', $dnid)->delete();
            DB::table('tdnc')->where('dnid', $dnid)->delete();
            DnDtl::where('dnid', $dnid)->delete();

            $gramt = ($request->totalservice ?? 0) + ($request->totalsparepart ?? 0);

            // UPDATE HEADER
            DnHdr::where('dnid', $dnid)->update([
                'bracoformc'       => $request->braco . $request->formc,
                'braco'            => $request->braco,
                'depo'             => $request->depo,
                'formc'            => $request->formc,
                'cusno'            => $request->cusno,
                'dnnum'            => $request->dnnum,
                'dndat'            => $request->dndat,
                'priod'            => $request->priod,
                'delto'            => $request->shpto,
                'quote'            => $request->quote,
                'curco'            => $request->curco,
                'crate'            => $request->crate,
                'vatax'            => $request->vatax,
                'gramt'            => $gramt,
                'odisa'            => $request->odisa ?? 0,
                'ntamt'            => $request->ntamt,
                'dpamt'            => $request->dpamt ?? 0,
                'txamt'            => $request->txamt,
                'blamt'            => $request->blamt,
                'total_service'    => $request->totalservice,
                'total_sparepart'  => $request->totalsparepart,
                'cuspo'            => $request->cuspo,
                'intxt'            => $request->intxt,
                'updated_at'       => now(),
                'updated_by'       => Auth::user()->name,
            ]);

            // INSERT ULANG SERVICE
            foreach ($request->opron as $i => $opron) {
                if(empty($opron)){
                    continue;
                }

                $dnlin = $i + 1;
                $gramt_service = array_sum($request->fee[$i] ?? []);
                $odisa_service = (float) ($request->odisa_service[$i] ?? 0);
                $ntamt_service = $gramt_service - $odisa_service;
                $odisp_service = $gramt_service > 0 ? ($odisa_service / $gramt_service) * 100 : 0;

                DnDtl::create([
                    'dnid'      => $dnid,
                    'braco'     => $request->braco,
                    'formc'     => $request->formc,
                    'dnnum'     => $request->dnnum,
                    'dnlin'     => $dnlin,
                    'tofee'     => 'SERVICE',
                    'opron'     => $opron,
                    'trqty'     => $request->quantity_service[$i] ?? 0,
                    'stdqu'     => $request->stdqu[$i] ?? null,
                    'lotno'     => $request->lotno[$i] ?? null,
                    'gramt'     => $gramt_service,
                    'odisa'     => $odisa_service,
                    'odisp'     => round($odisp_service, 2),
                    'netbe'     => $ntamt_service,
                ]);

                if(isset($request->tofee[$i])){
                    foreach($request->tofee[$i] as $j => $service){
                        if(empty($service)){
                            continue;
                        }

                        $feeService = $request->fee[$i][$j] ?? 0;

                        DB::table('tdnb')->insert([
                            'dnid'  => $dnid,
                            'braco' => $request->braco,
                            'dnnum' => $request->dnnum,
                            'formc' => $request->formc,
                            'dnlin' => $dnlin,
                            'serty' => $request->serty[$i][$j] ?? null,
                            'tofee' => $service,
                            'descr' => $request->descr[$i][$j] ?? null,
                            'gramt' => $feeService,
                            'odisp' => 0,
                            'odisa' => 0,
                            'net'   => $feeService,
                        ]);
                    }
                }
            }

            // INSERT ULANG SPAREPART + POTONG STOCK BARU
            if($request->filled('sparepart')){
                foreach($request->sparepart as $i => $sparepart){
                    if(empty($sparepart)){
                        continue;
                    }

                    $qty = (float) ($request->quantity_sparepart[$i] ?? 0);
                    $price = (float) ($request->price[$i] ?? 0);

                    if($qty <= 0 && $price <= 0){
                        continue;
                    }

                    $gramt_sparepart = $qty * $price;
                    $odisa_sparepart = (float) ($request->odisa_sparepart[$i] ?? 0);
                    $odisp_sparepart = $gramt_sparepart > 0 ? ($odisa_sparepart / $gramt_sparepart) * 100 : 0;
                    $ntamt_sparepart = $gramt_sparepart - $odisa_sparepart;

                    DB::table('tdnc')->insert([
                        'dnid'   => $dnid,
                        'braco'  => $request->braco,
                        'dnnum'  => $request->dnnum,
                        'formc'  => $request->formc,
                        'opron'  => $sparepart,
                        'lotno'  => $request->lotnos[$i] ?? null,
                        'warco'  => $request->warco[$i] ?? null,
                        'locco'  => $request->locco[$i] ?? null,
                        'trqty'  => $qty,
                        'qunit'  => $request->qunit[$i] ?? null,
                        'price'  => $price,
                        'gramt'  => $gramt_sparepart,
                        'odisa'  => $odisa_sparepart,
                        'odisp'  => round($odisp_sparepart, 2),
                        'netbe'  => $ntamt_sparepart,
                        'descr'  => $request->descr_sparepart[$i] ?? null,
                    ]);

                    DB::table('stobw_tbl')
                        ->where('braco', $request->braco)
                        ->where('opron', $sparepart)
                        ->update([
                            'toqoh' => DB::raw("toqoh - {$qty}")
                        ]);

                    DB::table('stobl_tbl')
                        ->where('braco', $request->braco)
                        ->where('opron', $sparepart)
                        ->where('lotno', $request->lotnos[$i])
                        ->update([
                            'toqoh' => DB::raw("toqoh - {$qty}")
                        ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('delivery_note.index')
                ->with('success', "DN \"$request->dnnum\" berhasil diupdate.");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Gagal update DN:', [
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel($dnid)
    {
        DB::beginTransaction();

        try {
            $dn = DnHdr::where('dnid', $dnid)->firstOrFail();

            if($dn->resta === 'C'){
                return back()->with('error', 'DN ini sudah dicancel.');
            }

            $spareparts = DB::table('tdnc')
                ->where('dnid', $dnid)
                ->get();

            foreach($spareparts as $sparepart){

                $qty = (float) ($sparepart->trqty ?? 0);

                if($qty <= 0){
                    continue;
                }

                DB::table('stobw_tbl')
                    ->where('braco', $sparepart->braco)
                    ->where('opron', $sparepart->opron)
                    ->update([
                        'toqoh' => DB::raw("toqoh + {$qty}")
                    ]);

                DB::table('stobl_tbl')
                    ->where('braco', $sparepart->braco)
                    ->where('opron', $sparepart->opron)
                    ->where('lotno', $sparepart->lotno)
                    ->update([
                        'toqoh' => DB::raw("toqoh + {$qty}")
                    ]);
            }

            DnHdr::where('dnid', $dnid)->update([
                'resta'      => 'C',
                'deleted_at' => now(),
                'deleted_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            DB::commit();

            return redirect()
                ->route('delivery_note.index')
                ->with('success', "DN \"$dn->dnnum\" berhasil dicancel dan stock sudah dikembalikan.");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Gagal cancel DN:', [
                'dnid' => $dnid,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function generateDnnum(Request $request)
    {
        $braco = auth()->user()->cabang;
        $formc = $request->formc;
        $depo = $request->depo;
        $dndat = $request->dndat;

        $year = Carbon::parse($dndat)->format('y');

        $last = DB::table('tdnh')
            ->where('braco', $braco)
            ->where('formc', $formc)
            ->where('depo', $depo)
            ->whereRaw("LEFT(dnnum,2) = ?", [$year])
            ->orderByDesc('dnnum')
            ->value('dnnum');

        if ($last) {
            $number = (int) substr($last, 2, 4) + 1;
        } else {
            $number = 1;
        }

        $running = str_pad($number, 4, '0', STR_PAD_LEFT);

        return $year . $running;
    }

    public function getBillAddress(Request $request)
    {
        $customer = DB::table('mcusmas')
            ->where('cusno', $request->cusno)
            ->first();

        $shiptos = DB::table('mstmas')
            ->where('cusno', $request->cusno)
            ->get();

        $billAddress = collect([
            $customer->address ?? '',
            $customer->opost ?? '',
        ])->filter()->implode(', ');

        return response()->json([
            'billadr' => $billAddress,
            'billcon' => $customer->billn ?? '',
            'shiptos' => $shiptos
        ]);
    }

    public function getCrate(Request $request)
    {
        $curco = $request->curco;

        $rate = DB::table('mcurco_tbl')
            ->where('curco', $curco)
            ->select('crate')
            ->first();

        return response()->json($rate);
    }

    public function getServiceType()
    {
        $serviceType = DB::table('msertyp')
            ->get();

        return response()->json($serviceType);
    }

    public function getLotnoSparepart(Request $request)
    {
        $lotnos = DB::table('stobl_tbl')
            ->where('opron', $request->sparepart)
            ->where('toqoh', '>', 0)

            ->select(
                'lotno',
                'warco',
                'locco',
                'toqoh',
                'qunit'
            )

            ->distinct()
            ->get();

        return response()->json($lotnos);
    }

    public function preview($id)
    {
        $dn = DnHdr::with([
            'mbranch',
            'mformcode',
            'mcusmas',
        ])->where('dnid', $id)->firstOrFail();

        $services = DB::table('tdna')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tdna.opron')
            ->where('tdna.dnid', $id)
            ->select('tdna.*', 'mpromas.prona')
            ->orderBy('tdna.dnlin')
            ->get();

        $serviceFees = DB::table('tdnb')
            ->where('dnid', $id)
            ->orderBy('dnlin')
            ->get()
            ->groupBy('dnlin');

        $spareparts = DB::table('tdnc')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tdnc.opron')
            ->where('tdnc.dnid', $id)
            ->select('tdnc.*', 'mpromas.prona')
            ->get();

        $shipto = DB::table('mstmas')
            ->where('cusno', $dn->cusno)
            ->where('shpto', $dn->delto)
            ->first();

        $html = view('teknik.delivery_note.delivery_note_print', compact(
            'dn',
            'services',
            'serviceFees',
            'spareparts',
            'shipto'
        ))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 8,
            'margin_bottom' => 45,
            'margin_left' => 8,
            'margin_right' => 8,
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output();
    }

    public function print($id)
    {
        $dn = DnHdr::where('dnid', $id)->firstOrFail();

        DB::table('tdnh')
            ->where('dnid', $id)
            ->update([
                'prctr' => DB::raw('COALESCE(prctr, 0) + 1')
            ]);

        $services = DB::table('tdna')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tdna.opron')
            ->where('tdna.dnid', $id)
            ->select('tdna.*', 'mpromas.prona')
            ->orderBy('tdna.dnlin')
            ->get();

        $serviceFees = DB::table('tdnb')
            ->where('dnid', $id)
            ->orderBy('dnlin')
            ->get()
            ->groupBy('dnlin');

        $spareparts = DB::table('tdnc')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'tdnc.opron')
            ->where('tdnc.dnid', $id)
            ->select('tdnc.*', 'mpromas.prona')
            ->get();

        $shipto = DB::table('mstmas')
            ->where('cusno', $dn->cusno)
            ->where('shpto', $dn->delto)
            ->first();

        $html = view('teknik.delivery_note.delivery_note_print', compact(
            'dn',
            'services',
            'serviceFees',
            'spareparts',
            'shipto'
        ))->render();

        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_top' => 8,
            'margin_bottom' => 45,
            'margin_left' => 8,
            'margin_right' => 8,
        ]);

        $mpdf->WriteHTML($html);

        $pdfContent = $mpdf->Output("{$dn->dnid}.pdf", "S");

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="'.$dn->dnid.'.pdf"');
    }
}