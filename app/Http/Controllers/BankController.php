<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    use WorklogTrait;

    public function all()
    {
        $banks = Bank::orderBy('name', 'asc')->get();
        $this->RegisterAction('El usuario ha consultado el catalogo de bancos');
        return response($banks, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200|unique:banks'
        ]);

        $newBank = Bank::create($request->all());
        $this->registerAction('El usuario ha creado el banco con ID: '.$newBank->id, 'medium');
        return response($newBank, 201);
    }

    public function show($id)
    {
        $bank = Bank::where('id', $id)->firstOrFail();
        $this->RegisterAction('El usuario ha consultado el banco con ID: ' . $id);
        return response($bank, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:200|unique:banks'
        ]);

        $bank = Bank::where('id', $id)->firstOrFail();
        $bank->update($request->all());
        return response($bank, 200);
    }

    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        $this->RegisterAction('El usuario ha archivado el banco con ID: ' . $id);
        return response(null, 204);
    }
}
