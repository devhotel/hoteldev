<?php require(getFromTemplate("common/header.php", false)); ?>
   <?php  if(isset($_SESSION['user']['id'])){ 
       unset($_SESSION['login_token_id']);
       ?>
          <script> 
            var url= "<?php echo DOCBASE; ?>"; 
            window.location = url; 
         </script> 
     <?php }
    ?>
<section id="page">
    
    <?php include(getFromTemplate("common/page_header.php", false)); ?>
    
    <section id="content" class="pt30 pb30">
        <?php 
         if(isset($_GET['token']) && base64_decode($_GET['token'])== @$_SESSION['login_token_id']){ ?>
          <div class="container" style="text-align:center;">
            <?php echo $page['text']; ?>
            <a class="btn btn_default popup-modal firstLevel" href="#user-popup"> Login </a>
          </div>
        <?php  }else{ ?>
          <script> 
            var url= "<?php echo DOCBASE; ?>"; 
            window.location = url; 
          </script> 
        <?php } ?>
    </section>
</section>
