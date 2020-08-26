<?php

namespace App\Http\Controllers;

use App\Piece;
use Illuminate\Http\Request;

class PieceController extends Controller
{
    public function index(Request $request)
    {
        $pieces = Piece::all();
    }
}
