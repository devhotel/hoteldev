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
              var u_type = user_type.val();
			 if(u_type=='registered'){
                 $('#permissionWrap').hide();
              }else{
                 $('#permissionWrap').show();
              }
		}
		
		user_type.on('change', function(){
			 var u_type = user_type.val();
			 if(u_type=='registered'){
                 $('#permissionWrap').hide();
              }else{
                 $('#permissionWrap').show();
              }
		});
		updateForm();
		
		$('.selectAll').click(function(e){
          var tr= $(e.target).closest('tr');
           $('td.checkchild input:checkbox',tr).prop('checked',this.checked);
           if(this.checked){
              // $('td.checkchild input:checkbox',tr).prop('readonly',true);
           }else{
               //$('td.checkchild input:checkbox',tr).prop('readonly',false);
           }
           
        });
      $('.checkchild').click(function(e){
          var tr= $(e.target).closest('tr');
          if(this.checked){
           //$('.selectAll',tr).prop('checked',false);
          }else{
           $('.selectAll',tr).prop('checked',false);
          } 
      }); 
	
		$('.someclass :checkbox').bind('click', function () {
            var $chk = $(this), $li = $chk.closest('li'), $ul, $parent ;
            if($li.has('ul')){
                $li.find(':checkbox').not(this).prop('checked', this.checked)
            }
            
            do{
                $ul = $li.parent();
                $parent = $ul.siblings(':checkbox');
                if($chk.is(':checked')){
                    $parent.prop('checked', $ul.has(':checkbox:not(:checked)').length == 0)
                } else {
                    $parent.prop('checked', false)
                }
                $chk = $parent;
                $li = $chk.closest('li');
            } while($ul.is(':not(.someclass)'));
        });
    });
</script>
