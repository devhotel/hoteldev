<?php
 $toGetListCms = array('article','tag','page','comment', 'currency', 'email_content','social','lang','location','menu', 'widget','slide','vendors','faq','text');
 $toGetListLabelCms = array('article'=>'Posts', 'comment'=>'Comments','currency'=>'Currency', 'email_content'=>'Email content','social'=>'Social Link ','lang'=>'Lang','location'=>'Location','tag'=>'Tags','page'=>'Pages', 'menu'=>'Menus', 'widget'=>'Widget','slide'=>'Sliders','vendors'=> 'Vendors','faq'=>'Faqs','text'=>'Texts');
    if(!empty($toGetListCms)){ ?>
        <li class="<?=(in_array(MODULE,$toGetListCms)?'active':'')?>">
            <span class="sub_toggle manage_hptel"> CMS </span>
             <ul class="sub_spand child sub_list <?=(in_array(MODULE,$toGetListCms)?'nav-show':'')?>"> 
               <?php 
                //var_dump($permissions);
                foreach ($toGetListCms as $key => $value) {
                   if($_SESSION['admin']['id']==1){
                      //$rhts = $module->getPermissions($_SESSION['admin']['type']);  
                       $rhts =array('all');
                    }else{
                      $gprows = db_getFieldValue($db, 'pm_user', 'permissions', $_SESSION['admin']['id'], $lang = 0);  
                      $gupermis = unserialize($gprows);
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

