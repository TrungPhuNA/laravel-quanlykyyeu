<table class="table">
    <thead>
    <tr>
        <th style="width: 100px;">Image</th>
        <th style="width: 30%">Name</th>
        <th class="text-center">Price</th>
        <th class="text-center">Qty</th>
        <th class="text-center">Total Money</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $key => $item)
        <tr>
            <td>
                <a href="{{ route('get.product.detail',\Str::slug($item->product->pro_slug ?? '').'-'.$item->od_product_id) }}"
                   title="{{ $item->name }}" class="avatar image contain">
                    <img alt="" src="{{ pare_url_file($item->product->pro_avatar ?? '') }}" style="width: 80px;height: 80px;" class="lazyload">
                </a>
            </td>
            <td>
                <a href="{{ route('get.product.detail',\Str::slug($item->product->pro_slug ?? '').'-'.$item->od_product_id) }}">
                    <strong>{{ strtolower($item->product->pro_name ?? '') }}</strong>
                </a>
            </td>
            <td class="text-center">
                <p><b>{{  number_format($item->od_price,0,',','.') }} đ</b></p>
            </td>
            <td class="text-center">
                <span>{{ $item->od_qty }}</span>
            </td>
            <td class="text-center">
                <span class="js-total-item"><b>{{ number_format($item->od_price * $item->od_qty,0,',','.') }} đ</b></span>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
