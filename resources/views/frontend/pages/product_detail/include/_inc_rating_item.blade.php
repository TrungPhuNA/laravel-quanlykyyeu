<div class="item">
    <p class="item_author">
        <span>{{ $rating->user->name ?? "Admin" }}</span>
        <span class="item_success"><i class="la la-check-circle"></i> Bought at the store </span>
    </p>
    <div class="item_review">
        <span class="item_review">
            @for ($j = 1 ; $j <= 5 ; $j ++)
                <i class="la la-star {{ $j <= $rating->r_number ? 'active' : '' }}"></i>
            @endfor
        </span>
        {{  $rating->r_content }}
    </div>
    <div class="item_footer">
        <span class="item_time"><i class="la la-clock-o"></i> evaluate {{  $rating->created_at }}</span>
    </div>
</div>