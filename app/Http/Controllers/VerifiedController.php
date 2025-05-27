<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class VerifiedController extends Controller
{
    //
    public function index($encodedNik)
    {
        $nik = base64_decode($encodedNik);

        $user = User::where('nik', $nik)->first();
        return view('verified.index', compact('user'));
    }
}
