<script>
$(document).ready(function(){
    $("#accommodation_alias_2_0").attr('readonly', 'readonly');
});
    $('#accommodation_name_2_0').on('keyup', function(){
        var stt = $(this).val();
        var str = stt.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();
        var str = str.replace(/^\s+|\s+$/gm,'');
        var str = str.replace(/\s+/g, '-');	
        $("#accommodation_alias_2_0").val(str);
    });
</script>