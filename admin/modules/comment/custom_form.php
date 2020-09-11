<script>
    $(function(){
        var comment_item_type = $('[name="comment_item_type_0[0]"]');
        var comment_id_item  = $('[name="comment_id_item_0[0]"]');
        var comment_item  = $('[name="comment_item_0[0]"]');

		function get_item_detail(){
		    var item_type = comment_item_type.val();
		    var id_item = comment_id_item.val();
		    comment_item.empty();
		    $.ajax({  
             url: 'get_item.php',
                    type: 'POST',
                    data: {item_type:item_type,id_item:id_item},
                    dataType :'json',
                    success: function(data){
                        if(data.title){
                          comment_item.val(data.title);
                          comment_item.append($('<option></option>').attr('value', data.id).text(data.id+'|'+data.title));
                        }else{
                          rate_people.val(0);
                        }
                    }
           });
		}
		
	get_item_detail();
		
	
    });
</script>
