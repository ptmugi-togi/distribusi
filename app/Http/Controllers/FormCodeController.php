<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Models\Mformcode;
use Yajra\DataTables\DataTables;

class FormCodeController extends Controller
{
    public function index()
    {
        $formc=DB::table('mformcode_tbl')
            ->select('mformcode_tbl.*')
            ->where('mformcode_tbl.braco', auth()->user()->cabang)
            ->get();

        return view('master.mformc.mformc_index', compact('formc'));
    }

    public function create(Request $request)
    {
        $branch = DB::table('mbranches')
            ->select('mbranches.*')
            ->get();

        return view('master.mformc.mformc_create', compact('branch'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'formc'   => 'required|unique:mformcode_tbl,formc',
            ], [
                'formc.unique' => 'Form Code sudah digunakan.',
            ]);

            $data = $request->except(['_token']);

            foreach ($data as $key => $value) {
                if (
                    is_string($value)
                ) {
                    $data[$key] = mb_strtoupper(trim($value), 'UTF-8');
                }
            }
 
            $data['bracoformc'] = ($data['braco'] ?? '') . ($data['formc'] ?? '');

            Mformcode::create([
                'bracoformc'        => $data['bracoformc'] ?? null,
                'braco'             => $data['braco'] ?? null,
                'formc'             => $data['formc'] ?? null,
                'descr'             => $data['descr'] ?? null,
                'pos1'              => $data['pos1'] ?? null,
                'name1'             => $data['name1'] ?? null,
                'pos2'              => $data['pos2'] ?? null,
                'name2'             => $data['name2'] ?? null,
                'pos3'              => $data['pos3'] ?? null,
                'name3'             => $data['name3'] ?? null,
                'pos4'              => $data['pos4'] ?? null,
                'name4'             => $data['name4'] ?? null,
                'docd1'             => $data['docd1'] ?? null,
                'docd2'             => $data['docd2'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('formc.index')
                ->with('success', "Data Form Code \"{$data['formc']}\" berhasil disimpan.");

        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Gagal simpan data form code:', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan: '.$e->getMessage());
        }
    }

    public function show(string $id)
    {
        $formc = Mformcode::findOrFail($id);

        return view('master.mformc.mformc_detail', compact('formc'));
    }

    public function edit(string $id)
    {
        $formc = Mformcode::findOrFail($id);

        return view('master.mformc.mformc_edit', compact('formc'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'formc' => 'required',
            ]);

            $formc = Mformcode::findOrFail($id);

            $data = $request->except(['_token', '_method', 'id']);

            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data[$key] = mb_strtoupper(trim($value), 'UTF-8');
                }
            }

            $newBracoformc =($data['braco'] ?? '') .($data['formc'] ?? '');

            $duplicate = Mformcode::where('bracoformc', $newBracoformc)
                ->where('bracoformc', '!=', $id)
                ->exists();

            if ($duplicate) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        "Form Code \"{$data['formc']}\" sudah digunakan."
                    );
            }

            $formc->update([
                'bracoformc' => $newBracoformc,
                'braco'      => $data['braco'] ?? null,
                'formc'      => $data['formc'] ?? null,
                'descr'      => $data['descr'] ?? null,
                'pos1'       => $data['pos1'] ?? null,
                'name1'      => $data['name1'] ?? null,
                'pos2'       => $data['pos2'] ?? null,
                'name2'      => $data['name2'] ?? null,
                'pos3'       => $data['pos3'] ?? null,
                'name3'      => $data['name3'] ?? null,
                'pos4'       => $data['pos4'] ?? null,
                'name4'      => $data['name4'] ?? null,
                'docd1'      => $data['docd1'] ?? null,
                'docd2'      => $data['docd2'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('formc.index')
                ->with(
                    'success',
                    "Data Form Code \"{$data['formc']}\" berhasil diubah."
                );

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Gagal update form code:', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Terjadi kesalahan: ' . $e->getMessage()
                );
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $formc = Mformcode::findOrFail($id);

            $formCode = $formc->bracoformc;

            $formc->delete();

            DB::commit();

            return redirect()
                ->route('formc.index')
                ->with('success', "Form Code \"$formCode\" berhasil dihapus.");

        } catch (\Exception $e) {

            DB::rollBack();

            \Log::error('Gagal hapus form code:', [
                'error' => $e->getMessage()
            ]);

            return back()
                ->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }
}
