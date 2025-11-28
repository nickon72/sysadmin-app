<?php

namespace App\Http\Controllers;

use App\Models\AccessPoint;
use Illuminate\Http\Request;

class AccessPointController extends Controller
{
    public function index()
    {
        $points = AccessPoint::orderBy('name')->get();
        return view('access-points.index', compact('points'));
    }
}