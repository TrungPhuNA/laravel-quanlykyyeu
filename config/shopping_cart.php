<?php

use App\Services\ShoppingCartService\PayOnlinePaymentService;
use App\Services\ShoppingCartService\PayTransferService;

return [
    'progress'  => [
        '-1' => 'Đặt hàng thành công',
        '1'  => 'Đã tiếp nhận',
        '2'  => 'Bàn giao vận chuyển',
        '3'  => 'Thành công',
    ],
    'drive_pay' => [
        'transfer' => [
            'process' => PayTransferService::class
        ],
        'online'   => [
            'process' => PayOnlinePaymentService::class,
        ],
        'paypal' => [
            'process' => \App\Services\ShoppingCartService\PayPayPalOnlineService::class
        ]
    ],
    'paypal' => [
        'client_id' => 'AT0Vk-Qh06mPyp-CUbpKN17pcAPuikqIFD9vhlJU09SHKO_6-foweWB8LohOIyXF90Bl9G5YTLvcGBwh',
        'secret' => 'EH8ZqRAFBtneAdKqJZB4mNsm_1ZOsQ-ArYmDUfTDGa4AQjAFSdjEIstpkXXh2f1pmsoHstEPZKrTxnCc',
        'settings' => [
            // PayPal mode, sanbox hoặc live
            'mode' => env('PAYPAL_MODE','sandbox'),
            // Thời gian của một kết nối (tính bằng giây)
            'http.ConnectionTimeOut' => 30,
            // Có ghi log khi xảy ra lỗi
            'log.logEnabled' => true,
            // Đường dẫn đền file sẽ ghi log
            'log.FileName' => storage_path() . '/logs/paypal.log',
            // Kiểu log
            'log.LogLevel' => 'INFO'
        ]
    ]
];
