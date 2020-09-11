<script>
    $(function(){
        $("#page_alias_2_0").attr('readonly', 'readonly');
		// variables init
        var page_id = $('[name="id"]');

		function updateForm(){
            //user_type.find('option').remove().attr("value",'administrator');
            var id = page_id.val();
            $('[name="page_id_parent_2[0]"] option[value="'+id+'"]').remove();
		}
		updateForm();
    });
     $('#page_name_2_0').on('keyup', function(){
        var stt = $(this).val();
        var str = stt.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();
        var str = str.replace(/^\s+|\s+$/gm,'');
        var str = str.replace(/\s+/g, '-');	
        $("#page_alias_2_0").val(str);
    });
</script>
