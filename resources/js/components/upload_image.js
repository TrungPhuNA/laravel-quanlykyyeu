var UploadImage = {
	init : function () {
		this.triggerInputFile()
		this.processUploads()
	},

	triggerInputFile()
	{
		$("body").on("click",".js-update-image", function(event){
			event.preventDefault()
			let $this = $(this)
			let $input = $this.next()
			if ($input.length > 0) {
				$input.trigger('click');
			}
		})
	},

	processUploads()
	{

		var imagesPreview = function(input, placeToInsertImagePreview) {
			if (input.files) {
				var filesAmount = input.files.length;
				for (let i = 0; i < filesAmount; i++) {
					var reader = new FileReader();
					reader.onload = function(event) {
						$($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
					}
					reader.readAsDataURL(input.files[i]);
				}
			}
		};

		// $('#gallery-photo-add').on('change', function() {
		// 	imagesPreview(this, 'div.gallery');
		// });

		$("body").on("change",".js-input-image", function (event) {
			event.preventDefault()
			let $this = $(this)
			let $gallery = $this.parents('form').find('div.gallery');
			imagesPreview(this, $gallery);
			// imagesPreview(this, 'div.gallery');
		})
	}
}

export default UploadImage