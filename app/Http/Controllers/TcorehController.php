<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tcoreh;

class TcorehController extends Controller
{
    public function index(){
        return Tcoreh::all();
    }
}
