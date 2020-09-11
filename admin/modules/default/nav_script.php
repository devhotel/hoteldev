<script> 
     $(document).ready(function(){
        $(".sub_toggle").click(function(){ 
          if ($(this).closest('li').find(".sub_spand").hasClass('nav-show')) {
              $(".sub_spand").removeClass('nav-show');
              $(this).parent().children("ul.sub_spand").removeClass('nav-show');
          }else {
              $(".sub_spand").removeClass('nav-show');
              $(this).parent().children("ul.sub_spand").addClass('nav-show');
          }
        });

        $(".toggle").click(function(){ 
          if ($(this).closest('li').find(".spand").hasClass('nav-show')) {
              $(".spand").removeClass('nav-show');
              $(this).parent().children("ul.spand").removeClass('nav-show');
          }else {
              $(".spand").removeClass('nav-show');
              $(this).parent().children("ul.spand").addClass('nav-show');
          }
        });
     });
</script> 