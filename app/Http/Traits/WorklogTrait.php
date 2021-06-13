<?php

namespace App\Http\Traits;
use App\Models\Worklog;
use Illuminate\Support\Facades\Auth;


trait WorklogTrait {
    public function RegisterAction($action) {
        $usuario = Auth::user();
        
        $bitacora = Worklog::create([
            'username'     =>$usuario->name,
            'actionLog'    =>$action,
        ]);
        
    }
}