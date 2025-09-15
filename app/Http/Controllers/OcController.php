<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mcusmas;
use App\Models\Msreno;
use App\Models\Mpgrup;
use App\Models\Mcls;

class OcController extends Controller
{
    public function index()
    {
        return view('master.oc', [
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }

    public function customerOc(){
        $cusmas=Mcusmas::all(); ?>
        <datalist id="customer_">
            <?php foreach($cusmas as $cusma){ ?>
            <option data-value="<?php echo $cusma->id; ?>" value="<?php echo $cusma->cusno.' ~ '.$cusma->cusna; ?>">
            <?php } ?>
        </datalist> <?php
    }
    public function renoOc(){
        $renos=Msreno::orderBy('sreno')->get(); ?>
        <datalist id="msrenosoc">
            <?php foreach($renos as $reno){ ?>
            <option data-value="<?php echo $reno->id; ?>" value="<?php echo $reno->sreno.' ~ '.$reno->srena.' ~ '.$reno->braco.' ~ '.$reno->steam; ?>">
            <?php } ?>
        </datalist> <?php
    }
    public function pgrupOc(){
        $mpgrup=Mpgrup::all();
        foreach($mpgrup as $pgrup){ ?>
            <option value="<?php echo $pgrup->pgrup ?>"><?php echo $pgrup->pgrup.' ~ '.$pgrup->descr ?></option>
        <?php }
    }
    public function clsOc(){
        $mcls=Mcls::all();
        foreach($mcls as $cls){ ?>
            <option value="<?php echo $cls->class ?>"><?php echo $cls->class.' ~ '.$cls->descr_cls ?></option>
        <?php }
    }

}
