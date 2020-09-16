<!-- Mobile LOGIN  -->
                          <fieldset class="login" id="details_login_mobile" <?php echo ($user_id == 0) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="alert alert-success alert-msg" style="display:none;"></div>
                                <div class="alert alert-danger alert-msg" style="display:none;"></div>
                                <p><span class="span_login"><h2>Continue with your Mobile/Email</h2></span>
                                <span class="span_sign"> or <a href="javascript:void(0)" class="" id="mobile_cpn_signup"> Create New Account </a></span></p>
                                <div class="form-group">
                                    <label class="control-label">Email or Mobile Number <sup style="color:red">*</sup></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="loguser" name="loguser" value="" placeholder="Enter email or Mobile No."  autocomplete="off"/>
                                        <div class="field-notice" id="logmsg"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                         <a href="javascript:void(0);" class="btn btn-primary"  onclick="get_otp_var('login');" ><span id="logspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>Continue </a>
                                    </div>
                                </div>
                            </fieldset>
                            
                            <!-- OTP VARIFY -->
                            <fieldset class="otp" id="details_otp_mobile" <?php echo ($user_id == 0) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="alert alert-success alert-msg" style="display:none;"></div>
                                <div class="alert alert-danger alert-msg" style="display:none;"></div>
                                <p><span class="span_login"><h2>Login Via OTP</h2></span>
                                <span class="otptext">Please enter the OTP sent to <span id="mtxt"></span> to login </span></p>
                                <div class="form-group">
                                    <label class="control-label">Enter Valid OTP</label>
                                    <div class="otp_resend_div">
                                          <a href="javascript:void(0);" onclick="get_otp_var('otp');" id="resend_otp"><span id="resspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>Resend OTP</a>
                                        <input type="text" class="form-control" id="otp" name="otp" value="" placeholder="Enter OTP"  autocomplete="off"/>
                                        <input type="hidden" id="otp_user_id" name="user_id" value="">
                                        <div class="field-notice" id="otpmsg"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                         <a href="javascript:void(0);" class="btn btn-primary" onclick="check_otp_var();" ><span id="otpspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>LOGIN</a>
                                         <a href="javascript:void(0);" id="mail_password">or Login via Password</a>
                                    </div>
                                    
                                </div>
                            </fieldset>
                            
                            <!-- Email Mobile sign up -->
                            <fieldset class="sign"  id="details_sign_mobile" <?php echo ($user_id == 0) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="alert alert-success alert-msg" style="display:none;"></div>
                                <div class="alert alert-danger alert-msg" style="display:none;"></div>
                                <p><span class="span_login"><h2> Create New Account </h2></span>
                                <span class="span_sign"><small> <?php echo $texts['ALREADY_HAVE_ACCOUNT']; ?></small><a href="javascript:void(0)" class="" id="mobile_cpn_login"><?php echo $texts['LOG_IN']; ?></a></span></p>
                                <div class="form-group">
                                    <label class="control-label">Email or Mobile Number <sup style="color:red">*</sup></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="sign_user" name="user" value="" placeholder="Enter email Or Mobile No."  autocomplete="off"/>
                                        <div class="field-notice" id="signmsg"></div>
                                        <div class="field-message" rel="signuser">OTP verification is required when account will be created using mobile number</div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Please enter <?php echo $texts['PASSWORD']; ?> <sup style="color:red">*</sup></label>
                                    <div class="">
                                        <input type="password" class="form-control" id="sign_pass" name="pass" placeholder="Enter Password"  autocomplete="off"/>
                                        <div class="field-notice" id="passmsg"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                         <a href="javascript:void(0);" class="btn btn-primary"  onclick="get_sign_var();" data-refresh="true"><span id="signspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>Continue</a>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- Sign OTP VARIFY -->
                            <fieldset class="otpv" id="sign_otp_verify" <?php echo ($user_id == 0) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="alert alert-success alert-msg" style="display:none;"></div>
                                <div class="alert alert-danger alert-msg" style="display:none;"></div>
                                <p><span class="span_login"><h2>Verify Your Mobile Number</h2></span>
                                <span class="otptext">Please enter the OTP sent to <span id="vtxt"></span> to login </span></p>
                                <div class="form-group">
                                    <label class="control-label">Enter Valid OTP</label>
                                    <div class="otp_resend_div">
                                        <a href="javascript:void(0);" onclick="get_otp_var('otpv');" id="resend_otp"><span id="vspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>Resend OTP</a>
                                        <input type="text" class="form-control" id="mvotp" name="otp" value="" placeholder="Enter OTP"  autocomplete="off"/>
                                        <div class="field-notice" id="mvmsg"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                         <a href="javascript:void(0);" class="btn btn-primary" onclick="sign_otp_varify();" ><span id="vrpspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>VERIFY</a>
                                        <!--  <a href="javascript:void(0);" id="mail_password">or Login via Password</a> -->
                                    </div>
                                </div>
                            </fieldset>
                            
                            <!-- Pass VARIFY -->
                            <fieldset class="pass"  id="details_password_email" <?php echo ($user_id == 0) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="alert alert-success alert-msg" style="display:none;"></div>
                                <div class="alert alert-danger alert-msg" style="display:none;"></div>
                                <p><span class="span_login"><h2> Login With Password </h2></span>
                                <span class="emailtext">Please enter the password for <span id="etxt"></span></span></p>
                                <div class="form-group">
                                    <label class="control-label"> Enter <?php echo $texts['PASSWORD']; ?>  <sup style="color:red">*</sup></label>
                                    <div class="otp_resend_div">
                                         <a href="javascript:void(0);" id="reset_pass"><span id="repspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>Reset Password</a>
                                         <input type="password" class="form-control" id="mpass" name="pass" value="" placeholder="Enter Password"  autocomplete="off"/>
                                         <input type="hidden" id="pass_user_id" name="user_id" value="">
                                         <div class="field-notice" id="pvsmsg"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                         <a href="javascript:void(0);" class="btn btn-primary" onclick="check_pass_var();"  data-refresh="true"><span id="passspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span> Login</a>
                                         <a href="javascript:void(0);" id="mobile_otp">or Login via OTP</a>
                                    </div>
                                </div>
                            </fieldset>

                            <!-- New pass reset -->
                            <fieldset class="rstotp" id="details_reset_pass" <?php echo ($user_id == 0) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="alert alert-success alert-msg" style="display:none;"></div>
                                <div class="alert alert-danger alert-msg" style="display:none;"></div>
                                <p><span class="span_login"><h2>Reset Password</h2></span>
                                <span> We will send you a reset OTP on your registered E-mail ID or Mobile Number </span></p>
                                <div class="form-group">
                                    <label class="control-label">Registered Email or Mobile Number <sup style="color:red">*</sup></label>
                                    <div class="">
                                        <input type="text" class="form-control" id="repass" name="repass" value="" placeholder="Enter email or Mobile No."  autocomplete="off"/>
                                        <div class="field-notice" id="rstmsg"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                         <a href="javascript:void(0);" class="btn btn-primary"  onclick="get_otpreset_var('rstotp');" ><span id="rstspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>Continue </a>
                                    </div>
                                </div>
                            </fieldset>


                            <!-- Reset OTP VARIFY -->
                            <fieldset class="chkotp" id="details_verify_otp" <?php echo ($user_id == 0) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="alert alert-success alert-msg" style="display:none;"></div>
                                <div class="alert alert-danger alert-msg" style="display:none;"></div>
                                <p><span class="span_login"><h2>Enter OTP</h2></span>
                                <span class="otptext">Please enter the OTP sent to <span id="rttxt"></span> to reset your passsword</span></p>
                                <div class="form-group">
                                    <label class="control-label">Enter Valid OTP</label>
                                    <div class="otp_resend_div">
                                          <a href="javascript:void(0);" onclick="get_otpreset_var('chkotp');" id="resend_otp"><span id="rtspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>Resend OTP</a>
                                        <input type="text" class="form-control" id="rsotp" name="otp" value="" placeholder="Enter OTP"  autocomplete="off"/>
                                        <input type="hidden" id="rst_user_id" name="user_id" value="">
                                        <div class="field-notice" id="ckmsg"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                         <a href="javascript:void(0);" class="btn btn-primary" onclick="check_reset_otp();" ><span id="chkspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span>VERIFY</a>
                                    </div>
                                    
                                </div>
                            </fieldset>


                            <!-- New pass reset -->
                            <fieldset class="retpass"  id="details_new_password" <?php echo ($user_id == 0) ? 'style="display:block"' : 'style="display:none"'; ?>>
                                <div class="alert alert-success alert-msg" style="display:none;"></div>
                                <div class="alert alert-danger alert-msg" style="display:none;"></div>
                                <p><span class="span_login"><h2> Create New Password </h2></span>
                                <span class="emailtext">Must be atleast 6 characters in length</span></p>
                                <div class="form-group">
                                    <label class="control-label">New <?php echo $texts['PASSWORD']; ?>  <sup style="color:red">*</sup></label>
                                    <div class="">
                                        <input type="password" class="form-control" id="npass" name="npass" value="" placeholder="Enter Password"  autocomplete="off"/>
                                        <input type="hidden" id="npass_user_id" name="user_id" value="">
                                        <div class="field-notice" id="pasmsg"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="text-center">
                                         <a href="javascript:void(0);" class="btn btn-primary" onclick="create_new_pass();"  data-refresh="true"><span id="passspin" style="display:none;"><i class="fa fa-spinner fa-spin"></i></span> Create Password</a>
                                        <!--  <a href="javascript:void(0);" id="mobile_otp">or Login via OTP</a> -->
                                    </div>
                                </div>
                            </fieldset>