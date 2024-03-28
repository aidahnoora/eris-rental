<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(100);

        return new GeneralResource(true, 'List Data Customers', $customers);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'identitas' => 'required',
            'umur' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::create([
            'nama' => $request->nama,
            'identitas' => $request->identitas,
            'umur' => $request->umur,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return new GeneralResource(true, 'Data Customer Berhasil Ditambahkan!', $customer);
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        return new GeneralResource(true, 'Detail Data Customer!', $customer);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'identitas' => 'required',
            'umur' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $customer = Customer::find($id);

        $customer->update([
            'nama' => $request->nama,
            'identitas' => $request->identitas,
            'umur' => $request->umur,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return new GeneralResource(true, 'Data Customer Berhasil Diubah!', $customer);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        $customer->delete();

        return new GeneralResource(true, 'Data Customer Berhasil Dihapus!', null);
    }
}
