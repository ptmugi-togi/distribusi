<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClosePeriodController extends Controller
{
    public function create()
    {
        $braco = Auth::user()->cabang;

        $periode = DB::table('tperiode')
            ->where('braco', $braco)
            ->where('status', 'O')
            ->orderBy('periode', 'DESC')
            ->value('periode');

        $formattedPeriode = $periode ? Carbon::createFromFormat('Ym', $periode)->format('m - Y') : '-';

        return view('closing.close_period.close_period_create', compact('braco', 'periode', 'formattedPeriode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'periode' => 'required',
            'close_period' => 'required|in:yes,no',
        ]);

        $user = Auth::user();
        $braco = $user->cabang;
        $periode = $request->input('periode');
        $closePeriod = $request->input('close_period');

        try {
            DB::transaction(function () use ($user, $braco, $periode, $closePeriod) {
                
                $bbmDetail = DB::table('tstord')
                    ->join('tstorh', 'tstord.bbmid', '=', 'tstorh.bbmid')
                    ->where('tstorh.braco', $braco)
                    ->where('tstorh.priod', $periode)
                    ->select(
                        'tstorh.braco',
                        'tstorh.warco',
                        'tstord.opron',
                        'tstord.locco',
                        'tstord.lotno',
                        DB::raw('SUM(tstord.trqty) as total_bbm')
                    )
                    ->groupBy('tstorh.braco', 'tstorh.warco', 'tstord.opron', 'tstord.locco', 'tstord.lotno')
                    ->get();

                $bbkDetail = DB::table('toutg')
                    ->join('tsisnh', 'toutg.bbkid', '=', 'tsisnh.bbkid')
                    ->where('tsisnh.braco', $braco)
                    ->where('tsisnh.priod', $periode)
                    ->select(
                        'tsisnh.braco',
                        'tsisnh.warco',
                        'toutg.opron',
                        'toutg.locco',
                        'toutg.lotno',
                        DB::raw('SUM(toutg.trqty) as total_bbk')
                    )
                    ->groupBy('tsisnh.braco', 'tsisnh.warco', 'toutg.opron', 'toutg.locco', 'toutg.lotno')
                    ->get();

                foreach ($bbmDetail as $item) {
                    DB::table('stobl_tbl')
                        ->where('braco', $item->braco)
                        ->where('warco', $item->warco)
                        ->where('opron', $item->opron)
                        ->where('locco', $item->locco)
                        ->where('lotno', $item->lotno)
                        ->increment('bbqoh', $item->total_bbm);
                }

                foreach ($bbkDetail as $item) {
                    DB::table('stobl_tbl')
                        ->where('braco', $item->braco)
                        ->where('warco', $item->warco)
                        ->where('opron', $item->opron)
                        ->where('locco', $item->locco)
                        ->where('lotno', $item->lotno)
                        ->decrement('bbqoh', $item->total_bbk);
                }

                $bbmWarco = $bbmDetail->groupBy(fn($i) => $i->braco.'-'.$i->warco.'-'.$i->opron);
                foreach ($bbmWarco as $group) {
                    $first = $group->first();
                    $qty = $group->sum('total_bbm');

                    DB::table('stobw_tbl')
                        ->where('braco', $first->braco)
                        ->where('warco', $first->warco)
                        ->where('opron', $first->opron)
                        ->increment('bbqoh', $qty);
                }

                $bbkWarco = $bbkDetail->groupBy(fn($i) => $i->braco.'-'.$i->warco.'-'.$i->opron);
                foreach ($bbkWarco as $group) {
                    $first = $group->first();
                    $qty = $group->sum('total_bbk');

                    DB::table('stobw_tbl')
                        ->where('braco', $first->braco)
                        ->where('warco', $first->warco)
                        ->where('opron', $first->opron)
                        ->decrement('bbqoh', $qty);
                }

                $bbmBraco = $bbmDetail->groupBy(fn($i) => $i->braco.'-'.$i->opron);
                foreach ($bbmBraco as $group) {
                    $first = $group->first();
                    $qty = $group->sum('total_bbm');

                    DB::table('stobb_tbl')
                        ->where('braco', $first->braco)
                        ->where('opron', $first->opron)
                        ->increment('bbqoh', $qty);
                }

                $bbkBraco = $bbkDetail->groupBy(fn($i) => $i->braco.'-'.$i->opron);
                foreach ($bbkBraco as $group) {
                    $first = $group->first();
                    $qty = $group->sum('total_bbk');

                    DB::table('stobb_tbl')
                        ->where('braco', $first->braco)
                        ->where('opron', $first->opron)
                        ->decrement('bbqoh', $qty);
                }

                if ($closePeriod === 'yes') {
                    DB::table('tperiode')
                        ->where('braco', $braco)
                        ->where('periode', $periode)
                        ->update([
                            'status' => 'C',
                            'tanggal_closing' => Carbon::now(),
                            'user' => $user->username ?? $user->name ?? 'system',
                        ]);

                    $nextPeriode = Carbon::createFromFormat('Ym', $periode)->addMonth()->format('Ym');

                    DB::table('tperiode')->insert([
                        'braco' => $braco,
                        'periode' => $nextPeriode,
                        'status' => 'O',
                        'tanggal_closing' => null,
                        'user' => $user->username ?? $user->name ?? 'system',
                        'tanggal_opening' => Carbon::now(),
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Closing period berhasil diproses dan periode baru telah dibuka!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}