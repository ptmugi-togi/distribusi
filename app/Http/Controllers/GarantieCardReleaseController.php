<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GarantieCardReleaseController extends Controller
{
    public function index(){
        $userBraco = Auth::user()->cabang;
        
        $gr = DB::table('mgamc')
            ->leftJoin('mcusmas', 'mgamc.cusno', '=', 'mcusmas.cusno')
            ->where('mgamc.braco', $userBraco)
            ->where('mgamc.formc', 'GR')
            ->get();

        $latestPeriod = DB::table('tperiode')
            ->where('braco', Auth::user()->cabang)
            ->orderByDesc('periode')
            ->first();

        $periodClosed = $latestPeriod && $latestPeriod->status === 'C';

        return view('teknik.garantie_card_release.garantie_card_release_index', compact('gr', 'userBraco', 'periodClosed'));
    }

    public function create(){

        return view('teknik.garantie_card_release.garantie_card_release_create');
    }

    public function store(Request $request)
    {
        $selectedItems = array_filter($request->items, function ($item) {
            return isset($item['selected']) && $item['selected'] == '1';
        });

        if (empty($selectedItems)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pilih minimal satu produk (centang checkbox) untuk disimpan!');
        }

        DB::beginTransaction();
        try {
            $braco = $request->braco ?? Auth::user()->cabang;
            $formc = $request->formc ?? 'GR';
            $tradt = Carbon::now()->format('Y-m-d');
            $year  = Carbon::parse($tradt)->format('y');

            $last = DB::table('mgamc')
                ->where('braco', $braco)
                ->where('formc', $formc)
                ->whereRaw("LEFT(refno, 2) = ?", [$year])
                ->orderBy('refno', 'desc')
                ->lockForUpdate()
                ->value('refno');

            if ($last) {
                $number = (int) substr($last, 2) + 1;
            } else {
                $number = 1;
            }

            $refno      = $year . str_pad($number, 4, '0', STR_PAD_LEFT);
            $customId   = $formc . $braco . $refno;
            $bracoformc = $braco . $formc;

            $userBraco = Auth::user()->cabang;
            $userName  = Auth::user()->name ?? '-';
            $userId    = Auth::id();
            $formc     = $request->input('formc', 'GR');

            $insertData = [];
            foreach ($selectedItems as $item) {
                $insertData[] = [
                    'id'         => $customId,
                    'braco'      => $userBraco,
                    'bracoformc' => $bracoformc,
                    'formc'      => $formc,
                    'refno'      => $refno,

                    'dobrc'     => $userBraco,
                    'dofcd'      => $request->dofcd,
                    'donom'      => $request->donom,
                    'dodat'      => Carbon::parse($request->dodat)->format('Y-m-d'),
                    'cusno'      => $request->cusno,
                    'cusna'      => $request->cusna,
                    'phone'      => $request->phone,
                    'shpto'      => $request->shpto,

                    'opron'      => $item['opron'],
                    'lotno'      => ($item['lotno'] !== '-') ? $item['lotno'] : null,

                    'gmcfr'      => $request->gmcfr,
                    'grdays'     => $request->grdays,
                    'gmcto'      => $request->gmcto,
                    'teraf'      => $request->teraf,
                    'trdays'     => $request->trdays,
                    'terat'      => $request->terat,
                    'terap'      => $request->terap ?? '-',
                    'terac'      => $request->terac ?? '-',
                    'noteh'      => $request->noteh,

                    'created_at' => Carbon::now(),
                    'created_by' => $userName,
                    'user_id'    => $userId,
                ];
            }

            DB::table('mgamc')->insert($insertData);

            DB::commit();

            return redirect()->route('gr.index')
                ->with('success', "Data Garantie Card Release $customId berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Gagal simpan GR', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan data ke mgamc: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $userBraco = Auth::user()->cabang;

        $items = DB::table('mgamc')
            ->leftJoin('mpromas', 'mpromas.opron', '=', 'mgamc.opron')
            ->leftJoin('mcusmas', 'mcusmas.cusno', '=', 'mgamc.cusno')
            ->leftJoin('tsisnh', function ($join) {
                $join->on('tsisnh.braco', '=', 'mgamc.braco')
                    ->on('tsisnh.formc', '=', 'mgamc.dofcd')
                    ->on('tsisnh.trano', '=', 'mgamc.donom');
            })
            ->leftJoin('mstmas', function ($join) {
                $join->on('mstmas.braco', '=', 'tsisnh.braco')
                    ->on('mstmas.cusno', '=', 'tsisnh.cusno')
                    ->on('mstmas.shpto', '=', 'tsisnh.shpto');
            })
            ->select(
                'mgamc.*',
                'mpromas.prona',
                'mcusmas.cusna',
                'tsisnh.tradt as dodat',
                'mstmas.deliveryaddress as address'
            )
            ->where('mgamc.id', $id)
            ->where('mgamc.braco', $userBraco)
            ->get();

        if ($items->isEmpty()) {
            return redirect()->route('gr.index')
                ->with('error', 'Data Garantie Card Release tidak ditemukan.');
        }

        $header = $items->first();

        return view('teknik.garantie_card_release.garantie_card_release_detail', compact('header', 'items'));
    }

    public function getDo(Request $request)
    {
        $userBraco = Auth::user()->cabang;
        $search = $request->input('q');

        $query = DB::table('tsisnh')
            ->leftJoin('mcusmas', 'mcusmas.cusno', '=', 'tsisnh.cusno')
            ->leftJoin('mstmas', function ($join) {
                $join->on('mstmas.braco', '=', 'tsisnh.braco')
                    ->on('mstmas.cusno', '=', 'tsisnh.cusno')
                    ->on('mstmas.shpto', '=', 'tsisnh.shpto');
            })
            ->select(
                'tsisnh.bbkid',
                'tsisnh.trano',
                'tsisnh.tradt',
                'tsisnh.cusno',
                'tsisnh.shpto',
                'mstmas.deliveryaddress',
                'mstmas.phone',
                'mcusmas.cusna'
            )
            ->where('tsisnh.braco', $userBraco)
            ->where('tsisnh.formc', 'DO');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('tsisnh.trano', 'LIKE', "%{$search}%")
                ->orWhere('tsisnh.bbkid', 'LIKE', "%{$search}%");
            });
        }

        $deliveryOrders = $query->orderBy('tsisnh.trano', 'desc')
            ->limit(30)
            ->get();

            
        $results = $deliveryOrders->map(function ($do) {
            $cusno = $do->cusno ?? '';
            $cusna = $do->cusna ?? '';
            
            return [
                'formc'   => 'DO',
                'dodate'  => Carbon::parse($do->tradt)->format('Y-m-d'),
                'dodate_formatted' => Carbon::parse($do->tradt)->format('d-m-Y'),
                'id'      => $do->bbkid,
                'trano'   => $do->trano,
                'text'    => 'DO - ' . $do->trano,
                'cusno'   => $cusno,
                'cusna'   => $cusna,
                'customer'=> implode(' - ', array_filter([$cusno, $cusna])),
                'shpto'   => $do->shpto ?? '',
                'address' => $do->deliveryaddress ?? '',
                'phone'   => $do->phone ?? '',
            ];
        });

        return response()->json(['results' => $results]);
    }

    public function getDetailDo(Request $request, $do)
    {
        $userBraco = Auth::user()->cabang;

        $detail = DB::table('toutg')
            ->join('mpromas', 'mpromas.opron', '=', 'toutg.opron')
            ->select(
                'toutg.trano',
                'toutg.opron',
                'mpromas.prona',
                DB::raw('COALESCE(TRIM(toutg.lotno), "-") as lotno'), // Ambil lotno langsung dari toutg
                DB::raw('SUM(toutg.trqty) as qty')
            )
            ->where(DB::raw('TRIM(toutg.bbkid)'), trim($do))
            ->groupBy(
                'toutg.trano', 
                'toutg.opron', 
                'mpromas.prona', 
                DB::raw('COALESCE(TRIM(toutg.lotno), "-")')
            )
            ->get();

        return response()->json($detail);
    }
}