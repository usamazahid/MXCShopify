<?php

require_once("inc/functions.php");
require_once("inc/variables.php");
if($shop_data[0] == 0){
    header('Location: install.php?shop=' . $_GET['shop']);
}
require_once("inc/header.php");
?>

<div class="container login-container">
    <div class="row">
        <div class="col-md-12 login-form-1">
            <h3>MXC User Verification</h3>
            <?
            if($shop_data[4] == "1"){
                echo '<div>
                <h2 class="verification">Already Verified</h2>
                </div>';
            }
       
                $array = array(
            	'webhook' => array(
            		'topic' => 'app/uninstalled', 
            		'address' => 'https://mxclogistics.com/api/MXCShopify/inc/Webhooks/delete_store.php?shop=' . $shop_url,
            		'format' => 'json'
                	)
                );
                
                
                $webhook = shopify_call($token, $shop_url, "/admin/api/2021-10/webhooks.json", $array, 'POST');
                
                $webhook = json_decode($webhook['response'], JSON_PRETTY_PRINT);
                
                
            ?>
            <form>
                <div class="form-group">
                    <input type="text" class="form-control" id="apikey" placeholder="Your API Key *" value="" required />
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="username" placeholder="Your Username *" value="" required />
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" placeholder="Your Password *" value="" required/>
                </div>
                <div class="form-group">
                    <input type="submit" id="btnSubmit" class="btnSubmit" value="Verify" />
                </div>
            </form>
        </div>
    </div>
</div>
        
<?php
include("inc/footer.php");
?>
<script>
$('#btnSubmit').on('click', function(){
    
    $("form").submit(function(e){
        e.preventDefault();
    });
    var apikey = $('#apikey').val();
    var username = $('#username').val();
    var password = $('#password').val();
    var shop = '<? echo $_GET['shop'] ?>';

    $.ajax({
            url: "../user_verification.php",
            method: "GET",
            data: {"ApiKey": apikey, "username": username, "password": password, 'shopify': shop},
            success: function(data){
                var result = jQuery.parseJSON(data);
                console.log(result.status);  
                if(result.status == 1){
                    alert("Verification Successful...");
                    window.parent.location.href = "https://" + shop + "/admin/orders";

                }else{
                    alert("Verification Failed...");
                }
            },
            error: function(data){
                console.log(data);
            }
         });
});
        

</script>