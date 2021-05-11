<script>
    $(function(){
        $("#destination_alias_2_0").attr('readonly', 'readonly');
        
    });
    $('#destination_name_2_0').on('keyup', function(){
        var stt = $(this).val();
        var str = stt.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();
        var str = str.replace(/^\s+|\s+$/gm,'');
        var str = str.replace(/\s+/g, '-');	
        $("#destination_alias_2_0").val(str);
    });
    
</script>