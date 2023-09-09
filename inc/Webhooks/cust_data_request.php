<?php

define('SHOPIFY_APP_SECRET', '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');

function verify_webhook($data, $hmac_header)
{
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];

$data = file_get_contents('php://input');
$verified = verify_webhook($data, $hmac_header);
error_log('Webhook verified: '.var_export($verified, true)); //check error.log to see the result
if ($verified) {
error_log('Webhook verified: '.var_export($verified, true)); //check error.log to see the result
} else {
  http_response_code(401);
}
?>