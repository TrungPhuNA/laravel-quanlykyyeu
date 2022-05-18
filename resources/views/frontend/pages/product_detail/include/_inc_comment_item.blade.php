<div class="item">
    <p class="item-auth"><span>{{ get_name_short($comment->user->name ?? "[N\A]") }}</span><span>{{ $comment->user->name ?? "[N\A]" }}</span></p>
    <p class="item-content">{{ $comment->cmt_content }}</p>
    @if ($comment->cmt_images)
        @php
            $listImagesCommend = json_decode($comment->cmt_images, true) ?? [];
        @endphp
        <p>
            @foreach($listImagesCommend as $img)
                <img src="{{ pare_url_file($img) }}" alt="" style="width: 100px;height: 100px;">
            @endforeach
        </p>
    @endif
    <p class="item-footer">
        <a href="" class="js-show-form-reply" data-name="{{ $comment->user->name ?? "[N\A]" }}"
           data-id="{{ $comment->cmt_parent_id ? $comment->cmt_parent_id : $comment->id }}" data-product="{{ $comment->cmt_product_id }}">Reply</a>
        <span>-</span>
        <a href="">{{ $comment->created_at->diffForHumans() }}</a>
    </p>
</div>
