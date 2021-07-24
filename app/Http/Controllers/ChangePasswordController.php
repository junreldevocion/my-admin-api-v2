<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangePasswordController extends Controller
{
    public function update(Request $request)
    {
        $fields = $request->validate([
            'oldpassword' => 'required|string',
            'npassword' => 'required|string',
            'confpassword' => 'required|string'
        ]);


    }
}
