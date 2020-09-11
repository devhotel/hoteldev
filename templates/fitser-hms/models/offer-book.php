    <?php
    global $res_room;
    if($article_alias == '') err404();

    $result = $db->query('SELECT * FROM pm_offer WHERE checked = 1 AND lang = '.LANG_ID.' AND alias = '.$db->quote($article_alias));
    if($result !== false && $db->last_row_count() == 1){
        
        $offer = $result->fetch(PDO::FETCH_ASSOC);
        $offer_id = $offer['id'];
        $hotel_id = $offer['id_hotel'];
        $offer_room_id = $offer['id_room'];
        $article_id = $hotel_id;
        $title_tag = $offer['name'];
        $page_title = $offer['name'];
        
        $page_subtitle = '';
        $page_alias = $pages[$page_id]['alias'].'/'.text_format($offer['alias']);
        
        $result = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND lang = '.LANG_ID.' AND id = '.$hotel_id);
        $hotel = $result->fetch(PDO::FETCH_ASSOC);
        
        $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = '.$hotel_id.' AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
        if($result_hotel_file !== false && $db->last_row_count() > 0){
            
            $row = $result_hotel_file->fetch();
            
            $file_id = $row['id'];
            $filename = $row['file'];
            
            if(is_file(SYSBASE.'medias/hotel/medium/'.$file_id.'/'.$filename))
                $page_img = getUrl(true).DOCBASE.'medias/hotel/medium/'.$file_id.'/'.$filename;
        }
        $my_page_alias ='package';
        $offer_alias = DOCBASE.$my_page_alias.'/'.text_format($offer['alias']);
        
        $field_notice = array();
        $msg_error = '';
        $msg_success = '';
        $room_stock = 1;
        $max_people = 30;
        $search_limit = 8;
        /*********** Num adults ************/
        if(isset($_REQUEST['num_adults']) && is_numeric($_REQUEST['num_adults'])) $_SESSION['num_adults'] = $_REQUEST['num_adults'];
        elseif(isset($_SESSION['book']['adults'])) $_SESSION['num_adults'] = $_SESSION['book']['adults'];
        elseif(!isset($_SESSION['num_adults'])) $_SESSION['num_adults'] = 1;

        /********** Num children ***********/
        if(isset($_REQUEST['num_children']) && is_numeric($_REQUEST['num_children'])) $_SESSION['num_children'] = $_REQUEST['num_children'];
        elseif(isset($_SESSION['book']['children'])) $_SESSION['num_children'] = $_SESSION['book']['children'];
        elseif(!isset($_SESSION['num_children'])) $_SESSION['num_children'] = 0;
        /*********** Num adults ************/
        
        if(isset($_REQUEST['num_room']) && is_numeric($_REQUEST['num_room'])) $_SESSION['num_room'] = $_REQUEST['num_room'];
        elseif(isset($_SESSION['book']['num_room'])) $_SESSION['num_room'] = $_SESSION['book']['num_room'];
        elseif(!isset($_SESSION['num_room'])) $_SESSION['num_room'] = 1;
        
        /*********** Num adults ************/
        if(isset($_REQUEST['ab']) && is_array($_REQUEST['ab'])) $_SESSION['ab'] = $_REQUEST['ab'];
        elseif(isset($_SESSION['book']['ab'])) $_SESSION['ab'] = $_SESSION['book']['ab'];
        elseif(!isset($_SESSION['ab'])) $_SESSION['ab'] = array();
        /****** Check in / out date ********/
        if(isset($_SESSION['from_date'])) $from_time = $_SESSION['from_date'];
        else $from_time = gmtime();

        if(isset($_SESSION['to_date'])) $to_time = $_SESSION['to_date'];
        else $to_time = gmtime()+86400;

        if(isset($_REQUEST['from_date']) && !empty($_REQUEST['from_date'])) $_SESSION['from_date'] = htmlentities($_REQUEST['from_date'], ENT_QUOTES, 'UTF-8');
        elseif(!isset($_SESSION['from_date'])) $_SESSION['from_date'] = gmdate('d/m/Y', $from_time);

        if(isset($_REQUEST['to_date']) && !empty($_REQUEST['to_date'])) $_SESSION['to_date'] = htmlentities($_REQUEST['to_date'], ENT_QUOTES, 'UTF-8');
        elseif(!isset($_SESSION['to_date'])) $_SESSION['to_date'] = gmdate('d/m/Y', $to_time);
        $num_people = $_SESSION['num_adults']+$_SESSION['num_children'];

        if(!is_numeric($_SESSION['num_adults'])) $field_notice['num_adults'] = $texts['REQUIRED_FIELD'];
        if(!is_numeric($_SESSION['num_children'])) $field_notice['num_children'] = $texts['REQUIRED_FIELD'];
        if($_SESSION['from_date'] == '') $field_notice['dates'] = $texts['REQUIRED_FIELD'];
        else{
            $time = explode('/', $_SESSION['from_date']);
            if(count($time) == 3) $time = gm_strtotime($time[2].'-'.$time[1].'-'.$time[0].' 00:00:00');
            if(!is_numeric($time)) $field_notice['dates'] = $texts['REQUIRED_FIELD'];
            else $from_time = $time;
        }
        if($_SESSION['to_date'] == '') $field_notice['dates'] = $texts['REQUIRED_FIELD'];
        else{
            $time = explode('/', $_SESSION['to_date']);
            if(count($time) == 3) $time = gm_strtotime($time[2].'-'.$time[1].'-'.$time[0].' 00:00:00');
            if(!is_numeric($time)) $field_notice['dates'] = $texts['REQUIRED_FIELD'];
            else $to_time = $time;
        }

        $today = gm_strtotime(gmdate('Y').'-'.gmdate('n').'-'.gmdate('j').' 00:00:00');

        if($from_time < $today || $to_time < $today || $to_time <= $from_time){
            $from_time = $today;
            $to_time = $today+86400;
            $_SESSION['from_date'] = gmdate('d/m/Y', $from_time);
            $_SESSION['to_date'] = gmdate('d/m/Y', $to_time);
        }

        if(is_numeric($from_time) && is_numeric($to_time)){
            $num_nights = ($to_time-$from_time)/86400;
        }else
        $num_nights = 0;
        if(count($field_notice) == 0){

            if($num_nights <= 0) $msg_error .= $texts['NO_AVAILABILITY'];
            else{
                require_once(getFromTemplate('common/functions.php', false));
                $res_hotel = getRoomsResult($from_time, $to_time, $_SESSION['num_adults'], $_SESSION['num_children']);
                if(empty($res_hotel)) $msg_error .= $texts['NO_AVAILABILITY'];
                else $_SESSION['res_hotel'] = $res_hotel;
            }
        }

        $search_offset = (isset($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : 0;
        
        $id_room = 0;

        $result_room_rate = $db->prepare('SELECT MIN(price) as min_price FROM pm_rate WHERE id_room = :id_room');
        $result_room_rate->bindParam(':id_room', $id_room);


        $id_hotel = 0;

        $result_budget_room = $db->prepare('SELECT * FROM pm_room WHERE id_hotel = :id_hotel AND checked = 1 AND lang = '.LANG_ID);
        $result_budget_room->bindParam(':id_hotel', $hotel_id);
        $result_base_rate = $db->prepare('SELECT MIN(price) as min_price FROM pm_room WHERE id_hotel = :id_hotel');
        $result_base_rate->bindParam(':id_hotel', $id_hotel);


        $hidden_hotels = array();
        $hidden_rooms = array();
        $room_prices = array();
        $hotel_prices = array();
        $base_hotel_prices = array();
        $result_budget_hotel = $db->query('SELECT * FROM pm_hotel WHERE id='.$hotel_id.' AND checked = 1 AND lang = '.LANG_ID);
        if($result_budget_hotel !== false){
            
            foreach($result_budget_hotel as $i => $row){
                $id_hotel = $row['id'];
                $hotel_min_price = 0;
                $hotel_max_price = 0;
                $result_budget_room->execute();
                if($result_budget_room !== false){
                    foreach($result_budget_room as $row){
                        $id_room = $row['id'];
                        $room_price = $row['price']; 
                        $max_people = $row['max_people'];
                        $min_people = $row['min_people'];
                        $max_adults = $row['max_adults'];
                        $max_children = $row['max_children'];
                        $base_hotel_prices[$id_hotel] = $room_price;
                        
                        if(!isset($res_hotel[$id_hotel][$id_room])
                            || isset($res_hotel[$id_hotel][$id_room]['error'])
                            || ($_SESSION['num_adults']+$_SESSION['num_children'] > $max_people)
                            || ($_SESSION['num_adults']+$_SESSION['num_children'] < $min_people)
                            || ($_SESSION['num_adults'] > $max_adults)
                            || ($_SESSION['num_children'] > $max_children)){
                            $amount = $room_price;
                        $result_room_rate->execute();
                        if($result_room_rate !== false && $db->last_row_count() > 0){
                            $row = $result_room_rate->fetch();
                            if($row['min_price'] > 0) $amount = $row['min_price'];
                        }
                        $full_price = 0;
                        $type = $texts['NIGHT'];
                        $price_night = $amount;
                    }else{
                        $amount = $res_hotel[$id_hotel][$id_room]['amount']+$res_hotel[$id_hotel][$id_room]['fixed_sup'];
                        $full_price = $res_hotel[$id_hotel][$id_room]['full_price']+$res_hotel[$id_hotel][$id_room]['fixed_sup'];
                        $type = $num_nights.' '.$texts['NIGHTS'];
                        $price_night = $amount/$num_nights;
                        
                    }
                    
                    if((!empty($price_min) && $price_night < $price_min) || (!empty($price_max) && $price_night > $price_max)) $hidden_rooms[] = $id_room;
                    else{
                        $room_prices[$id_room]['amount'] = $amount;
                        $room_prices[$id_room]['full_price'] = $full_price;
                        $room_prices[$id_room]['type'] = $type;
                    }
                    if(empty($hotel_min_price) || $price_night < $hotel_min_price) $hotel_min_price = $price_night;
                    if(empty($hotel_max_price) || $price_night > $hotel_max_price) $hotel_max_price = $price_night;
                }
            } 
            if((!empty($price_min) && $hotel_max_price < $price_min) || (!empty($price_max) && $hotel_min_price > $price_max)) $hidden_hotels[] = $id_hotel;
            $hotel_prices[$id_hotel] = $hotel_min_price;
        }
    }
    
    $result_rating = $db->prepare('SELECT AVG(rating) as avg_rating FROM pm_comment WHERE item_type = \'hotel\' AND id_item = :id_hotel AND checked = 1 AND rating > 0 AND rating <= 5');
    $result_rating->bindParam(':id_hotel', $hotel_id);
    
    $id_facility = 0;
    $result_facility_file = $db->prepare('SELECT * FROM pm_facility_file WHERE id_item = :id_facility AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
    $result_facility_file->bindParam(':id_facility', $id_facility);

    $room_facilities = '0';
    $result_room_facilities = $db->prepare('SELECT * FROM pm_facility WHERE lang = '.LANG_ID.' AND FIND_IN_SET(id, :room_facilities) ORDER BY rank LIMIT 18');
    $result_room_facilities->bindParam(':room_facilities', $room_facilities);


    $query_room = 'SELECT * FROM pm_room WHERE id_hotel = :id_hotel AND checked = 1 AND lang = '.LANG_ID;    
    if(!empty($hidden_rooms)) $query_room .= ' AND id NOT IN('.implode(',', $hidden_rooms).')';
    $query_room .= ' ORDER BY';
    if(!empty($room_ids)) $query_room .= ' CASE WHEN id IN('.implode(',', $room_ids).') THEN 3 ELSE 4 END,';
    $query_room .= ' price';
    $result_room = $db->prepare($query_room);
    $result_room->bindParam(':id_hotel', $hotel_id);

    $result_room_file = $db->prepare('SELECT * FROM pm_room_file WHERE id_item = :id_room AND checked = 1 AND lang = '.LANG_ID.' AND type = \'image\' AND file != \'\' ORDER BY rank');
    $result_room_file->bindParam(':id_room', $id_room);


    $query_hotel = 'SELECT * FROM pm_hotel WHERE WHERE id='.$hotel_id.' AND checked = 1 AND lang = '.LANG_ID;
    if(!empty($hidden_hotels)) $query_hotel .= ' AND id NOT IN('.implode(',', $hidden_hotels).')';
    $query_hotel .= ' ORDER BY';
    if($hotel_id != 0) $query_hotel .= ' CASE WHEN id = '.$hotel_id.' THEN 1 ELSE 4 END,';
    if(!empty($hotel_ids)) $query_hotel .= ' CASE WHEN id IN('.implode(',', $hotel_ids).') THEN 3 ELSE 4 END,';
    $query_hotel .= ' rank';

    $num_results = 0;
    $result_hotel = $db->query($query_hotel);
    if($result_hotel !== false){
        $num_results = $db->last_row_count();
        
        $visible_hotels = $result_hotel->fetchAll(PDO::FETCH_COLUMN, 0);
        if(!empty($visible_hotels)){
            $visible_hotels = array_intersect_key($hotel_prices, array_flip($visible_hotels));
            $subtitle = str_replace('{min_price}', formatPrice(min($visible_hotels)*CURRENCY_RATE), $texts['BEST_RATES_SUBTITLE']);
            if($article_id > 0) $page_subtitle = $subtitle;
            else $page['subtitle'] = $subtitle;
        }
    }

    $query_hotel .= ' LIMIT '.$search_limit.' OFFSET '.$search_offset;

    $result_hotel = $db->query($query_hotel);

    
}else err404();

check_URI(DOCBASE.$page_alias);

    /* ==============================================
     * CSS AND JAVASCRIPT USED IN THIS MODEL
     * ==============================================
     */
    $javascripts[] = DOCBASE.'js/plugins/sharrre/jquery.sharrre.min.js';

    $javascripts[] = DOCBASE.'js/plugins/jquery.event.calendar/js/jquery.event.calendar.js';
    $javascripts[] = DOCBASE.'js/plugins/jquery.event.calendar/js/languages/jquery.event.calendar.'.LANG_TAG.'.js';
    $stylesheets[] = array('file' => DOCBASE.'js/plugins/jquery.event.calendar/css/jquery.event.calendar.css', 'media' => 'all');

    $stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css', 'media' => 'all');
    $stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.theme.default.min.css', 'media' => 'all');
    $javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js';

    $stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/3.5.5/css/star-rating.min.css', 'media' => 'all');
    $javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/3.5.5/js/star-rating.min.js';

    $stylesheets[] = array('file' => DOCBASE.'js/plugins/isotope/css/style.css', 'media' => 'all');
    $javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/1.5.25/jquery.isotope.min.js';
    $javascripts[] = DOCBASE.'js/plugins/isotope/jquery.isotope.sloppy-masonry.min.js';

    $stylesheets[] = array('file' => DOCBASE.'js/plugins/lazyloader/lazyloader.css', 'media' => 'all');
    $javascripts[] = DOCBASE.'js/plugins/lazyloader/lazyloader.js';

    $javascripts[] = DOCBASE.'js/plugins/live-search/jquery.liveSearch.js';
    
    ?>
    
     <script>
        var night = '<?php echo $offer['no_day_night'];?>';
     </script>

   <?php require(getFromTemplate('common/send_comment.php', false));

    require(getFromTemplate('common/header.php', false)); ?>

    <section id="page">
        
        <?php include(getFromTemplate('common/page_header.php', false)); ?>
        <?php
        $nb_comments = 0;
        $item_type = 'hotel';
        $item_id = $hotel_id;
        $allow_comment = ALLOW_COMMENTS;
        $allow_rating = ALLOW_RATINGS;
        if($allow_comment == 1){
            $result_comment = $db->query('SELECT * FROM pm_comment WHERE id_item = '.$item_id.' AND item_type = \''.$item_type.'\' AND checked = 1 ORDER BY add_date DESC');
            if($result_comment !== false)
                $nb_comments = $db->last_row_count();
        }
        
        ?>
        
        <div id="content" class="pb30">
            <div class="container">
                <div class="alert alert-success" style="display:none;"></div>
                <div class="alert alert-danger" style="display:none;"></div>
            </div>
            <section class="hotel_details_new">
               <div class="container">
                  <div class="row mb10">
                        <div class="col-md-12">
                           
                            <div class="owl-carousel owlWrapper" data-items="1" data-autoplay="false" data-dots="true" data-nav="false" data-rtl="<?php echo (RTL_DIR) ? 'true' : 'false'; ?>">
                                <?php
                                if(!empty($offer['video'])){ ?>
                                    <div class="video-container">
                                        <iframe src="<?php echo $offer['video']; ?>" frameborder="0"></iframe>
                                    </div>
                                    <?php
                                }
                                $result_offer_file = $db->query('SELECT * FROM pm_offer_file WHERE id_item = '.$offer_id.' AND checked = 1 AND lang = '.DEFAULT_LANG.' AND type = \'image\' AND file != \'\' ORDER BY rank');
                                if($result_offer_file !== false){
                                    foreach($result_offer_file as $i => $row){
                                        $file_id = $row['id'];
                                        $filename = $row['file'];
                                        $label = $row['label'];
                                        
                                        $realpath = SYSBASE.'medias/offer/big/'.$file_id.'/'.$filename;
                                        $thumbpath = DOCBASE.'medias/offer/big/'.$file_id.'/'.$filename;
                                        
                                        if(is_file($realpath)){ ?>
                                            <img alt="<?php echo $label; ?>" src="<?php echo $thumbpath; ?>" class="img-responsive" style="max-height:600px;"/>
                                            <?php
                                        }
                                    }
                                } ?>
                            </div>
                            <div class="package_header">
                                <div class="col-md-12 offer_title" itemprop="title">
                              <h1 class="mb0"><?php echo $offer['name']; ?></h1>
                               <div class="pkg_rht">
                               		<div class="date_validity"> Validity :  <span class="from_date"><?php echo gmdate('d F Y', $offer['day_start']); ?></span> <span class="to_date"></span>To <?php echo gmdate('d F Y', $offer['day_end']); ?>  </div>
                               		<div class="capacity">
                                     <?php
                                      $adults = $offer['max_adults'];
                                      $kids = $offer['max_children'];
                                      if($adults > 0){
                                        echo '<span>'.$adults.'</span>';
                                        echo '<span>'.($adults>1?' Adults ':' Adult ').'</span>';
                                      }
                                      if($kids > 0){
                                        echo '<span>'.$kids.'</span>';
                                        echo '<span>'.($kids>1?' Kids':' Kid').'</span>';
                                      }
                                    ?>
                                 </div>
                                  <div class="price listing_details_sngprice">
                                      <span>Offer Price</span>
                                        <?php echo formatPrice($offer['offer_price']*CURRENCY_RATE); ?>
                                  </div>
                               </div>
                              </div>
                        </div>
                      </div>
                    </div>
                 <div class="roomtype">
                   <div id="search-page" class="mb30">
                    <?php include(getFromTemplate('common/search-offer.php', false)); ?>
                   </div>
                  <form action="<?php echo DOCBASE.$sys_pages['booking']['alias']; ?>" method="get" class="ajax-form">
                    <input type="hidden" name="from_time" value="<?php echo $from_time; ?>">
                    <input type="hidden" name="to_time" value="<?php echo $to_time; ?>">
                    <input type="hidden" name="nights" value="<?php echo $offer['no_day_night'];?>">
                    <input type="hidden" name="id_hotel" value="<?php echo $hotel_id; ?>">
                    <input type="hidden" name="hotel" value="<?php echo $hotel['title']; ?>">
                    <input type="hidden" name="offer_id" value="<?php echo $offer_id; ?>">
                    <input type="hidden" name="offer_price" value="<?php echo $offer['offer_price']; ?>">
                    <div class="booking-result">
                        <div class="">
                            
                            <div class="">
                                <?php
                                $result_room->execute();
                                if (array_key_exists($hotel_id,$res_hotel)){
                                    if($result_room !== false){
                                        $kr=0;
                                        if(isset($_SESSION['num_room'])){
                                         $num_room=$_SESSION['num_room'];
                                     }
                                     foreach($result_room as $row){
                                        if ($offer_room_id==$row['id'] && array_key_exists($row['id'],$res_hotel[$hotel_id])){
                                            $id_room = $row['id'];
                                            $room_title = $row['title'];
                                            $room_alias = $row['alias'];
                                            $room_subtitle = $row['subtitle'];
                                            $room_descr = $row['descr'];
                                            $room_price = $row['price'];
                                            $room_stock = $row['stock'];
                                            $max_adults = $row['max_adults'];
                                            $max_children = $row['max_children'];
                                            $max_people = $row['max_people'];
                                            $min_people = $row['min_people'];
                                            $room_facilities = $row['facilities'];
                                            $bed_type = $row['bed_type'];
                                            $num_of_bed = $row['number_beds'];
                                            $room_dimention = $row['room_dimention'];
                                            $views = $row['views'];
                                            //var_dump($res_hotel) ;
                                            //var_dump($res_room) ;
                                            $room_stock = isset($res_room[$id_room]['room_stock']) ? $res_room[$id_room]['room_stock'] : $row['stock'];
                                            
                                            $amount = $room_prices[$id_room]['amount'];
                                            $full_price = $room_prices[$id_room]['full_price'];
                                            $type = $room_prices[$id_room]['type']; ?>

                                            <input type="hidden" name="rooms[]" value="<?php echo $id_room; ?>">
                                            <input type="hidden" name="room_<?php echo $id_room; ?>" value="<?php echo $room_title; ?>">
                                            
                                            <div class="row room-result">
                                                <div class="col-lg-3">
                                                    <?php
                                                    $result_room_file->execute();
                                                    if($result_room_file !== false && $db->last_row_count() > 0){
                                                        $row = $result_room_file->fetch(PDO::FETCH_ASSOC);

                                                        $file_id = $row['id'];
                                                        $filename = $row['file'];
                                                        $label = $row['label'];

                                                        $realpath = SYSBASE.'medias/room/small/'.$file_id.'/'.$filename;
                                                        $thumbpath = DOCBASE.'medias/room/small/'.$file_id.'/'.$filename;
                                                        $zoompath = DOCBASE.'medias/room/big/'.$file_id.'/'.$filename;

                                                        if(is_file($realpath)){
                                                            $s = getimagesize($realpath); ?>
                                                            <div class="img-container lazyload md">
                                                                <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo"  height="<?php echo $s[1]; ?>">
                                                            </div>
                                                            <?php
                                                        }
                                                    } ?>
                                                </div>
                                                <div class="col-sm-4 col-md-5 col-lg-4">
                                                    <h4><?php echo $room_title; ?></h4>
                                                    <p><?php echo $room_subtitle; ?></p>
                                                    <?php echo strtrunc(strip_tags($room_descr), 100); ?>
                                                    <div class="clearfix mt10">
                                                       <?php
                                                       $result_room_facilities->execute();
                                                       if($result_room_facilities !== false && $db->last_row_count() > 0){
                                                        foreach($result_room_facilities as $row){
                                                            $id_facility = $row['id'];
                                                            $facility_name = $row['name'];
                                                            
                                                            $result_facility_file->execute();
                                                            if($result_facility_file !== false && $db->last_row_count() > 0){
                                                                $row = $result_facility_file->fetch();
                                                                
                                                                $file_id = $row['id'];
                                                                $filename = $row['file'];
                                                                $label = $row['label'];
                                                                
                                                                $realpath = SYSBASE.'medias/facility/big/'.$file_id.'/'.$filename;
                                                                $thumbpath = DOCBASE.'medias/facility/big/'.$file_id.'/'.$filename;
                                                                
                                                                if(is_file($realpath)){ ?>
                                                                <span class="facility-icon">
                                                                    <img alt="<?php echo $facility_name; ?>" title="<?php echo $facility_name; ?>" src="<?php echo $thumbpath; ?>" class="tips">
                                                                </span>
                                                                <?php
                                                            }
                                                        }
                                                    }
                                                } ?>
                                            </div>
                                            <?php
                                            if($bed_type !=""){
                                                ?>
                                                <div class="clearfix mt10">
                                                    <p><b>Bed Type : </b><?php echo $bed_type; ?></p>
                                                </div>
                                                <?php } ?>
                                                <?php
                                                if($num_of_bed !=""){
                                                    ?>
                                                    <div class="clearfix mt10">
                                                        <p><b>Number of Beds : </b><?php echo $num_of_bed; ?></p>
                                                    </div>
                                                    <?php } ?>
                                                      <?php
                                                            if($room_dimention !=""){
                                                                ?>
                                                                <div class="clearfix mt10">
                                                                    <p><b>Room Dimension : </b><?php echo $room_dimention; ?></p>
                                                                </div>
                                                                <?php } ?>
                                                                <?php
                                                                if($views !=""){
                                                                    ?>
                                                                    <div class="clearfix mt10">
                                                                        <p><b>Room View : </b><?php echo $views; ?></p>
                                                                    </div>
                                                                    <?php } ?>
                                                                </div>
                                                                <div class="col-lg-5 col-md-6 col-sm-7 text-center sep hotel_detail_right">
                                                                    <!--<div class="price">
                                                                        <span itemprop="priceRange"><?php echo formatPrice($offer['offer_price']*CURRENCY_RATE); ?></span>
                                                                        <?php
                                                                        if($full_price > 0 && $full_price > $amount){ ?>
                                                                        <br><s class="text-warning"><?php echo formatPrice($offer['offer_price']*CURRENCY_RATE); ?></s>
                                                                        <?php
                                                                    } ?>
                                                                   </div> -->
                                                                <!--<div class="mb10 text-muted"><?php echo $texts['PRICE'].' / '.$type; ?></div>
                                                                <?php //echo $texts['CAPACITY']; ?> : <i class="fas fa-fw fa-male"></i>x<?php echo $max_people; ?> -->
                                                                
                                                                <?php if($room_stock > 0){ ?>
                                                                <div class="pt10 form-inline" id="offer_room_count">
                                                                    <i class="fas fa-fw fa-tags"></i> <?php echo $texts['NUM_ROOMS']; ?><br>
                                                                    
                                                                    <select name="num_rooms[<?php echo $id_room; ?>]" class="form-control room_select selectpicker btn-group-sm sendAjaxForm" data-target="#room-options-<?php echo $id_room; ?>" data-extratarget="#booking-amount_<?php echo $id_hotel; ?>" data-action="<?php echo getFromTemplate('common/change_num_rooms.php'); ?>?room=<?php echo $id_room; ?>">
                                                                      <?php
                                                                          for($i = 0; $i <= $room_stock; $i++){ ?>
                                                                          <?php $selected = '';
                                                                          if(isset($_SESSION['num_room']) && $num_room <= $room_stock && $kr==0 && $num_room == $i){
                                                                           $selected =' selected="selected"';
                                                                           $kr = 1;
                                                                       }elseif(isset($_SESSION['num_room']) && $num_room >= $room_stock && $kr==0 && $room_stock == $i ){
                                                                           $selected =' selected="selected"';
                                                                           $num_room = $num_room - $room_stock;
                                                                       } ?>
                                                                       <option value="<?php echo $i; ?>" <?php echo $selected; ?> ><?php echo $i; ?></option>
                                                                       <?php
                                                                     } ?>
                                                                  </select>
                                                           
                                                               </div>
                                                       <?php
                                                   }else{ ?>
                                                   <div class="mt10 btn btn-danger btn-block" disabled="disabled"><?php echo $texts['NO_AVAILABILITY']; ?></div>
                                                   <?php } ?>
                                       
                                                                <!--<p class="lead">
                                                                        <span class="clearfix"></span>
                                                                        <a class="btn btn-primary mt10 btn-block ajax-popup-link btn-sm" href="<?php echo getFromTemplate('common/room-popup.php', true); ?>" data-params="room=<?php echo $id_room; ?>">
                                                                            <i class="fas fa-fw fa-plus-circle"></i>
                                                                            <?php echo $texts['READMORE']; ?>
                                                                        </a>
                                                                </p>-->
                                                                    
                                                                </div>
                                                               <!-- <div class="col-lg-3 col-md-4 col-sm-5 sep">
                                                                    <div class="hb-calendar" data-cur_month="<?php echo gmdate('n', $from_time); ?>" data-cur_year="<?php echo gmdate('Y', $from_time); ?>" data-custom_var="room=<?php echo $id_room; ?>" data-day_loader="<?php echo getFromTemplate('common/get_days.php'); ?>"></div>
                                                                </div>-->
                                                                <div class="clearfix"></div>
                                                                <div id="offer_room_opt">
                                                                   <div id="room-options-<?php echo $id_room; ?>" class="room-options"></div>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <?php
                                                        }
                                                    }
                                                } 
                                            }else{ ?>
                                            <div class="alert alert-danger">
                                              <?php echo $texts['ROOM_NOT_AVAILABLE']; ?>.
                                          </div>
                                          <?php  } ?>
                                          
                                          <div class="mt10 booking-summary">
                                            <span id="booking-amount_<?php echo $hotel_id; ?>">
                                               <?php
                                               $room_stock = 0;
                                               $result_room->execute();
                                               if($result_room !== false){
                                                  foreach($result_room as $row){
                                                     $id_room = $row['id'];
                                                     $room_stock = isset($res_hotel[$hotel_id][$id_room]['room_stock']) ? $res_hotel[$hotel_id][$id_room]['room_stock'] : $row['stock'];
                                                 }
                                             }
                                             
                                             if($num_nights <= 0 || (empty($res_hotel[$hotel_id]) && $room_stock > 0) || (!empty($res_hotel[$hotel_id]) && $room_stock <= 0)){
                                              echo '
                                              <input type="hidden" name="adults" value="'.$_SESSION['num_adults'].'">
                                              <input type="hidden" name="children" value="'.$_SESSION['num_children'].'">';
                                          } ?>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          
                      </div>
                  </form>
              </div>
              <div class="hotel_about">
                  <div class="col-sm-12 col-md-6 col-lg-6">
                       <h2><?php echo $hotel['title']; ?></h2>
                       <h3>Address</h3>
                         <p><?php echo $hotel['address']; ?></p>
                         <script type="text/javascript">
                            var locations = [
                            ['<?php echo $hotel['title']; ?>', '<?php echo $hotel['address']; ?>', '<?php echo $hotel['lat']; ?>', '<?php echo $hotel['lng']; ?>']
                            ];
                        </script>
                        <div id="mapWrapper" class="mb10" data-marker="<?php echo getFromTemplate('images/marker.png'); ?>" data-api_key="<?php echo GMAPS_API_KEY; ?>"></div>
                  </div>
                  <div class="col-sm-12 col-md-6 col-lg-6">
                      <?php echo $hotel['descr']; ?>
                  </div> 
            </div>
            <?php
            if($hotel['cancel_policy'] !=""){
                ?>
                <div class="hotel_review_panel">
                 <h2>Cancellation Policy</h2>
                 <?php echo $hotel['cancel_policy']; ?>
                 
             </div>
             <?php } ?>
             <div class="hotel_review_panel">
                 <h2>Rating & Reviews</h2>
                 <?php
                 
                 $nb_comments = 0;
                 $item_type = 'hotel';
                 $item_id = $hotel_id;
                 $allow_comment = ALLOW_COMMENTS;
                 $allow_rating = ALLOW_RATINGS;
                 if($allow_comment == 1){
                    $result_comment = $db->query('SELECT * FROM pm_comment WHERE id_item = '.$item_id.' AND item_type = \''.$item_type.'\' AND checked = 1 ORDER BY add_date DESC');
                    if($result_comment !== false)
                        $nb_comments = $db->last_row_count();
                }
                
                ?>
                <?php
                foreach($result_comment as $i => $row){ ?>
                <div class="ratingbox">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3"><div class="rating_pic"><img src="<?php echo getFromTemplate("images/user.png"); ?>" alt="" /><?php echo $row['name']; ?></div></div>
                        <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                           <div class="rating_info">
                              
                            <?php
                            if($allow_rating == 1 && $row['rating'] > 0 && $row['rating'] <= 5){ ?>
                            <input type="hidden" class="rating" value="<?php echo $row['rating']; ?>" data-rtl="<?php echo (RTL_DIR) ? true : false; ?>" data-size="xs" readonly="true" data-show-clear="false" data-show-caption="false">
                            <?php
                        } ?>
                        <p><?php echo nl2br($row['msg']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>

</div>
</section>

</div>
</section>
<script>
   var night = '<?php echo $offer['no_day_night'];?>';
  function initMap() {
    var markerArray = [];

            // Instantiate a directions service.
            var directionsService = new google.maps.DirectionsService;

            // Create a map and center it on Manhattan.
            var map = new google.maps.Map(document.getElementById('map'), {
              zoom: 13,
              center: {lat: 40.771, lng: -73.974}
          });

            // Create a renderer for directions and bind it to the map.
            var directionsDisplay = new google.maps.DirectionsRenderer({map: map});

            // Instantiate an info window to hold step text.
            var stepDisplay = new google.maps.InfoWindow;

            // Display the route between the initial start and end selections.
            calculateAndDisplayRoute(
                directionsDisplay, directionsService, markerArray, stepDisplay, map);
            // Listen to change events from the start and end lists.
            var onChangeHandler = function() {
              calculateAndDisplayRoute(
                  directionsDisplay, directionsService, markerArray, stepDisplay, map);
          };
          document.getElementById('start').addEventListener('keyup', onChangeHandler);
          document.getElementById('end').addEventListener('change', onChangeHandler);
      }

      function calculateAndDisplayRoute(directionsDisplay, directionsService, markerArray, stepDisplay, map) {
            // First, remove any existing markers from the map.
            for (var i = 0; i < markerArray.length; i++) {
              markerArray[i].setMap(null);
          }

            // Retrieve the start and end locations and create a DirectionsRequest using
            // WALKING directions.
            directionsService.route({
              origin: document.getElementById('start').value,
              destination: document.getElementById('end').value,
              travelMode: 'WALKING'
          }, function(response, status) {
              // Route the directions and pass the response to a function to create
              // markers for each step.
              if (status === 'OK') {
                document.getElementById('warnings-panel').innerHTML =
                '<b>' + response.routes[0].warnings + '</b>';
                directionsDisplay.setDirections(response);
                showSteps(response, markerArray, stepDisplay, map);
            } else {
                window.alert('Directions request failed due to ' + status);
            }
        });
        }

        function showSteps(directionResult, markerArray, stepDisplay, map) {
            // For each step, place a marker, and add the text to the marker's infowindow.
            // Also attach the marker to an array so we can keep track of it and remove it
            // when calculating new routes.
            var myRoute = directionResult.routes[0].legs[0];
            for (var i = 0; i < myRoute.steps.length; i++) {
              var marker = markerArray[i] = markerArray[i] || new google.maps.Marker;
              marker.setMap(map);
              marker.setPosition(myRoute.steps[i].start_location);
              attachInstructionText(
                  stepDisplay, marker, myRoute.steps[i].instructions, map);
          }
      }

      function attachInstructionText(stepDisplay, marker, text, map) {
        google.maps.event.addListener(marker, 'click', function() {
              // Open an info window when the marker is clicked on, containing the text
              // of the step.
              stepDisplay.setContent(text);
              stepDisplay.open(map, marker);
          });
    }
    function place_initialize() {
        var input = document.getElementById('start');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
    // place variable will have all the information you are looking for.
    console.log(place.geometry);
    console.log(place.geometry);
});
    }

</script>
<script>
    $(document).ready(function() {
        //alert(night);
      var sync1 = $("#sync1");
      var sync2 = $("#sync2");
      
      sync1.owlCarousel({
        singleItem : true,
        autoPlay : false,
        slideSpeed : 1000,
        navigation: true,
        pagination:false,
        afterAction : syncPosition,
        responsiveRefreshRate : 200,
    });
    sync2.owlCarousel({
        items : 5,
        itemsDesktop      : [1199,5],
        itemsDesktopSmall     : [979,4],
        itemsTablet       : [768,3],
        itemsMobile       : [479,2],
        pagination:false,
        autoPlay : false,
        responsiveRefreshRate : 100,
        afterInit : function(el){
          el.find(".owl-item").eq(0).addClass("synced");
      }
  });
      
      function syncPosition(el){
        var current = this.currentItem;
        $("#sync2")
        .find(".owl-item")
        .removeClass("synced")
        .eq(current)
        .addClass("synced")
        if($("#sync2").data("owlCarousel") !== undefined){
          center(current)
      }
  }
  
  $("#sync2").on("click", ".owl-item", function(e){
    e.preventDefault();
    var number = $(this).data("owlItem");
    sync1.trigger("owl.goTo",number);
});
  
  function center(number){
    var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
    var num = number;
    var found = false;
    for(var i in sync2visible){
      if(num === sync2visible[i]){
        var found = true;
    }
}

if(found===false){
  if(num>sync2visible[sync2visible.length-1]){
    sync2.trigger("owl.goTo", num - sync2visible.length+2)
}else{
    if(num - 1 === -1){
      num = 0;
  }
  sync2.trigger("owl.goTo", num);
}
} else if(num === sync2visible[sync2visible.length-1]){
  sync2.trigger("owl.goTo", sync2visible[1])
} else if(num === sync2visible[0]){
  sync2.trigger("owl.goTo", num-1)
}
}

});

</script>