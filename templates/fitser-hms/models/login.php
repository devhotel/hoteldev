<?php require(getFromTemplate("common/header.php", false));
  require(getFromTemplate("common/register/social_login/core.inc.php", false));
?>
<section id="page">
    <?php include(getFromTemplate("common/page_header.php", false)); ?>
    <section id="content" class="pt30 pb30">
    <?php 
       $user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
        if(isset($_SESSION['user'])){
               $url = getUrl(true).DOCBASE; 
               echo '<script>window.location = "'.$url.'";</script>';
        }else{ ?>
          <div class="login_wrapper">
    	   <div class="login_wrapper_inner">
    		 <!-- <div class="login_img">
    		   <img src="<?php echo getFromTemplate("images/login-gift-icon.png"); ?>" alt="" />
    		   <h2>Manage your bookings <span>Any time any where</span></h2>
    		   <h2>Our best rates <span>Special discounted member rates</span></h2>
    	    </div> -->
    	         <div class="login_panel">
    	             <?php include(getFromTemplate("common/register/login-view.php", false)); ?>    
                    <div class="social_or">-<?php echo $texts['OR']; ?>-</div>
                    <div class="social_login_bitton">
                    <!--  <fb:login-button scope="public_profile,email" onlogin="checkLoginState();"> </fb:login-button> -->
                    <div class="fb-login-button" data-scope="publish_stream,email" data-onlogin="checkLoginState();" data-width="" data-size="large" data-button-type="continue_with" data-auto-logout-link="false" data-use-continue-as="false"></div>
                   <div id="gSignIn"></div></div>                               
               </div>
	         </div>
      </div>
    <?php  } ?>
    </section>
</section>

<?php include(getFromTemplate("common/register/login_script.php", false)); ?>  
