<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InfosGeraisController extends Controller
{
        public function indexInfos (){

            return view ('financeiro.controle');
        }
}
