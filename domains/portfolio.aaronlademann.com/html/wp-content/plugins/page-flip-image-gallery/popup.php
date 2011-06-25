<?php 
error_reporting(0);

$wpconfig = '../../../wp-config.php';
if ( !file_exists($wpconfig) ) $wpconfig = $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';

require_once $wpconfig;

$book_id = (int)$_GET['book_id'];
$book = new Book($book_id);

$title = $book->name;


$sql = "SELECT `bgImage` FROM `".$pageFlip->table_name."` WHERE `id` = '{$book_id}'";
$bgImage = $wpdb->get_var( $sql );

if ( empty( $bgImage ) )
{
	$bgImage = $pageFlip->bgFile;
}
else
{
	if ( $bgImage == "-1" )
		$bgImage = '';
	else
	{
		$sql = "select `filename` from `" . $pageFlip->table_img_name . "` where `id` = '" . $bgImage . "' and `type` = 'bg'";
		$bgImage = $pageFlip->plugin_url . $pageFlip->imagesDir . '/' . $wpdb->get_var( $sql );
	}
}

$backgroundImage = $bgImage;

if (preg_match('/^0x([0-9A-Fa-f]+)$/', $book->backgroundColor, $m))
	$backgroundColor = '#'.$m[1];

$backgroundImageUrl = "url($backgroundImage)";
$backgroundPosition = $book->backgroundImagePlacement;

;echo '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'; echo $title; ;echo '</title>
'; wp_head(); ;echo '<style type="text/css">
body {
	margin: 0;
	font-family: sans-serif;
';
	echo $backgroundColor ? "\tbackground-color: {$backgroundColor};\n" : '';
	echo $backgroundImage ? "\tbackground-image: {$backgroundImageUrl};\n" : '';
	echo $backgroundPosition ? "\tbackground-position: {$backgroundPosition};\n" : '';
;echo '}
body, a {
}
</style>
</head>

<body>
';
	echo $pageFlip->html->viewBook($book, '100%', '100%', $backgroundImage);
;echo '</body>
</html>';
?>