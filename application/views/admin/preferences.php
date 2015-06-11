<div class="container">
	<div class="row">  
		<div class="span6">  
			<ul class="breadcrumb">  
				<li>  
					<a href="<?php echo base_url() . "admin";?>">Dashboard Home</a> <span class="divider">></span>  
				</li>
				<li class="active">Appearance Preferences</li>
			</ul>  
		</div>  
	</div>
	<div class="page-header">
		<h3>Appearance Preferences</h3>
	</div>
	<?php if ( ! is_writable(FCPATH. 'application/config/preferences.php')) { echo '<div class="alert alert-error">WARNING: ' . FCPATH. 'application/config/preferences.php' . " is not writable by the webserver, preference changes will not be saved</div><hr>"; }?>
	<div class="tabbable">
		<ul class="nav nav-tabs">
			<li <?php if ( $this->session->userdata('preferences_tab') === "background" ) { echo "class='active'"; } ?>><a href="#background" data-toggle="tab">Background</a></li>
			<li <?php if ( $this->session->userdata('preferences_tab') === "headr" ) { echo "class='active'"; } ?>><a href="#headr" data-toggle="tab">Header</a></li>
			<li <?php if ( $this->session->userdata('preferences_tab') === "font" ) { echo "class='active'"; } ?>><a href="#font" data-toggle="tab">Font</a></li>
			<li <?php if ( $this->session->userdata('preferences_tab') === "logo" ) { echo "class='active'"; } ?>><a href="#logo" data-toggle="tab">Logo</a></li>
			<li <?php if ( $this->session->userdata('preferences_tab') === "themes" ) { echo "class='active'"; } ?>><a href="#themes" data-toggle="tab">Themes</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane<?php if ( $this->session->userdata('preferences_tab') === "background" ) { echo " active"; } ?>" id="background">
				<h4>Background</h4>
				<?php
				$num_items = count($background_map);
//				$num_rows = intval($num_items / 3);
				$num_rows = ($num_items > 1) ? round($num_items / 3) : 1;
				$item = 0;
				$attributes = array('name' => 'backgroundform', 'id' => 'backgroundform');
				echo form_open('admin/background', $attributes);
				for ($row = 1; $row <= $num_rows; $row++): ?>
				<div class="row-fluid">
					<?php
					for ( $i = 1; $i <= 4; $i++):
						$item++;
						if ( $item <= $num_items):
//							print "$row -> $item -> $i<br />";
					?>
					<div class="span3">
						<div class="<?php if ( $background_map[$item-1] == $current_background ): ?>well-highlight<?php else: ?>well<?php endif; ?>">
							<div style="float:right; text-align:center">
								<a href="<?php echo base_url() . "admin/delete_file/backgrounds/" . $background_map[$item-1];?>" rel="popover" data-content="Permanently delete this background (action cannot be undone)." data-original-title="Delete Background"><i class="icon-remove-sign"></i></a>
							</div>
							<a href="#" class="background_thumbnail"><img src="<?php echo base_url(); ?>resources/images/backgrounds/<?php echo $background_map[$item-1];?>" width="256px" height="256px" alt=""></a>
							<input type="radio" class="background_thumbnail_radio" value="<?php echo $background_map[$item-1]; ?>" name="background" id="background<?php echo $item; ?>" <?php if ( $background_map[$item-1] == $current_background ) { print 'checked="checked"'; } ?> /> <?php if ( $background_map[$item-1] == $current_background ) { print '(Current Background)'; } ?>
							<?php echo $background_map[$item-1]; ?>
							<br />
						</div>
					</div>
					<?php
						else:
							break;
						endif;
					endfor;
					?>
				</div><!--/row-->
				<?php endfor; ?>
				<!--<button type="submit" class="btn btn-primary"><i class="icon-th icon-white"></i>  Set background</button>-->
				<div class="row">&nbsp;</div>
				<?php if ( ! is_writable(FCPATH. 'resources/images/backgrounds')): ?>
					<?php echo '<div class="alert alert-error">WARNING: ' . FCPATH. 'resources/images/backgrounds' . " is not writable by the webserver, custom backgrounds cannot be uploaded</div>"; ?>
				<?php else: ?>
					<?php // echo br(1); ?>
					<a class="btn btn-primary btn-medium" href="#uploadBackgroundModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Upload your own background image." data-original-title="Upload background"><i class="icon-file icon-white"></i>  Upload Background</a>
					<?php echo br(2); ?>
				<?php endif; ?>				
				<?php echo form_close(); ?>
			</div>
			
			
<script>
$(document).ready(function () {
	$("#header_colour_from").spectrum({
		color: "#<?php echo $header_colour_from; ?>",
		showInitial: true,
		showInput: true,
		preferredFormat: "hex"
	});
	
	$("#header_colour_to").spectrum({
		color: "#<?php echo $header_colour_to; ?>",
		showInitial: true,
		showInput: true,
		preferredFormat: "hex"
	});

	$("#navbar_selected_tab_colour").spectrum({
		color: "#<?php echo $navbar_selected_tab_colour; ?>",
		showInitial: true,
		showInput: true,
		preferredFormat: "hex"
	});

	$("#navbar_font_colour").spectrum({
		color: "#<?php echo $navbar_font_colour; ?>",
		showInitial: true,
		showInput: true,
		preferredFormat: "hex"
	});
//
	$("#navbar_font_colour_hover").spectrum({
		color: "#<?php echo $navbar_font_colour_hover; ?>",
		showInitial: true,
		showInput: true,
		preferredFormat: "hex"
	});
});
</script>  
			
			
			<div class="tab-pane<?php if ( $this->session->userdata('preferences_tab') === "headr" ) { echo " active"; } ?>" id="headr">
				<h4>Header Colour</h4>
				<p>Specify the blend of your header (for a solid header use the same colour for both boxes).</p>
				<br />
				<div class="row">
					<div class="span2">Blend from colour:</div>
					<div class="span3"><input type="text" name="header_colour_from" value='<?php echo $header_colour_from; ?>' id="header_colour_from" maxlength="7" size="4"   /></div>
					<!--<div class="span3"><input class='simple_color_from' value='<?php // echo $header_colour_from; ?>'/></div>-->
				</div>
				<br/>
				<div class="row">
					<div class="span2">Blend to colour:</div>
					<div class="span3"><input type="text" name="header_colour_to" value='<?php echo $header_colour_to; ?>' id="header_colour_to" maxlength="7" size="4"   /></div>
					<!--<div class="span3"><input class='simple_color_to' value='<?php // echo $header_colour_to; ?>'/></div>-->
				</div>
				<!--<input class="btn" type='button' id='alert_button' value='Show Value'/>-->
				<br />
				<div class="row">
					<div class="span2">Selected tab colour:</div>
					<div class="span3"><input type="text" name="navbar_selected_tab_colour" value='<?php echo $navbar_selected_tab_colour; ?>' id="navbar_selected_tab_colour" maxlength="7" size="4"   /></div>
				</div>
				
				<br />
				<br />
				<button id="pick_button" type="submit" class="btn btn-primary" rel="popover" data-content="Click to set the header colour blend." data-original-title="Set Header Colours" ><i class="icon-tint icon-white"></i>  Set header colours</button>&nbsp;&nbsp;&nbsp;
				<!--<button id="colour_default" type="submit" class="btn btn-success" rel="popover" data-content="Reset the header to and from colours to the default values." data-original-title="Reset Colours" ><i class="icon-refresh"></i>  Reset to default</button>-->
				<br />
				<br />
				<br />
				<br />
			</div>
			<div class="tab-pane<?php if ( $this->session->userdata('preferences_tab') === "font" ) { echo " active"; } ?>" id="font">
				<h4>Font Family, Size & Colour</h4>
				<style>
					<!-- Load  -->
					<?php foreach ( $this->config->item('fonts') as $font_key => $font_value ):
						echo "@font-face {
						font-family: '$font_key';
						font-style: normal;
						font-weight: 400;
						src: local('$font_key'), url(" . base_url("resources/fonts/google/" .  strtolower($font_key) . ".woff)") . " format('woff');
					}";
					
					endforeach; ?>
				</style>
				<?php 
				$attributes = array('name' => 'fontform', 'id' => 'fontform');
				echo form_open('admin/font', $attributes);
				?>
				<table class="table table-striped table-bordered table-hover table-condensed">
					<thead>
						<tr>
							<th>Name</th>
							<th>Sample</th>
							<th>Select</th>
						</tr>
					</thead>
					<tbody>
				<?php foreach ( $this->config->item('fonts') as $font_key => $font_value ):?>
					<?php $lower = strtolower($font_value); $val = str_replace( ' ', '-', $lower ); ?>
						<tr>
							<td><?php echo anchor_popup("http://www.google.com/webfonts/specimen/$font_key", $font_value, array('title' => 'Google Fonts'));?></td>
							<td style="<?php echo "font-family: '$font_value', serif; font-size: 18px;"; ?>">Example font style... 1234567890!@£$%^&*()</td>
							<td><input type="radio" value="<?php echo $font_key; ?>" name="font" id="<?php echo $val; ?>" <?php if ( $font_key == $this->config->item('current_font_link') ) { print 'checked="checked"'; } ?> /> <?php if ( $font_key == $this->config->item('current_font_link') ) { print '(Current Font)'; } ?></td>
						</tr>
				<?php endforeach; ?>
					</tbody>
				</table>
				<br />

				<div class="row">
					<div class="span2">Font size:</div>
					<div class="span3"><input type="text" name="fontsize" value='<?php echo $this->config->item('font_size'); ?>' id="fontsize" maxlength="7" size="4" style="width:30%" /></div>
				</div>
				<br />				
				<div class="row">
					<div class="span2">Header font colour:</div>
					<div class="span3"><input type="text" name="navbar_font_colour" value='<?php echo $navbar_font_colour; ?>' id="navbar_font_colour" maxlength="7" size="4"   /></div>
				</div>
				<br />
				<div class="row">
					<div class="span2">Header font (hover) colour:</div>
					<div class="span3"><input type="text" name="navbar_font_colour_hover" value='<?php echo $navbar_font_colour_hover; ?>' id="navbar_font_colour_hover" maxlength="7" size="4"   /></div>
				</div>

				<br />
				<br />
				<button type="submit" class="btn btn-primary"><i class="icon-font icon-white"></i> | Save font parameters</button>
				<?php echo form_close(); ?>
			</div>
			<div class="tab-pane<?php if ( $this->session->userdata('preferences_tab') === "logo" ) { echo " active"; } ?>" id="logo">
				<h4>Logo Style</h4>
				<?php
				$num_items = count($logo_map);
				$num_rows = ($num_items > 1) ? round($num_items / 3) : 1;
				$item = 0;
				echo form_open('admin/logo');
				for ($row = 1; $row <= $num_rows; $row++): ?>
				<div class="row-fluid">
				<?php for ( $i = 1; $i <= 4; $i++):
					$item++;
					if ( $item <= $num_items): ?>
					<div class="span3">
						<div class="<?php if ( $logo_map[$item-1] == $current_logo ): ?>well-highlight<?php else: ?>well<?php endif;?>">
							<div style="float:right; text-align:center">
								<a href="<?php echo base_url() . "admin/delete_file/logos/" . $logo_map[$item-1];?>" rel="popover" data-content="Permanently delete this logo (action cannot be undone)." data-original-title="Delete Background"><i class="icon-remove-sign"></i></a>
							</div>
							<a href="#" class="logo_thumbnail"><img src="<?php echo base_url(); ?>resources/images/logos/<?php echo $logo_map[$item-1];?>" width="256px" height="256px" alt=""></a>
							<?php // echo $logo_map[$item-1];?><!-- <hr> -->
							<input type="radio" class="logo_thumbnail_radio" value="<?php echo $logo_map[$item-1]; ?>" name="logo" id="logo<?php echo $item; ?>" <?php if ( $logo_map[$item-1] == $current_logo ) { print 'checked="checked"'; } ?> /> <?php if ( $logo_map[$item-1] == $current_logo ) { print '(Current Logo)'; } ?>
							<!--<br />-->
							<!--<button class="btn btn-small btn-info"><i class="icon-trash"></i></button>-->
						</div>
					</div>
					<?php else:
							break;
						endif;
					endfor; ?>
				</div><!--/row-->
				<?php endfor; ?>
				<!--<button type="submit" class="btn btn-primary"><i class="icon-picture icon-white"></i>  Set logo</button>-->
				<?php echo form_close(); ?>
				
				<?php if ( ! is_writable(FCPATH. 'resources/images/logos')): ?>
					<?php echo '<div class="alert alert-error">WARNING: ' . FCPATH. 'resources/images/logos' . " is not writable by the webserver, custom images cannot be uploaded</div>"; ?>
				<?php else: ?>
					<?php // echo br(1); ?>
					<a class="btn btn-primary btn-medium" href="#uploadImageModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Upload your own logo image, the image can be automatically resized if required (requires GD to be installed and enabled in PHP)." data-original-title="Upload Logo"><i class="icon-file icon-white"></i>  Upload Logo</a>
					<?php echo br(2); ?>
				<?php endif; ?>
			</div>
			<div class="tab-pane<?php if ( $this->session->userdata('preferences_tab') === "themes" ) { echo " active"; } ?>" id="themes">
				<h4>Themes</h4>
				<p>Themes are preset combinations of fonts, logos, headers and backgrounds. Click on a theme button to select a theme, or save the current appearance preferences to a new theme.</p>
				<br />
				<?php foreach ( $themes as $theme ): ?>
				<button type="button" class="btn btn-small change_theme" id="<?php echo $theme['theme_name']; ?>" rel="popover" data-content="Click to switch to this theme (current combination of preferences will be lost if not already saved to a theme)." data-original-title="Select Theme"><i class="icon-picture"></i>  <?php echo $theme['theme_name']; ?></button> <a class="btn btn-danger btn-small" href="<?php echo base_url() . "admin/delete_theme/" . $theme['theme_id'];?>" rel="popover" data-content="Permanently delete this theme (action cannot be undone)." data-original-title="Delete Theme"><i class="icon-remove-sign icon-white"></i></a>
				<br /><br />
				<?php endforeach; ?>
				<br /><br /><a class="btn btn-primary" href="#saveThemeModal" data-toggle="modal" data-backdrop="false" rel="popover" data-content="Save the current selection of appearance preferences as a new theme (clicking triggers a popup to enter your theme name)" data-original-title="Save Theme" ><i class="icon-file"></i> Save Theme</a>
				<hr>
			</div>
		</div>
		<a href="<?php echo base_url() . "admin";?>" class="btn" ><i class="icon-home"></i> Admin Dashboard</a><br /><br />
	</div>
</div><!--/.fluid-container-->

<div id="uploadImageModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 align="center" id="myModalLabel">Upload Custom Logo</h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<form method="post" action="<?php echo base_url("admin/upload_logo"); ?>" enctype="multipart/form-data" >
				<label for="userfile"><h4>Select your file (gif, jpg, png):</h4></label>
				<input type="file" name="userfile" id="userfile" size="20" /><br /><br />
				<!--<label for="title">Title</label>-->
				Rename to (optional, do not provide extension): <input type="text" name="title" id="title" value="" /><br />
				<!--<input type="submit" name="submit" id="upload_submit" />-->
				<?php if (function_exists("gd_info")): ?>
				<br />
				<input type="checkbox" name="resize" value="yes" checked> Try to resize?
				<br />
				<?php endif; ?>
				<br />
				<button type="submit" class="btn btn-primary" id="upload_submit"><i class="icon-picture icon-white"></i>  Upload Logo</button>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>

<div id="uploadBackgroundModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 align="center" id="myModalLabel">Upload Custom Background</h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<form method="post" action="<?php echo base_url("admin/upload_background"); ?>" enctype="multipart/form-data" >
				<label for="userfile"><h4>Select your file (gif, jpg, png):</h4></label>
				<input type="file" name="userfile" id="userfile" size="20" /><br /><br />
				Rename to (optional, do not provide extension): <input type="text" name="title" id="title" value="" /><br />
				<br />
				<button type="submit" class="btn btn-primary" id="upload_submit"><i class="icon-picture icon-white"></i>  Upload Logo</button>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>

<div id="saveThemeModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 align="center" id="myModalLabel">Save Theme</h4>
	</div>
	<div class="modal-body">
		<div class="well">
			<form method="post" action="<?php echo base_url("admin/save_theme"); ?>" enctype="multipart/form-data" >
				Theme name: <input type="text" name="theme_name" id="theme_name" value="" /><br />
				<br />
				<button type="submit" class="btn btn-primary" id="theme_submit"><i class="icon-picture icon-white"></i>  Save Theme</button>
			</form>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#" class="btn" data-dismiss="modal">Close</a>  
	</div>
</div>