<?php 
    $getcity = mysql_query("SELECT cityid, cityname FROM city");
?>
<select size="1" id="consignee_city" name="consignee_city" >
<option value="<? echo $order['shipping_address']['city'] ?>" selected><? echo $order['shipping_address']['city'] ?></option>
<?
while($city = mysql_fetch_array($getcity)){
  $selected = '';
  if(strtoupper($city[1])==strtoupper($order['shipping_address']['city'])) $selected = "selected='selected'";
    echo "<option value='" . $city[1] . "' ".$selected." >" . $city[1] . "</option>";
}
?>

</select>