<?php
require_once("functions.php");
require_once("variables.php");

// $usid = mysql_fetch_array(mysql_query("SELECT mid, sid, city, User_Id FROM users WHERE username='".$user."'"));
// $uid = $usid[0];

if(isset($_POST['createBooking'])){

$shippername = mysql_real_escape_string($_POST['shippername']) ;
$shippercity = mysql_real_escape_string($_POST['shippercity']) ;
$shipperarea = mysql_real_escape_string($_POST['shipperarea']) ;
$shippercell = mysql_real_escape_string($_POST['shippercell']) ;
$bookingdate = mysql_real_escape_string($_POST['bookingdate']) ;
$shipperlandline = mysql_real_escape_string($_POST['shipperlandline']) ;
$shipperemail = mysql_real_escape_string($_POST['shipperemail']) ;
$pickupaddress = mysql_real_escape_string($_POST['pickupaddress']) ;
$returnaddress = mysql_real_escape_string($_POST['returnaddress']) ;
$consigneename = mysql_real_escape_string($_POST['consigneename']) ;
$consigneeref = mysql_real_escape_string($_POST['consigneeref']) ;
$consigneeemail = mysql_real_escape_string($_POST['consigneeemail']) ;
$consigneecell = mysql_real_escape_string($_POST['consigneecell']) ;
$consigneeaddress = mysql_real_escape_string($_POST['consigneeaddress']) ;
$origincity = mysql_real_escape_string($_POST['origincity']) ;
$destcity = mysql_real_escape_string($_POST['destcity']);
$servicetype = mysql_real_escape_string($_POST['servicetype']) ;
$pieces = mysql_real_escape_string($_POST['pieces']) ;
$weight = mysql_real_escape_string($_POST['weight']) ;
$amount = mysql_real_escape_string($_POST['amount']) ;
$consignmentdescription = mysql_real_escape_string($_POST['consignmentdescription']) ;
$consignmentremarks = mysql_real_escape_string($_POST['consignmentremarks']) ;

$result = mysql_query("SELECT * from newpricing where userId = '".$usid[3]."' ");
$inOrigin = true;
$serviceCharges = 0;

//$destcityID = mysql_fetch_array(mysql_query("SELECT CityID FROM city where CityName = '".$destcity."'"));

while($row = mysql_fetch_assoc($result)){
	if($row['cityId'] == $destcity){
        if($weight <= $row['wFirst']){
            $serviceCharges = $row['pFirst'];
        }elseif($weight <= $row['wSecond']){
            $serviceCharges = $row['pSecond'];
        }elseif($weight <= $row['wThird']){
            $serviceCharges = $row['pThird'];
        }else{
            $thirdPrice = $row['pThird'];
            $remainingPrice =  ceil($weight - $row['wThird']) * $row['addKg'];
           // echo ceil($weight - $row['wThird']);
            $serviceCharges = $thirdPrice + $remainingPrice;
         
            
       
        }
    $inOrigin = false;
	}
}
if($inOrigin){
   $generalRates =mysql_fetch_array(mysql_query("SELECT * from newpricing where userId = '".$usid[3]."' and cityId = 0"));
    if($weight <= $generalRates['wFirst']){
            $serviceCharges = $generalRates['pFirst'];
        }elseif($weight <= $generalRates['wSecond']){
            $serviceCharges = $generalRates['pSecond'];
        }elseif($weight <= $generalRates['wThird']){
            $serviceCharges = $generalRates['pThird'];
        }else{
            $thirdPrice = $generalRates['pThird'];
            $remainingPrice =  ceil($weight - $generalRates['wThird']) * $generalRates['addKg'];
            $serviceCharges = $thirdPrice + $remainingPrice;

        }
}


$type = '-02-0';
$bucket = 0;
if(isset($_POST['bucket'])){
    $bucket = 1;
    $type = "-05-0";
}
$lastentry2 = mysql_fetch_array(mysql_query("SELECT if(max(cnno) is NULL, 10000000, SUBSTRING_INDEX(max(cnno), '-', -1))  + 1 AS cnno FROM bookings where account = ".$uid." and cnno like '%".$type."%'"));
$varcnno2 = $lastentry2[0];
$cnno = $uid.$type.$varcnno2;




$res = mysql_query("INSERT INTO bookings SET 
account='".$uid."',  
bookingdate='".$bookingdate."',  
cnno='".$cnno."',  
shippername='".$shippername."',  
shippercity='".$shippercity."',  
shipperarea='".$shipperarea."',  
shippercell='".$shippercell."',  
shipperlandline='".$shipperlandline."',  
shipperemail='".$shipperemail."',  
pickupaddress='".$pickupaddress."',  
returnaddress='".$returnaddress."',  
consigneename='".$consigneename."',  
consigneeref='".$consigneeref."',  
consigneeemail='".$consigneeemail."',  
consigneecell='".$consigneecell."',  
consigneeaddress='".$consigneeaddress."',  
origincity='".$origincity."',
destcity='".$destcity."',  
servicetype='".$servicetype."',  
pieces='".$pieces."',
status='0',
weight='".$weight."',  
consignmenttype='',  
consignmentdescription='".$consignmentdescription."',  
consignmentremarks='".$consignmentremarks."',  
amount='".$amount."',
serviceCharges = '".$serviceCharges."',
Bucket = '".$bucket."' ");
echo mysql_error();

if($res)
mysql_query("update users set last_cn = '".intval($lastentry2[0] + 1)."' where mid = $uid");
echo $cnno;

echo mysql_error();
}




if(isset($_POST['bulk'])){

$uid = $_POST['bulk'];
$data = $_POST['data'];
$token = $_POST['token'];
$shop_url = $_POST['shop_url'];


$userdet = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE mid='".$uid."' "));
echo mysql_error();
//print_r($data);

$shippername = $userdet[9] . ' ' .$userdet[10];
$shippercity = $userdet[13];
$shipperarea = $userdet[31];
$shippercell = $userdet[16];
$shipperemail = $userdet[17];
$pickupaddress = $userdet[12];
$returnaddress = $userdet[32];


$result = mysql_query("SELECT * from newpricing where userId = '".$userdet[1]."' ");
echo mysql_error();


$lastentry =mysql_fetch_array(mysql_query("SELECT if(max(cnno) is NULL, 10000000, SUBSTRING_INDEX(max(cnno), '-', -1))  + 1 AS cnno FROM bookings where account = ".$uid." and cnno like '%-02-%'"));
echo mysql_error();

$varcnno = $lastentry[0];


$cndata = array();
$shopify_order_num = array();

foreach ($data as  $value) {
    $lastentry2 = $lastentry[0];
	$destcity = mysql_fetch_array(mysql_query("SELECT CityID FROM city where CityName = '".mysql_real_escape_string(strtoupper($value['consignee_city']))."'"));
	echo mysql_error();
	$servicetype = mysql_real_escape_string($value['service_type']);
	$vactoprint = $uid."-02-0" .$varcnno;
	$orderDate = date("Y-m-d");
	$weight = mysql_real_escape_string($value['weight']);
	$inOrigin = true;
    $serviceCharges = 0;
    
    //$destcityID = mysql_fetch_array(mysql_query("SELECT CityID FROM city where CityName = '".$destcity."'"));
    
    while($row = mysql_fetch_assoc($result)){
    	if($row['cityId'] == $destcity[0]){
            if($weight <= $row['wFirst']){
                $serviceCharges = $row['pFirst'];
            }elseif($weight <= $row['wSecond']){
                $serviceCharges = $row['pSecond'];
            }elseif($weight <= $row['wThird']){
                $serviceCharges = $row['pThird'];
            }else{
                $thirdPrice = $row['pThird'];
                $remainingPrice =  ceil($weight - $row['wThird']) * $row['addKg'];
              // echo ceil($weight - $row['wThird']);
                $serviceCharges = $thirdPrice + $remainingPrice;
             
                
           
            }
        $inOrigin = false;
    	}
    }
    if($inOrigin){
      $generalRates =mysql_fetch_array(mysql_query("SELECT * from newpricing where userId = '".$userdet[1]."' and cityId = 0"));
      echo mysql_error();
        if($weight <= $generalRates['wFirst']){
                $serviceCharges = $generalRates['pFirst'];
            }elseif($weight <= $generalRates['wSecond']){
                $serviceCharges = $generalRates['pSecond'];
            }elseif($weight <= $generalRates['wThird']){
                $serviceCharges = $generalRates['pThird'];
            }else{
                $thirdPrice = $generalRates['pThird'];
                $remainingPrice =  ceil($weight - $generalRates['wThird']) * $generalRates['addKg'];
                $serviceCharges = $thirdPrice + $remainingPrice;
    
            }
    }

	
	$cndata[] .= "(
	'$uid', 
	'$orderDate' , 
	'$vactoprint', 
	'$shippername', 
	'$shippercity', 
	'$shippercity', 
	'$shipperarea', 
	'$shippercell', 
	'$shippercell',
	'$shipperemail', 
	'$pickupaddress', 
	'$returnaddress', 
	'".mysql_real_escape_string($value['consignee_name'])."',
	'".mysql_real_escape_string($value['consignee_email'])."',
	'".mysql_real_escape_string($value['order_number'])."', 
	'".mysql_real_escape_string($value['consignee_cell'])."', 
	'".mysql_real_escape_string($value['consignee_address'])."', 
	'$destcity[0]', 
	'$servicetype', 
	'".mysql_real_escape_string($value['pieces'])."', 
	'".mysql_real_escape_string($value['weight'])."', 
	'".mysql_real_escape_string($value['consignment_description'])."', 
	'".mysql_real_escape_string($value['consignment_remarks'])."', 
	'".mysql_real_escape_string($value['amount'])."', 
	'".$serviceCharges."'
	)";
	
	$a = array("mid"=>$uid, "order_id"=>$value['order_id'], "cnno"=> "'$vactoprint'");
	array_push($shopify_order_num, $a);
	


	$varcnno += 1;

		
}


// print_r($shopify_order_num);

$sql = "INSERT into bookings(account, bookingdate, cnno, shippername, origincity, shippercity, shipperarea, shippercell, shipperlandline, shipperemail, pickupaddress, returnaddress, consigneename, consigneeemail, consigneeref, consigneecell, consigneeaddress, destcity, servicetype, pieces, weight, consignmentdescription, consignmentremarks, amount, serviceCharges) values";
$sql .= implode(', ', $cndata);
mysql_query($sql);
echo mysql_error();

$values = array();
foreach ($shopify_order_num as $rowValues) {

    $values[] = "(" . implode(', ', $rowValues) . ")";
}

$add_order = "Insert into shopify_orders(mid, shopify_order_number, cnno) values";
$add_order .=  implode (', ', $values);
mysql_query($add_order);

$shop = shopify_call($token, $shop_url, "/admin/api/2022-07/shop.json", array(), 'GET');
$shop = json_decode($shop['response'], JSON_PRETTY_PRINT);


// $array2 = [];
foreach($shopify_order_num as $son){
    $url = 'https://'.$shop_url.'/admin/api/2022-10/fulfillments.json';
    $trackingUrl = 'http://mxclogistics.com/tracking?cnno=' . $son['cnno']. '&Carrier=MXC'; 
    $requestArray = array (
      'fulfillment' => 
          array (
            'line_items_by_fulfillment_order' => 
            array (
              0 => 
              array (
                'fulfillment_order_id' => $son['order_id'],
              ),
            ),
            'tracking_info' => 
            array (
              'number' => $son['cnno'],
              'url' => $trackingUrl,
            ),
          ),
        );
        // echo json_encode($requestArray);
        // exit;
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestArray));
    
    $headers = array();
    $headers[] = 'X-Shopify-Access-Token:'.$token;
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    echo '<pre>';
    echo 'resuly';
    print_r($result);
    exit;
    curl_close($ch);
        
// 	$response = shopify_call($token, $shop_url, '/admin/orders/'.$son['order_id'].'/fulfillments.json', array
//         (
//           'fulfillment' => array(
//               'tracking_number' => $son['cnno'],
//               'tracking_company'=> 'MXC',
//               'tracking_url' => 'http://mxclogistics.com/tracking?cnno=' . $son['cnno']. '&Carrier=MXC',
//               'notify_customer' => true,
//               'location_id' => $shop['shop']['primary_location_id']
//             )
//         ),
//         'POST', array());
    
}


    
    
print_r($response);
echo mysql_error();
mysql_query("update users set last_cn = '".intval($varcnno + 1)."' where mid = $uid");
//echo $sql;


}


?> 