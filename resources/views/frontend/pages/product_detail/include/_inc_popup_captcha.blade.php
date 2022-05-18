<div id="popup-captcha" class="modal text-center">
    <div class="header">Warning</div>
    <div class="content">
        <p>Please check the box <b> I am not a robot </b> to continue functionality</p>
        <div class="form-group">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
            <script src="https://www.google.com/recaptcha/api.js?hl=vi" async defer></script>
        </div>
    </div>
    <div class="footer">
        <button type="submit" class="btn btn-pink js-submit-captcha">Confirm</button>
    </div>
</div>
