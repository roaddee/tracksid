<?php

namespace App\Http\Controllers\Admin\Wilayah;

use App\Models\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegionRequest;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Region::desa())
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $edit   = '<a href="' . url('desa/' . $data->id . '/edit') . '" class="btn btn-sm btn-warning btn-sm"><i class="fas fa-pencil-alt"></i></a>';
                    $delete = '<button data-href="' . url('desa/' . $data->id) . '" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirm-delete"><i class="fas fa-trash"></i></button>';

                    return '<div class="btn btn-group">' . $edit . $delete . '</div>';
                })
                // ->editColumn('nama_desa', function ($data) {
                //     if ($data->nama_desa_baru) {
                //         return $data->nama_desa_baru . '<br><code title="Permendagri No. 77 Tahun 2019">' .$data->nama_desa . '</code>';
                //     }

                //     return $data->nama_desa;
                // })
                ->rawColumns(['action', 'nama_desa'])
                ->make(true);
        }
        
        return view('admin.wilayah.desa.index');
    }

    public function create()
    {
        return view('admin.wilayah.desa.create');
    }

    public function store(RegionRequest $request)
    {
        $input = $request->all();

        if (Region::create($input)) {
            return redirect('desa')->with('success', 'Data berhasil disimpan');
        }

        return back()->with('error', 'Data gagal disimpan');
    }

    public function edit($id)
    {
        return view('admin.wilayah.desa.edit', [
            'desa' => Region::desa()->findOrFail($id),
        ]);
    }

    public function update(RegionRequest $request, $id)
    {
        $input = $request->all();
        $desa = Region::desa()->find($id);

        if ($desa->nama_desa != $input['region_name']) {
            $input['new_region_name'] = $input['region_name'];

            unset($input['region_name']);
        }

        if ($desa->update($input)) {
            return redirect('desa')->with('success', 'Data berhasil diubah');
        }

        return back()->with('error', 'Data gagal diubah');
    }

    public function destroy($id)
    {
        if (Region::destroy($id)) {
            return redirect('desa')->with('success', 'Data berhasil dihapus');
        }

        return redirect('desa')->with('error', 'Data gagal dihapus');
    }
}