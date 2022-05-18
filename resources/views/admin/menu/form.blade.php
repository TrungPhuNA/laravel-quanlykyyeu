<form role="form" action="" method="POST" autocomplete="off">
    @csrf
    <div class="col-sm-8">
        <div class="form-group {{ $errors->first('mn_name') ? 'has-error' : '' }}">
            <label for="name">{{  __('admin_article.menu.label_name')}} <span class="text-danger">(*)</span></label>
            <input type="text" class="form-control" name="mn_name"  placeholder="" value="{{ $menu->mn_name ?? '' }}">
            @if ($errors->first('mn_name'))
                <span class="text-danger">{{ $errors->first('mn_name') }}</span>
            @endif
        </div>
    </div>
    <div class="col-sm-4">

    </div>
    <div class="col-sm-12">
        <div class="box-footer text-center">
            <a href="{{ route('admin.menu.index') }}" class="btn btn-danger">
                {{  __('admin_article.btn_save')}} <i class="fa fa-undo"></i></a>
            <button type="submit" class="btn btn-success">{{  __('admin_article.btn_save')}} <i class="fa fa-save"></i></button>
        </div>
    </div>
</form>