<?php debug_backtrace() || die("Direct access not permitted"); ?>
<footer>
  <section id="mainFooter">
    <div class="container" id="footer">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <?php displayWidgets("footer_col_3", $page_id); ?>
          <div class="footer_social">
            <a href="https://play.google.com/store/apps/details?id=com.methms&hl=en" target="_blank"><img src="<?php echo getFromTemplate("images/google-play-icon.png"); ?>"></a>
            <a href="https://www.facebook.com/Gupta-Hotels-109171163776532" target="_blank"><img src="<?php echo getFromTemplate("images/facebook-icon.png"); ?>" alt="" /></a>
            <a href="https://www.instagram.com/gupta.hotels/" target="_blank"><img src="<?php echo getFromTemplate("images/instagram-icon.png"); ?>" alt="" /></a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div id="footerRights">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <p style="margin: 0;">
            &copy; <?php echo date("Y"); ?>
            <?php echo OWNER . " " . $texts['ALL_RIGHTS_RESERVED']; ?>
          </p>
        </div>
        <div class="col-md-6">
          <p class="text-right" style="margin: 0;">
            <!--<a href="<?php echo DOCBASE; ?>feed/" target="_blank" title="<?php echo $texts['RSS_FEED']; ?>"><i class="fas fa-fw fa-rss"></i></a>-->
            <?php
            $foot_nav_id = get_top_nav_id($menus['footer']);
            foreach ($menus['footer'] as $nav_id => $nav) { ?>
              <a href="<?php echo $nav['href']; ?>" class="<?php if ($foot_nav_id == $nav_id) echo " active"; ?>" title="<?php echo $nav['title']; ?>"><?php echo $nav['name']; ?></a>
              &nbsp;&nbsp;
            <?php
            } ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</footer>
<?php
if (isset($_SESSION['book']['total']) && $page_id != $sys_pages['booking-activities']['id'] && $page_id != $sys_pages['details']['id'] && $page_id != $sys_pages['summary']['id'] && $page_id != $sys_pages['payment']['id']) { ?>
  <div id="booking-cart" class="alert alert-dismissible">
    <form method="post" class="ajax-form">
      <a href="#" class="close sendAjaxForm" data-action="<?php echo getFromTemplate('common/cancel_booking.php'); ?>" data-dismiss="alert" aria-label="close">&times;</a>
      <?php
      if (isset($_SESSION['book']['rooms']) && count($_SESSION['book']['rooms']) > 0) {
        $rooms = array_keys($_SESSION['book']['rooms']);
        $id_room = array_shift($rooms);
        $result_room_file = $db->query('SELECT * FROM pm_room_file WHERE id_item = ' . $id_room . ' AND checked = 1 AND lang = ' . LANG_ID . ' AND type = \'image\' AND file != \'\' ORDER BY rank');
        if ($result_room_file !== false && $db->last_row_count() > 0) {
          $row = $result_room_file->fetch(PDO::FETCH_ASSOC);
          $file_id = $row['id'];
          $filename = $row['file'];
          $label = $row['label'];
          $realpath = SYSBASE . 'medias/room/small/' . $file_id . '/' . $filename;
          $thumbpath = DOCBASE . 'medias/room/small/' . $file_id . '/' . $filename;
          $zoompath = DOCBASE . 'medias/room/big/' . $file_id . '/' . $filename;
          if (is_file($realpath)) {
            $s = getimagesize($realpath); ?>
            <div class="img-container sm pull-left">
              <img alt="<?php echo $label; ?>" src="<?php echo $thumbpath; ?>">
            </div>
      <?php
          }
        }
      }
      $step = (isset($_SESSION['book']['step'])) ? $_SESSION['book']['step'] : 'details'; ?>
      <a href="<?php echo DOCBASE . $sys_pages[$step]['alias']; ?>" class="alert-link"><?php echo $texts['COMPLETE_YOUR_BOOKING']; ?></a><br>
      <small><?php echo gmstrftime(DATE_FORMAT, @$_SESSION['book']['from_date']); ?> <i class="fas fa-fw fa-arrow-right"></i> <?php echo gmstrftime(DATE_FORMAT, @$_SESSION['book']['to_date']); ?></small><br>
      <?php if (isset($_SESSION['book']['num_rooms'])) echo @$_SESSION['book']['num_rooms'] . ' ' . getAltText($texts['ROOM'], $texts['ROOMS'], @$_SESSION['book']['num_rooms']); ?> -
      <b><?php if (@$_SESSION['book']['total'] > 0) echo ' - ' . formatPrice(@$_SESSION['book']['total']); ?></b>
      <div class="clearfix"></div>
    </form>
  </div>
<?php
} ?>
<a href="#" id="toTop"><i class="fas fa-fw fa-angle-up"></i></a>
<script src="https://apis.google.com/js/client:platform.js?onload=renderButton" async defer></script>
<script>
  var booking_search = function() {
    $("#form_search").submit(); // Submit the form
  }
  var booking_reset = function() {
    $("#form_reset").submit(); // Submit the form
  }
  function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the link that opened the tab
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
  }
</script>
<script>
  $(document).ready(function() {
    $('.check_price').click(function() {
      $('.check_price').not(this).prop('checked', false);
    });
    $('.check_star').click(function() {
      $('.check_star').not(this).prop('checked', false);
    });
    $('.check_desti').click(function() {
      $('.check_desti').not(this).prop('checked', false);
    });
    $('.check_accomo').click(function() {
      $('.check_accomo').not(this).prop('checked', false);
    });
  });
</script>
<script>
  $(function() {
    <?php
    if (isset($msg_error) && $msg_error != "") { ?>
      var msg_error = '<?php echo preg_replace("/(\r\n|\n|\r)/", "", nl2br($msg_error)); ?>';
      if (msg_error != '') {
        $('.alert-danger').html(msg_error);
        $('.alert-danger').slideDown();
      }
      console.log($('.alert-danger').html());
    <?php
    }
    if (isset($msg_success) && $msg_success != "") { ?>
      var msg_success = '<?php echo preg_replace("/(\r\n|\n|\r)/", "", nl2br($msg_success)); ?>';
      if (msg_success != '') $('.alert-success').html(msg_success).slideDown();
    <?php
    }
    if (isset($field_notice) && !empty($field_notice))
      foreach ($field_notice as $field => $notice) echo "$('.field-notice[rel=\"" . $field . "\"]').html('" . $notice . "').fadeIn('slow').parent().addClass('alert alert-danger');\n"; ?>
  });
</script>

<script>
  $(function() {
    if ($('#hidden_btn').is(':visible')) { //if the container is visible on the page
      $('#hidden_btn').trigger("click");
    }
    $("#user_modal_ico").on('click', function() {
      $('.alert').hide();
    });
  });
</script>
<script>
  (function($) {
    $('img').bind('contextmenu', function(e) {
      return false;
    });
  })(jQuery);
</script>
<script type="text/javascript">
  var ct = 0;
  $(document).ready(function() {
    $(".add-row").click(function() {
      $('.addRoomAlert').text('');
      var numItems = $('.room-rows').length;
      if (numItems <= 5) {
        var name = $("#name").val();
        var email = $("#email").val();
        $("#rooms_rows").append(markup);
        update_rmaudkik();
      } else {
        $('.addRoomAlert').text('You have reached maximum rooms booking');
      }
    });
    $("#search_str").focus(function() {
      var innerHTML = "";
      $(this).val(innerHTML);
    });

    $("#search_str").blur(function() {
      var page_id = $('#page_id').val();
      //if (page_id == 9) {
      //var innerHTML = localStorage.getItem("hotel_name");
      //} else {
      var innerHTML = "<?php echo @$destination_name; ?>";
      // }
      $(this).val(innerHTML);
    });

    $("#check_availabilities").click(function() {
      var page_id = $('#page_id').val();
      var destination = $('#destination_id').val();
      if (destination > 0 || page_id == 9) {
        $('#destination_msg').html('');
        $('form#bookingsearch').submit();
      } else {
        //$('#destination_msg').html('<?php echo '<span style="color:Red">' . $texts["DESTINATION_REQUIRED"] . '</span>'; ?>');
        $('#destination_msg').html('');
        $('form#bookingsearch').submit();
      }

    });



    $(".confirm").click(function() {
      var numItems = $('.room-rows').length;
      $('#room_container').fadeToggle();
      update_rmaudkik();
    });

    $("#input_room_row").click(function() {
      $('#room_container').fadeToggle();
      update_rmaudkik();
    });
    // Find and remove selected table rows

  });
  var removeRow = function(el) {
    $(el).parents("div.room-rows").remove();
    $('.addRoomAlert').text('');
    var numItems = $('.room-rows').length;
    update_rmaudkik();
  }

  var update_rmaudkik = function() {
    var adlt = 0;
    var kds = 0;
    jQuery('div.room-rows').each(function(key, val) {
      var rsm = key + 1;
      var room = $(this);
      $(room).find("span.roomCount").text('Room ' + rsm);
      var adultsCount = $(room).find(".guestminus option:selected").val();
      var kidsCount = $(room).find(".guestplus option:selected").val();
      adlt = adlt + parseInt(adultsCount);
      kds = kds + parseInt(kidsCount);
      var guests = adlt + kds;
      if (rsm == 1) {
        $('#sRooms').text(rsm + ' Room');
      } else {
        $('#sRooms').text(rsm + ' Rooms');
      }
      if (adlt == 1) {
        $('#sGuests').text(guests + ' Guest');
      } else {
        $('#sGuests').text(guests + ' Guests');
      }
      /*if(kds==1){
        $('#sGuests').text(adlt+' Guest');  
      }else{
        $('#sGuests').text(adlt+' Guests');
      }*/
      $('#num_adults').val(adlt);
      $('#num_children').val(kds);
      $('#num_rooms').val(rsm);
      $('#num_guests').val(guests);
    });
  }



  var loadscript = function() {

    // And now fire change event when the DOM is ready
    jQuery('select.room_select').each(function(key, val) {
      var room = jQuery(this);
      room.change();
    });
    setTimeout(function() {
      jQuery('select.adult_select').each(function(key, val) {
        var adult = jQuery(this);
        adult.change();
      });
      jQuery('select.kids_select ').each(function(key, val) {
        var kids = jQuery(this);
        kids.change();
      });
    }, 1500);

    setTimeout(function() {
      $('#offer_room_count').hide();
      $('#offer_room_opt').hide();

    }, 500);

  }

  $(window).bind("load", function() {
    setTimeout(function() {
      //loadscript();
      // $('#hidden_btn').click(); 
    }, 2000);
    var page_id = $('#page_id').val();
    if (page_id == 9) {
      //var innerHTML = localStorage.getItem("hotel_name");
      var innerHTML = "<?php echo @$destination_name; ?>";
      setTimeout(
        function() {
          $('#search_str').val(innerHTML);
          var pageURL = $(location).attr("href");
          $('#bookingsearch').attr('action', pageURL);
          $('#bookingsearch').attr('method', 'post');
        }, 500);

    }
  });

  $("select.room_select").change(function() {
    setTimeout(function() {
      jQuery('select.adult_select').each(function(key, val) {
        var adult = jQuery(this);
        adult.change();
      });
      jQuery('select.kids_select ').each(function(key, val) {
        var kids = jQuery(this);
        kids.change();
      });
    }, 1000);
  });
</script>
<!--<script>
var stickEl = $('.sticky'),
  stickyElTop = stickEl.offset().top;

var sticky = function(){  
  var scrollTop = $(window).scrollTop();  
  if (stickyElTop < scrollTop+20) {   
      stickEl.addClass('is-fixed');  
  } else {  
      stickEl.removeClass('is-fixed');   
  }  
};
    
$(window).scroll(function() {
  sticky();
}); 
</script>-->
<script>
  $(function() {
    $('ul.suggest_list').css('display', 'none');
    var hotel_url = '<?php echo DOCBASE; ?>';
    var minlength = 2;
    //$('ul.suggest_list').css('display','none');
    $("#search_str").keyup(function() {
      var that = this,
        search_str = $(this).val();
      //alert(search_str);
      $('ul.suggest_list').css('display', 'none');
      if (search_str.length >= minlength) {
        $.ajax({
          type: "GET",
          url: "<?php echo getFromTemplateNew('common/get_ajax_hotel_location.php'); ?>",
          data: {
            'search_str': search_str
          },
          dataType: "json",
          success: function(json) {
            $('ul.suggest_list').html('');

            if (json.hotels.length > 0) {
              $('ul.suggest_list').css('display', 'block');
              $.each(json.hotels, function(key, data) {
                var li = '<li class="hotelitem" data-id="' + data.id + '" data-alias="' + data.alias + '" data-name="' + data.title + ', ' + data.city + '">' + data.title + ', ' + data.city + '</li>'
                $('ul.suggest_list').append(li);
              })
            }
            if (json.destination.length > 0) {
              $('ul.suggest_list').css('display', 'block');
              $.each(json.destination, function(key, res) {
                var li = '<li class="localitem" data-id="' + res.id + '" data-name="' + res.title + '">' + res.title + '</li>'
                $('ul.suggest_list').append(li);
              })
            }
            if (json.destination.length == 0 && json.hotels.length == 0) {
              $('ul.suggest_list').css('display', 'block');
              var li = '<li style="color:red">Hotel/location Not Found!</li>'
              $('ul.suggest_list').append(li);
            }
          }
        });
      }
    });

    $("ul.suggest_list").on('click', '.hotelitem', function() {
      var go_to_url = hotel_url + 'hotels/' + $(this).attr("data-alias")
      localStorage.setItem("hotel_name", $(this).attr("data-name"));
      //this will redirect us in same window
      document.location.href = go_to_url;
      $('ul.suggest_list').css('display', 'none');
    });

    $("ul.suggest_list").on('click', '.localitem', function() {
      $('#destination_id').val($(this).attr("data-id"));
      $('#search_str').val($(this).attr("data-name"));
      $('ul.suggest_list').css('display', 'none');
      var page_id = $('#page_id').val();
      if (page_id == 9) {
        var pageURL = hotel_url + 'booking/';
        $('#bookingsearch').attr('action', pageURL);
        $('#bookingsearch').attr('method', 'post');

      }
    });

    $("div.nrom").on('click', '.plus', function() {
      var id = $(this).attr("data-p");
      var valId = $('#nrmv_' + $(this).attr("data-p")).val();
      var rooms = parseInt(valId) + 1;
      var maxRoom = findMaxValue($('select#rms_' + id));
      if (rooms > maxRoom) {
        $('#nrmv_' + $(this).attr("data-p")).val(maxRoom);
        $('select#rms_' + id).val(maxRoom);
      } else {
        $('#nrmv_' + $(this).attr("data-p")).val(rooms);
        $('select#rms_' + id).val(rooms);
      }
      $('select#rms_' + id).change();
    });

    $("div.nrom").on('click', '.minus', function() {
      var id = $(this).attr("data-m");
      var valId = $('#nrmv_' + $(this).attr("data-m")).val();
      var rooms = parseInt(valId) - 1;
      var maxRoom = findMaxValue($('select#rms_' + id));
      if (rooms > 0) {
        $('#nrmv_' + $(this).attr("data-m")).val(rooms);
        $('select#rms_' + id).val(rooms);
      }
      $('select#rms_' + id).change();
    });

  });
  var odid = 0;
  var select_room = function(id, rooms) {
    var maxRoom = findMaxValue($('select#rms_' + id));
    $('select.room_select').val(0);
    $('.incrmnt input').val(0);
    $('.rm_select').show();
    $('.rm_remove').hide();
    $('.div_rms').hide();
    if (rooms > maxRoom) {
      $('select#rms_' + id).val(maxRoom);
    } else {
      $('select#rms_' + id).val(rooms);
      $('#nrmv_' + id).val(rooms);
    }
    $('#div_rms_' + id).show();
    if (odid > 0) {
      $('#rms_' + odid).val(0);
      $('select#rms_' + odid).change();
    }

    $('select#rms_' + id).change();
    $('#rm_select_' + id).hide();
    $('#rm_remove_' + id).show();
    odid = id;
  }
  var unselect_room = function(id) {
    //alert(odid);
    $('select#rms_' + id).val(0);
    $('#nrmv_' + id).val(0);
    $('#rms_' + odid).val(0);
    $('select#rms_' + odid).change();
    $('#div_rms_' + id).hide();
    $('select#rms_' + id).change();
    $('#rm_select_' + id).show();
    $('#rm_remove_' + id).hide();
  }


  function findMaxValue(element) {
    var maxValue = undefined;
    $('option', element).each(function() {
      var val = $(this).attr('value');
      val = parseInt(val, 10);
      if (maxValue === undefined || maxValue < val) {
        maxValue = val;
      }
    });
    return maxValue;
  }
</script>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId: '1376685939160319',
      cookie: true,
      xfbml: true,
      version: 'v5.0',
      oauth: true
    });

    FB.AppEvents.logPageView();

  };

  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
      return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>

<script>
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);

    });
  }

  function statusChangeCallback(response) {
    var fbLogin = document.getElementById('facebookLogin'),
      fbLogout = document.getElementById('facebookLogout'),
      greet = document.getElementById('userGreeting'),
      fbStatus = document.getElementById('status');
    console.log(response);
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      App.sendUserData();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      fbStatus.innerHTML = 'Please log into this app.';
      greet.innerHTML = '';
    } else {
      // they are logged into this app or not.
      fbLogin.style.display = 'inline';
      fbLogout.style.display = 'none';
      greet.innerHTML = '';
      fbStatus.innerHTML = 'Login Using';
    }
  }

  /**
   * Check status and show the login dialog
   */
  function loginUsingFacebook() {
    FB.login(function(response) {
      FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
      });
    }, {
      scope: 'public_profile, email'
    });
  }
  /**
   * Logout of facebook
   */
  function logoutFacebook() {
    FB.logout(function(response) {
      FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
      });
    });
  }

  var App = {
    /**
     * Greet the user with a message
     */
    greetUser: function() {
      FB.api('/me?fields=name,email', function(data) {
        var greet = document.getElementById('userGreeting'),
          msg;
        msg = 'Welcome ' + data.name + '.';
        if (typeof data.email !== 'undefined') {
          msg += ' Your email id is ' + data.email;
        }
        alert(msg);
        greet.innerHTML = msg;
      }, {
        'scope': 'email'
      });
    },

    /**
     * Sends the user data to the server side.
     */
    sendUserData: function() {
      FB.api('/me?fields=name,email', function(data) {
        data['authResponse'] = FB.getAuthResponse();
        data['provider'] = 'Facebook';
        data['status'] = 'connected';
        // Store user id for invalidating session later
        localStorage.setItem('app_social_uid', data['id']);
        // Send the user data to the server 
        console.log(data.name + '---' + data.email);
        //alert(data.email);
        $.ajax({
          type: "POST",
          url: "<?php echo base_url('/templates/main/common/register/facebook_callback.php'); ?>",
          data: data,
          cache: false,
          success: function(response) {
            var response = $.parseJSON(response);
            if (response.error != '') $('.alert.alert-danger', 'fieldset.sign').html(response.error).slideDown();
            if (response.success != '') {
              setTimeout(function() {
                if (response.code == '1') {
                  window.location.href = response.redirect;
                }
              }, 1000);
            }
            $('#signspin').hide();
          },
          error: function(error) {
            console.log('Error in sending ajax data');
          }
        });
      }, {
        'scope': 'email'
      });
    },
  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s);
    js.id = id;
    js.src = "https//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
<script>
  // Render Google Sign-in button
  function renderButton() {
    gapi.signin2.render('gSignIn', {
      'scope': 'profile email',
      'width': 240,
      'height': 50,
      'longtitle': true,
      'theme': 'dark',
      'onsuccess': onSuccess,
      'onfailure': onFailure
    });
  }

  // Sign-in success callback
  function onSuccess(googleUser) {
    // Get the Google profile data (basic)
    //var profile = googleUser.getBasicProfile();

    // Retrieve the Google account data
    gapi.client.load('oauth2', 'v2', function() {
      var request = gapi.client.oauth2.userinfo.get({
        'userId': 'me'
      });
      request.execute(function(resp) {
        // Display the user details
        var profileHTML = '<h3>Welcome ' + resp.given_name + '! <a href="javascript:void(0);" onclick="signOut();">Sign out</a></h3>';
        profileHTML += '<img src="' + resp.picture + '"/><p><b>Google ID: </b>' + resp.id + '</p><p><b>Name: </b>' + resp.name + '</p><p><b>Email: </b>' + resp.email + '</p><p><b>Gender: </b>' + resp.gender + '</p><p><b>Locale: </b>' + resp.locale + '</p><p><b>Google Profile:</b> <a target="_blank" href="' + resp.link + '">click to view profile</a></p>';
        //document.getElementsByClassName("userContent")[0].innerHTML = profileHTML;
        //alert(resp.id);
        var user_id = resp.id;
        var sign_user = resp.email;
        //alert(resp.email);
        $.ajax({
          url: "<?php echo base_url('/templates/main/common/register/google_callback.php'); ?>",
          type: "POST",
          cache: false,
          data: {
            sign_user: sign_user
          },
          success: function(response) {
            var response = $.parseJSON(response);
            if (response.error != '') $('.alert.alert-danger', 'fieldset.sign').html(response.error).slideDown();
            if (response.success != '') {
              setTimeout(function() {
                if (response.code == '1') {
                  //location.reload(); 
                  window.location.href = response.redirect;
                }
              }, 1000);
            }
            $('#signspin').hide();
          }
        });
        document.getElementById("gSignIn").style.display = "none";
        //document.getElementsByClassName("userContent")[0].style.display = "block";
        //alert(resp.id);
      });
    });
  }

  // Sign-in failure callback
  function onFailure(error) {
    alert(error);
  }

  // Sign out the user
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function() {
      document.getElementsByClassName("userContent")[0].innerHTML = '';
      document.getElementsByClassName("userContent")[0].style.display = "none";
      document.getElementById("gSignIn").style.display = "block";
    });

    auth2.disconnect();
  }
</script>
</body>

</html>