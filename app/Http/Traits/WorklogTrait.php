<?php

namespace App\Http\Traits;
use App\Models\worklog;
use Illuminate\Support\Facades\Auth;


trait WorklogTrait {
    public function RegisterAction($action) {
        $usuario = Auth::user();
        
        $bitacora = worklog::create([
            'username'     =>$usuario->name,
            'actionLog'    =>$action,
        ]);
        
    }
}