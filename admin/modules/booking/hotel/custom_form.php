<script>
$(document).ready(function(){
    $("#hotel_alias_2_0").attr('readonly', 'readonly');
});
    $('#hotel_title_2_0').on('keyup', function(){
        var stt = $(this).val();
        var str = stt.replace(/[`~!@#$%^&*()_\-+=\[\]{};:'"\\|\/,.<>?\s]/g, ' ').toLowerCase();
        var str = str.replace(/^\s+|\s+$/gm,'');
        var str = str.replace(/\s+/g, '-');	
        $("#hotel_alias_2_0").val(str);
    });
</script>