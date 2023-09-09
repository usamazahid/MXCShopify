<?
$path = $_SERVER['DOCUMENT_ROOT'];

require_once($path."/api/dbcon.php");

date_default_timezone_set('Asia/Karachi');

$requests = $_GET;
$hmac = $_GET['hmac'];
$shop_url = $_GET['shop'];
$serializeArray = serialize($requests);
$requests = array_diff_key($requests, array( 'hmac' => ''));
ksort($requests);


$sql = "Select count(*), access_token, store_url, mid, verified from shopify_stores where store_url = '".$_GET['shop']."'";

$shop_data = mysql_fetch_array(mysql_query($sql));

$token = $shop_data[1];
$uid = $shop_data[3];

?>