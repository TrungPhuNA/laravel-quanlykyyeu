<ul>
	@for($i = 1; $i <= 6; $i++)
		<li class="{{ Request::get('price') == $i ? "active" : "" }}">
			<a href="{{ request()->fullUrlWithQuery(['price' =>  $i]) }}">
				{{  $i == 6 ? "Bigger 100k " : "Price " . number_format($i * 200000,0,',','.')  ." vnđ" }}
			</a>
		</li>
	@endfor
</ul>