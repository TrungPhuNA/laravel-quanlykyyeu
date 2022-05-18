<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SystemPay\PayHistory;
use App\Models\SystemPay\PayIn;
use App\Models\SystemPay\PayOut;
use App\Services\ShoppingCartService\PayBaseService;
use App\Services\Wallet\WalletPayIn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalShoppingController extends PayBaseService
{
    private $paypalSvc;
    protected $apiContent;
    protected $idTransaction;

    public function __construct()
    {
        $this->apiContent = new  ApiContext(
            new OAuthTokenCredential(config('shopping_cart.paypal.client_id'), config('shopping_cart.paypal.secret'))
        );
        $this->apiContent->setConfig(config('shopping_cart.paypal.settings'));
        //http://hungnm-sharing.blogspot.com/2015/04/tich-hop-laravel-4-voi-paypal.html
        //https://developer.paypal.com/docs/api/quickstart/payments/#additional-information
    }

    public function postPay(Request $request)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');


        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal(10);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription("Payment description");

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('get.shopping.list'))
            ->setCancelUrl(route('get.shopping.list'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->apiContent);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            return redirect()->route('get.shopping.list');
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        \Session::put('paypal_payment_id', $payment->getId());

        if (isset($redirect_url)) {
            // redirect to paypal
            // Tiến hành Lưu DB
            try {
                $data                      = $request->except('_token');
                $data['tst_user_id']       = \Auth::user()->id;
                $data['tst_user_id']       = \Auth::user()->id;
                $data['tst_total_money']   = str_replace(',', '', \Cart::subtotal(0));
                $data['created_at']        = Carbon::now();
                $shopping                  = \Cart::content();
                $data['options']['orders'] = $shopping;

                $dataTransaction = $this->getDataTransaction($data);
                //1. Lưu đơn hàng
                $this->idTransaction = \App\Models\Transaction::insertGetId($dataTransaction);
                $orders              = $data['options']['orders'] ?? [];
                //2. Lưu chi tiết đơn hàng
                if ($this->idTransaction)
                    $this->syncOrder($orders, $this->idTransaction);

                $money  = $dataTransaction['tst_total_money'];
                $userID = $dataTransaction['tst_user_id'];

                //  Add pay in
                $optionsPayIn = [
                    'meta_detail' => 'Online deposit',
                    'pi_provider' => 3
                ];

                $pi_provider = Arr::get($optionsPayIn, 'pi_provider', 0);

                $dataPayIn['pi_status']   = PayIn::STATUS_SUCCESS;
                $dataPayIn['pi_month']    = date('m');
                $dataPayIn['pi_year']     = date('Y');
                $dataPayIn['pi_user_id']  = \Auth::user()->id;
                $dataPayIn['pi_money']    = $money;
                $dataPayIn['pi_provider'] = $pi_provider;
                $dataPayIn['created_at']  = Carbon::now();
                $dataPayIn['pi_admin_id'] = 0;
                $idPayIn                  = PayIn::insertGetId($dataPayIn);

                $messages = Arr::get($optionsPayIn, 'meta_detail', null);

                if ($idPayIn) {
                    PayHistory::insert([
                        'ph_code'        => 'IN' . $idPayIn,
                        'ph_user_id'     => $userID,
                        'ph_credit'      => $money,
                        'ph_balance'     => \Auth::user()->balance,
                        'ph_status'      => 1,
                        'ph_meta_detail' => $messages,
                        'ph_month'       => date('m'),
                        'ph_year'        => date('Y'),
                        'created_at'     => Carbon::now()
                    ]);
                }
                //3. Trừ tiền
                $this->deductionMoney($money, $userID);

                //4. Lưu thống kê
                $this->savePayOut($money, $userID, $this->idTransaction);
                $dataTransaction['tst_type'] = \App\Models\Transaction::TYPE_ONLINE;
            } catch (\Exception $exception) {
                Log::error("[Error Paypal Online: ]" . $exception->getMessage());
            }

            return redirect()->to($redirect_url);
        }
    }

    public function deductionMoney($money, $userId)
    {
        \DB::table('users')->where('id', $userId)
            ->decrement('balance', $money);
    }

    public function savePayOut($money, $userId, $transactionID)
    {
        $dataPayOut = [
            'po_status'         => PayOut::STATUS_SUCCESS,
            'po_month'          => date('m'),
            'po_transaction_id' => $transactionID,
            'po_year'           => date('Y'),
            'po_user_id'        => $userId,
            'po_money'          => $money,
            'created_at'        => Carbon::now()
        ];

        $idPayOut = PayOut::insertGetId($dataPayOut);

        if ($idPayOut) {
            PayHistory::insert([
                'ph_code'    => 'PAYOUT' . $idPayOut,
                'ph_user_id' => $userId,
                'ph_debit'   => $money,
                'ph_balance' => \Auth::user()->balance,
                'ph_status'  => 1,
                'ph_month'   => date('m'),
                'ph_year'    => date('Y'),
                'created_at' => Carbon::now()
            ]);
        }
        Log::info("save OK");
    }

    public function status()
    {
        $paymentStatus = $this->paypalSvc->getPaymentStatus();
        dd($paymentStatus);
    }

    public function paymentList()
    {
        $limit  = 10;
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