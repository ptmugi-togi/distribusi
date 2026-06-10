<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\McHdr;
use App\Models\McDtl;
use App\Models\McPayment;

class MaintenanceContractController extends Controller
{

    public function index()
    {
        $userBraco = Auth::user()->cabang;
        
        $mc = McHdr::with('mbranch', 'mcusmas', 'mformcode')
            ->where('braco', $userBraco)
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('teknik.maintenance_contract.maintenance_contract_index', compact('mc', 'userBraco', 'periodClosed'));
    }

    public function create()
    {
        $userBraco = Auth::user()->cabang;
        
        $mc = McHdr::with('mbranch', 'mcusmas', 'mformcode')
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

        $tax = DB::table('mtaxes')
            ->where('braco', auth()->user()->cabang)
            ->first();

        return view('teknik.maintenance_contract.maintenance_contract_create', compact('mc', 'minDate', 'depo', 'customers', 'tax'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'refno' => 'required',
            'mcdat' => 'required|date',
            'depo' => 'required',
            'cusno' => 'required',
            'mcpriods' => 'required|date',
            'mcpriode' => 'required|date',

            'opron' => 'required|array',
            'gramt_product' => 'required|array',

            'toppc' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $mcid = $request->braco .  $request->depo . $request->formc . $request->refno;

            $odisp = $request->odisa / 100;

            $header = McHdr::create([
                'mcid' => $mcid,
                'braco' => $request->braco,
                'depo' => $request->depo,
                'formc' => $request->formc,
                'bracoformc' => $request->braco . $request->formc,
                'refno' => $request->refno,
                'mcdat' => $request->mcdat,
                'priod' => $request->priod,
                'cusno' => $request->cusno,
                'mcnom' => $request->cuspo,
                'curco' => $request->curco,
                'gramt' => $request->gramt ?? 0,
                'odisa' => $request->odisa ?? 0,
                'odisp' => $request->odisp ?? 0,
                'ntamt' => $request->ntamt ?? 0,
                'vatax' => $request->vatax ?? 0,
                'txamt' => $request->txamt ?? 0,
                'blamt' => $request->blamt ?? 0,
                'gmcfr' => $request->mcpriods,
                'gmcto' => $request->mcpriode,
                'noteh' => $request->intxt,

                'created_at' => now(),
                'created_by' => auth()->user()->name,
                'updated_at' => now(),
                'updated_by' => auth()->user()->name
            ]);

            foreach ($request->opron as $i => $opron) {
                if (!$opron) continue;

                McDtl::create([
                    'mcid' => $mcid,
                    'braco' => $request->braco,
                    'depo' => $request->depo,
                    'formc' => $request->formc,
                    'refno' => $request->refno,

                    'opron' => $opron,
                    'lotno' => $request->lotno[$i] ?? null,
                    'mcsts' => $request->mcsts[$i] ?? null,
                    'pvisi' => $request->pvisi[$i] ?? null,
                    'fvisi' => $request->fvisi[$i] ?? null,
                    'price' => $request->gramt_product[$i] ?? 0,

                    'shpto' => $request->shpto[$i] ?? null,
                    'add01' => $request->add01[$i] ?? null,
                    'add02' => $request->add02[$i] ?? null,
                    'add03' => $request->add03[$i] ?? null,
                    'add04' => $request->add04[$i] ?? null,
                    'city' => $request->city[$i] ?? null,
                    'delcon' => $request->delcon[$i] ?? null,
                    'phone' => $request->phone[$i] ?? null,
                    'noted' => $request->noted[$i] ?? null,
                ]);
            }

            foreach ($request->toppc as $i => $toppc) {
                if ($toppc === null || $toppc === '') continue;

                McPayment::create([
                    'mcid' => $mcid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'depo' => $request->depo,
                    'refno' => $request->refno,

                    'phase' => $i + 1,
                    'descr' => $request->desc[$i] ?? null,
                    'toppc' => $toppc,

                    'gramt' => $request->gramt_termin[$i] ?? 0,
                    'odisa' => $request->odisa_termin[$i] ?? 0,
                    'odisp' => $request->odisp_termin[$i] ?? 0,
                    'ntamt' => $request->ntamt_termin[$i] ?? 0,
                    'vatax' => $request->vatax ?? 0,
                    'txamt' => $request->txamt_termin[$i] ?? 0,
                    'blamt' => $request->blamt_termin[$i] ?? 0,
                    'billd' => $request->billd[$i] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('maintenance_contract.index')
                ->with('success', "Data \"$mcid\" berhasil disimpan.");

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Gagal simpan MC:', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($mcid)
    {
        $mc = McHdr::with([
            'mcdtls.mpromas',
            'mcphase',
            'mcusmas',
            'mbranch',
        ])->where('mcid', $mcid)->firstOrFail();

        return view('teknik.maintenance_contract.maintenance_contract_detail', compact('mc'));
    }

    public function edit($mcid)
    {
        $mc = McHdr::with(['mcdtls.mpromas', 'mcphase'])
            ->where('mcid', $mcid)
            ->firstOrFail();

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

        $tax = DB::table('mtaxes')
            ->where('braco', auth()->user()->cabang)
            ->first();

        return view('teknik.maintenance_contract.maintenance_contract_edit', compact('mc', 'minDate', 'depo', 'customers', 'tax'));
    }

    public function update(Request $request, $mcid)
    {
        $request->validate([
            'refno' => 'required',
            'mcdat' => 'required|date',
            'depo' => 'required',
            'cusno' => 'required',
            'mcpriods' => 'required|date',
            'mcpriode' => 'required|date',

            'opron' => 'required|array',
            'gramt_product' => 'required|array',

            'toppc' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $mc = McHdr::where('mcid', $mcid)->firstOrFail();

            $mc->update([
                'braco' => $request->braco,
                'depo' => $request->depo,
                'formc' => $request->formc,
                'bracoformc' => $request->braco . $request->formc,
                'refno' => $request->refno,
                'mcdat' => $request->mcdat,
                'priod' => $request->priod,
                'cusno' => $request->cusno,
                'mcnom' => $request->cuspo,
                'curco' => $request->curco,

                'gramt' => $request->gramt ?? 0,
                'odisa' => $request->odisa ?? 0,
                'odisp' => $request->odisp ?? 0,
                'ntamt' => $request->ntamt ?? 0,
                'vatax' => $request->vatax ?? 0,
                'txamt' => $request->txamt ?? 0,
                'blamt' => $request->blamt ?? 0,

                'gmcfr' => $request->mcpriods,
                'gmcto' => $request->mcpriode,
                'noteh' => $request->intxt,

                'updated_at' => now(),
                'updated_by' => auth()->user()->name,
            ]);

            McDtl::where('mcid', $mcid)->delete();
            McPayment::where('mcid', $mcid)->delete();

            foreach ($request->opron as $i => $opron) {
                if (!$opron) continue;

                McDtl::create([
                    'mcid' => $mcid,
                    'braco' => $request->braco,
                    'depo' => $request->depo,
                    'formc' => $request->formc,
                    'refno' => $request->refno,

                    'opron' => $opron,
                    'lotno' => $request->lotno[$i] ?? null,
                    'mcsts' => $request->mcsts[$i] ?? null,
                    'pvisi' => $request->pvisi[$i] ?? null,
                    'fvisi' => $request->fvisi[$i] ?? null,
                    'price' => $request->gramt_product[$i] ?? 0,

                    'shpto' => $request->shpto[$i] ?? null,
                    'add01' => $request->add01[$i] ?? null,
                    'add02' => $request->add02[$i] ?? null,
                    'add03' => $request->add03[$i] ?? null,
                    'add04' => $request->add04[$i] ?? null,
                    'city' => $request->city[$i] ?? null,
                    'delcon' => $request->delcon[$i] ?? null,
                    'phone' => $request->phone[$i] ?? null,
                    'noted' => $request->noted[$i] ?? null,
                ]);
            }

            foreach ($request->toppc as $i => $toppc) {
                if ($toppc === null || $toppc === '') continue;

                McPayment::create([
                    'mcid' => $mcid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'depo' => $request->depo,
                    'refno' => $request->refno,

                    'phase' => $i + 1,
                    'descr' => $request->desc[$i] ?? null,
                    'toppc' => $toppc,

                    'gramt' => $request->gramt_termin[$i] ?? 0,
                    'odisa' => $request->odisa_termin[$i] ?? 0,
                    'odisp' => $request->odisp_termin[$i] ?? 0,
                    'ntamt' => $request->ntamt_termin[$i] ?? 0,
                    'vatax' => $request->vatax ?? 0,
                    'txamt' => $request->txamt_termin[$i] ?? 0,
                    'blamt' => $request->blamt_termin[$i] ?? 0,
                    'billd' => $request->billd[$i] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('maintenance_contract.index')
                ->with('success', "Data \"$mcid\" berhasil diupdate.");

        } catch (\Throwable $e) {
            DB::rollBack();

            \Log::error('Gagal update MC:', ['error' => $e->getMessage()]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cancel($mcid)
    {
        DB::beginTransaction();

        try {
            $mc = McHdr::where('mcid', $mcid)->firstOrFail();

            if($mc->resta === 'C'){
                return back()->with('error', 'MC ini sudah dicancel.');
            }

            McHdr::where('mcid', $mcid)->update([
                'resta'      => 'C',
                'canceled_at' => now(),
                'canceled_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            DB::commit();

            return redirect()
                ->route('maintenance_contract.index')
                ->with('success', "MC \"$mc->mcid\" berhasil dicancel.");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Gagal cancel MC:', [
                'mcid' => $mcid,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function getCustomerAddress(Request $request)
    {
        $customer = DB::table('mcusmas')
            ->where('cusno', $request->cusno)
            ->first();

        $shiptos = DB::table('mstmas')
            ->where('cusno', $request->cusno)
            ->get();

        $address = collect([
            $customer->offad ?? '',
            $customer->offad2 ?? '',
            $customer->offad3 ?? '',
            $customer->offad4 ?? '',
            $customer->offcy ?? '',
        ])->filter()->implode(', ');

        return response()->json([
            'cusna' => $customer->cusna,
            'address' => $address,
            'offad' => $customer->offad ?? '',
            'offad2' => $customer->offad2 ?? '',
            'offad3' => $customer->offad3 ?? '',
            'offad4' => $customer->offad4 ?? '',
            'offcy' => $customer->offcy ?? '',
            'offph' => $customer->offph ?? '',
            'billcon' => $customer->contp ?? '',
            'shiptos' => $shiptos
        ]);
    }
}