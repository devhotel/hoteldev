<script>
    $(function(){
       
		// variables init
        var room_max_adults = $('[name="room_max_adults_2[0]"]');
        var room_max_children = $('[name="room_max_children_2[0]"]');
        var room_max_people = $('[name="room_max_people_2[0]"]');
        var room_min_people  = $('[name="room_min_people_2[0]"]');
        var room_stock  = $('[name="room_stock_2[0]"]');


		// updates number of nights
		function updateMax(){
			var max_adults= room_max_adults.val();
			var max_children= room_max_children.val();
			var max_people = parseInt(max_adults) + parseInt(max_children);
			//alert(max_people);
			//room_max_people.val(max_people);
			if(isNaN(max_people)) {
                max_people = 0;
            }
			room_max_people.empty().append('<option selected="selected" value="'+max_people+'">'+max_people+'</option>');
            room_max_people.attr("readonly", true); 
            room_min_people.find('option').remove();
            for (var i = 1; i < max_people; i++) {
                var option = document.createElement("option");
                option.value = i;
                option.text = i;
                room_min_people.append(option);
            }
		}
		
		room_max_adults.on('change keyup', function(){
			updateMax();
		});
		room_max_children.on('change keyup', function(){
			updateMax();
		});
		updateMax();
		
		function roomCounter(){
		     var rCount= room_stock.val();
             var inputFormDiv = document.getElementById('table_room_names');
             var fc =inputFormDiv.querySelectorAll('.fc_room_names_name').length;
                if(fc <= rCount){
                    for(var i=fc; i<rCount;i++){
                        $('#entry_room_names').click();
                    }
                }else{
                    alert('Please remove room alocation then reduce number of rooms');
                    room_stock.val(fc);
                }
            document.getElementById('entry_room_names').style.display='none'; 
		}
		
		room_stock.on('change', function(){
			roomCounter();
		});
		document.getElementById('entry_room_names').style.display='none';
       //roomCounter();
        
        $("#room_alias_2_0").attr('readonly', 'readonly');
        $('#room_title_2_0').on('keyup', function(){
            var stt = $(this).val();
            var str = stt.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();
            var str = str.replace(/^\s+|\s+$/gm,'');
            var str = str.replace(/\s+/g, '-');	
            $("#room_alias_2_0").val(str);
        });
    });
  
</script>
