$(document).ready(function() {
    
    var citiesList = [];


    $.ajax({
        url: "/api/citylist.php",
        method: "get",
        dataType: 'json',
        success: function(data){
            
        citiesList = data;
        console.log(citiesList);
        console.log(citiesList.findIndex(x => x.CityName ==="ISLAMABAD"));
        },
        error: function(data){
            console.log(data);
        }
    });
 
    var table = $('#example').DataTable({
        searching: false,
        columnDefs: [{
            'checkboxes': {
            'selectRow': true
            },
            targets:   0
        }],
         select: {
            style:    'multi',
            selector: 'td:first-child'
        }
    });

    $('#submit').click( function() {
        
    
    
    var customer_id = $('#customer_id').val();
    var token = $('#token').val();
    var shop_url = $('#shop_url').val();
    
    var data = table.rows({selected:  true}).nodes().to$();
    var bookingData = [];
    var error = false;
    
    
    $.each(data, function(index, rowId){
        
    let obj = Object.fromEntries(new URLSearchParams($(rowId).find('input, select').serialize()));
    
    var cityIndex = citiesList.findIndex(x => x.CityName.trim() === obj.consignee_city.toUpperCase().trim());
    
        if( cityIndex < 0){
          alert("Invalid City Name: " + obj.consignee_city);
              error = true;
              return false;
        }else if(obj.weight  == "" || obj.weight <= 0){
          alert("Weight Cannot be Empty or 0");
              error = true;
              return false;
         }
         
    bookingData.push(obj);
    
    });
    

   if(!error && bookingData.length >= 1){
   // console.log(bookingData); 
    // $(this).prop('disabled', true);

    $.ajax({
        url: "inc/create_order.php",
        method: "post",
        crossDomain: true,
        data: {"bulk":customer_id, "data" : bookingData, "token":token, "shop_url":shop_url},
        success: function(data){
            console.log(data);
            $('#print').removeAttr('hidden');
        },
        error: function(data){
            console.log(data);
        }
    });
   }    
    
    
    
    });
    
    
  $("#consignee_city").select2( {
  allowClear: true,
  width:'100%'
  } );
  
  
    $('#print').click( function() {
        
    var customer_id = $('#customer_id').val();
    var data = table.rows({selected:  true}).nodes().to$();
    var bookingData = [];
    var error = false;
    
    $.each(data, function(index, rowId){
        
    let obj = Object.fromEntries(new URLSearchParams($(rowId).find('input, select').serialize()));
    
    bookingData.push(obj);
    
    });
    

   if(!error && bookingData.length >= 1){
    console.log(bookingData); 


    $.ajax({
        url: "inc/print_cn.php",
        method: "post",
        crossDomain: true,
        data: {"uid":customer_id, "data" : bookingData},
        success: function(data){
          // Create a form
            var mapForm = document.createElement("form");
            mapForm.target = "_blank";    
            mapForm.method = "POST";
            mapForm.action = "slip.php";
            
            // Create an input
            var mapInput = document.createElement("input");
            mapInput.type = "hidden";
            mapInput.name = "uid";
            mapInput.value = customer_id;
            
            // Add the input to the form
            mapForm.appendChild(mapInput);
            
            // Add the form to dom
            document.body.appendChild(mapForm);
            
            // Just submit
            mapForm.submit();
        },
        error: function(data){
            console.log(data);
        }
    });
    
   }    
    
    
    
    });
    
});
