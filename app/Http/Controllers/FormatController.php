<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Format;
use Illuminate\Http\Request;
use App\Constants\ContractType as ConstantsContractType;
use Carbon\Carbon;

class FormatController extends Controller
{
    use WorklogTrait;

    public function index()
    {
        $formats = Format::all();
        $this->RegisterAction('El usuario ha consultado el catalogo de formatos');
        return response($formats, 200);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name'         => 'required|string|max:100',
            'file'         => 'required|mimes:doc,docx,pdf',
            'type'         => 'required|string|max:100',
            'type_contract'   => 'string|max:100',
        ]);
        $unique = uniqid('Format_');
        switch ($fields['type']) {
            case ConstantsContractType::TA:
                $format = Format::where('type', ConstantsContractType::TA)->where('is_active', true)->where('type_contract', $fields['type_contract'])->first();
                break;
            case ConstantsContractType::TI:
                $format = Format::where('type', ConstantsContractType::TI)->where('is_active', true)->where('type_contract', $fields['type_contract'])->first();
                break;
            case ConstantsContractType::SPNP:
                $format = Format::where('type', ConstantsContractType::SPNP)->where('is_active', true)->where('type_contract', $fields['type_contract'])->first();
                break;
            default:
                $fileName = $unique."-".$request->file('file')->getClientOriginalName();
                \Storage::disk('formats')->put($fileName, \File::get($fields['file']));
                $newFormat = Format::create([
                    'name'              => $fields['name'],
                    'file_url'          => $fileName,
                    'type'              => $fields['type'],
                ]);
                $this->RegisterAction('El usuario ha ingresado un nuevo formato con id: ' . $newFormat->id, 'medium');
                return response($newFormat, 200);
                break;
        }
        $format->is_active = false;
        $format->save();
        $fileName = $unique."-".$request->file('file')->getClientOriginalName();
        \Storage::disk('formats')->put($fileName, \File::get($fields['file']));

        $newFormat = Format::create([
            'name'              => $fields['name'],
            'file_url'          => $fileName,
            'type'              => $fields['type'],
            'type_contract'     => $fields['type_contract'],
        ]);

        $this->RegisterAction('El usuario ha ingresado un nuevo formato con id: ' . $newFormat->id, 'medium');
        return response($newFormat, 200);
    }

    public function show($id)
    {
        $format = Format::findOrFail($id);
        $format->encoded_file = base64_encode(\Storage::disk('formats')->get($format->file_url));
        $this->RegisterAction('El usuario ha consultado el formato con id: '.$id);
        return response($format->encoded_file, 200);
    }

}
