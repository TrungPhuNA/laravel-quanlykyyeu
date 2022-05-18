<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ShoppingCartService\PayManager;
use Illuminate\Http\Request;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Order;
use App\Mail\TransactionSuccess;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ShoppingCartController extends Controller
{
    private $vnp_TmnCode = "T6TM3ICM"; //Mã website tại VNPAY
    private $vnp_HashSecret = "KJNIKUMHGKTFHZRLFKIVTUHUNBSAZXJH"; //Chuỗi bí mật
    private $vnp_Url = "http://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
//    private $vnp_Returnurl = "http://doantotnghiep.abc/gio-hang/thanh-toan-pay";
    private $vnp_Returnurl = 'http://laravel-bangiay.local:8888/shopping/hook';
    protected $idTransaction = 0;

    public function index(Request $request)
    {
        if ($request->paymentId) {
            \Cart::destroy();
            \Session::flash('toastr', [
                'type'    => 'success',
                'message' => 'Thanh toán thành công'
            ]);
            return redirect()->route('get.home');
        }
        $shopping = \Cart::content();
        $viewData = [
            'title_page' => 'Danh sách giỏ hàng',
            'shopping'   => $shopping
        ];
        return view('frontend.pages.shopping.index', $viewData);
    }

    /**
     * Thêm giỏ hàng
     * */
    public function add($id)
    {
        $product = Product::find($id);

        //1. Kiểm tra tồn tại sản phẩm
        if (!$product) return redirect()->to('/');

        // 2. Kiểm tra số lượng sản phẩm
        if ($product->pro_number < 1) {
            //4. Thông báo
            \Session::flash('toastr', [
                'type'    => 'error',
                'message' => 'Số lượng sản phẩm không đủ'
            ]);

            return redirect()->back();
        }
        $cart          = \Cart::content();
        $idCartProduct = $cart->search(function ($cartItem) use ($product) {
            if ($cartItem->id == $product->id) return $cartItem->id;
        });
        if ($idCartProduct) {
            $productByCart = \Cart::get($idCartProduct);
            if ($product->pro_number < ($productByCart->qty + 1)) {
                \Session::flash('toastr', [
                    'type'    => 'error',
                    'message' => 'Số lượng sản phẩm không đủ'
                ]);
                return redirect()->back();
            }
        }

        // 3. Thêm sản phẩm vào giỏ hàng
        \Cart::add([
            'id'      => $product->id,
            'name'    => $product->pro_name,
            'qty'     => 1,
            'price'   => number_price($product->pro_price, $product->pro_sale),
            'weight'  => '1',
            'options' => [
                'sale'      => $product->pro_sale,
                'price_old' => $product->pro_price,
                'image'     => $product->pro_avatar
            ]
        ]);

        //4. Thông báo
        \Session::flash('toastr', [
            'type'    => 'success',
            'message' => 'Thêm giỏ hàng thành công'
        ]);

        return redirect()->back();
    }

    public function postPay(Request $request)
    {
        Cache::forget('HOME.PRODUCT_PAY');
        $data = $request->except("_token");
        if (!\Auth::user()->id) {
            //4. Thông báo
            \Session::flash('toastr', [
                'type'    => 'error',
                'message' => 'Đăng nhập để thực hiện tính năng này'
            ]);

            return redirect()->back();
        }

        $data['tst_user_id']     = \Auth::user()->id ? \Auth::user()->id : 0;
        $data['tst_type']        = 2;
        $data['tst_total_money'] = str_replace(',', '', \Cart::subtotal(0));
        $data['created_at']      = Carbon::now();

        // check nếu thanh toán ví thì kiểm tra số tiền
        if ($request->pay == 'online') {
            if (get_data_user('web', 'balance') < $data['tst_total_money']) {
                \Session::flash('toastr', [
                    'type'    => 'error',
                    'message' => 'Số tiền của bạn không đủ để thanh toán. Hãy nạp thêm tiền để thanh toán từ ví của bạn'
                ]);
                return redirect()->back();
            }
        }

        // Lấy thông tin đơn hàng
        $shopping                  = \Cart::content();
        $data['options']['orders'] = $shopping;

        $options['drive'] = $request->pay;

        // return $this->payOnline($request, $data,$shopping, $options);
//        return ;
       try {
           \Cart::destroy();
           new PayManager($data, $shopping, $options);
       } catch (\Exception $exception) {
           Log::error("[Errors pay shopping cart]" . $exception->getMessage());
       }

       \Session::flash('toastr', [
           'type'    => 'success',
           'message' => 'Đơn hàng của bạn đã được lưu'
       ]);

       return redirect()->to('/');
    }

    public function update(Request $request, $id)
    {
        if ($request->ajax()) {

            //1.Lấy tham số
            $qty       = $request->qty ?? 1;
            $idProduct = $request->idProduct;
            $product   = Product::find($idProduct);

            //2. Kiểm tra tồn tại sản phẩm
            if (!$product) return response(['messages' => 'Không tồn tại sản sản phẩm cần update']);

            //3. Kiểm tra số lượng sản phẩm còn ko
            if ($product->pro_number < $qty) {
                return response([
                    'messages' => 'Số lượng cập nhật không đủ',
                    'error'    => true
                ]);
            }

            //4. Update
            \Cart::update($id, $qty);

            return response([
                'messages'   => 'Cập nhật thành công',
                'totalMoney' => \Cart::subtotal(0),
                'totalItem'  => number_format(number_price($product->pro_price, $product->pro_sale) * $qty, 0, ',', '.')
            ]);
        }
    }

    /**
     *  Xoá sản phẩm đơn hang
     * */
    public function delete(Request $request, $rowId)
    {
        if ($request->ajax()) {
            \Cart::remove($rowId);
            return response([
                'totalMoney' => \Cart::subtotal(0),
                'type'       => 'success',
                'message'    => 'Xoá sản phẩm khỏi đơn hàng thành công'
            ]);
        }
    }

    public function hookCallback(Request $request)
    {
        if ($request->vnp_ResponseCode == '00')
        {
            $transactionID = $request->vnp_TxnRef;

            $transaction = Transaction::find($transactionID);
            if ($transaction)
            {
                \Cart::destroy();
//                $transaction->tr_type = Transaction::TYPE_PAY;
                $transaction->save();
                \Session::flash('toastr', [
                    'type'    => 'success',
                    'message' => 'Thanh toán thành công'
                ]);

                return redirect()->to('/');
            }

            return redirect()->to('/')->with('danger','Mã đơn hàng không tồn tại');

        }

        return  redirect()->to('/');
    }

    public function payOnline(Request $request, $data, $shopping, $options)
    {
        // Sau khi xử lý xong bắt đầu xử lý online
        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);

        $dataTransaction     = $this->getDataTransaction($data);

        $this->idTransaction = Transaction::insertGetId($dataTransaction);
        $orders              = $this->data['options']['orders'] ?? [];
        if ($this->idTransaction)
            $this->syncOrder($orders, $this->idTransaction);

        // tham so dau vao
        $inputData = array(
            "vnp_Version"    => "2.0.0",
            "vnp_TmnCode"    => $this->vnp_TmnCode,
            "vnp_Amount"     => $dataTransaction['tst_total_money'] * 100, // so tien thanh toan,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $_SERVER['REMOTE_ADDR'], // IP
            "vnp_Locale"     => 'vi', // ngon ngu,
            "vnp_OrderInfo"  => 'Thanh toán Onlinr', // noi dung thanh toan,
            "vnp_OrderType"  => 'billpayment',    // loai hinh thanh toan
            "vnp_ReturnUrl"  => $this->vnp_Returnurl,   // duong dan tra ve
            "vnp_TxnRef"     => $this->idTransaction, // ma don hang,
        );

        if ($request->bank_code) {
            $inputData['vnp_BankCode'] = $request->bank_code;
        }
        ksort($inputData);
        $query    = "";
        $i        = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . $key . "=" . $value;
            } else {
                $hashdata .= $key . "=" . $value;
                $i        = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }


        $vnp_Url = $this->vnp_Url . "?" . $query;
        if ($this->vnp_HashSecret) {
            $vnpSecureHash = hash('sha256', $this->vnp_HashSecret . $hashdata);
            $vnp_Url       .= 'vnp_SecureHashType=SHA256&vnp_SecureHash=' . $vnpSecureHash;
        }

        $returnData = array(
            'code'    => '00',
            'message' => 'success',
            'data'    => $vnp_Url
        );

        return redirect()->to($returnData['data']);
    }

    public function getDataTransaction($data)
    {
        return [
            "tst_name"        => Arr::get($data, 'tst_name'),
            "tst_phone"       => Arr::get($data, 'tst_phone'),
            "tst_address"     => Arr::get($data, 'tst_address'),
            "tst_email"       => Arr::get($data, 'tst_email'),
            "tst_note"        => Arr::get($data, 'tst_note'),
            "tst_user_id"     => Arr::get($data, 'tst_user_id'),
            "tst_total_money" => Arr::get($data, 'tst_total_money'),
            "tst_type"        => Arr::get($data, 'tst_type'),
            "created_at"      => Carbon::now()
        ];
    }

    /**
     * @param $productId
     * Tăn số lượng sản phẩm
     */
    public function incrementPayProduct($productId)
    {
        \DB::table('products')
            ->where('id', $productId)
            ->increment("pro_pay");
    }

    /**
     * @param $orders
     * @param $transactionID
     * Lưu chi tiết đơn hàng
     */
    public function syncOrder($orders, $transactionID)
    {
        if ($orders) {
            foreach ($orders as $key => $item) {
                $order               = $this->getDataOrder($item, $transactionID);
                $order['created_at'] = Carbon::now();
                //1. Lưu chi tiết đơn hàng
                Order::insert($order);

                //2. Tăng pay ( số lượt mua của sản phẩm dó)
                $this->incrementPayProduct($item->id);
            }
        }
    }

    /**
     * @param $order
     * @param $transactionID
     * @return array
     */
    public function getDataOrder($order, $transactionID)
    {
        return [
            'od_transaction_id' => $transactionID,
            'od_product_id'     => $order->id,
            'od_sale'           => $order->options->sale,
            'od_qty'            => $order->qty,
            'od_price'          => $order->price
        ];
    }
}
