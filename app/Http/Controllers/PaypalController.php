<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class PaypalController extends Controller
{
    public function complete()
    {
        return view('completePaiement'); 
    }
}
