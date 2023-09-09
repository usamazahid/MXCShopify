<?php

// Set variables for our request
$shop = $_GET['shop'];
$api_key = "ebd83b06470a2eb9b346e4fc73de7e26";
$scopes = "write_orders, write_products, read_customers";
$redirect_uri = "https://mxclogistics.com/api/MXCShopify/generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();