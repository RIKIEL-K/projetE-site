<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class PaypalController extends Controller
{
    public function complete()
    {
        return view('completePaiement'); // Assurez-vous d'avoir une vue correspondante
    }
}
