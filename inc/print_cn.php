<?
require_once("variables.php");

$data = $_POST['data'];
$uid = $_POST['uid'];

 mysql_query("DELETE from printcns where account ='".$uid."'");

foreach($data as $bookingData){
    
    $cnno = mysql_fetch_array(mysql_query("Select cnno from shopify_orders where shopify_order_number = '".$bookingData['order_id']."' order by serial desc limit 1"));
    echo $cnno[0];
    mysql_query("INSERT INTO printcns (account, cnno) values('".$uid."', '".$cnno[0]."')");
    echo "INSERT INTO printcns (account, cnno) values('".$uid."', '".$cnno[0]."')";
}
echo mysql_error();

?>