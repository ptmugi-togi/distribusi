<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\BpbHdr;
use App\Models\BpbDtl;
use App\Models\Mwarco;

class BpbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bpbhdr = BpbHdr::all();

        return view('logistic.bpb.bpb_index', compact('bpbhdr'));
    }
    
    public function generateReqno(Request $request)
    {
        $braco = auth()->user()->cabang;
        $formc = $request->formc;
        $year = date('y');

        $last = DB::table('tsreqh')
            ->where('braco', $braco)
            ->where('formc', $formc)
            ->whereRaw("LEFT(reqno,2) = ?", [$year])
            ->orderBy('reqno','desc')
            ->value('reqno');

        if ($last) {
            $number = (int)substr($last, 2) + 1;
        } else {
            $number = 1;
        }

        return $year . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function getWarcoDetail($code)
    {
        $warco = DB::table('mwarco_tbl')->where('warco', $code)->first();

        return response()->json([
            'contp' => $warco->contp ?? '',
            'address' => $warco->address ?? '',
            'warna' => $warco->warna ?? ''
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
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

        $mbranch = DB::table('mbranches')->get();

        $mwarco = DB::table('mwarco_tbl')
            ->where('braco', auth()->user()->cabang)
            ->get();

        $defaultDelCo = $mwarco->first()->warco ?? null;

        return view('logistic.bpb.bpb_create', compact('periodeAktif', 'minDate', 'mbranch', 'mwarco', 'defaultDelCo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $bpbid = $request->braco . $request->formc . $request->reqno;

            // Simpan header
            BpbHdr::create([
                'bpbid' => $bpbid,
                'braco' => $request->braco,
                'warco' => $request->warco,
                'formc' => $request->formc,
                'reqno' => $request->reqno,
                'reqdt' => $request->reqdt,
                'sorfc' => $request->sorfc,
                'sorno' => $request->sorno,
                'rqfor' => $request->rqfor,
                'reqto' => $request->reqto,
                'reqtn' => $request->reqtn,
                'delto' => $request->delto,
                'delco' => $request->delco,
                'warna' => $request->warna,
                'contp' => $request->contp,
                'noteh' => $request->noteh,
                'created_at' => now(),
                'created_by' => Auth::user()->name,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                BpbDtl::create([
                    'bpbid' => $bpbid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'reqno' => $request->reqno,
                    'opron' => $useOpron,
                    'rqqty' => $request->rqqty[$i],
                    'eariv' => $request->eariv[$i],
                    'aloka' => $request->aloka[$i],
                    'noted' => $request->noted[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('bpb.index')->with('success', "Data BPB \"$bpbid\" berhasil disimpan.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal simpan BPB:', ['error' => $e->getMessage()]);
            return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bpb = BpbHdr::with('bpbdtls', 'mwarco', 'mpromas')->findOrFail($id);
        return view('logistic.bpb.bpb_detail', compact('bpb'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // ambil header
        $bpb = BpbHdr::with('bpbdtls')->findOrFail($id);

        return view('logistic.bpb.bpb_edit', compact('bpb'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $bpbid = $request->braco . $request->formc . $request->reqno;

            // update header
            BpbHdr::where('bpbid', $bpbid)->update([
                'noteh' => $request->noteh,
                'updated_at' => now(),
                'updated_by' => Auth::user()->name,
            ]);

            // hapus detail
            BpbDtl::where('bpbid', $bpbid)->delete();

            // Loop tiap detail barang
            foreach ($request->opron as $i => $useOpron) {
                BpbDtl::create([
                    'bpbid' => $bpbid,
                    'braco' => $request->braco,
                    'formc' => $request->formc,
                    'reqno' => $request->reqno,
                    'opron' => $useOpron,
                    'rqqty' => $request->rqqty[$i],
                    'eariv' => $request->eariv[$i],
                    'aloka' => $request->aloka[$i],
                    'noted' => $request->noted[$i],
                ]);
            }

            DB::commit();
            return redirect()->route('bpb.index')->with('success', "Data BPB \"$bpbid\" berhasil dirubah.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Gagal ubah BPB:', ['error' => $e->getMessage()]);
            return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat mengubah: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bpb = BpbHdr::where('bpbid', $id)->firstOrFail();
            $bpb->bpbdtls()->delete();
            $bpb->delete();

            return redirect()->route('bpb.index')
                ->with('success', 'Data BPB "'.$id.'" berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('bpb.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
