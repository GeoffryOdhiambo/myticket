<?php

namespace App\Http\Controllers\Public\Payments;

use App\Http\Controllers\Controller;
use App\Services\Payments\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MpesaCallbackController extends Controller
{
    public function __invoke(Request $request, PaymentManager $payments): Response
    {
        $payments->driver('mpesa')->handleCallback($request->all());

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
