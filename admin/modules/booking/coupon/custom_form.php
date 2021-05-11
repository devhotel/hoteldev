<script>
    $(function(){
        
        var coupon_id_hotel  = $('[name="coupon_id_hotel_0[0]"]');
        var coupon_rooms_temp  = $('[name="coupon_rooms_0[0]_tmp[]"]');
        var coupon_rooms  = $('[name="coupon_rooms_0[0][]"]');

		function get_room_detail(st){
		    var id_hotel = coupon_id_hotel.val();
		    coupon_rooms_temp.empty();
		    if(st==1){
		       coupon_rooms.empty();
		    }
		    $.ajax({  
             url: 'get_hotel.php',
                    type: 'POST',
                    data: {id_hotel:id_hotel},
                    dataType :'json',
                    success: function(data){
                         $.each(data, function (key, entry) {
                             var str = entry.hotel;
                             var FC = str.charAt(0);
                             var lword = str.split(" ").pop();
                             var LC = lword.charAt(0);
                             var MN = FC +LC;
                             coupon_rooms_temp.append($('<option></option>').attr('value', entry.id).text(entry.hotel+' | '+entry.title));
                          })
                    }
           });
		}
		coupon_id_hotel.on('change keyup', function(){
			get_room_detail(1);
		});
		get_room_detail(0);
    });
</script>
