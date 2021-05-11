<?php require(getFromTemplate("common/header.php", false)); ?> 
<section id="page">
    <?php include(getFromTemplate("common/page_header.php", false)); ?>
    <section id="content" class="pt30 pb30">
        <?php 
        if(isset($_SESSION['user'])){
            header("Location: ".DOCBASE.LANG_ALIAS);
            exit();    
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
                                <p><?php echo $texts['NEW_PASSWORD_NOTICE']; ?></p>
                                    <div class="form-group">
                                        <label for="email"><?php echo $texts['EMAIL']; ?><span style="color:red">*</span> </label>
                                        <input type="text" id="email" class="form-control" name="email" value="" placeholder="<?php echo $texts['EMAIL']; ?>">
                                        <div class="field-notice" rel="email"></div>
                                    </div>
                                    <div class="form-group">
                                         <a href="javascript:void(0);" class="btn btn-default btn_login sendAjaxForm" data-action="<?php echo getFromTemplate("common/register/reset.php"); ?>" data-refresh="false"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['NEW_PASSWORD']; ?></a>
                                    </div>
                                    </form>
                                     <h4><a  href="<?php echo getUrl(true).DOCBASE.'sign-up'; ?>"><?php echo $texts['I_SIGN_UP']; ?></a></h4>
                                     <h4><?php echo $texts['ALREADY_HAVE_ACCOUNT']; ?><a  href="<?php echo getUrl(true).DOCBASE.'login'; ?>"><?php echo $texts['LOG_IN']; ?></a></h4>
    	                   </div>
           
	           </div>
          </div>
       <?php  } ?>
    </section>
</section>
