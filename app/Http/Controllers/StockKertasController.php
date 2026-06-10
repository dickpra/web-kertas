<?php

namespace App\Http\Controllers;

use App\Models\StockKertas;
use Illuminate\Http\Request;

class StockKertasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = StockKertas::orderBy('id', 'desc');

        if ($search) {
            $query->where('no_roll', 'LIKE', '%' . $search . '%');
        }

        $data_kertas = $query->paginate(15)->appends(['search' => $search]);
        return view('master.index', compact('data_kertas', 'search'));
    }

    public function scanView()
    {
        return view('master.scan');
    }

    public function checkRollApi($no_roll)
    {
        $kertas = StockKertas::where('no_roll', $no_roll)->first();

        if ($kertas) {
            return response()->json(['success' => true, 'data' => $kertas]);
        }

        return response()->json(['success' => false, 'message' => 'Nomor Roll tidak ditemukan di database!']);
    }
}