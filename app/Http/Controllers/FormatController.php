<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\Format;
use Illuminate\Http\Request;

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
            'name'  => 'required|string|max:100|unique:formats',
            'file'  => 'required|mimes:doc,docx,pdf',
        ]);

        $formatName = preg_replace('/\s+/', '-', $fields['name']);
        $fileExt = pathinfo($fields['file']->getClientOriginalName(), PATHINFO_EXTENSION);
        $fileUrl = $formatName.'.'.$fileExt;

        \Storage::disk('formats')->put($fileUrl, \File::get($fields['file'])); 
        
        $newFormat = Format::create([
            'name'      => $fields['name'],
            'fileUrl'   => $fileUrl,
        ]);

        $this->RegisterAction('El usuario ha ingresado un nuevo formato con id: '.$newFormat->id, 'medium');
        return response($newFormat, 200);
    }

    public function show($id)
    {
        $format = Format::findOrFail($id);
        $file = base64_encode(\Storage::disk('formats')->get($format->fileUrl));
        $this->RegisterAction('El usuario ha consultado el formato con id: '.$id);
        return response(['encodedFile' => $file], 200);
    }
     
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'name'  => 'required|string|max:100',
            'file'  => 'required|mimes:doc,docx,pdf',
        ]);

        $format = Format::findOrFail($id);
        $formatByName = Format::withTrashed()->where('name', $fields['name'])->first();
        if($formatByName && $formatByName->id != $format->id){
            return response(['message' => 'El valor del campo name ya está en uso'], 400);
        }

        $formatName = preg_replace('/\s+/', '-', $fields['name']);
        $fileExt = pathinfo($fields['file']->getClientOriginalName(), PATHINFO_EXTENSION);
        $fileUrl = $formatName.'.'.$fileExt;

        \Storage::disk('formats')->delete($format->fileUrl);
        \Storage::disk('formats')->put($fileUrl, \File::get($fields['file'])); 

       $format->update([
            'name'      => $fields['name'],
            'fileUrl'   => $fileUrl,
        ]);

        $this->RegisterAction('El usuario ha actualizado el formato con id: '.$id, 'medium');
        return response($format, 200);
    }

    public function destroy($id)
    {
        $format = Format::findOrFail($id);
        $format->delete();
        \Storage::disk('formats')->delete($format->fileUrl);

        $this->RegisterAction('El usuario ha eliminado el formato con id: '.$id, 'medium');
        return response(null, 204);
    }
}
