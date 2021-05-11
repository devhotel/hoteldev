<script>
    $(function(){
		// variables init
        var user_type = $('[name="user_type_0[0]"]');
        var user_role_type = $('[name="user_role_type_2[0]"]');

		
		// updates number of nights
		function updateForm(){
            //user_type.find('option').remove().attr("value",'administrator');
            var role_type = user_role_type.val();
            if(role_type!='administrator'){
              $('[name="user_type_0[0]"] option[value="administrator"]').remove();
              $('[name="user_type_0[0]"] option[value="hotel"]').remove();  
            }
		}
		updateForm();
    });
</script>
