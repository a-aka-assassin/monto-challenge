<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Guzzle\Http\Exception\ClientErrorResponseException;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $provider = $request->provider;
        $invoiceId = $request->invoiceid;

        $guzzle = new \GuzzleHttp\Client;

        if (!($invoiceId == "9f587e13-682e-4d91-867f-fd3aec3b70b8" || $invoiceId == 1)) {
            return Response::json(array(
                'code'      =>  418,
                'message'   =>  "I am a teapot"
            ), 418);
        }


        $response = $guzzle->get('https://qa-app.capcito.com/api/v3/work-sample/invoices/' . $provider . '/' . $invoiceId);

        $result = json_decode((string) $response->getBody(), true);






        if ($provider == 'vizma') {

            foreach ($result['Rows'] as $row) {
                $rows[] = array("article_name" => $row['UnitName'], "quantity" => $row['Quantity'], "price" => $row['UnitPrice']);
            }

            $resultArray = array("id" => $result['Id'], "invoice_nr" => $result['InvoiceNumber'], "dates" => array("created_at" => $result['InvoiceDate'], "updated_at" => $result['InvoiceDate'], "issue_date" => $result['InvoiceDate'], "due_date" => $result['DueDate']), "currency" => $result['CurrencyCode'], "total_amount" => $result['TotalAmount'], "custom_notes" => "null", "rows" => $rows);
        }

        if ($provider == 'fortsocks') {

            foreach ($result['rows'] as $row) {
                $rows[] = array("id" => $row['id'], "quantity" => $row['quantity'], "price" => $row['price'], "vat" => $row['vat'], "product-name" => $row['product-name'], "unit" => $row['unit']);
            }


            $resultArray = array("id" => $result['id'], "invoice_nr" => $result['invoice-nr'], "seller" => array("name" => $result['seller']['name'], "organisation-number" => $result['seller']['organisation-number']), "buyer" => array("name" => $result['buyer']['name'], "organisation-number" => $result['buyer']['organisation-number'], "invoicing" => array("email" => $result['buyer']['invoicing']['email'], "address1" => $result['buyer']['invoicing']['address1'], "address2" => $result['buyer']['invoicing']['address2'], "zip-code" => $result['buyer']['invoicing']['zip-code'], "state" => $result['buyer']['invoicing']['state'], "country" => $result['buyer']['invoicing']['country'])), "invoice-date" => $result['invoice-date'], "due-date" => $result['due-date'], "delivery-date" => $result['delivery-date'], "currency-rate" => $result['currency-rate'], "sent" => $result['sent'], "notes" => $result['notes'], "country-code" => $result['country-code'], "amount" => $result['amount'], "rows" => $rows);
        }

        $jsonEncodedResult = json_encode($resultArray);

        return $jsonEncodedResult;
    }
}
