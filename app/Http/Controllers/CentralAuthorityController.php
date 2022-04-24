<?php

namespace App\Http\Controllers;

use App\Http\Traits\WorklogTrait;
use App\Models\CentralAuthority;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;

class CentralAuthorityController extends Controller
{
    use WorklogTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $authorities = CentralAuthority::all();
        $this->RegisterAction('El usuario ha consultado el catalogo de autoridades centrales');
        return response($authorities, 200);
    }

    public function changeStatus($id)
    {
        $centralAuthority = CentralAuthority::findOrFail($id);
        $centralAuthority->status = !$centralAuthority->status;
        return response($centralAuthority, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'position'      => 'required|string|max:120',
            'firstName'     => 'required|string|max:60',
            'middleName'    => 'string|max:60',
            'lastName'      => 'required|string|max:120',
            'dui'           => 'required|string|max:20',
            'nit'           => 'required|string|max:20',
            'birth_date'    => 'required|date|before:today',
            'start_period'  => 'required|date',
            'end_period'    => 'required|date|after:start_period',
            'profession'    => 'required|string',
            'reading_signature' => 'required|string',
        ]);
        $newAuthority = new  CentralAuthority ($request->all());
        $newAuthority->text_dui = $this->duiToText($newAuthority->dui);
        $newAuthority->text_nit = $this->nitToText($newAuthority->nit);
        $newAuthority->save();

        $this->RegisterAction('El usuario ha creado la autoridad central con ID: ' . $newAuthority['id'],  "high");
        return response($newAuthority, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CentralAuthority  $centralAuthority
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $authority = CentralAuthority::findOrFail($id);
        $this->RegisterAction('El usuario ha visto la autoridad central con ID: ' . $id);
        return response($authority, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CentralAuthority  $centralAuthority
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'position'    => 'required|string|max:120',
            'firstName'   => 'required|string|max:60',
            'middleName'  => 'string|max:60',
            'lastName'    => 'required|string|max:120',
            'dui'         => 'required|string|max:20',
            'nit'         => 'required|string|max:20',
            'birth_date'  => 'required|date|before:today',
            'start_period'=> 'required|date',
            'end_period'  => 'required|date|after:start_period',
            'profession'    => 'required|string',
            'reading_signature' => 'required|string',
        ]);

        $authority = CentralAuthority::findOrFail($id);
        $authority->update($request->all());
        $authority->text_dui = $this->duiToText($authority->dui);
        $authority->text_nit = $this->nitToText($authority->nit);
        $authority->save();
        $this->RegisterAction('El usuario ha actualizado la autoridad central con ID : ' . $id, "high");
        return response($authority, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CentralAuthority  $centralAuthority
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $authority = CentralAuthority::findOrFail($id);
        $authority->delete();

        $this->RegisterAction('El usuario ha borrado la autoridad central con ID: ' . $id, "high");
        return response(null, 204);
    }

    public function duiToText($dui){
        $formatter = new NumeroALetras();
        if (substr($dui,0,-9) == 0 && substr($dui,1,-8) == !0) {
          return  $textDui = "CERO ".$formatter->toString(substr($dui,0,-2))."GUION ".$formatter->toString(substr($dui,-1))."";
        } if(substr($dui,1,-8) == 0 && substr($dui,2,-7) == !0) {
            return $textDui = "CERO CERO ".$formatter->toString(substr($dui,0,-2))."GUION ".$formatter->toString(substr($dui,-1))."";
        }if(substr($dui,2,-7) == 0 && substr($dui,3,-6) == !0){
            return $textDui = "CERO CERO CERO ".$formatter->toString(substr($dui,0,-2))."GUION ".$formatter->toString(substr($dui,-1))."";
        }else{
            return $textDui = "CERO CERO CERO CERO ".$formatter->toString(substr($dui,0,-2))."GUION ".$formatter->toString(substr($dui,-1))."";
        }
    }

    public function nitToText($nit){
        $formatter = new NumeroALetras();
        $nitParts = explode("-",$nit);
        
         if (substr($nitParts[0],0,-3) == 0 && substr($nitParts[0],1,-2) == !0) {
            $part1 ="CERO ".$formatter->toString($nitParts[0])."";
        } else {
            $part1 ="CERO CERO ".$formatter->toString($nitParts[0])."";
        }
        
        if (substr($nitParts[1],0,-5) == 0 && substr($nitParts[1],1,-4) == !0) {
             $part2 ="CERO ".$formatter->toString($nitParts[1])."";
        } if(substr($nitParts[1],1,-4) == 0){
             $part2 ="CERO CERO ".$formatter->toString($nitParts[1])."";
        }else{
             $part2 = $formatter->toString($nitParts[1]);
        }

        if (substr($nitParts[2],0,-2) == 0 && substr($nitParts[2],1,-1) == !0) {
             $part3 ="CERO ".$formatter->toString($nitParts[2])."";
        } if(substr($nitParts[2],1,-1) == 0){
             $part3 ="CERO CERO ".$formatter->toString($nitParts[2])."";
        }else{
             $part3 = $formatter->toString($nitParts[2]);
        }
        $part4 =  $formatter->toString($nitParts[3]);
        return $textNIt = "".$part1."-".$part2."-".$part3."-".$part4."";
        
    }
}
