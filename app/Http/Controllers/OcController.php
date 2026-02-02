<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\OcHdr;
use App\Models\OcDtl;
use App\Models\Mcurco;


class OcController extends Controller
{
    public function index()
    {
        $userBraco = Auth::user()->cabang;

        $ochdr = OcHdr::with('mbranch', 'mcusmas', 'mformcode', 'msreno')
            ->where('braco', $userBraco)
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('marketing.oc_sa.oc_index', compact('ochdr', 'periodClosed'));
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

        return view('marketing.oc_sa.oc_create', compact('periodeAktif', 'minDate', 'branches', 'customer', 'sales', 'currency'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $ocid = $request->braco . $request->formc . $request->sorno;

            $bracoformc = $request->braco . $request->formc;

            // Simpan header
            OcHdr::create([
                'ocid' => $ocid,
                'bracoformc' => $bracoformc,
                'braco' => $request->braco,
                'formc' => $request->formc,
                'sorno' => $request->sorno,
                'sordt' => $request->sordt,
                'priod' => $request->priod,
                'cusno' => $request->cusno,
                'sreno' => $request->sreno,
                'topay' => $request->topay,
                'curco' => $request->curco,
                'crate' => $request->crate,
                'ebtyp' => $request->ebtyp,
                'edisp' => $request->edisp,
                'edisa' => $request->edisa,
                'nodeb' => $request->nodeb,
                'cuspo' => $request->cuspo,
                'dpper' => $request->dpper,
                'sqper' => $request->sqper,
                'sqtbr' => $request->sqtbr,
                'sqtsr' => $request->sqtsr,
                'delto' => $request->delto,
                'idprov' => $request->delto_prov,
                'idkab' => $request->delto_kab,
                'noteh' => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                OcDtl::create([
                    'ocid' => $ocid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'sorno' => $request->sorno,
                    'opron' => $useOpron,
                    'prona' => $request->prona[$i],
                    'qtyor' => $request->qtyor[$i],
                    'stdqu' => $request->stdqu[$i],
                    'rqeta' => $request->rqeta[$i],
                    'whetd' => $request->whetd[$i],
                    'price' => $request->price[$i],
                    'plist' => $request->plist[$i],
                    'odisp' => $request->odisp[$i],
                    'teknik' => $request->teknik[$i],
                    'srcog' => $request->srcog[$i],
                    'putama' => $request->putama[$i],
                    'noted' => $request->noted[$i],
                ]);
            }

            DB::commit();

            return redirect()->route('oc.index')->with('success', "Data OC \"$ocid\" berhasil disimpan.");
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
        $oc = OcHdr::with('ocdtls.mpromas')->findOrFail($id);

        $delto = DB::table('mstmas')
            ->where('braco', $oc->braco)
            ->where('cusno', $oc->cusno)
            ->where('shpto', $oc->delto)
            ->first();

        return view('marketing.oc_sa.oc_detail', compact('oc', 'delto'));
    }

    public function edit(string $id)
    {
        $oc = OcHdr::with('ocdtls.mpromas')->findOrFail($id);

        $delto = DB::table('mstmas')
            ->where('braco', $oc->braco)
            ->where('cusno', $oc->cusno)
            ->where('shpto', $oc->delto)
            ->first();

        $sales = DB::table('msreno')
            ->where('braco', $oc->braco)
            ->get();

        $branches = DB::table('mbranches')->get();
        
        $currency = DB::table('mcurco_tbl')->get();

        return view('marketing.oc_sa.oc_edit', compact('oc', 'delto', 'sales', 'currency', 'branches'));
    }

    public function update(Request $request, string $id)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $ocid = $request->braco . $request->formc . $request->sorno;

            // update header
            OcHdr::where('ocid', $ocid)->update([
                'sreno' => $request->sreno,
                'topay' => $request->topay,
                'cuspo' => $request->cuspo,
                'curco' => $request->curco,
                'crate' => $request->crate,
                'ebtyp' => $request->ebtyp,
                'edisp' => $request->edisp,
                'edisa' => $request->edisa,
                'nodeb' => $request->nodeb,
                'dpper' => $request->dpper,
                'sqper' => $request->sqper,
                'sqtbr' => $request->sqtbr,
                'sqtsr' => $request->sqtsr,
                'delto' => $request->delto,
                'noteh' => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // hapus detail
            OcDtl::where('ocid', $ocid)->delete();

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                OcDtl::create([
                    'ocid' => $ocid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'sorno' => $request->sorno,
                    'opron' => $useOpron,
                    'prona' => $request->prona[$i],
                    'qtyor' => $request->qtyor[$i],
                    'stdqu' => $request->stdqu[$i],
                    'rqeta' => $request->rqeta[$i],
                    'whetd' => $request->whetd[$i],
                    'price' => $request->price[$i],
                    'plist' => $request->plist[$i],
                    'odisp' => $request->odisp[$i],
                    'teknik' => $request->teknik[$i],
                    'srcog' => $request->srcog[$i],
                    'putama' => $request->putama[$i],
                    'noted' => $request->noted[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('oc.index')->with('success', "Data OC \"$ocid\" berhasil dirubah.");
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
        try {
            $oc = OcHdr::where('woid', $id)->firstOrFail();
            $oc->ocdtls()->delete();
            $oc->delete();

            return redirect()->route('oc.index')
                ->with('success', 'Data OC "'.$id.'" berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('oc.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, string $id)
    {
        try {
            $oc = OcHdr::where('ocid', $id)->firstOrFail();
            $oc->update(
                [
                    'resta' => 'C',
                    'cancd' => $request->cancd,
                    'reason' => $request->reason,
                    'cancp' => $request->cancp,
                    'updated_at' => now(),
                    'updated_by' => Auth::user()->name
                ]
            );
            return redirect()->route('oc.index')
                ->with('success', 'Data OC "'.$id.'" berhasil dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->route('oc.index')
                ->with('error', 'Gagal membatalkan data: ' . $e->getMessage());
        }
    }

    public function generateOcnum(Request $request)
    {
        $braco = auth()->user()->cabang;
        $formc = 'SA';
        $sordt = $request->sordt;

        $year = Carbon::parse($sordt)->format('y');

        $last = DB::table('tcoreh')
            ->where('braco', $braco)
            ->where('formc', $formc)
            ->whereRaw("LEFT(sorno,2) = ?", [$year])
            ->orderBy('sorno','desc')
            ->value('sorno');

        if ($last) {
            $number = (int)substr($last, 2) + 1;
        } else {
            $number = 1;
        }

        return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
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

    public function getSalesSplit($sqtbr)
    {
        $data = DB::table('msreno')
            ->where('braco', $sqtbr)
            ->orderBy('sreno')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getMstmasDelto(Request $request)
    {
        $data = DB::table('mstmas')
            ->where('braco', auth()->user()->cabang)
            ->where('cusno', $request->cusno)
            ->select('shpto')
            ->distinct()
            ->orderBy('shpto')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getMstmasDetail(Request $request)
    {
        $data = DB::table('mstmas')
            ->where('braco', auth()->user()->cabang)
            ->where('cusno', $request->cusno)
            ->where('shpto', $request->delto)
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data address tidak ditemukan.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
