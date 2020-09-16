<?php
$stylesheets[] = array('file' => DOCBASE.'js/plugins/isotope/css/style.css', 'media' => 'all');
$javascripts[] = DOCBASE.'js/plugins/isotope/jquery.isotope.min.js';
$javascripts[] = DOCBASE.'js/plugins/isotope/jquery.isotope.sloppy-masonry.min.js';

$stylesheets[] = array('file' => DOCBASE.'js/plugins/lazyloader/lazyloader.css', 'media' => 'all');
$javascripts[] = DOCBASE.'js/plugins/lazyloader/lazyloader.js';

require(getFromTemplate('common/header.php', false)); ?>

<section id="page">
    
    <?php include(getFromTemplate('common/page_header.php', false)); ?>
    
    <div id="content" class="pt30 pb20">
        <div class="container">
            <div class="row">
                <?php
                if($page['text'] != ''){ ?>
                    <div class="col-md-12"><?php echo $page['text']; ?></div>
                    <?php
                } ?>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <form action="<?php echo DOCBASE; ?>offers" method="get" class="offers_destination" id="offers_destination">
                         <select name="id_destination" class="selectpicker destination" id="destination">
                            <?php 
                             echo '<option value="0" >All Destination</option>';
                              $result_des = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND lang = '.LANG_ID);
                               if($result_des !== false && $db->last_row_count() > 0){
                                 foreach($result_des as $i => $row){ 
                                     $selected = (isset($_REQUEST['id_destination']) && $_REQUEST['id_destination']==$row['id'] )?'selected="selected"':'';
                                     echo '<option value="'.$row['id'].'" '.$selected.'>'.$row['name'].'</option>';
                                 }
                               }
                             ?>
                         </select>
                    </form>
                </div>
                <?php
                $lz_offset = 1;
                $lz_limit = 9;
                $lz_pages = 0;
                $num_records = 0;
                $id_destination =0;
                if(isset($_REQUEST['id_destination'])){
                  $id_destination = $_REQUEST['id_destination'];  
                }
                $result = $db->query('SELECT count(*) FROM pm_offer WHERE checked = 1 AND lang = '.LANG_ID);
                if($result !== false){
                    $num_records = $result->fetchColumn(0);
                    $lz_pages = ceil($num_records/$lz_limit);
                }
                 if($num_records > 0){ ?>
                    <div class="isotopeWrapper clearfix isotope lazy-wrapper" data-loader="<?php echo getFromTemplate('common/get_offer.php'); ?>" data-mode="click" data-limit="<?php echo $lz_limit; ?>" data-pages="<?php echo $lz_pages; ?>" data-more_caption="<?php echo $texts['LOAD_MORE']; ?>" data-is_isotope="true" data-variables="page_id=<?php echo $page_id; ?>&page_alias=<?php echo $page['alias']; ?>">
                        <?php include(getFromTemplate('common/get_offer.php', false)); ?>
                    </div>
                    <?php
                } ?>
            </div>
        </div>
    </div>
</section>

<script>
   $(document).ready(function(){
       $('#destination').change(function(){
           $('#offers_destination').submit();
        });
    }); 
</script>



