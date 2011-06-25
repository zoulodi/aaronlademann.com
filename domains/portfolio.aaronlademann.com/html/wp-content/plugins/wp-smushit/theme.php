<?php
/**
 * Admin page for smushing all the images in a given WordPress theme.
 *
 * Expects a `theme` in the query string.
 *
 * @version 1.3
 * @package WP_SmushIt
 */

if ( FALSE == is_admin() ) {
	wp_die(__('You are not logged in to the dashboard.', WP_SMUSHIT_DOMAIN));
}

if ( FALSE === current_user_can('edit_themes') ) {
	wp_die(__('You don\'t have permission to work with themes.', WP_SMUSHIT_DOMAIN));
}

ob_start();

$theme = null;
$theme_path = null;
$theme_url = null;


if ( isset($_GET['theme']) && !empty($_GET['theme']) ) {

	$theme = attribute_escape($_GET['theme']);
	$theme_path = get_theme_root() . '/' . $theme;
	$theme_url = get_theme_root_uri() . '/' . $theme;
}


?>
<div class="wrap">
<div id="icon-plugins" class="icon32"><br /></div><h2>WP Smush.it: Smush Theme Assets</h2>

<?php
	// Smush files
	if (isset($_POST['action']) && $_POST['action'] == 'smush_theme' ):

		if ( function_exists('check_admin_referer') ) check_admin_referer('wp-smushit_smush-theme' . $theme);


?>

<p>Processing files in the <strong><?php echo $theme; ?></strong> theme.</p>

<?php

	foreach($_POST['smushitlink'] as $l) {
			// decode and sanitize the file path
			$asset_url = base64_decode($l);
			$asset_path = str_replace($theme_url, $theme_path, $asset_url);

			print "<p>Smushing <span class='code'>$asset_url</span><br/>";

			list($processed_path, $msg) = wp_smushit($asset_path);

			echo "<em>&#x2013; $msg</em>.</p>\n";
			ob_flush();
		}


?>

<p>Finished processing all the files in the <strong><?php echo $theme; ?></strong> theme.</p>

<p><strong>Actions:</strong> 
	<a href="themes.php?page=<?php echo basename(dirname(__FILE__)) . '/theme.php&amp;theme=' . $theme; ?>" title="<?php _e('Smush other files in this theme', WP_SMUSHIT_DOMAIN); ?>" target="_parent"><?php _e('Smush other files in this theme', WP_SMUSHIT_DOMAIN); ?></a> |

	<a href="<?php echo '?page=' . basename(dirname(__FILE__)) . '/theme.php'; ?>" title="<?php _e('Work with a different theme', WP_SMUSHIT_DOMAIN); ?>" target="_parent"><?php _e('Work with a different theme', WP_SMUSHIT_DOMAIN); ?></a>
</p>


<?php
	// Select files to smush
	elseif( $theme ):
		$td = get_theme_data($theme_path  . '/style.css');

?>

<form method="post" action="">
<input type="hidden" name="action" value="smush_theme"/>
<?php
	if ( function_exists('wp_nonce_field') ) wp_nonce_field('wp-smushit_smush-theme' . $theme);
?>

<table class="widefat fixed" cellspacing="0">
	<thead>
	<tr>
	<th scope="col" id="cb" class="manage-column column-cb check-column" style=""><input type="checkbox" /></th>
	<th scope="col" id="name" class="manage-column column-name" style="">File name</th>
	</tr>
	<tbody>
<?php

	$theme_files = list_files($theme_path, 5);

    foreach($theme_files as $file) {

		if ( preg_match('/\.(jpg|jpeg|png|gif)$/i', $file) < 1 ) {
			continue;
		}

		$file = str_replace(TEMPLATEPATH, '', $file);

		$file_url = $theme_url . $file;


?>
	<tr valign="middle">
		<th scope="row" class="check-column"><input type="checkbox" name="smushitlink[]" value="<?php echo attribute_escape(base64_encode($file_url)); ?>" /></th>
		<td class="column-name"><strong><a class='row-title' href='<?php echo $file_url; ?>'><?php echo $file_url; ?></a></strong></td>
	</tr>
<?php

    }
  //  closedir($handle);
?>
</table>

<input type="submit">
</form>


<?php else: ?>


<p>Select a theme.</p>
<ul>
<?php
	$themes = get_themes();

	foreach($themes as $t) {
		printf("\t<li><a href=\"?page=%s&amp;theme=%s\">%s</a></li>\n",
				basename(dirname(__FILE__)) . '/theme.php',
				$t['Template'],
				$t['Name']);
	}
?>
</ul>
<?php endif; ?>
</div>
