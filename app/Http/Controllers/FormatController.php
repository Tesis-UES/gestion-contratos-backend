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
            'code'         => 'required|string|max:50|unique:formats',
            'name'         => 'required|string|max:100',
            'is_template'  => 'required|boolean',
            'file'         => 'required|mimes:doc,docx,pdf',
        ]);

        $formatName = preg_replace('/\s+/', '-', $fields['code']);
        $fileExt = pathinfo($fields['file']->getClientOriginalName(), PATHINFO_EXTENSION);
        $file_url = $formatName.'.'.$fileExt;
        \Storage::disk('formats')->put($file_url, \File::get($fields['file'])); 
        
        $newFormat = Format::create([
            'code'         => $fields['code'],
            'name'         => $fields['name'],
            'is_template'  => $fields['is_template'],
            'file_url'     => $file_url,
        ]);

        $this->RegisterAction('El usuario ha ingresado un nuevo formato con id: '.$newFormat->id, 'medium');
        return response($newFormat, 200);
    }

    public function show($id)
    {
        $format = Format::findOrFail($id);
        $format->encoded_file = base64_encode(\Storage::disk('formats')->get($format->file_url));
        $this->RegisterAction('El usuario ha consultado el formato con id: '.$id);
        return response($format, 200);
    }
     
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'code'        => 'required|string|max:100',
            'name'        => 'required|string|max:100',
            'is_template' => 'required|boolean',
            'file'        => 'mimes:doc,docx,pdf',
        ]);

        $format = Format::findOrFail($id);

        if($format->is_template && $fields['code'] != $format->code) {
            return response(['message' => 'No se puede editar el codigo de un archivo plantilla'], 400);
        }

        if($format->is_template == true && $fields['is_template'] != true) {
            return response(['message' => 'No se puede eliminar la etiqueta de plantilla de un archivo'], 400);
        }

        if($fields['code'] != null) {
            $formatByCode = Format::withTrashed()->where('code', $fields['code'])->first();
            if($formatByCode && $formatByCode->id != $format->id) {
                return response(['message' => 'El valor del campo código ya ha sido ocupado'], 400);
            }
        }

        if($fields['file']){
            $formatName = preg_replace('/\s+/', '-', $fields['code']);
            $fileExt = pathinfo($fields['file']->getClientOriginalName(), PATHINFO_EXTENSION);
            $file_url = $formatName.'.'.$fileExt;
    
            \Storage::disk('formats')->delete($format->file_url);
            \Storage::disk('formats')->put($file_url, \File::get($fields['file'])); 
        } else {
            $file_url = $format->file_url;
        }


       $format->update([
            'name'      => $fields['name'],
            'file_url'  => $file_url,
        ]);

        $this->RegisterAction('El usuario ha actualizado el formato con id: '.$id, 'medium');
        return response($format, 200);
    }

    public function destroy($id)
    {
        $format = Format::findOrFail($id);
        if($format->is_template){
            return response(['message' => 'No se pueden eliminar los archivos plantilla'], 400);
        }
        $format->delete();
        \Storage::disk('formats')->delete($format->file_url);

        $this->RegisterAction('El usuario ha eliminado el formato con id: '.$id, 'medium');
        return response(null, 204);
    }
}
