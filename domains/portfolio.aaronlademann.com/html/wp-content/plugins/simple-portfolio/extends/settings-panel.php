<?php

add_action('admin_init', 'portfolio_register_settings');
add_action('admin_menu', 'portfolio_add_option_page');


function portfolio_register_settings() {
	register_setting( 'portfolio-options', 'info-fields', '' );
	register_setting( 'portfolio-options', 'slug', '' );
	register_setting( 'portfolio-options', 'use-xml' );
}

function portfolio_add_option_page() {
	add_options_page('Portfolio Settings', 'Portfolio', 'administrator', 'portfolio-settings', 'portfolio_options');
}

function portfolio_options() {
?>
  	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br /></div>
		<h2>Portfolio Options</h2>
		<form action="options.php" method="post">
		
			<?php settings_fields('portfolio-options'); ?>
			
			<h3>Slug</h3>
			<p>This enables you to change the permalink structure for the portfolio and the related projects.</p>
			<?php
				if (get_option('permalink_structure') != ''):
					echo "<input type=\"text\" name=\"slug\" class=\"regular-text code\" value=\"" . (trim(get_option('slug')) != '' ? get_option('slug') : 'portfolio') . "\" style=\"width:500px;\" />";
				else:
					echo "<p>Permalinks are disabled! <a class=\"button\" href=\"options-permalink.php\">Change Permalinks</a></p>";
				endif;
			?>
		
			<div style="display:block;height:20px;">&nbsp;</div>
			
			<h3>XML output</h3>
			<?php $xml_url = get_site_url() . '/' .  (trim(get_option('slug')) != '' ? get_option('slug') : 'portfolio') . '.xml'; ?>
			<?php
				if (get_option('permalink_structure') != ''):
			?>
				<p>When enabled, <?php echo "<a href=\"$xml_url\" target=\"_blank\">" . $xml_url . "</a>"; ?> generates the xml.</p>
				<p>
					<label title="dont use xml">
						<input type="radio" value="0" name="use-xml" <?php echo (get_option('use-xml') == '0') ? "checked=\"checked\"" : ""; ?>" >
						<span>Disabled</span>
					</label>
				</p>
				<p>
					<label title="generate only portfolio data">
						<input type="radio" value="1" name="use-xml" <?php echo (get_option('use-xml') == '1') ? "checked=\"checked\"" : ""; ?>" >
						<span>Enabled &gt; Portfolio Data</span>
					</label>
				</p>
				<p>
					<label title="generate portfolio and all wordpress data">
						<input type="radio" value="2" name="use-xml" <?php echo (get_option('use-xml') == '2') ? "checked=\"checked\"" : ""; ?>" >
						<span>Enabled &gt; All Data (including WP data such as pages, posts, custom menu's, categories and links) </span>
					</label>
				</p>
			<?php
				else:
					echo "<p>Permalinks are disabled! <a class=\"button\" href=\"options-permalink.php\">Change Permalinks</a></p>";
				endif;
			?>
			
			


			<div style="display:block;height:20px;">&nbsp;</div>
			
			<h3>Common Information Fields</h3>
			<p>Create the fields that are mostly common for each project. These fields will automatically be added to each project.</p>
			<p>Note: When deleting a field that's in use, it does effect the relating field in the project. When deleting make sure it's not in use by any project</p>
			
			<div style="padding:10px;">
				<input type="button" value="Add field" class="button tagadd" onclick="add_field();" style="margin-top:20px;margin-bottom:20px;" />
				<div id="info_fields">
					<ul>
					<?php
						foreach(get_option_preformatted() as $field):
							if (trim($field) != '') :
								echo "<li>";
								echo "<span class=\"drag-handle\"></span>";
								echo "<input type=\"text\" class=\"regular-text code\" value=\"" . htmlentities($field) . "\" style=\"width:500px;\" />";
								echo "<input type=\"button\" value=\"Delete\" onclick=\"delete_field(this);\"  />";
								echo "</li>";
							endif;
						endforeach;
					?>
					</ul>
				</div>
			</div>

			<input type="hidden" name="info-fields" value="" />
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e("Save changes", "portfolio");?>" onclick="save_form();" />
			</p>
	
		</form>
		
		<?php include('credits.php'); ?>
	</div>

	<script type="text/javascript">
		function add_field() {
			var field_html = "<span class=\"drag-handle\"></span>";
			field_html += "<input type=\"text\" class=\"regular-text code\" value=\"\" style=\"width:500px;\" />";
			field_html += "<input type=\"button\" value=\"Delete\" onclick=\"delete_field(this);\"  />";
			jQuery('#info_fields ul').prepend('<li>' + field_html + '</li>');
			jQuery('#info_fields ul li').first().hide();
			jQuery('#info_fields ul li').first().slideDown('fast');
		}
		
		function delete_field( field ) {
			jQuery(field).parent().slideUp('fast', function(e){ jQuery(this).html(''); });
		}
		
		function save_form() {
			var fields = [];
			jQuery('#info_fields input[type="text"]').each(function(index, value){
				var v = jQuery(value).attr('value');
				if (jQuery.trim(v) != '')
					fields.push(v);
			});
			
			jQuery('input[type="hidden"][name="info-fields"]').attr('value', fields.join(','));
			jQuery('form[action="options.php"]').submit();
		}
		
		jQuery(document).ready(function(){
			jQuery('div#info_fields ul').sortable({
				containment: 'parent',
				tolerance: 'pointer',
				handle: '.drag-handle',
				opacity: 0.6
			});
		});

	</script>
	
	<script type='text/javascript' src='<?php echo WP_PLUGIN_URL; ?>/simple-portfolio/js/jquery-ui-1.8.4.custom.min.js'></script>
	
<?php
}

/**
* Get the preformatted common options as array
* @see Settings Panel
*/
function get_option_preformatted() {
	$options = array();
	
	$fields = split(",", get_option('info-fields'));
	foreach ($fields as $field):
		if (trim($field) != '')
			$options[strtolower(str_replace(' ', '_', $field))] = $field;
	endforeach;
	
	return $options;
}

?>