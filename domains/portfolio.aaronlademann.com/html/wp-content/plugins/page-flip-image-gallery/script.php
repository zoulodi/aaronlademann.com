<?php 
ob_start();
error_reporting(0);

if ($_GET['action'] == 'test')
{
	echo "OK\n".$_GET['id'];
	exit();
}

$actions = array( 'loadalbumxml', 'savealbumxml', 'loadlayouts' );
if( !in_array( $_GET['action'], $actions ) || empty( $_GET['id'] ) )
{
	die('You are not allowed to call this page directly.');
}


preg_match('#^(.*)/wp-content/#', str_replace('\\', '/', __FILE__), $m);
$basedir = $m[1];

$debug = fopen($basedir.'/wp-content/pageflip/debug.txt', 'w');

$cookie = isset($_SERVER['HTTP_COOKIE']) ? urlencode( $_SERVER['HTTP_COOKIE'] ) : '';

$post = Array('feAction' => $_GET['action'], 'cookie' => $cookie, 'bookId' => (int)$_GET['id']);

if ( $_GET['action'] == 'savealbumxml' )
{
	$post['xml'] = urlencode(file_get_contents('php://input'));
}

$_POST = &$post;

ob_end_clean();
ob_start();

require_once $basedir.'/wp-config.php';

$content = ob_get_clean();
ob_start();

if ( !empty($content) )
{
	header( 'Content-Type: text/xml' );

	$content = substr( $content, strpos($content, '<?xml') );
	$content = trim($content);
	if ( substr($content, -1) == '0' )
		$content = substr($content, 0, strlen($content) - 1);

	ob_end_clean();
	echo $content;
	ob_start();
}

fwrite($debug, $content);
fclose($debug);

?>