<?php debug_backtrace() || die("Direct access not permitted"); ?>
<!-- Navigation -->
<?php
$_SESSION['module_referer'] = 'booking';
$csrf_tokendash = get_token('list');
?>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <div class="admin_header_left">
      <div class="logo">
        <a class="navbar-brand" href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/"><img src="<?php echo DOCBASE . ADMIN_FOLDER; ?>/images/admin-logo.png" alt="" /></a>
      </div>
      <div class="booking_search">
        <form id="form" action="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/booking/index.php?view=list" method="get">
          <input type="hidden" name="view" value="list" />
          <input type="hidden" name="csrf_token" value="<?php echo $csrf_tokendash; ?>" />
          <input id="list_q_search" placeholder="Enter Booking ID" type="text" value="" name="q_search" class="input_search"> <input type="submit" id="search" class="booking_search_sub" name="search">
        </form>
      </div>
    </div>
    <div class="admin_header_right">
      <?php if ($_SESSION['admin']['id'] == 1 || ((in_array('add', $permissions) || in_array('all', $permissions)) && MODULE == 'booking')) { ?>
        <div class="new_link">
          <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/booking/new.php?view=form&id=0" class="a_notifications">New Booking</a>
        </div>
      <?php } ?>
      <!-- <div class="info_links">
        <a href="" class="a_notifications">
          <img src="<?php echo DOCBASE . ADMIN_FOLDER; ?>/images/notification-icon.png">
        </a>
      </div> -->
      <div class="profile_info">
        <div id="info-header">
          <?php //echo $texts['CONNECTED_AS']; 
          ?> <i class="fas fa-fw fa-user"></i>
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo "<b>" . $_SESSION['admin']['login'] . "</b> (" . $_SESSION['admin']['type'] . ")"; ?>&nbsp;
                <span class="caret"></span>
              </a>
              <ul class="dropdown-menu">
                <li> <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/profile.php"><i class="fas fa-fw fa-edit"></i> <?php echo $texts['EDIT']; ?> Profile</a></li>
                <?php if ($_SESSION['admin']['type'] == 'manager') { ?>
                  <li> <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/manager-login.php?action=logout"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOG_OUT']; ?></a></li>
                <?php } else if ($_SESSION['admin']['type'] == 'hotel') { ?>
                  <li> <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/hotel-login.php?action=logout"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOG_OUT']; ?></a></li>
                <?php } else { ?>
                  <li> <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/login.php?action=logout"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOG_OUT']; ?></a></li>
                <?php } ?>
              </ul>
            </li>
          </ul>
        </div>

      </div>
      <div class="dash_setting">
        <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/settings.php?page=setting" class="a_setting">
          <img src="<?php echo DOCBASE . ADMIN_FOLDER; ?>/images/settings-icon.png">
        </a>
      </div>
    </div>

  </div>
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav parent">
      <li>
        <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/" <?php if (strpos($_SERVER['SCRIPT_NAME'], ADMIN_FOLDER . "/index.php") !== false) echo " class=\"active\""; ?>>
          <i class="fas fa-fw fa-tachometer-alt"></i> <span class="mlebel"><?php echo $texts['DASHBOARD']; ?></span>
        </a>
      </li>
      <?php
      foreach ($modules as $module) {
        $title = $module->getTitle();
        $name = $module->getName();
        $dir = $module->getDir();
        $icon = $module->getIcon();
        $link = $dir . "/index.php?view=list";
        if ($icon == "") $icon = "puzzle-piece";
        $classname = ($dirname == $name) ? " class=\"active\"" : "";
        //$rights = $module->getPermissions($_SESSION['admin']['type']);
        if ($_SESSION['admin']['id'] == 1) {
          $rights = $module->getPermissions($_SESSION['admin']['type']);
          //var_dump($rights);
        } else {
          $prows = db_getFieldValue($db, 'pm_user', 'permissions', $_SESSION['admin']['id'], $lang = 0);
          $upermis = unserialize($prows);
          if (isset($upermis[$name])) {
            if (!is_array($upermis[$name])) {
              $rights[] = $upermis[$name];
            } else {
              $rights = $upermis[$name];
            }
          } else {
            $rights[] = 'no_access';
          }
        }

        if (!in_array("no_access", $rights) && !empty($rights)) {
          $unList = array('booking', 'hotel', 'room');
          if (in_array($name, $unList)) {
            if ($icon == 'wallet') {
              echo "<li><a href=\"" . $link . "\"" . $classname . "><span class=\"icon icon-" . $icon . "\"></span><span class=\"mlebel\">" . $title . "</span></a></li>";
            } else {
              echo "<li><a href=\"" . $link . "\"" . $classname . "><i class=\"fa fa-fw fa-" . $icon . "\"></i> <span class=\"mlebel\">" . $title . "</span></a></li>";
            }
          }
        }
      } ?>
      <?php
      $prows = db_getFieldValue($db, 'pm_user', 'permissions', $_SESSION['admin']['id'], $lang = 0);
      $upermis = unserialize($prows);
      ?>
      <?php if (($_SESSION['admin']['id'] == 1 || isset($upermis['Calender'])) || $_SESSION['admin']['type'] == "administrator") { ?>
        <!-- <li>
          <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/booking/availabilities.php" <?php if (strpos($_SERVER['SCRIPT_NAME'], "availabilities.php") !== false) echo " class=\"active\""; ?>>
            <i class="fas fa-fw fa-cog"></i> <span class="mlebel">Calendar</span>
          </a>
        </li> -->
        <li>
          <a href="<?= DOCBASE . ADMIN_FOLDER . "/calendar.php" ?>" <?php if (strpos($_SERVER['SCRIPT_NAME'], "calendar.php") !== false) echo " class=\"active\""; ?>>
            <i class="fas fa-fw fa-cog"></i> <span class="mlebel">Calendar</span>
          </a>
        </li>
      <?php }
      if ($_SESSION['admin']['id'] == 1 || isset($upermis['CustomReports'])) { ?>
        <li>
          <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/report.php" <?php if (strpos($_SERVER['SCRIPT_NAME'], "report.php") !== false) echo " class=\"active\""; ?>>
            <i class="fas fa-fw fa-cog"></i> <span class="mlebel">Custom Reports</span>
          </a>
        </li>
      <?php } ?>

      <?php //include(SYSBASE.ADMIN_FOLDER.'/modules/default/group_nav.php');  
      ?>
      <!--<li><a href="<?php echo DOCBASE; ?>"><i class="fas fa-fw fa-eye"></i> <?php echo $texts['PREVIEW']; ?></a></li> -->
      <?php
      $active_list  = array('offer', 'cms', 'service', 'facility', 'coupon', 'tax', 'accommodation', 'destination', 'article', 'tag', 'page', 'comment', 'currency', 'email_content', 'social', 'lang', 'location', 'menu', 'widget', 'slide', 'vendors', 'faq', 'text', 'user');
      $toGetListSet = array('offer', 'cms', 'service', 'facility', 'coupon', 'tax', 'accommodation', 'destination', 'user');
      $toGetListLabelSet = array(
        'offer' => 'Offers',
        'currency' => 'Currency',
        'email_content' => 'Email Content',
        'social' => 'Social', 'lang' => 'Language',
        'location' => 'Location', 'service' => 'Service',
        'facility' => 'Facilities', 'coupon' => 'Coupons',
        'tax' => 'Taxes', 'accommodation' => 'Accommodation',
        'destination' => 'Destination',
        'user' => 'Users'
      );
      ?>
      <li class="goroup_items  <?= (in_array(MODULE, $active_list) ? 'active' : '') ?>">
        <span class="toggle manage_hptel">Setting</span>
        <ul class="spand child sub_list <?= (in_array(MODULE, $active_list) ? 'nav-show' : '') ?>">
          <?php
          //var_dump($permissions);
          foreach ($toGetListSet as $key => $value) {
            if ($value == 'cms') {
              include(SYSBASE . ADMIN_FOLDER . '/modules/default/cms_nav.php');
            } else {
              if ($_SESSION['admin']['id'] == 1) {
                //$rhts = $module->getPermissions($_SESSION['admin']['type']);  
                $rhts = array('all');
              } else {
                $gprows = db_getFieldValue($db, 'pm_user', 'permissions', $_SESSION['admin']['id'], $lang = 0);
                $gupermis = unserialize($gprows);
                if (isset($upermis[$value])) {
                  if (!is_array($gupermis[$value])) {
                    $rhts[] = $gupermis[$value];
                  } else {
                    $rhts = $gupermis[$value];
                  }
                } else {
                  $rhts[] = 'no_access';
                }
              }
              if (!in_array("no_access", $rhts) &&  !empty($rhts)) {
                if ($value == 'user') { ?>
                  <li class="<?= (MODULE == $value ? 'active' : '') ?>"><a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/<?= $value ?>/index.php?view=list"><?php echo $toGetListLabelSet[$value]; ?></a></li>
                <?php } else {
                ?>
                  <li class="<?= (MODULE == $value ? 'active' : '') ?>"><a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/modules/booking/<?= $value ?>/index.php?view=list"><?php echo $toGetListLabelSet[$value]; ?></a></li>
          <?php
                }
              }
            }
          } ?>
          <?php if ($_SESSION['admin']['type'] == "administrator") { ?>
            <li>
              <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/gallery.php?type=list" <?php if (strpos($_SERVER['SCRIPT_NAME'], "gallery.php") !== false) echo " class=\"active\""; ?>>
                <i class="fas fa-fw fa-cog"></i> <span class="mlebel">Gallery</span>
              </a>
            </li>
          <?php  if ($_SESSION['admin']['id'] == 1 || isset($upermis['Testimonial'])) {
          ?>
              <li> <a href="<?= DOCBASE . ADMIN_FOLDER . "/testimonial.php?type=list" ?>" <?php if (strpos($_SERVER['SCRIPT_NAME'], "testimonial.php") !== false) echo " class=\"active\""; ?>>
                  <span class="mlebel">Testimonial</span>
                </a>
              </li>
            <?php }
            if ($_SESSION['admin']['id'] == 1 || isset($upermis['Settings'])) { ?>
              <li> <a href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/settings.php?page=setting" <?php if (strpos($_SERVER['SCRIPT_NAME'], "settings.php") !== false) echo " class=\"active\""; ?>>
                  <i class="fas fa-fw fa-cog"></i> <span class="mlebel"><?php echo $texts['SETTINGS']; ?></span>
                </a>
              </li>
          <?php }
          } ?>
        </ul>
      </li>
    </ul>
  </div>
</nav>