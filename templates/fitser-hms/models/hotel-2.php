<?php
if ($article_alias == '') err404();

$result = $db->query('SELECT * FROM pm_hotel WHERE checked = 1 AND lang = ' . LANG_ID . ' AND alias = ' . $db->quote($article_alias));
if ($result !== false && $db->last_row_count() == 1) {

  $hotel = $result->fetch(PDO::FETCH_ASSOC);

  $hotel_id = $hotel['id'];
  $article_id = $hotel_id;
  $title_tag = $hotel['title'] . ' - ' . $title_tag;
  $page_title = $hotel['title'];
  $page_subtitle = '';
  $page_alias = $pages[$page_id]['alias'] . '/' . text_format($hotel['alias']);
  $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = ' . $hotel_id . ' AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
  if ($result_hotel_file !== false && $db->last_row_count() > 0) {

    $row = $result_hotel_file->fetch();

    $file_id = $row['id'];
    $filename = $row['file'];

    if (is_file(SYSBASE . 'medias/hotel/medium/' . $file_id . '/' . $filename))
      $page_img = getUrl(true) . DOCBASE . 'medias/hotel/medium/' . $file_id . '/' . $filename;
  }
} else err404();

check_URI(DOCBASE . $page_alias);

/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
$javascripts[] = DOCBASE . 'js/plugins/sharrre/jquery.sharrre.min.js';
$stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.carousel.min.css', 'media' => 'all');
$stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/assets/owl.theme.default.min.css', 'media' => 'all');
$javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.2.4/owl.carousel.min.js';
$stylesheets[] = array('file' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/3.5.5/css/star-rating.min.css', 'media' => 'all');
$javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/3.5.5/js/star-rating.min.js';
$stylesheets[] = array('file' => DOCBASE . 'js/plugins/isotope/css/style.css', 'media' => 'all');
$javascripts[] = '//cdnjs.cloudflare.com/ajax/libs/jquery.isotope/1.5.25/jquery.isotope.min.js';
$javascripts[] = DOCBASE . 'js/plugins/isotope/jquery.isotope.sloppy-masonry.min.js';
$stylesheets[] = array('file' => DOCBASE . 'js/plugins/lazyloader/lazyloader.css', 'media' => 'all');
$javascripts[] = DOCBASE . 'js/plugins/lazyloader/lazyloader.js';
$javascripts[] = DOCBASE . 'js/plugins/live-search/jquery.liveSearch.js';


require(getFromTemplate('common/send_comment.php', false));

require(getFromTemplate('common/header.php', false)); ?>

<section id="page">

  <?php include(getFromTemplate('common/page_header.php', false)); ?>


  <div id="content" class="pb30">
    <div class="container">
      <div class="alert alert-success" style="display:none;"></div>
      <div class="alert alert-danger" style="display:none;"></div>
    </div>

    <section class="hotel_gallery_page">
      <?php
      $arrdata = array();
      $arr = array();
      $result_hotel_file = $db->query('SELECT * FROM pm_hotel_file WHERE id_item = ' . $hotel_id . ' AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank');
      if ($result_hotel_file !== false) {
        foreach ($result_hotel_file as $i => $row) {
          $label = ($row['label'] != "" ? $row['label'] : 'Feature');
          if (!in_array($label, $arr)) {
            $arr[] = $label;
            $arrdata[$label][] = $row;
          } else {
            $arrdata[$label][] = $row;
          }
        }
      }

      $result_hotel_room = $db->query('SELECT *  FROM pm_room WHERE id_hotel = ' . $hotel_id . ' AND checked = 1');


      //echo '<pre>';       
      //var_dump($arrdata);
      //echo '</pre>' 

      ?>
      <div class="container">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
          <?php if (!empty($arr)) {
            foreach ($arr as $k => $v) { ?>
              <li class="tablinks <?php echo ($k == 0 ? 'active' : ''); ?>"><a href="#<?php echo strtolower(preg_replace('/\s*/', '', $v)); ?>" onclick="openCity(event, '<?php echo strtolower(preg_replace('/\s*/', '', $v)); ?>')"><?php if ($v != "") {
                                                                                                                                                                                                                                        if ($v == 'Feature') {
                                                                                                                                                                                                                                          print 'Amenities';
                                                                                                                                                                                                                                        } else {
                                                                                                                                                                                                                                          print $v;
                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                      } else {
                                                                                                                                                                                                                                        print 'Hotel Images';
                                                                                                                                                                                                                                      } ?></a></li>
            <?php }
          }
          //var_dump( $result_hotel_room);
          if ($result_hotel_room->execute() !== false && $db->last_row_count() > 0) {
            foreach ($result_hotel_room as $i => $rrow) { ?>
              <li class="tablinks"><a href="#<?php echo strtolower(preg_replace('/\s*/', '', $rrow['title'])); ?>" onclick="openCity(event, '<?php echo strtolower(preg_replace('/\s*/', '',  $rrow['alias'])); ?>')"><?php if (strtolower(preg_replace('/\s*/', '', $rrow['title'])) == 'feature') {
                                                                                                                                                                                                                        print 'Amenities';
                                                                                                                                                                                                                      } else {
                                                                                                                                                                                                                        echo $rrow['title'];
                                                                                                                                                                                                                      } ?></a></li>
          <?php }
          }
          ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content gallery">
          <?php if (!empty($arr)) {
            foreach ($arr as $k => $v) { ?>
              <div class="tab-pane tabcontent <?php echo ($k == 0 ? 'active' : ''); ?>" id="<?php echo strtolower(preg_replace('/\s*/', '', $v)); ?>">
                <?php
                if (!empty($arrdata[$v])) {
                  foreach ($arrdata[$v] as $i => $row) {
                    $file_id = $row['id'];
                    $filename = $row['file'];
                    $label = $row['label'];
                    $realpath = SYSBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;
                    $thumbpath = DOCBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;
                    if (is_file($realpath)) { ?>
                      <a data-lightbox="roadtrip" href="<?php echo $thumbpath; ?>" class="example-image-link gallery_img img-container lazyload md ">
                        <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" height="150">
                      </a>
                <?php }
                  }
                }
                ?>

              </div>
            <?php }
          }
          if ($result_hotel_room->execute() !== false && $db->last_row_count() > 0) {
            foreach ($result_hotel_room as $i => $rcrow) { ?>
              <div class="tab-pane tabcontent" id="<?php echo strtolower(preg_replace('/\s*/', '', $rcrow['alias'])); ?>">
                <?php
                $result_room_file = $db->query('SELECT * FROM pm_room_file WHERE id_item = ' . $rcrow['id'] . ' AND checked = 1 AND lang = ' . DEFAULT_LANG . ' AND type = \'image\' AND file != \'\' ORDER BY rank');
                if ($result_room_file !== false) {
                  foreach ($result_room_file as $i => $flrow) {
                    $file_id = $flrow['id'];
                    $filename = $flrow['file'];
                    $label = $flrow['label'];
                    $realpath = SYSBASE . 'medias/room/big/' . $file_id . '/' . $filename;
                    $thumbpath = DOCBASE . 'medias/room/big/' . $file_id . '/' . $filename;
                    if (is_file($realpath)) { ?>
                      <a data-lightbox="roadtrip" href="<?php echo $thumbpath; ?>" class="example-image-link gallery_img img-container lazyload md ">
                        <img alt="<?php echo $label; ?>" data-src="<?php echo $thumbpath; ?>" itemprop="photo" height="150">
                      </a>
                <?php }
                  }
                }
                ?>
              </div>
          <?php }
          } ?>
        </div>
      </div>
    </section>

  </div>
</section>
<script>
  function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
  }

  // Get the element with id="defaultOpen" and click on it
  document.getElementById("defaultOpen").click();
</script>