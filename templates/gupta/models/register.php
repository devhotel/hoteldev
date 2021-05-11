<?php require(getFromTemplate("common/header.php", false)); ?> 
<section id="page">
    <?php include(getFromTemplate("common/page_header.php", false)); ?>
    <section id="content" class="pt30 pb30">
        <?php 
        if(isset($_SESSION['user'])){
               
        }else{ ?>
          <div class="login_wrapper">
            
    	
    	   <div class="login_wrapper_inner">
    		 <div class="login_img">
    		   <img src="<?php echo getFromTemplate("images/login-gift-icon.png"); ?>" alt="" />
    		   <h2>Manage your bookings <span>Any time any where</span></h2>
    		   <h2>Our best rates <span>Special discounted member rates</span></h2>
    	   </div>
    	    
    	     <div class="login_panel">
    	        <h2>Continue with your email</h2>
                    <form method="post" action="<?php echo DOCBASE.$page['alias']; ?>" class="form-horizontal ajax-form">
                              <div class="alert alert-success" style="display:none;"></div>
                               <div class="alert alert-danger" style="display:none;"></div>
                                <input type="hidden" name="signup_type" value="quick" class="noreset">
                                
                                   <input type="hidden" name="signup_redirect" value="<?php echo getUrl(true).DOCBASE.$sys_pages['account']['alias']; ?>" class="noreset">
                                        <div class="form-group">
                                                <label for="mobile"><?php echo $texts['MOBILE']; ?> <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" id="mobile" name="mobile" value="" placeholder="9898989898">
                                            
                                            <div class="field-notice" rel="mobile"></div>
                                        </div>
                                         <div class="form-group">
                                                <label for="firstname"><?php echo $texts['FIRSTNAME']; ?> <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="firstname" value="" placeholder="<?php echo $texts['FIRSTNAME']; ?>">
                                                <div class="field-notice" rel="firstname"></div>
                                        </div>
                                        <div class="form-group">
                                                <label for="lastname"><?php echo $texts['LASTNAME']; ?> <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="lastname" value="" placeholder="<?php echo $texts['LASTNAME']; ?>">
                                                <div class="field-notice" rel="lastname"></div>
                                        </div>
                                        <div class="form-group">
                                                <label for="lastname"><?php echo $texts['EMAIL']; ?> <span style="color:red">*</span></label>
                                                <input type="text" class="form-control" name="email" value="" placeholder="<?php echo $texts['EMAIL']; ?>">
                                                <div class="field-notice" rel="email"></div>
                                        </div>
                                        <div class="form-group">
                                                <label for="password"><?php echo $texts['PASSWORD']; ?> <span style="color:red">*</span></label>
                                                <input type="password" class="form-control" name="password" value="" placeholder="<?php echo $texts['PASSWORD']; ?>">
                                                <div class="field-notice" rel="password"></div>
                                        </div>
                                        <div class="form-group">
                                               <label for="password"><?php echo $texts['PASSWORD_CONFIRM']; ?> <span style="color:red">*</span></label>
                                               <input type="password" class="form-control" name="password_confirm" value="" placeholder="<?php echo $texts['PASSWORD_CONFIRM']; ?>">
                                               <div class="field-notice" rel="password_confirm"></div>
                                        </div>
                                        <input type="hidden" name="postcode" value="" class="noreset">
                                        <input type="hidden" name="city" value="" class="noreset">
                                        <input type="hidden" name="company" value="" class="noreset">
                                        <input type="hidden" name="country" value="" class="noreset">
                                        <!--<div class="form-group">
                                            <input type="hidden" name="hotel_owner" value="0" />
                                            <input type="radio" name="hotel_owner" id="hotel_owner_1" value="1"> <label for="hotel_owner_1"><?php echo $texts['I_AM_HOTEL_OWNER']; ?></label> &nbsp;
                                            <input type="radio" name="hotel_owner" id="hotel_owner_0" value="0" checked="checked"> <label for="hotel_owner_0"><?php echo $texts['I_AM_TRAVELER']; ?></label>
                                        </div>-->
                                        <div class="form-group">
                                                <a href="javascript:void(0);" class="btn btn-default btn_login sendAjaxForm" data-action="<?php echo getFromTemplate("common/register/signup-front.php"); ?>" data-clear="true"><?php echo $texts['SIGN_UP']; ?></a>
                                        </div>
                                    </form>
                                     <h4><?php echo $texts['ALREADY_HAVE_ACCOUNT']; ?><a  href="<?php echo getUrl(true).DOCBASE.'login'; ?>"><?php echo $texts['LOG_IN']; ?></a></h4>
                                     <h4><a  href="<?php echo getUrl(true).DOCBASE.'forgot-password'; ?>"><?php echo $texts['FORGOTTEN_PASSWORD']; ?></a></h4>
    	                   </div>
           
	         </div>
          </div>
       <?php  } ?>
    </section>
</section>
