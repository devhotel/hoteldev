<?php
$toGetList = array('hotel', 'rate', 'room','offer');
$toGetListLabel = array('hotel'=>'Hotels', 'rate'=>'Room rate', 'room'=>'Rooms','offer'=> 'Offers');
    if(!empty($toGetList)){ 
        ?>
        <li class="goroup_items  <?=(in_array(MODULE,$toGetList)?'active':'')?>">
            <span class="toggle manage_hptel">Manage Hotels </span>
             <ul class="spand child sub_list <?=(in_array(MODULE,$toGetList)?'nav-show':'')?>"> 
               <?php 
                //var_dump($permissions);
                foreach ($toGetList as $key => $value) {
                   if($_SESSION['admin']['id']==1){
                      //$rhts = $module->getPermissions($_SESSION['admin']['type']);  
                       $rhts =array('all');
                    }else{
                      $gprows = db_getFieldValue($db, 'pm_user', 'permissions', $_SESSION['admin']['id'], $lang = 0);  
                      $gupermis = unserialize($gprows);
                        /*echo '<pre>';
                            print_r($upermis[$value]);
                        echo '</pre>';*/
                      if(isset($upermis[$value])){
                          if(!is_array($gupermis[$value])){
                               $rhts[]=$gupermis[$value];
                          }else{
                               $rhts=$gupermis[$value];
                          }
                       }else{
                          $rhts[]='no_access';
                       }
                    }

                    if(!in_array("no_access", $rhts) &&  !empty($rhts)){ ?>
                      <li  class="<?=(MODULE==$value?'active':'')?>"><a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/modules/booking/<?=$value?>/index.php?view=list"><?php echo $toGetListLabel[$value]; ?></a></li>
                    <?php
                    } 
                  } ?>
                
            </ul>
        </li>
<?php } 

$toGetListMstr = array('service','facility', 'coupon', 'tax','accommodation','destination');
$toGetListLabelMstr = array('service'=>'Service','facility'=>'Facilities', 'coupon'=>'Coupons', 'tax'=>'Taxes','accommodation'=>'Accommodation','destination'=> 'Destination');
    if(!empty($toGetListMstr)){ 
        ?>
        <li class="goroup_items  <?=(in_array(MODULE,$toGetListMstr)?'active':'')?>">
            <span class="toggle manage_hptel">Manage Masters </span>
             <ul class="spand child sub_list <?=(in_array(MODULE,$toGetListMstr)?'nav-show':'')?>"> 
               <?php 
                //var_dump($permissions);
                foreach ($toGetListMstr as $key => $value) {
                   if($_SESSION['admin']['id']==1){
                      //$rhts = $module->getPermissions($_SESSION['admin']['type']);  
                       $rhts =array('all');
                    }else{
                      $gprows = db_getFieldValue($db, 'pm_user', 'permissions', $_SESSION['admin']['id'], $lang = 0);  
                      $gupermis = unserialize($gprows);
                        /*echo '<pre>';
                            print_r($upermis[$value]);
                        echo '</pre>';*/
                      if(isset($upermis[$value])){
                          if(!is_array($gupermis[$value])){
                               $rhts[]=$gupermis[$value];
                          }else{
                               $rhts=$gupermis[$value];
                          }
                       }else{
                          $rhts[]='no_access';
                       }
                    }

                    if(!in_array("no_access", $rhts) &&  !empty($rhts)){ ?>
                      <li  class="<?=(MODULE==$value?'active':'')?>"><a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/modules/booking/<?=$value?>/index.php?view=list"><?php echo $toGetListLabelMstr[$value]; ?></a></li>
                    <?php
                    } 
                  } ?>
                
            </ul>
        </li>
<?php } 

$toGetListCms = array('article','tag','page', 'menu', 'widget','slide','vendors','faq','text');
$toGetListLabelCms = array('article'=>'Posts','tag'=>'Tags','page'=>'Pages', 'menu'=>'Menus', 'widget'=>'Widget','slide'=>'Sliders','vendors'=> 'Vendors','faq'=>'Faqs','text'=>'Texts');
    if(!empty($toGetListCms)){ ?>
        <li class="goroup_items  <?=(in_array(MODULE,$toGetListCms)?'active':'')?>">
            <span class="toggle manage_hptel">Manage CMS </span>
             <ul class="spand child sub_list <?=(in_array(MODULE,$toGetListCms)?'nav-show':'')?>"> 
               <?php 
                //var_dump($permissions);
                foreach ($toGetListCms as $key => $value) {
                   if($_SESSION['admin']['id']==1){
                      //$rhts = $module->getPermissions($_SESSION['admin']['type']);  
                       $rhts =array('all');
                    }else{
                      $gprows = db_getFieldValue($db, 'pm_user', 'permissions', $_SESSION['admin']['id'], $lang = 0);  
                      $gupermis = unserialize($gprows);
                        /*echo '<pre>';
                            print_r($upermis[$value]);
                        echo '</pre>';*/
                      if(isset($upermis[$value])){
                          if(!is_array($gupermis[$value])){
                               $rhts[]=$gupermis[$value];
                          }else{
                               $rhts=$gupermis[$value];
                          }
                       }else{
                          $rhts[]='no_access';
                       }
                    }
                    if(!in_array("no_access", $rhts) &&  !empty($rhts)){ ?>
                      <li  class="<?=(MODULE==$value?'active':'')?>"><a href="<?php echo DOCBASE.ADMIN_FOLDER; ?>/modules/<?=$value?>/index.php?view=list"><?php echo $toGetListLabelCms[$value]; ?></a></li>
                    <?php
                    } 
                  } ?>
                
            </ul>
        </li>
<?php } 

