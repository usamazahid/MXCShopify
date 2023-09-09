<?php

require_once("inc/variables.php");
require_once("inc/functions.php");

// Set variables for our request
$api_key = "ebd83b06470a2eb9b346e4fc73de7e26";
$shared_secret = "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";


$computed_hmac = hash_hmac('sha256', http_build_query($requests), $shared_secret);

// Use hmac data to check that the response is from Shopify or not
if (hash_equals($hmac, $computed_hmac)) {

	// Set variables for our request
	$query = array(
		"client_id" => $api_key, // Your API key
		"client_secret" => $shared_secret, // Your app credentials (secret key)
		"code" => $requests['code'] // Grab the access key from the URL
	);

	// Generate access token URL
	$access_token_url = "https://" . $shop_url . "/admin/oauth/access_token";

	// Configure curl client and execute request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $access_token_url);
	curl_setopt($ch, CURLOPT_POST, count($query));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
	$result = curl_exec($ch);
	curl_close($ch);

	// Store the access token
	$result = json_decode($result, true);
	$access_token = $result['access_token'];

	    
	// Show the access token (don't do this in production!)
	$sql = "INSERT INTO shopify_stores (store_url, access_token) Values('".$shop_url."', '".$access_token."')";
	mysql_query("delete from shopify_stores where store_url = '".$shop_url."'");
	if(mysql_query($sql)){
	    header('Location: https://' . $shop_url . '/admin/apps/mxc');
	    exit();
	}else{
	    echo "Error installation" . mysql_error();
	}

} else {
	// Someone is trying to be shady!
	die('This request is NOT from Shopify!');
}