<?php require(getFromTemplate("common/header.php", false));
  require(getFromTemplate("common/register/social_login/core.inc.php", false));
?>
<section id="page">
    <?php include(getFromTemplate("common/page_header.php", false)); ?>
    <section id="content" class="pt30 pb30">
    <?php 
        if(isset($_SESSION['user'])){
               $url = getUrl(true).DOCBASE.'account'; 
               echo '<script>window.location = "'.$url.'";</script>';
        }else{ ?>
          <div class="login_wrapper">
    	   <div class="login_wrapper_inner">
    		 <div class="login_img">
    		   <img src="<?php echo getFromTemplate("images/login-gift-icon.png"); ?>" alt="" />
    		   <h2>Manage your bookings <span>Any time any where</span></h2>
    		   <h2>Our best rates <span>Special discounted member rates</span></h2>
    	   </div>
   
    	    
    	     <div class="login_panel" id="with_email">
    	             <a class="btn fblogin" href="<?php echo $loginUrl; ?>"><i class="fas fa-fw fa-facebook"></i>Login With Facebook</a> <a href="<?php echo $authUrl ?>"> Login With Google</a>
<br>
    	                                             - <?php echo $texts['OR']; ?> -

    	            <h2>Continue with your email</h2>
                        <form method="post" action="<?php echo DOCBASE.$page['alias']; ?>" class="form-horizontal ajax-form">
                            <div class="alert alert-success" style="display:none;"></div>
                            <div class="alert alert-danger" style="display:none;"></div>
                                <div class="form-group">
                                    <label for="email"><?php echo $texts['EMAIL']; ?><span style="color:red">*</span> </label>
                                    <input type="text" id="email" class="form-control" name="user" value="" placeholder="email@hotel.com" required="required" >
                                    <div class="field-notice" rel="user"></div>
                                </div>
                                <div class="form-group">
                                     <label for="password"><?php echo $texts['PASSWORD']; ?> <span style="color:red">*</span></label>
                                        <input type="password" id="password" class="form-control" name="pass" value="" placeholder="******"  >
                                        <div class="field-notice" rel="pass"></div>
                                </div>
                                <div class="form-group">
                                    <a href="javascript:void(0);" class="btn btn-default btn_login sendAjaxForm" data-action="<?php echo getFromTemplate("common/register/login-front.php"); ?>" data-refresh="true"><?php echo $texts['LOG_IN']; ?></a>
                                </div>
                            </form>
                                <h4>Already have an account? <a href="javascript:void(0);" id="btn_login_with_mobile">Login with mobile</a></h4>
                                <h4><a  href="<?php echo getUrl(true).DOCBASE.'forgot-password'; ?>"><?php echo $texts['FORGOTTEN_PASSWORD']; ?></a></h4>
                                <h4><a  href="<?php echo getUrl(true).DOCBASE.'sign-up'; ?>"><?php echo $texts['I_SIGN_UP']; ?></a></h4>
    	                   </div>
    	                    
                        	<div class="login_panel" id="with_mobile" style="display:none;">
                        		<h2>Continue with your mobile no.</h2>
                        		<h3>An OTP will be sent via SMS to verify your number.</h3>
                        		 <form class="form-horizontal ajax-form mopt" id="mobileOtpSend"  action="<?php echo DOCBASE.$page['alias']; ?>" method="post">
                        		        <div class="alert alert-success" style="display:none;"></div>
                                        <div class="alert alert-danger" style="display:none;"></div>
                            		     <div class="form-group">
                            		        <label for="mobile">Mobile <span style="color:red">*</span></label>
                            			    <input class="form-control" id="mobile" type="tel" value="" name="mobile" placeholder="9922558825" >
                            			    <div class="field-notice" rel="mobile"></div>
                            			 </div>
                        			 <div class="form-group">
                        			    <a href="javascript:void(0);" onclick="getOtp();" class="btn btn-default btn_login"  data-refresh="true">Send</a>
                        		     </div>
                        		</form>
                        		<form class="form-horizontal ajax-form glog" id="mobileOtpLogin" style="display:none;"  action="<?php echo DOCBASE.$page['alias']; ?>" method="post">
                        		        <div class="alert alert-success" style="display:none;"></div>
                                        <div class="alert alert-danger" style="display:none;"></div>
                            		     <div class="form-group">
                            		         <input id="user_id" type="hidden" value="" name="user_id">
                            		        <label for="mobile">OTP<span style="color:red">*</span></label>
                            			    <input class="form-control" id="otp" type="tel" value="" name="otp" placeholder="5869" >
                            			    <div class="field-notice" rel="otp"></div>
                            			 </div>
                        			 <div class="form-group">
                        			    <a href="javascript:void(0);" onclick="getVerify();" class="btn btn-default btn_login"  data-refresh="true">Verify</a>
                        		     </div>
                        		</form>
                        		<h4>Already have an account? <a href="javascript:void(0);" id="btn_login_with_email">Login with email</a></h4>
                        		<h4><a  href="<?php echo getUrl(true).DOCBASE.'forgot-password'; ?>"><?php echo $texts['FORGOTTEN_PASSWORD']; ?></a></h4>
                                <h4><a  href="<?php echo getUrl(true).DOCBASE.'sign-up'; ?>"><?php echo $texts['I_SIGN_UP']; ?></a></h4>
                        	</div>
   
	        </div>
         </div>
    <?php  } ?>
    </section>
</section>
<script>
$(document).ready(function(){
  $("#btn_login_with_mobile").click(function(){
     $("#with_email").hide();
     $("#with_mobile").show();
  });
  $("#btn_login_with_email").click(function(){
      $("#with_mobile").hide();
      $("#with_email").show();
  });
});
var getOtp=function(){
    var mobile = $('#mobile').val();
    $('.alert.alert-danger', 'form.mopt').html('').slideUp();
    $('.alert.alert-success','form.mopt').html('').slideUp();
    $.ajax({
    url: "<?php echo getFromTemplate("common/register/send_otp.php"); ?>",
    type: "POST",
    cache: false,
    data: {mobile : mobile},
    success: function(response){
               var response = $.parseJSON(response);
                    if(response.error != '') $('.alert.alert-danger', 'form.mopt').html(response.error).slideDown();
                    if(response.success != ''){
                         $('.alert.alert-success','form.mopt').html(response.success).slideDown();
                         $('#user_id').val(response.user_id);
                         setTimeout( function() 
                          {
                            $("#mobileOtpSend").hide();
                            $("#mobileOtpLogin").show();
                          }, 2000);
                         
                    }
     } 
   });
}
var getVerify=function(){
    var otp = $('#otp').val();
    var user_id = $('#user_id').val();
    $('.alert.alert-danger', 'form.glog').html('').hide();
    $('.alert.alert-success','form.glog').html('').hide();
    $.ajax({
    url: "<?php echo getFromTemplate("common/register/login_otp.php"); ?>",
    type: "POST",
    cache: false,
    data: {otp : otp,user_id:user_id},
    success: function(response){
               var response = $.parseJSON(response);
                    if(response.error != '') $('.alert.alert-danger', 'form.glog').html(response.error).slideDown();
                    if(response.success != ''){
                        $('.alert.alert-success','form.glog').html(response.success).slideDown();
                        setTimeout(
                          function() 
                          {
                           window.location.href = response.redirect;
                          }, 2000);
                          
                    }
        } 
   });
}
</script
