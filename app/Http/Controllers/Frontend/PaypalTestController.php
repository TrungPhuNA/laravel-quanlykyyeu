<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ShoppingCartService\PayPalService as PayPalSvc;

class PaypalTestController extends Controller
{
    private $paypalSvc;


    protected $data = [
        [
            'name' => 'Vinataba',
            'quantity' => 1,
            'price' => 1.5,
            'sku' => '1'
        ],
        [
            'name' => 'Marlboro',
            'quantity' => 1,
            'price' => 1.6,
            'sku' => '2'
        ],
        [
            'name' => 'Esse',
            'quantity' => 1,
            'price' => 1.8,
            'sku' => '3'
        ]
    ];

    public function __construct(PayPalSvc $paypalSvc)
    {
        $this->paypalSvc = $paypalSvc;
    }

    public function index()
    {
        $data = $this->data;
        $transactionDescription = "Tobaco";

        $paypalCheckoutUrl = $this->paypalSvc
            // ->setCurrency('eur')
            ->setReturnUrl(url('paypal/status'))
            // ->setCancelUrl(url('paypal/status'))
            ->setItem($data)
            // ->setItem($data[0])
            // ->setItem($data[1])
            ->createPayment($transactionDescription);

        if ($paypalCheckoutUrl) {
            return redirect($paypalCheckoutUrl);
        } else {
            dd(['Error']);
        }
    }

    public function status()
    {
        $paymentStatus = $this->paypalSvc->getPaymentStatus();
        dd($paymentStatus);
    }

    public function paymentList()
    {
        $limit = 10;
        $offset = 0;

        $paymentList = $this->paypalSvc->getPaymentList($limit, $offset);

        dd($paymentList);
    }

    public function paymentDetail($paymentId)
    {
        $paymentDetails = $this->paypalSvc->getPaymentDetails($paymentId);

        dd($paymentDetails);
    }
}
