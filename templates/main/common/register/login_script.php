<script>
  $(document).ready(function() {
    $("#details_sign_mobile").hide();
    $("#details_otp_mobile").hide();
    $("#details_password_email").hide();
    $("#details_reset_pass").hide();
    $("#details_verify_otp").hide();
    $("#details_new_password").hide();
    $("#sign_otp_verify").hide();

    $("#mobile_cpn_signup").click(function() {
      $("#details_login_mobile").hide();
      $("#details_otp_mobile").hide();
      $("#details_password_email").hide();
      $("#details_sign_mobile").show();
    });
    $("#mobile_cpn_login").click(function() {
      $("#details_sign_mobile").hide();
      $("#details_otp_mobile").hide();
      $("#details_password_email").hide();
      $("#details_login_mobile").show();
    });
  });


  $('#mail_password').bind('click', function() {
    $('.alert-msg').hide();
    $("#details_otp_mobile").hide();
    $("#details_password_email").show();
  });

  $('#mobile_otp').bind('click', function() {
    $('.alert-msg').hide();
    $("#details_otp_mobile").show();
    $("#details_password_email").hide();
    get_otp_var('otp');
  });

  $('#reset_pass').bind('click', function() {
    $('.alert-msg').hide();
    $("#details_reset_pass").show();
    $("#details_password_email").hide();
  });

  var get_otp_var = function(st) {
    var loguser = $('#loguser').val();
    $("input[name=otp]").val('');
    $('.alert.alert-danger', 'fieldset.login').html('').slideUp();
    $('.alert.alert-success', 'fieldset.login').html('').slideUp();
    $('.alert.alert-danger', 'fieldset.otp').html('').slideUp();
    $('.alert.alert-success', 'fieldset.otp').html('').slideUp();
    $('.alert.alert-danger', 'fieldset.otpv').html('').slideUp();
    $('.alert.alert-success', 'fieldset.otpv').html('').slideUp();

    var logst = false;
    $('#logmsg').html('');
    if (loguser != '') {
      if ($.isNumeric(loguser)) {
        if ((loguser.length < 10)) {
          var msg = 'Please enter a valid Mobile Number.';
          $('#logmsg').html(msg);
          $('#loguser').removeClass('valied').addClass("invalied");
          logst = false;
        } else {
          $('#loguser').removeClass('invalied').addClass("valied");
          logst = true;
        }
      } else {
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        if (testEmail.test(loguser)) {
          $('#loguser').removeClass('invalied').addClass("valied");
          logst = true;
        } else {
          var msg = 'Please enter a valid Email.';
          $('#logmsg').html(msg);
          $('#loguser').removeClass('valied').addClass("invalied");
          logst = false;
        }

      }
    } else {
      var msg = ' Please enter a valid Email Id or Mobile Number.';
      $('#logmsg').html(msg);
      $('#loguser').removeClass('valied').addClass("invalied");
      logst = false;
    }
    if (logst) {
      $('#logspin').show();
      $('#resspin').show();
      $('#vspin').show();
      $.ajax({
        url: "<?php echo getFromTemplate("common/register/otp.php", false); ?>",
        type: "POST",
        cache: false,
        data: {
          loguser: loguser,
          stat: st
        },
        success: function(response) {
          var response = $.parseJSON(response);
          if (response.error != '') $('.alert.alert-danger', 'fieldset.login').html(response.error).slideDown();
          if (response.success != '') {
            $('.alert.alert-success', 'fieldset.login').html(response.success).slideDown();
            $('#otp_user_id').val(response.user_id);
            $('#pass_user_id').val(response.user_id);
            $('#mtxt').text(loguser);
            $('#etxt').text(loguser);
            $('#repass').val(loguser);
            setTimeout(function() {
              if (response.code == '1') {
                $("#details_login_mobile").hide();
                $("#details_otp_mobile").show();
              }
              if (response.code == '2') {
                $("#details_login_mobile").hide();
                $("#details_password_email").show();
              }
              if (response.code == '3') {
                $('.alert.alert-success', 'fieldset.otpv').html(response.success).slideDown();
              }
              if (response.code == '4') {
                $('.alert.alert-success', 'fieldset.otp').html(response.success).slideDown();
              }
            }, 1000);
          }
          $('#resspin').hide();
          $('#logspin').hide();
          $('#vspin').hide();

        }
      });
    }
  }

  var check_otp_var = function() {
    var otp = $('#otp').val();
    var user_id = $('#otp_user_id').val();
    $('.alert.alert-danger', 'fieldset.otp').html('').hide();
    $('.alert.alert-success', 'fieldset.otp').html('').hide();

    var chkotp = false;
    if (otp != '') {
      if ($.isNumeric(otp)) {
        if ((otp.length < 4)) {
          var msg = 'The OTP Must have 4 Characters.';
          //$('#otpmsg').html(msg);
          $('.alert.alert-danger', 'fieldset.otp').html(msg).slideDown();
          $('#otp').removeClass('valied').addClass("invalied");
          chkotp = false;
        } else {
          $('#otp').removeClass('invalied').addClass("valied");
          chkotp = true;
        }
      } else {
        var msg = ' Please enter a valid OTP.';
        //$('#otpmsg').html(msg);
        $('.alert.alert-danger', 'fieldset.otp').html(msg).slideDown();
        $('#otp').removeClass('valied').addClass("invalied");
        chkotp = false;
      }
    } else {
      var msg = ' Please enter a valid OTP.';
      //$('#otpmsg').html(msg);
      $('.alert.alert-danger', 'fieldset.otp').html(msg).slideDown();
      $('#otp').removeClass('valied').addClass("invalied");
      chkotp = false;
    }

    if (chkotp) {
      $('#otpspin').show();
      $.ajax({
        url: "<?php echo getFromTemplate("common/register/login_otp.php"); ?>",
        type: "POST",
        cache: false,
        data: {
          otp: otp,
          user_id: user_id
        },
        success: function(response) {
          var response = $.parseJSON(response);
          if (response.error != '') $('.alert.alert-danger', 'fieldset.otp').html(response.error).slideDown();
          if (response.success != '') {
            //$('.alert.alert-success','fieldset.otp').html(response.success).slideDown();
            setTimeout(
              function() {
                location.reload();
              }, 1000);

          }
          $('#otpspin').hide();
        }
      });
    }
  }

  var get_sign_var = function() {
    var sign_user = $('#sign_user').val();
    var sign_pass = $('#sign_pass').val();
    $('.alert.alert-danger', 'fieldset.sign').html('').slideUp();
    $('.alert.alert-success', 'fieldset.sign').html('').slideUp();
    var signst = false;
    var signsp = false;
    $('#passmsg').html('');
    $('#signmsg').html('');
    if (sign_user == "") {
      var msg = 'Please enter a valid Email or Mobile number.';
      $('#signmsg').html(msg);
      $('#sign_user').removeClass('valied').addClass("invalied");
      signst = false;
    } else {
      if ($.isNumeric(sign_user)) {
        if ((sign_user.length < 10)) {
          var msg = 'Please enter a valid Mobile Number.';
          $('#signmsg').html(msg);
          $('#sign_user').removeClass('valied').addClass("invalied");
          signst = false;
        } else {
          $('#sign_user').removeClass('invalied').addClass("valied");
          signst = true;
        }
      } else {
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        if (testEmail.test(sign_user)) {
          $('#sign_user').removeClass('invalied').addClass("valied");
          signst = true;
        } else {
          var msg = 'Please enter a valid Email.';
          $('#signmsg').html(msg);
          $('#sign_user').removeClass('valied').addClass("invalied");
          signst = false;
        }
      }
    }

    if (sign_pass != "") {
      if (sign_pass.length < 6) {
        var msg = 'The Password Must have atleast 6 Characters';
        $('#passmsg').html(msg);
        $('#sign_pass').removeClass('valied').addClass("invalied");
        signsp = false;
      } else {
        $('#sign_pass').removeClass('invalied').addClass("valied");
        signsp = true;
      }
    } else {
      var msg = 'Please Enter Password.';
      $('#passmsg').html(msg);
      $('#sign_pass').removeClass('valied').addClass("invalied");
      signsp = false;
    }
    if (signst && signsp) {
      $('#signspin').show();
      $.ajax({
        url: "<?php echo getFromTemplate("common/register/short_sign.php"); ?>",
        type: "POST",
        cache: false,
        data: {
          sign_user: sign_user,
          sign_pass: sign_pass
        },
        success: function(response) {
          var response = $.parseJSON(response);
          if (response.error != '') $('.alert.alert-danger', 'fieldset.sign').html(response.error).slideDown();
          if (response.success != '') {
            // $('.alert.alert-success','fieldset.sign').html(response.success).slideDown();
            $('#otp_user_id').val(response.user_id);
            $('#pass_user_id').val(response.user_id);
            $('#mtxt').text(sign_user);
            $('#etxt').text(sign_user);
            $('#vtxt').text(sign_user);
            $('#repass').val(sign_user);
            $('#loguser').val(sign_user);
            setTimeout(function() {
              if (response.code == '1') {

                $("#details_sign_mobile").hide();
                $("#sign_otp_verify").show();
              }
              if (response.code == '2') {
                //$("#details_sign_mobile").hide();
                //$("#details_password_email").show();
                location.reload();
              }

            }, 1000);
          }
          $('#signspin').hide();
        }
      });
    }
  }

  var check_pass_var = function() {
    var pass = $('#mpass').val();
    var user_id = $('#pass_user_id').val();
    $('.alert.alert-danger', 'fieldset.pass').html('').hide();
    $('.alert.alert-success', 'fieldset.pass').html('').hide();
    $('#passspin').show();
    $.ajax({
      url: "<?php echo getFromTemplate("common/register/login_pass.php"); ?>",
      type: "POST",
      cache: false,
      data: {
        pass: pass,
        user_id: user_id
      },
      success: function(response) {
        var response = $.parseJSON(response);
        if (response.error != '') $('.alert.alert-danger', 'fieldset.pass').html(response.error).slideDown();
        if (response.success != '') {
          //$('.alert.alert-success','fieldset.pass').html(response.success).slideDown();
          setTimeout(
            function() {
              location.reload();
            }, 1000);

        }
        $('#passspin').hide();
      }
    });
  }

  var sign_otp_varify = function() {
    var otp = $('#mvotp').val();
    //var user_id = $('#otp_user_id').val();
    $('.alert.alert-danger', 'fieldset.otpv').html('').hide();
    $('.alert.alert-success', 'fieldset.otpv').html('').hide();

    var chkotp = false;
    if (otp != '') {
      if ($.isNumeric(otp)) {
        if ((otp.length < 4)) {
          var msg = 'The OTP Must have 4 Characters.';
          $('#mvmsg').html(msg);
          $('#mvotp').removeClass('valied').addClass("invalied");
          chkotp = false;
        } else {
          $('#mvotp').removeClass('invalied').addClass("valied");
          chkotp = true;
        }
      } else {
        var msg = ' Please enter a valid OTP.';
        $('#mvmsg').html(msg);
        $('#mvotp').removeClass('invalied').addClass("valied");
        chkotp = false;
      }
    } else {
      var msg = ' Please enter a valid OTP.';
      $('#mvmsg').html(msg);
      $('#mvotp').removeClass('valied').addClass("invalied");
      chkotp = false;
    }

    if (chkotp) {
      $('#vrpspin').show();
      $.ajax({
        url: "<?php echo getFromTemplate("common/register/otp_verify_sign.php"); ?>",
        type: "POST",
        cache: false,
        data: {
          otp: otp
        },
        success: function(response) {
          var response = $.parseJSON(response);
          if (response.error != '') $('.alert.alert-danger', 'fieldset.otpv').html(response.error).slideDown();
          if (response.success != '') {
            //$('.alert.alert-success','fieldset.otpv').html(response.success).slideDown();
            setTimeout(
              function() {
                location.reload();
              }, 1000);

          }
          $('#vrpspin').hide();
        }
      });
    }
  }

  var get_otpreset_var = function(st) {
    var loguser = $('#repass').val();
    $('.alert.alert-danger', 'fieldset.rstotp').html('').slideUp();
    $('.alert.alert-success', 'fieldset.rstotp').html('').slideUp();
    $('.alert.alert-danger', 'fieldset.chkotp').html('').hide();
    $('.alert.alert-success', 'fieldset.chkotp').html('').hide();
    var logst = false;
    $('#logmsg').html('');
    if (loguser != '') {
      if ($.isNumeric(loguser)) {
        if ((loguser.length < 10)) {
          var msg = 'Please enter a valid Mobile Number.';
          $('#rstmsg').html(msg);
          $('#loguser').removeClass('valied').addClass("invalied");
          logst = false;
        } else {
          $('#loguser').removeClass('invalied').addClass("valied");
          logst = true;
        }
      } else {
        var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
        if (testEmail.test(loguser)) {
          $('#loguser').removeClass('invalied').addClass("valied");
          logst = true;
        } else {
          var msg = 'Please enter a valid Email.';
          $('#rstmsg').html(msg);
          $('#loguser').removeClass('valied').addClass("invalied");
          logst = false;
        }

      }
    } else {
      var msg = ' Please enter a valid Email Id or Mobile Number.';
      $('#rstmsg').html(msg);
      $('#loguser').removeClass('valied').addClass("invalied");
      logst = false;
    }
    if (logst) {
      $('#rstspin').show();
      $('#rtspin').show();

      $.ajax({
        url: "<?php echo getFromTemplate("common/register/reset_otp.php"); ?>",
        type: "POST",
        cache: false,
        data: {
          loguser: loguser,
          stat: st
        },
        success: function(response) {
          var response = $.parseJSON(response);
          if (response.error != '') $('.alert.alert-danger', 'fieldset.rstotp').html(response.error).slideDown();
          if (response.success != '') {
            $('.alert.alert-success', 'fieldset.rstotp').html(response.success).slideDown();
            $('#otp_user_id').val(response.user_id);
            $('#pass_user_id').val(response.user_id);
            $('#rst_user_id').val(response.user_id);
            $('#rttxt').text(loguser);
            $('#loguser').val(loguser);
            setTimeout(function() {
              if (response.code == '3') {
                $('.alert.alert-success', 'fieldset.chkotp').html(response.success).slideDown();
              }
              $("#details_reset_pass").hide();
              $("#details_verify_otp").show();
            }, 1000);
          }
          $('#rstspin').hide();
          $('#rtspin').hide();

        }
      });
    }
  }

  var check_reset_otp = function() {
    var otp = $('#rsotp').val();
    var user_id = $('#otp_user_id').val();
    $('.alert.alert-danger', 'fieldset.chkotp').html('').hide();
    $('.alert.alert-success', 'fieldset.chkotp').html('').hide();

    var chkotp = false;
    if (otp != '') {
      if ($.isNumeric(otp)) {
        if ((otp.length < 4)) {
          var msg = 'The OTP Must have 4 Characters.';
          //$('#ckmsg').html(msg);
          $('.alert.alert-danger', 'fieldset.chkotp').html(msg).slideDown();
          $('#rsotp').removeClass('valied').addClass("invalied");
          chkotp = false;
        } else {
          $('#rsotp').removeClass('invalied').addClass("valied");
          chkotp = true;
        }
      } else {
        var msg = ' Please enter a valid OTP.';
        //$('#ckmsg').html(msg);
        $('.alert.alert-danger', 'fieldset.chkotp').html(msg).slideDown();
        $('#rsotp').removeClass('invalied').addClass("valied");
        chkotp = false;
      }
    } else {
      var msg = ' Please enter a valid OTP.';
      //$('#ckmsg').html(msg);
      $('.alert.alert-danger', 'fieldset.chkotp').html(msg).slideDown();
      $('#rsotp').removeClass('valied').addClass("invalied");
      chkotp = false;
    }

    if (chkotp) {
      $('#chkspin').show();
      $.ajax({
        url: "<?php echo getFromTemplate("common/register/check_otp.php"); ?>",
        type: "POST",
        cache: false,
        data: {
          otp: otp,
          user_id: user_id
        },
        success: function(response) {
          var response = $.parseJSON(response);
          if (response.error != '') $('.alert.alert-danger', 'fieldset.chkotp').html(response.error).slideDown();
          if (response.success != '') {
            $('.alert.alert-success', 'fieldset.chkotp').html(response.success).slideDown();
            setTimeout(
              function() {
                if (response.code == '1') {
                  $('#npass_user_id').val(response.user_id);
                  $("#details_verify_otp").hide();
                  $("#details_new_password").show();
                }
              }, 1000);

          }
          $('#chkspin').hide();
        }
      });
    }
  }

  var create_new_pass = function() {
    var pass = $('#npass').val();
    var user_id = $('#pass_user_id').val();
    $('.alert.alert-danger', 'fieldset.retpass').html('').hide();
    $('.alert.alert-success', 'fieldset.retpass').html('').hide();
    var signsp = false;
    if (pass != "") {
      if (pass.length < 6) {
        var msg = 'The Password Must have atleast 6 Characters';
        $('#pasmsg').html(msg);
        $('#npass').removeClass('valied').addClass("invalied");
        signsp = false;
      } else {
        $('#npass').removeClass('invalied').addClass("valied");
        signsp = true;
      }
    } else {
      var msg = 'Please Enter a Valid Password.';
      $('#pasmsg').html(msg);
      $('#npass').removeClass('valied').addClass("invalied");
      signsp = false;
    }
    if (signsp) {
      $('#passspin').show();
      $.ajax({
        url: "<?php echo getFromTemplate("common/register/reset_pass.php"); ?>",
        type: "POST",
        cache: false,
        data: {
          pass: pass,
          user_id: user_id
        },
        success: function(response) {
          var response = $.parseJSON(response);
          if (response.error != '') $('.alert.alert-danger', 'fieldset.retpass').html(response.error).slideDown();
          if (response.success != '') {
            $('.alert.alert-success', 'fieldset.retpass').html(response.success).slideDown();
            setTimeout(
              function() {
                $('#pass_user_id').val(response.user_id);
                $("#details_new_password").hide();
                $("#details_password_email").show();
              }, 1000);

          }
          $('#passspin').hide();
        }
      });
    }
  }
</script>