<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\OcSbHdr;
use App\Models\OcSbDtl;
use App\Models\Mcurco;


class OcSbController extends Controller
{
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $ocsbhdr = OcSbHdr::with('mbranch', 'mcusmas', 'mformcode', 'msreno')
            ->where('braco', $userBraco)
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('marketing.oc_sb.oc_sb_index', compact('ocsbhdr', 'periodClosed'));
    }

    public function create()
    {
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

        $customer = DB::table('mcusmas')
                    ->where('braco', auth()->user()->cabang)
                    ->get();
        
        $sales = DB::table('msreno')
                    ->where('braco', auth()->user()->cabang)
                    ->get();

        $branches = DB::table('mbranches')->get();

        $currency = DB::table('mcurco_tbl')->get();

        $taxes = DB::table('mtaxes')
                    ->where('braco', auth()->user()->cabang)
                    ->first();

        $depo = DB::table('mdepos')
                    ->where('braco', auth()->user()->cabang)
                    ->get();

        return view('marketing.oc_sb.oc_sb_create', compact('periodeAktif', 'minDate', 'branches', 'customer', 'sales', 'currency', 'taxes', 'depo'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $ocsbid = $request->braco . $request->formc . $request->sorno;

            // Simpan header
            OcSbHdr::create([
                'ocsbid' => $ocsbid,
                'depo' => $request->depo,
                'braco' => $request->braco,
                'formc' => $request->formc,
                'sorno' => $request->sorno,
                'priod' => $request->priod,
                'sordt' => $request->sordt,
                'sreno' => $request->sreno,
                'cusno' => $request->cusno,
                'pcuto' => $request->pcuto,
                'nodeb' => $request->nodeb,
                'curco' => $request->curco,
                'crate' => $request->crate,
                'cuspo' => $request->cuspo,
                'gross' => $request->gross_hdr,
                'edisa' => $request->edisa_hdr,
                'odisa' => $request->odisa_hdr,
                'insfe' => $request->insfe,
                'vatax' => $request->vatax,
                'vtamt' => $request->vtamt,
                'billv' => $request->billv_hdr,
                'noteh' => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                OcSbDtl::create([
                    'ocsbid' => $ocsbid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'sorno' => $request->sorno,
                    'opron' => $useOpron,
                    'prona' => $request->prona[$i],
                    'qtyor' => $request->qtyor[$i],
                    'stdqu' => $request->stdqu[$i],
                    'price' => $request->price[$i],
                    'plist' => $request->plist[$i],
                    'odisp' => $request->odisp[$i],
                    'teknik' => $request->teknik[$i],
                    'putama' => $request->putama[$i],
                    'delto' => $request->delto[$i],
                    'noted' => $request->noted_installation[$i],
                ]);
            }

            if ($request->has('bom')) {
                foreach ($request->bom as $installIndex => $items) {
                    foreach ($items as $item) {
                        DB::table('tprojc')->insert([
                            'ocsbid' => $ocsbid,
                            'braco' => $request->braco,
                            'formc' => $request->formc,
                            'sorno' => $request->sorno,
                            'delto' => $request->delto[$installIndex],

                            'uopron' => $request->opron[$installIndex],
                            'opron' => $item['matno'],
                            'stdqu' => $item['unit'],
                            'trqty' => $item['qty'],
                            'delqt' => 0,
                        ]);
                    }
                }
            }

            if ($request->has('toppc')) {

                foreach ($request->toppc as $i => $percent) {

                    DB::table('tprojd')->insert([

                        'ocsbid'   => $ocsbid,
                        'braco'  => $request->braco,
                        'formc'  => $request->formc,
                        'sorno'  => $request->sorno,

                        'phase'  => $i + 1,
                        'descr'  => $request->descr[$i] ?? null,
                        'toppc'  => $percent,

                        'gross'  => $request->gross[$i] ?? 0,
                        'odisa'  => $request->odisa[$i] ?? 0,
                        'ntamt'  => $request->ntamt[$i] ?? 0,
                        'blamt'  => $request->blamt[$i] ?? 0,
                        'edisa'  => $request->edisa[$i] ?? 0,

                        'billd'  => $request->billd[$i] ?? null,

                        // QUOTA 1
                        'smqp1'  => $request->smqp1 ?? 0,
                        'smqtb1' => $request->smqtb1 ?? null,
                        'smqts1' => $request->smqts1 ?? null,

                        // QUOTA 2
                        'smqp2'  => $request->smqp2 ?? 0,
                        'smqtb2' => $request->smqtb2 ?? null,
                        'smqts2' => $request->smqts2 ?? null,

                        // QUOTA 3
                        'smqp3'  => $request->smqp3 ?? 0,
                        'smqtb3' => $request->smqtb3 ?? null,
                        'smqts3' => $request->smqts3 ?? null,

                        // QUOTA 4
                        'smqp4'  => $request->smqp4 ?? 0,
                        'smqtb4' => $request->smqtb4 ?? null,
                        'smqts4' => $request->smqts4 ?? null,

                        // QUOTA 5
                        'smqp5'  => $request->smqp5 ?? 0,
                        'smqtb5' => $request->smqtb5 ?? null,
                        'smqts5' => $request->smqts5 ?? null,

                        'noted'  => $request->noted_invoicing[$i] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('oc_sb.index')->with('success', "Data OC \"$ocsbid\" berhasil disimpan.");
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Gagal simpan OC:', ['error' => $e->getMessage()]);
                return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
            } 
    }

    public function show(string $id)
    {
        $ocsb = OcSbHdr::with('ocsbdtls.mpromas', 'ocsbdtls.ocsbhdr')->findOrFail($id);

        $bomList = DB::table('tprojc')
            ->join('mpromas', 'tprojc.opron', '=', 'mpromas.opron')
            ->where('tprojc.ocsbid', $id)
            ->select(
                'tprojc.*',
                'mpromas.prona'
            )
            ->get()
            ->groupBy('uopron');
        
        $detailsInvoicing = DB::table('tprojd')
            ->where('ocsbid', $id)
            ->orderBy('phase')
            ->get();

        $branches = DB::table('mbranches')->get();

        $salesList = DB::table('msreno')->get();

        $salesName = $salesList->pluck('srena', 'sreno')->toArray();

        return view('marketing.oc_sb.oc_sb_detail', compact( 'ocsb', 'bomList', 'detailsInvoicing', 'branches', 'salesName'));
    }

    public function edit(string $id)
    {
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

        $ocsb = OcSbHdr::with('ocsbdtls.mpromas', 'ocsbdtls.ocsbhdr')->findOrFail($id);

        $bomList = DB::table('tprojc')
            ->join('mpromas', 'tprojc.opron', '=', 'mpromas.opron')
            ->where('ocsbid', $id)
            ->select(
                'tprojc.*',
                'mpromas.prona'
            )
            ->get()
            ->groupBy('uopron');
        
        $detailsInvoicing = DB::table('tprojd')
            ->where('ocsbid', $id)
            ->orderBy('phase')
            ->get();

        $branches = DB::table('mbranches')->get();
        
        $currency = DB::table('mcurco_tbl')->get();

        return view('marketing.oc_sb.oc_sb_edit', compact('periodeAktif', 'minDate', 'ocsb', 'bomList', 'detailsInvoicing', 'currency', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $ocsbid = $request->braco . $request->formc . $request->sorno;

            // update header
            OcSbHdr::where('ocsbid', $ocsbid)->update([
                'insfe' => $request->insfe,
                'nodeb' => $request->nodeb,
                'edisa' => $request->edisa_hdr,
                'cuspo' => $request->cuspo,
                'noteh' => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // hapus detail
            OcSbDtl::where('ocsbid', $ocsbid)->delete();

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                OcSbDtl::create([
                    'ocsbid' => $ocsbid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'sorno' => $request->sorno,
                    'opron' => $useOpron,
                    'prona' => $request->prona[$i],
                    'qtyor' => $request->qtyor[$i],
                    'stdqu' => $request->stdqu[$i],
                    'price' => $request->price[$i],
                    'plist' => $request->plist[$i],
                    'odisp' => $request->odisp[$i],
                    'teknik' => $request->teknik[$i],
                    'putama' => $request->putama[$i],
                    'delto' => $request->delto[$i],
                    'noted' => $request->noted_installation[$i],
                ]);
            }

            DB::table('tprojc')
                ->where('ocsbid', $ocsbid)
                ->delete();

            if ($request->has('bom')) {
                foreach ($request->bom as $installIndex => $items) {
                    foreach ($items as $item) {
                        DB::table('tprojc')->insert([
                            'ocsbid' => $ocsbid,
                            'braco' => $request->braco,
                            'formc' => $request->formc,
                            'sorno' => $request->sorno,
                            'delto' => $request->delto[$installIndex],

                            'uopron' => $request->opron[$installIndex],
                            'opron' => $item['matno'],
                            'stdqu' => $item['unit'],
                            'trqty' => $item['qty'],
                            'delqt' => 0,
                        ]);
                    }
                }
            }

            DB::table('tprojd')
            ->where('ocsbid', $ocsbid)
            ->delete();

            if ($request->has('toppc')) {

                foreach ($request->toppc as $i => $percent) {

                    DB::table('tprojd')->insert([

                        'ocsbid'   => $ocsbid,
                        'braco'  => $request->braco,
                        'formc'  => $request->formc,
                        'sorno'  => $request->sorno,

                        'phase'  => $i + 1,
                        'descr'  => $request->descr[$i] ?? null,
                        'toppc'  => $percent,

                        'gross'  => $request->gross[$i] ?? 0,
                        'odisa'  => $request->odisa[$i] ?? 0,
                        'ntamt'  => $request->ntamt[$i] ?? 0,
                        'blamt'  => $request->blamt[$i] ?? 0,
                        'edisa'  => $request->edisa[$i] ?? 0,

                        'billd'  => $request->billd[$i] ?? null,

                        // QUOTA 1
                        'smqp1'  => $request->smqp1 ?? 0,
                        'smqtb1' => $request->smqtb1 ?? null,
                        'smqts1' => $request->smqts1 ?? null,

                        // QUOTA 2
                        'smqp2'  => $request->smqp2 ?? 0,
                        'smqtb2' => $request->smqtb2 ?? null,
                        'smqts2' => $request->smqts2 ?? null,

                        // QUOTA 3
                        'smqp3'  => $request->smqp3 ?? 0,
                        'smqtb3' => $request->smqtb3 ?? null,
                        'smqts3' => $request->smqts3 ?? null,

                        // QUOTA 4
                        'smqp4'  => $request->smqp4 ?? 0,
                        'smqtb4' => $request->smqtb4 ?? null,
                        'smqts4' => $request->smqts4 ?? null,

                        // QUOTA 5
                        'smqp5'  => $request->smqp5 ?? 0,
                        'smqtb5' => $request->smqtb5 ?? null,
                        'smqts5' => $request->smqts5 ?? null,

                        'noted'  => $request->noted_invoicing[$i] ?? null,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('oc_sb.index')->with('success', "Data OC \"$ocsbid\" berhasil dirubah.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal ubah OC:', ['error' => $e->getMessage()]);
            return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat mengubah: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $oc = OcSbHdr::where('ocsbid', $id)->firstOrFail();

            DB::table('tprojb')->where('ocsbid', $id)->delete();

            DB::table('tprojc')->where('ocsbid', $id)->delete();

            DB::table('tprojd')->where('ocsbid', $id)->delete();

            $oc->delete();

            DB::commit();

            return redirect()->route('oc_sb.index')
                ->with('success', 'OC "'.$id.'" berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('oc_sb.index')
                ->with('error', 'Gagal menghapus OC: '.$e->getMessage());
        }
    }

    public function cancel(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $oc = OcSbHdr::where('ocsbid', $id)->firstOrFail();

            $oc->update([
                'resta'  => 'C',
                'cancd'  => $request->cancd,
                'cancp'  => date('mY', strtotime($request->cancd)),
                'reason' => $request->reason,
                'updated_at' => now(),
                'updated_by' => auth()->user()->name,
            ]);

            DB::commit();

            return redirect()->route('oc_sb.index')
                ->with('success', 'OC "'.$id.'" berhasil dicancel.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('oc_sb.index')
                ->with('error', 'Gagal cancel OC: '.$e->getMessage());
        }
    }

    public function getCurrencyRate($curco)
    {
        $currency = Mcurco::find($curco);

        if ($currency) {
            return response()->json([
                'success' => true,
                'crate' => $currency->crate,
            ]);
        }

        return response()->json(['success' => false], 404);
    }

    public function getSubProduct(Request $request)
    {
        return DB::table('mbomd as b')
            ->join('mpromas as p', 'p.opron', '=', 'b.matno')
            ->where('b.opron', $request->opron)
            ->select(
                'b.matno',
                'p.prona',
                'b.rqqty',
                'b.stdqu'
            )
            ->orderBy('b.id')
            ->get();
    }

    public function getSalesByBranch(Request $request)
    {
        $sales = DB::table('msreno')
            ->where('braco', $request->branch)
            ->get(['sreno', 'srena']);

        return response()->json($sales);
    }

    // edit bom
    public function getBomByOc(Request $request)
    {
        $data = DB::table('tprojc')
            ->where('ocsbid', $request->ocsbid)
            ->where('opron', $request->opron)
            ->get();

        return response()->json($data);
    }
}
