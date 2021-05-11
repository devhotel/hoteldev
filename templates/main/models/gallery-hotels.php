<?php
$stylesheets[] = array("file" => DOCBASE . "js/plugins/isotope/css/style.css", "media" => "all");
$javascripts[] = DOCBASE . "js/plugins/isotope/jquery.isotope.min.js";
$javascripts[] = DOCBASE . "js/plugins/isotope/jquery.isotope.sloppy-masonry.min.js";

$stylesheets[] = array("file" => DOCBASE . "js/plugins/lazyloader/lazyloader.css", "media" => "all");
$javascripts[] = DOCBASE . "js/plugins/lazyloader/lazyloader.js";

require(getFromTemplate("common/header.php", false)); ?>

<section id="page">

    <?php include(getFromTemplate("common/page_header.php", false)); ?>

    <div id="content" class="pt30 pb20">
        <div class="container">
            <div class="row">
                <?php
                if ($page['text'] != "") { ?>
                    <div class="col-md-12"><?php echo $page['text']; ?></div>
                <?php
                } ?>
                <form method="get" action="" class="booking-search">
                    <div class="page_search">
                        <div class="row">
                            <div class="col-md-6 col-sm-4 col-xs-12">
                                <div class="input-wrapper form-inline">
                                    <i class="fa fa-map-marker"></i>
                                    <div class="input-group">
                                        <?php
                                        if (isset($_GET['id_destination']) && $_GET['id_destination'] > 0) {
                                            $id_destination = $_GET['id_destination'];
                                        } else {
                                            $id_destination = 0;
                                        }
                                        if (isset($_GET['id_hotel']) && $_GET['id_hotel'] > 0) {
                                            $id_hotel = $_GET['id_hotel'];
                                        } else {
                                            $id_hotel = 0;
                                        }
                                        $result_location = $db->query('SELECT * FROM pm_destination WHERE checked = 1 AND  lang = ' . LANG_ID);
                                        ?>
                                        <select class="form-control" name="id_destination" id="id_destination">
                                            <option value="0">All Destination</option>
                                            <?php
                                            foreach ($result_location as $key => $location) {
                                                if ($id_destination > 0 && $location['id'] == $id_destination) {
                                                    $select = 'selected="selected"';
                                                } else {
                                                    $select = '';
                                                }
                                                echo '<option value="' . $location['id'] . '" ' . $select . '>' . $location['name'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field-notice" rel="id_hotel"></div>
                                </div>
                            </div>
                            <div class="col-md-5 col-sm-4 col-xs-12">
                                <div class="input-wrapper form-inline">
                                    <i class="fa fa-bed"></i>
                                    <div class="input-group">
                                        <?php
                                        $sql = "SELECT * FROM pm_hotel WHERE checked = 1 ";
                                        $id_hotel = '';
                                        $id_destination = '';
                                        if (isset($_GET['id_destination']) && $_GET['id_destination'] > 0) {
                                            $sql .= " AND id_destination  = " . $_GET['id_destination'];
                                            //$id_destination = $_GET['id_destination'];
                                        }
                                        if (isset($_GET['id_hotel']) && $_GET['id_hotel'] > 0) {
                                            $sql .= " AND id  = " . $_GET['id_hotel'];
                                            $id_hotel = $_GET['id_hotel'];
                                        }

                                        $sql .= " AND lang = " . LANG_ID;
                                        $result_hotels = $db->query($sql);
                                        ?>
                                        <select class="form-control" name="id_hotel" id="id_hotel">
                                            <option value="0">All Hotels</option>
                                            <?php
                                            foreach ($result_hotels as $key => $hotel) {
                                                if ($id_hotel > 0 && $hotel['id'] == $id_hotel) {
                                                    $select = 'selected="selected"';
                                                } else {
                                                    $select = '';
                                                }
                                                echo '<option value="' . $hotel['id'] . '" ' . $select . '>' . $hotel['title'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field-notice" rel="id_hotel"></div>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-1 col-xs-12">
                                <div class="form-group">
                                    <!-- <button class="btn btn-block btn-primary" type="button" name="check_availabilities" id="check_availabilities">GO</button> -->
                                    <button type="submit" class="btn btn-default btn-gallery" name="change_date">GO</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <?php
                $lz_offset = 1;
                $lz_limit = 8;
                $lz_pages = 0;
                $num_records = 0;
                $sql = "SELECT count(*) FROM pm_hotel WHERE checked = 1 ";
                $id_hotel = '';
                $id_destination = '';
                if (isset($_GET['id_destination']) && $_GET['id_destination'] > 0) {
                    $sql .= " AND id_destination  = " . $_GET['id_destination'];
                    $id_destination = $_GET['id_destination'];
                }
                if (isset($_GET['id_hotel']) && $_GET['id_hotel'] > 0) {
                    $sql .= " AND id  = " . $_GET['id_hotel'];
                    $id_hotel = $_GET['id_hotel'];
                }

                $sql .= " AND lang = " . LANG_ID;

                $result = $db->query($sql);
                if ($result !== false) {
                    $num_records = $result->fetchColumn(0);
                    $lz_pages = ceil($num_records / $lz_limit);
                }
                if ($num_records > 0) { ?>
                    <div class="isotopeWrapper clearfix isotope lazy-wrapper" data-loader="<?php echo getFromTemplate("common/get_hotels_gallery.php"); ?>" data-mode="click" data-destination="<?php echo $id_destination; ?>" data-hotel="<?php echo $id_hotel; ?>" data-limit="<?php echo $lz_limit; ?>" data-pages="<?php echo $lz_pages; ?>" data-more_caption="<?php echo $texts['LOAD_MORE']; ?>" data-is_isotope="true" data-variables="page_id=<?php echo $page_id; ?>&page_alias=<?php echo $page['alias']; ?>">
                        <?php include(getFromTemplate("common/get_hotels_gallery.php", false)); ?>
                    </div>
                <?php
                } ?>
            </div>
        </div>
    </div>
</section>

<script>
    var destination = $('[name="id_destination"]');
    var hotels = $('[name="id_hotel"]');

    function get_hotels_by_destination() {
        var id_location = destination.val();
        hotels.empty();
        $.ajax({
            url: '/templates/main/include/get_hotel.php',
            type: 'POST',
            data: {
                id_location: id_location
            },
            dataType: 'json',
            success: function(data) {
                var html = '';
                $.each(data, function(key, entry) {
                    hotels.append($('<option></option>').attr('value', entry.id).text(entry.title));
                })
            }
        });
    }
    destination.on('change keyup', function() {
        get_hotels_by_destination();
    });
</script>