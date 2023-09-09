<?php
require_once("inc/functions.php");
require_once("inc/variables.php");
include("inc/header.php");


$order_ids = implode(",", $requests['ids']);
$orders = shopify_call($token, $shop_url, "/admin/api/2022-07/orders.json", array("status" => "any", "fulfillment_status" => "any", "ids" => $order_ids ), 'GET');
$orders = json_decode($orders['response'], JSON_PRETTY_PRINT);
// echo '<pre>';
// print_r($orders);
// echo '</pre>';
// $json = array(
// 	"metafield" => array(
// 		"namespace" => "custom_fields",
// 		"key" => "my_meta_key",
// 		"value" => "my_meta_value",
// 		"value_type" => "string"
// 	)
// );


// $response = shopify_call($token, $shop_url, '/admin/orders/4577230782695/metafields.json', $json, 'POST', array());
    
// print_r($response);
        
      
?>
<div class="container-fluid">
<input type="hidden" id="customer_id" value="<? echo $uid ?>">
<input type="hidden" id="token" value="<? echo $token ?>">
<input type="hidden" id="shop_url" value="<? echo $shop_url ?>">
<div class="col-lg-12">

<buton class="btn btn-success" id="print">Print</buton>
</div>



<table id="example" class="display" style="width:100%">
    <thead>
        <tr>
            <th></th>
            <th>Order#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
            <th>Reference</th>
            <th>Description</th>
            <th>Price</th>
            <th>Items</th>
            <th>Weight</th>
            <th>Service</th>
            <th>Remarks</th>
            <th hidden>Order ID</th>
        </tr>
    </thead>
    <tbody>
        <?
        foreach($orders['orders'] as $order)
            {
        ?>
        <tr>
          
            
            <td></td>
            <td><input type="text" id="order_number" name="order_number" value="<? echo $order['order_number'] ?>" readonly></td>
            <td><input type="text" id="consignee_name" name="consignee_name" value="<? echo $order['shipping_address']['name'] ?>"></td>
            <td><input type="text" id="consignee_email" name="consignee_email" value="<? echo $order['customer']['email'] ?>"></td>
            <td><input type="text" id="consignee_cell" name="consignee_cell" value="<? echo (($order['shipping_address']['phone'] == '') ? $order['phone'] : $order['shipping_address']['phone']) ?>"></td>
            <td><input type="text" id="consignee_address" name="consignee_address" value="<? echo $order['shipping_address']['address1'] . ' ' .$order['shipping_address']['address2'] ?>"></td>
            <td><input type="text" id="" name="" value="<? echo $order['shipping_address']['city'] ?>" hidden><? include("inc/controls/city.php") ?></td>
            <td><input type="text" id="consignee_reference" name="consignee_reference" value=""></td>
            <td><input type="text" id="consignment_description" name="consignment_description" value="<?foreach($order['line_items'] as $items){echo $items['name'] . ", \n"; foreach($items['properties'] as $properties){echo $properties['value'] . ", ";}}?>"></td>
            <td><input type="text" id="amount" name="amount" value="<? echo ($order['payment_gateway_names'][0]== 'Cash on Delivery (COD)')? $order['current_total_price'] : 0 ?>"></td>
            <td><input type="text" id="pieces" name="pieces" value="<? echo sizeof($order['line_items']) ?>"></td>
            <td><input type="text" id="weight" name="weight" value="<? echo $order['total_weight'] / 1000 ?>"></td>
            <td><? include("inc/controls/services.php") ?></td>
            <td><input type="text" id="consignment_remarks" name="consignment_remarks" value="<? echo $order['note'] ?>"></td>
            <td hidden><input type="text" id="order_id" name="order_id" value="<? echo $order['id'] ?>">
            <input type="text" id="location_id" name="location_id" value="<? echo $order['line_items'][0]['origin_location']['id'] ?>">
            </td>
        </tr>
        <?
              }
        ?>
    </tbody>
    <tfoot>
        <tr>
           <th></th>
            <th>Order#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Address</th>
            <th>City</th>
            <th>Reference</th>
            <th>Description</th>
            <th>Price</th>
            <th>Items</th>
            <th>Weight</th>
            <th>Service</th>
            <th>Remarks</th>
            <th hidden>Order ID</th>
        </tr>
    </tfoot>
</table>
</div>
<?
include("inc/footer.php");
?>

<script src="inc/scripts/bulk-order.js"></script>
