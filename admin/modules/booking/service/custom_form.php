<script>
    $(function(){
        
        var service_id_hotel  = $('[name="service_id_hotel_2[0]"]');
        var service_rooms_temp  = $('[name="service_rooms_2[0]_tmp[]"]');
        var service_rooms  = $('[name="service_rooms_2[0][]"]');

		function get_room_detail(st){
		    var id_hotel = service_id_hotel.val();
		    service_rooms_temp.empty();
		    if(st==1){
		        service_rooms.empty();
		    }
		    
		    $.ajax({  
             url: 'get_hotel.php',
                    type: 'POST',
                    data: {id_hotel:id_hotel},
                    dataType :'json',
                    success: function(data){
                         $.each(data, function (key, entry) {
                             service_rooms_temp.append($('<option></option>').attr('value', entry.id).text(entry.hotel+' | '+entry.title));
                          })
                    }
           });
		}
		service_id_hotel.on('change keyup', function(){
			get_room_detail(1);
		});
		get_room_detail(0);
    });
</script>
