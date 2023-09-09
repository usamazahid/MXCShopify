<?php
require_once("../variables.php");
require_once("../functions.php");

define('SHOPIFY_APP_SECRET', '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'); // Replace with your SECRET KEY

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$res = '';
$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$topic_header = $_SERVER['HTTP_X_SHOPIFY_TOPIC'];
$shop_header = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
$data = file_get_contents('php://input');
$decoded_data = json_decode($data, true);
error_log('Response: '. $data);
error_log('shop_header: '. $shop_header);
$verified = verify_webhook($data, $hmac_header);

if( $verified == true ) {
  if( $topic_header == 'app/uninstalled' || $topic_header == 'shop/update') {
    if( $topic_header == 'app/uninstalled' ) {

      $sql = "DELETE FROM shopify_stores WHERE store_url='".$shop_header."' LIMIT 1";
      $result = mysql_query($sql);

      //$response->shop_domain = $decoded_data['domain'];

      $res = $decoded_data['domain'] . ' is successfully deleted from the database';
    } else {
      $res = $data;
    }
  }
} else {
  $res = 'The request is not from Shopify';
}

error_log('Response: '. $res); //check error.log to see the result
?>