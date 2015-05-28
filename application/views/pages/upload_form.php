<div class="container">
	<div id="main_container">
		<h2>jQuery Fileuploder Plugin</h2>

		<form action="index.php/upload/do_upload" method="post" enctype="multipart/form-data">

			<input type="file" name="userfile" class="fileUpload" multiple>

			<button id="px-submit" type="submit">Upload</button>
			<button id="px-clear" type="reset">Clear</button>

		</form>

		<script type="text/javascript">
			jQuery(function($){
				$('.fileUpload').fileUploader({
					allowedExtension: 'jpg|jpeg|gif|png|zip|avi',
					afterEachUpload: function(data, status, formContainer){
						$jsonData = $.parseJSON( $(data).find('#upload_data').text() );
					}
				});
//				alert("testing");
			});
		</script>

	</div>
</div>