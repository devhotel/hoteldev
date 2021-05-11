<script>
    $(function(){
        var rate_id_room  = $('[name="rate_id_room_0[0]"]');
        var rate_id_hotel  = $('[name="rate_id_hotel_0[0]"]');
        var rate_people  = $('[name="rate_people_0[0]"]');
        var rate_price  = $('[name="rate_price_0[0]"]');
        var rate_tax  = $('[name="rate_taxes_0[0]"]');

        //rate_people.attr('readonly', true);
		function get_room_detail(){
		    var id_room = rate_id_room.val();
		    $.ajax({  
             url: 'get_room.php',
                    type: 'POST',
                    data: {id_room:id_room},
                    dataType :'json',
                    success: function(reg){
                        if(reg.max_people){
                          //rate_people.val(reg.max_people);
                          rate_people.val(1);
                          rate_price.val(reg.price);
                        }else{
                          rate_people.val(0);
                        }
                    }
           });
		}
		rate_id_room.on('change keyup', function(){
			get_room_detail();
		});
		
		function get_tax_detail(){
		    var id_hotel = rate_id_hotel.val();
		    $.ajax({  
             url: 'get_hotel.php',
                    type: 'POST',
                    data: {id_hotel:id_hotel},
                    dataType :'json',
                    success: function(reg){
                        if(reg.id > 0){
                          rate_tax.val(reg.id);
                        }else{
                          rate_tax.val(0);
                        }
                    }
           });
		}
		rate_id_hotel.on('change keyup', function(){
			get_tax_detail();
		});
		
    });
</script>
