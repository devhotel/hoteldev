<?php
require_once('../../../common/lib.php');
require_once('../../../common/define.php');
$item_id = (int) $_POST['id'];
$id_booking = (int) $_POST['id_booking'];
$allow_comment = ALLOW_COMMENTS;
$allow_rating = ALLOW_RATINGS;
$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;
$result_user = $db->query("SELECT * FROM pm_user WHERE id = " . $db->quote($user_id) . " AND checked = 1");
if ($result_user !== false && $db->last_row_count() > 0) {
    $row = $result_user->fetch();

    $name = $row['firstname'] . ' ' . $row['lastname'];
    $email = $row['email'];
    $mobile = $row['mobile'];
    $phone = $row['phone'];
}

?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/3.5.5/css/star-rating.min.css">

<!-- Comments -->
<div class="white-popup-block review_booking_popup" id="popup-review-<?php echo $item_id; ?>">
    <h3 class="mb10"><?php echo $texts['LET_US_KNOW']; ?></h3>

    <div class="alert alert-success" style="display:none;"></div>
    <div class="alert alert-danger" style="display:none;"></div>

    <div class="row">
        <form method="post" id="post_review" class="ajax-form" action="<?php echo getFromTemplate("common/review_ajax.php"); ?>">
            <input type="hidden" name="item_type" value="hotel">
            <input type="hidden" name="item_id" value="<?php echo $item_id; ?>">
            <input type="hidden" name="id_booking" value="<?php echo $id_booking; ?>">
            <div class="col-sm-12">
                <div style="display:none;">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fas fa-fw fa-user"></i></div>
                            <input type="text" class="form-control" name="name" value="<?php echo htmlentities($name, ENT_QUOTES, "UTF-8"); ?>" placeholder="<?php echo $texts['LASTNAME'] . " " . $texts['FIRSTNAME']; ?> *">
                        </div>
                        <div class="field-notice" rel="name"></div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><i class="fas fa-fw fa-envelope"></i></div>
                            <input type="text" class="form-control" name="email" value="<?php echo htmlentities($email, ENT_QUOTES, "UTF-8"); ?>" placeholder="<?php echo $texts['EMAIL']; ?> *">
                        </div>
                        <div class="field-notice" rel="email"></div>
                    </div>
                </div>

                <?php if (CAPTCHA_PKEY != '' && CAPTCHA_SKEY != '') { ?>
                    <div class="form-group">
                        <div class="input-group mb5"></div>
                        <div class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_PKEY; ?>"></div>
                    </div>
                <?php
                } ?>

                <div class="form-group form-inline">
                    <p><label for="rating">Rating</label></p>
                    <?php $hotel_params = $db->query("SELECT * FROM pm_feedback_params where id_hotel = " . $item_id)->fetchAll();
                    //print_r($hotel_params);
                    if (!empty($hotel_params)) { ?>
                        <ul id="review_rating_params">
                            <input type="hidden" name="rating" value="">
                            <?php foreach ($hotel_params as $key => $value) { ?>
                                <li>
                                    <div class="col-md-12 input-group mb5">
                                        <div class="col-md-5">
                                            <span><?php echo $value['params']; ?></span>
                                        </div>
                                        <div class="col-md-7">
                                            <span>
                                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                    <?php echo $i; ?><input type="radio" class="review_param" name="params[<?php echo strtolower(str_replace(" ", "_", $value['params'])); ?>]" value="<?php echo $i; ?>">
                                                <?php } ?>
                                            </span>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } else { ?>
                        <div class="input-group mb5">
                            <!-- <input type="hidden" name="rating" class="rating" value="" data-rtl="false" min="1" max="5" data-step="1" data-size="xs" data-show-clear="false" data-show-caption="false">-->
                            <select name="rating" class="rating">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    <?php } ?>

                </div>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fas fa-fw fa-quote-left"></i></div>
                        <textarea class="form-control" name="msg" placeholder="<?php echo $texts['COMMENT']; ?> *" rows="9"><?php echo htmlentities('', ENT_QUOTES, "UTF-8"); ?></textarea>
                    </div>
                    <div class="field-notice" rel="msg"></div>
                </div>
                <input type="hidden" name="send_comment" value="1">
                <div class="form-group row">
                    <span class="col-sm-12">
                        <button type="button" onclick="post_review();" class="btn btn-primary"><i class="fas fa-fw fa-paper-plane"></i> <?php echo $texts['SEND']; ?></button> <i> * <?php echo $texts['REQUIRED_FIELD']; ?></i>
                    </span>
                </div>
            </div>
        </form>

    </div>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-star-rating/3.5.5/js/star-rating.min.js"></script>
<script type="text/javascript">
    $('.review_param').click(function() {

        var total = 0,
            valid_labels = 0,
            average;

        $('#review_rating_params .review_param').each(function() {
            if ($(this).prop('checked')) {
                var val = parseInt($(this).val(), 10);
                if (!isNaN(val)) {
                    valid_labels += 1;
                    total += val;
                }
            }
        });

        console.log(total)

        average = total / valid_labels;
        $('input[name=rating]').val(average);
    });
</script>