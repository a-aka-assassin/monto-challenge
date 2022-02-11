<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $provider = $request->provider;
        $invoiceId = $request->invoiceid;

        $guzzle = new \GuzzleHttp\Client;


        $response = $guzzle->get('https://qa-app.capcito.com/api/v3/work-sample/invoices/' . $provider . '/' . $invoiceId);

        $result = json_decode((string) $response->getBody(), true);

        return $result;
    }

    public function gui(Request $request)
    {
        $provider = $request->provider;
        $invoiceId = $request->invoiceid;

        $guzzle = new \GuzzleHttp\Client;


        $response = $guzzle->get('https://qa-app.capcito.com/api/v3/work-sample/invoices/' . $provider . '/' . $invoiceId);
        $result = json_decode((string) $response->getBody(), true);

        if ($provider == 'vizma') {
            return view('vizma', ['result' => $result]);
        } else {
            return view('forsocks', ['result' => $result]);
        }
    }
}
