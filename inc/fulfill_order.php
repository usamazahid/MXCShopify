<?php
require_once("functions.php");

$token = $_POST['token'];
$shop_url = $_POST['shop_url'];
$data = json_decode($_POST['order']);
echo $data[0];
// foreach ($data as  $value) {
    
//     echo $value;
    
// // $response = shopify_call($token, $shop_url, '/admin/orders/'.$value[1].'/fulfillments.json', array
// //         (
// //           'fulfillment' => array(
// //               'tracking_number' => $value[2],
// //               'notify_customer' => true,
// //               'location_id' => 61483286679
// //             )
// //         ),
// //         'POST', array());
        
        



        
// print_r($response);
// }
?>