<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tbolh;
use App\Models\Mvendor;
use App\Models\Mcurco;

class BlawbController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tbolh = tbolh:: with(['vendor', 'currency', 'branches', 'formcode'])->get();
        return view('purchasing.blawb.blawb_index', compact('tbolh'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vendors = Mvendor::select('supno','supna')->orderBy('supno')->get();
        $currencies = Mcurco::select('curco')->get();

        return view('purchasing.blawb.blawb_create', compact('vendors', 'currencies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
