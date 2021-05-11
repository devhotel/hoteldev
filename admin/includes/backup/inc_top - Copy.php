<?php debug_backtrace() || die ("Direct access not permitted"); ?>
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
              <a class="navbar-brand" href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/"><img src="<?php echo DOCBASE.ADMIN_FOLDER; ?>/images/admin-logo.png" alt="" /></a>
            </div>
          <div class="booking_search">
            <form  id="form"action="<?php echo DOCBASE.ADMIN_FOLDER; ?>/modules/booking/booking/index.php?view=list" method="get">
               <input type="hidden" name="view" value="list"/>
               <input type="hidden" name="csrf_token" value="<?php echo $csrf_tokendash; ?>"/>
               <input id="list_q_search" type="text" value="" name="q_search" class="input_search"> <input type="submit" id="search" class="booking_search_sub" name="search">
            </form>
          </div>
        </div>
        
        <div class="admin_header_right">
          <div class="new_link">
             <a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/modules/booking/booking/new.php?view=form&id=0" class="a_notifications">New Booking</a>
          </div>
          <div class="info_links">
             <a href="" class="a_notifications">
               <img src="<?php echo DOCBASE.ADMIN_FOLDER; ?>/images/notification-icon.png">
             </a>
          </div>
          <div class="profile_info">
              <div id="info-header">
              <?php //echo $texts['CONNECTED_AS']; ?> <i class="fas fa-fw fa-user"></i>
               <ul class="nav navbar-nav">
                   <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo "<b>".$_SESSION['admin']['login']."</b> (".$_SESSION['admin']['type'].")"; ?>&nbsp;
                      <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                          <li> <a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/profile.php"><i class="fas fa-fw fa-edit"></i> <?php echo $texts['EDIT']; ?> Profile</a></li>
                          <?php if($_SESSION['admin']['type']=='manager'){ ?>
                            <li> <a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/manager-login.php?action=logout"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOG_OUT']; ?></a></li>
                          <?php }else if($_SESSION['admin']['type']=='hotel'){ ?>
                            <li> <a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/hotel-login.php?action=logout"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOG_OUT']; ?></a></li>
                          <?php }else{ ?>
                            <li> <a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/login.php?action=logout"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOG_OUT']; ?></a></li>
                          <?php } ?>
                          
                      </ul>
                   </li>              
              </ul>
          </div>

          </div>
          <div class="dash_setting">
            <a href="" class="a_setting">
             <img src="<?php echo DOCBASE.ADMIN_FOLDER; ?>/images/settings-icon.png 
">
            </a>
          </div>
        </div>

    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li>
                <a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/"<?php if(strpos($_SERVER['SCRIPT_NAME'], ADMIN_FOLDER."/index.php") !== false) echo " class=\"active\""; ?>>
                    <i class="fas fa-fw fa-tachometer-alt"></i> <span class="mlebel"><?php echo $texts['DASHBOARD']; ?></span>
                </a>
            </li>
            <?php
            $groupnNav = array();
            $parentNav = array('hotel_manager' =>'Manage Hotels');
            $groupnNavItem=array(
                 'hotel'=>'hotel_manager',
                 'page'=>'hotel_manager',
                 'types'=>'hotel_manager',
                 'rate'=>'hotel_manager',
                 'offer'=>'hotel_manager',
                 'booking'=>'hotel_manager',
             );
            $toList = array();
            foreach($modules as $module){
                $title = $module->getTitle();
                $dir = $module->getDir();
                $icon = $module->getIcon();
                $link = $dir."/index.php?view=list";
                if($icon == "") $icon = "puzzle-piece";
                    $classname = ($dirname == $name) ? " class=\"active\"" : "";
                    //$rights = $module->getPermissions($_SESSION['admin']['type']);
                    if($_SESSION['admin']['id']==1){
                      $rights = $module->getPermissions($_SESSION['admin']['type']);  
                    }else{
                      $prows = db_getFieldValue($db, 'pm_user', 'permissions', $_SESSION['admin']['id'], $lang = 0);  
                      $upermis = unserialize($prows);
                      if(isset($upermis[$name])){
                          if(!is_array($upermis[$name])){
                              $rights[]=$upermis[$name];
                          }else{
                              $rights=$upermis[$name];
                          }
                       }else{
                          $rights[]='no_access';
                       }
                    }
                   
                 if(!in_array("no_access", $rights) && !empty($rights)){
                    
                    if(array_key_exists($name, $groupnNavItem)){
                       if(!in_array($groupnNavItem[$name], $toList)){
                        
                       }

                      if($icon=='wallet'){
                          $groupnNav[$groupnNavItem[$name]][] = "<li><a href=\"".$link."\"".$classname."><span class=\"icon icon-".$icon."\"></span><span class=\"mlebel\">".$title."</span></a></li>";  
                      }else{
                          $groupnNav[$groupnNavItem[$name]][] = "<li><a href=\"".$link."\"".$classname."><i class=\"fa fa-fw fa-".$icon."\"></i> <span class=\"mlebel\">".$title."</span></a></li>";  
                      }
                   }else{
                    if($icon=='wallet'){
                          $groupnNav['other'][] =  "<li><a href=\"".$link."\"".$classname."><span class=\"icon icon-".$icon."\"></span><span class=\"mlebel\">".$title."</span></a></li>";  
                      }else{
                          $groupnNav['other'][]=  "<li><a href=\"".$link."\"".$classname."><i class=\"fa fa-fw fa-".$icon."\"></i> <span class=\"mlebel\">".$title."</span></a></li>";  
                      }
                   }
                }
             } 
             ksort($groupnNav);
             $tplist = array();
             $stat=0;
             foreach ($groupnNav as $key => $Nav) {
              if(!in_array($key, $tplist) && $key=='other' ){
                if($stat>0){
                  echo '</ul></li>';
                }
               $tplist[] = $key;
               echo "<li><a>".$parentNav[$key]."</a>";
               echo '<ul>';
                 echo $Nav;
               }else{
                 echo $Nav;
               }
             }
             ?>

            <?php
            if($_SESSION['admin']['type'] == "administrator"){ ?>
                <li>
                    <a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/settings.php"<?php if(strpos($_SERVER['SCRIPT_NAME'], "settings.php") !== false) echo " class=\"active\""; ?>>
                        <i class="fas fa-fw fa-cog"></i> <span class="mlebel"><?php echo $texts['SETTINGS']; ?></span>
                    </a>
                </li>
                <?php
            } ?>
        </ul>
    </div>
</nav>
