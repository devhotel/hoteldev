<?php
$msg_error = "";
$msg_success = "";
$field_notice = array();

if (isset($_GET['view'])) $view = $_GET['view'];
else $view = "account";

$user_id = isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 0;


$result_user = $db->query("SELECT * FROM pm_user WHERE id = " . $db->quote($user_id) . " AND checked = 1");
if ($result_user !== false && $db->last_row_count() > 0) {
    $row = $result_user->fetch();

    $firstname = $row['firstname'];
    $lastname = $row['lastname'];
    $login = $row['login'];
    $email = $row['email'];
    $address = $row['address'];
    $postcode = $row['postcode'];
    $city = $row['city'];
    $company = $row['company'];
    $country = $row['country'];
    $mobile = $row['mobile'];
    $phone = $row['phone'];
} else {
    $firstname = "";
    $lastname = "";
    $login = "";
    $email = "";
    $address = "";
    $postcode = "";
    $city = "";
    $company = "";
    $country = "";
    $mobile = "";
    $phone = "";
}
if ($user_id == 0) {
    $login_url = base_url('/login');
    echo '<script> window.location.href = "' . $login_url . '"; </script>';
    exit;
}
require(getFromTemplate("common/header.php", false)); ?>

<section id="page">

    <?php include(getFromTemplate("common/page_header.php", false)); ?>

    <div id="content" class="pt30 pb30 account_sec">
        <div class="container">
            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>
            <?php
            if ($user_id > 0) { ?>
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="pagination account_tab pull-right">
                            <li<?php if ($view == "account") echo " class=\"active\""; ?>><a href="?view=account"><?php echo $texts['MY_ACCOUNT']; ?></a></li>
                                <!--<li<?php //if($view == "change-password") echo " class=\"active\""; 
                                        ?>><a href="?view=change-password"><?php //echo $texts['CHANGE_PASSWORD']; 
                                                                            ?></a></li>-->
                                <li<?php if ($view == "booking-history") echo " class=\"active\""; ?>><a href="?view=booking-history"><?php echo $texts['BOOKING_HISTORY']; ?></a></li>
                                    <!--<li<?php if ($view == "favorites") echo " class=\"active\""; ?>><a href="?view=favorites"><?php echo $texts['WISHLIST_TEXT']; ?></a></li>-->
                                    <!--<li<?php if ($view == "wallet") echo " class=\"active\""; ?>><a href="?view=wallet"><?php echo $texts['WALLET']; ?></a></li>-->
                                    <!--<li<?php //if($view == "transaction") echo " class=\"active\""; 
                                            ?>><a href="?view=transaction"><?php //echo $texts['TRANSACTION_TEXT']; 
                                                                            ?></a></li>-->
                        </ul>
                    </div>
                </div>
            <?php
            }

            $hotel_id = 0;
            $result_hotel = $db->prepare("SELECT title,city FROM pm_hotel WHERE id = :hotel_id AND lang = " . LANG_ID);
            $result_hotel->bindParam(':hotel_id', $hotel_id);

            if ($view == "booking-history" && $user_id > 0) { ?>
                <fieldset>

                    <legend>
                        <?php
                        if (isset($_GET['type'])) {
                            $type = $_GET['type'];
                            if ($type == "ub") {
                                $result_booking = $db->query("SELECT * FROM pm_booking WHERE id_user = " . $db->quote($user_id) . " AND from_date > " . time() . " AND status !=2 ORDER BY add_date DESC");
                                echo $texts['UPCOMING_BOOKING_HISTORY'];
                            } elseif ($type == "ob") {
                                $result_booking = $db->query("SELECT * FROM pm_booking WHERE id_user = " . $db->quote($user_id) . " AND from_date <= " . time() . " AND to_date >= " . time() . " ORDER BY add_date DESC");
                                echo $texts['ONGOING_BOOKING_HISTORY'];
                            } elseif ($type == "pb") {
                                $result_booking = $db->query("SELECT * FROM pm_booking WHERE id_user = " . $db->quote($user_id) . " AND to_date < " . time() . " ORDER BY add_date DESC");
                                echo $texts['PAST_BOOKING_HISTORY'];
                            } else {
                                $result_booking = $db->query("SELECT * FROM pm_booking WHERE id_user = " . $db->quote($user_id) . " ORDER BY add_date DESC");
                                echo $texts['BOOKING_HISTORY'];
                            }
                        } else {
                            $type = '';
                            $result_booking = $db->query("SELECT * FROM pm_booking WHERE id_user = " . $db->quote($user_id) . " ORDER BY add_date DESC");
                            echo $texts['BOOKING_HISTORY'];
                        }
                        $result_booking = $result_booking->fetchAll();
                        ?>

                        <div class="pull-right" style="margin-bottom: 10px;">
                            <select class="form-control" onchange="document.location.href=this.value">
                                <option value="<?php echo DOCBASE . $page['alias']; ?>?view=booking-history" <?php echo ($view == "booking-history") ? "selected" : ''; ?>>All Booking</option>
                                <option value="<?php echo DOCBASE . $page['alias']; ?>?view=booking-history&type=ub" <?php echo ($type == "ub") ? "selected" : ''; ?>>Upcoming Booking</option>
                                <option value="<?php echo DOCBASE . $page['alias']; ?>?view=booking-history&type=ob" <?php echo ($type == "ob") ? "selected" : ''; ?>>Ongoing Booking</option>
                                <option value="<?php echo DOCBASE . $page['alias']; ?>?view=booking-history&type=pb" <?php echo ($type == "pb") ? "selected" : ''; ?>>Past Booking </option>
                            </select>
                        </div>
                    </legend>
                    <div class="wallet_wraper">
                        <?php
                        if (!empty($result_booking)) { ?>
                            <?php
                            foreach ($result_booking as $i => $row) {

                                $hotel_id = $row['id_hotel'];
                                $hotel = '';
                                if ($result_hotel->execute() !== false && $db->last_row_count() > 0)
                                    $bresult_hotel = $result_hotel->fetch();
                                $hotel = $bresult_hotel['title'];
                                $hotel_city = $bresult_hotel['city'];
                            ?>
                                <div class="booking_box_top">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-1 col-md-1 col-lg-1">
                                            <span class="fontawsome"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <h3><?php echo $hotel_city; ?></h3>
                                            <p><?php echo date('jS F Y', $row['from_date']); ?> - <?php echo date('jS F Y', $row['to_date']); ?></p>
                                            <?php $result_cancel = $db->query('SELECT * FROM pm_booking_cancel WHERE id_booking = ' . $row['id']);
                                            if ($result_cancel !== false && $db->last_row_count() > 0) { ?>
                                                <div>
                                                    <?php foreach ($result_cancel as $cancel) { ?>
                                                        <p class="booking_cancel">
                                                            <span style="text-transform: capitalize;"><?php echo $cancel['cancel_type']; ?> cancelled </span>
                                                            <strong>Refund amount : <?php echo formatPrice($cancel['refund_amount'] * CURRENCY_RATE);; ?></strong><br />
                                                            <span style="text-transform: capitalize;">Cancelled : </span><strong><?= $cancel['cancel_element'] ?></strong>
                                                        </p>
                                                    <?php } ?>
                                                </div>
                                            <?php  } ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <h3><?php echo $hotel; ?></h3>
                                            <p>Booking ID : <a href="<?php echo getFromTemplate("common/booking-popup.php"); ?>" data-params="id=<?php echo $row['id']; ?>" class="ajax-popup-link"> <?php echo $row['id']; ?></a></p>
                                            <p style="text-transform: capitalize;"><?php if ($row['adults'] > 0) echo $row['adults'] . ' ' . getAltText($texts['ADULT'], $texts['ADULTS'], $row['adults']); ?>
                                                <?php if ($row['children'] > 0) echo ' , ';
                                                if ($row['children'] > 0) echo $row['children'] . ' ' . getAltText($texts['CHILD'], $texts['CHILDREN'], $row['children']); ?>
                                            </p>
                                            <p><?php echo formatPrice($row['total'] * CURRENCY_RATE); ?> </p>
                                        </div>
                                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                                            <?php
                                            switch ($row['status']) {
                                                case 1:
                                                    echo '<span class="pull-right label label-primary" style="color:#fff">' . $texts['AWAITING'] . '</span>';
                                                    break;
                                                case 2:
                                                    echo '<span class="pull-right label label-danger" style="color:#fff">' . $texts['CANCELLED'] . '</span>';
                                                    break;
                                                case 3:
                                                    echo '<span class="pull-right label label-danger" style="color:#fff">' . $texts['REJECTED_PAYMENT'] . '</span>';
                                                    break;
                                                case 4:
                                                    echo '<span class="pull-right label label-success" style="color:#fff">' . $texts['PAYED'] . '</span>';
                                                    break;
                                                default:
                                                    echo '<span class="pull-right label label-primary" style="color:#fff">' . $texts['AWAITING'] . '</span>';
                                                    break;
                                            }
                                            ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">

                                            <a href="<?php echo getFromTemplate("common/booking-popup.php"); ?>" data-params="id=<?php echo $row['id']; ?>" class="ajax-popup-link">View Details <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                                            <br />
                                            <?php
                                            $now = strtotime("now");
                                            if ($row['from_date'] >= $now && $row['status'] != 2 && $row['checked_out'] == '') { ?>
                                                <a href="<?php echo getFromTemplate("common/cancel_popup.php"); ?>" data-params="id=<?php echo $row['id']; ?>" class="ajax-popup-link">Cancel Booking <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                                            <?php } ?>
                                            <?php
                                            if ($row['checked_out'] == 'out') {
                                                $result_review = $db->query("SELECT * FROM pm_comment WHERE item_type ='hotel' AND id_booking = " . $row['id']);
                                                //var_dump($db->last_row_count()); 
                                                if ($result_review->execute() !== false && $db->last_row_count() > 0) {
                                                    $review = $result_review->fetch();
                                                    echo ($review['checked'] == 1 ? '<span class="text-success">Review Approved</span>' : '<span class="text-warning">Review Pending</span>');
                                                } else { ?>
                                                    <a href="<?php echo getFromTemplate("common/review-popup.php"); ?>" data-params="id=<?php echo $hotel_id; ?>& id_booking=<?php echo $row['id']; ?>" class="ajax-popup-link">Feedback <i class="fa fa-chevron-right" aria-hidden="true"></i></a>
                                            <?php
                                                }
                                            } ?>
                                        </div>
                                        <?php
                                        $booking_cancels = $db->query("SELECT * FROM pm_booking_cancel AS pbc WHERE  pbc.`id_booking` = " . $row['id'])->fetchAll();
                                        //var_dump($booking_cancels);
                                        if (!empty($booking_cancels)) {
                                            foreach ($booking_cancels as $kc => $cancel) { ?>
                                                <!--  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                      <p style="color:red;"> Booking <?php echo $cancel['cancel_type'] . ' Cancelled'; ?> Due to <?php echo $cancel['reason']; ?> Cancellation Change <?php echo $cancel['refund_charge']; ?>  </p>
                                   </div> -->
                                        <?php }
                                        } ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php
                        } else { ?>
                            <p class="lead text-center text-muted"><?php echo $texts['NO_BOOKING_YET']; ?></p>
                        <?php
                        } ?>
                    </div>
                </fieldset>
            <?php
            } elseif ($view == "change-password" && $user_id > 0) { ?>
                <fieldset>
                    <legend><?php echo $texts['CHANGE_PASSWORD']; ?></legend>
                    <div class="row wallet_wraper">
                        <form method="post" action="<?php echo DOCBASE . $page['alias']; ?>" role="form" class="ajax-form">
                            <div class="alert alert-success" style="display:none;"></div>
                            <div class="alert alert-danger" style="display:none;"></div>
                            <input type="hidden" name="signup_type" value="complete">
                            <div class="col-sm-6">

                                <input type="hidden" class="form-control" name="firstname" value="<?php echo $firstname; ?>" />
                                <input type="hidden" class="form-control" name="lastname" value="<?php echo $lastname; ?>" />
                                <input type="hidden" class="form-control" name="username" value="<?php echo $login; ?>" />
                                <input type="hidden" class="form-control" name="email" value="<?php echo $email; ?>" readonly />

                                <div class="row form-group">
                                    <label class="col-lg-3 control-label"><?php echo ($user_id > 0) ? $texts['NEW_PASSWORD'] : $texts['PASSWORD']; ?></label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="password" value="" required />
                                        <div class="field-notice" rel="password"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-lg-3 control-label"><?php echo $texts['PASSWORD_CONFIRM']; ?></label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="password_confirm" value="" required />
                                        <div class="field-notice" rel="password_confirm"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-lg-3 control-label"></label>
                                    <div class="col-lg-9">
                                        <input type="checkbox" name="privacy_agreement" value="1"> <?php echo $texts['PRIVACY_POLICY_AGREEMENT']; ?>
                                        <div class="field-notice" rel="privacy_agreement"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12 text-right">
                                        <i class="text-muted"> * <?php echo $texts['REQUIRED_FIELD']; ?> </i><br>
                                        <a href="#" class="btn btn-primary sendAjaxForm" data-action="<?php echo getFromTemplate("common/register/account-edit.php"); ?>" <?php if ($user_id == 0) echo " data-clear=\"true\""; ?>><i class="fa fa-edit"></i> <?php echo ($user_id > 0) ? $texts['UPDATE'] : $texts['SIGN_UP']; ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">

                                <input type="hidden" class="form-control" name="address" value="<?php echo $address; ?>" />


                                <input type="hidden" class="form-control" name="postcode" value="<?php echo $postcode; ?>" />


                                <input type="hidden" class="form-control" name="city" value="<?php echo $city; ?>" />
                                <input type="hidden" class="form-control" name="country" value="<?php echo $country; ?>" />


                                <input type="hidden" class="form-control" name="phone" value="<?php echo $phone; ?>" />


                                <input type="hidden" class="form-control" name="mobile" value="<?php echo $mobile; ?>" />


                                <input type="hidden" class="form-control" name="company" value="<?php echo $company; ?>" />


                            </div>
                        </form>
                    </div>
                </fieldset>
            <?php } elseif ($view == "favorites" && $user_id > 0) { ?>
                <fieldset>
                    <legend><?php echo $texts['WISHLIST_TEXT']; ?></legend>
                    <div class="row wallet_wraper">

                        <div class="col-sm-12">
                            <?php
                            $wishlist_sql = $db->query("SELECT * FROM pm_wishlist WHERE user_id =" . $db->quote($user_id) . " ORDER BY date DESC");
                            $wishlist_result = $wishlist_sql->fetchAll();
                            if (!empty($wishlist_result)) {
                            ?>
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#ID</th>
                                            <th class="text-center">Image</th>
                                            <th class="text-center"><?php echo $texts['HOTEL']; ?></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($wishlist_result as $i => $row) {
                                            $result_hotel = $db->prepare("SELECT * FROM pm_hotel WHERE id = :hotel_id AND lang = " . LANG_ID);
                                            $result_hotel->bindParam(':hotel_id', $row['hotel_id']);
                                            $result_hotel->execute();
                                            //$hotel = $result_hotel->fetchColumn(0);
                                            $hotel_data = $result_hotel->fetch();
                                            $result_hotel_file = $db->prepare('SELECT * FROM pm_hotel_file WHERE id_item = :id_hotel AND checked = 1 AND lang = ' . LANG_ID . ' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
                                            $result_hotel_file->bindParam(':id_hotel', $row['hotel_id']);
                                            $result_hotel_file->execute();
                                            $file = $result_hotel_file->fetch(PDO::FETCH_ASSOC);

                                            $file_id = $file['id'];
                                            $filename = $file['file'];
                                            $realpath = SYSBASE . 'medias/hotel/medium/' . $file_id . '/' . $filename;
                                            $thumbpath = DOCBASE . 'medias/hotel/medium/' . $file_id . '/' . $filename;
                                            $zoompath = DOCBASE . 'medias/hotel/big/' . $file_id . '/' . $filename;
                                        ?>
                                            <tr>
                                                <td class="text-center"><?php echo $i + 1;  ?></td>
                                                <td class="text-center"><a href="<?php echo DOCBASE . $sys_pages['hotels']['alias'] . '/' . text_format($hotel_data['alias']); ?>"><img alt="<?php echo $label;  ?>" src="<?php echo $thumbpath; ?>" width="150px"></a></td>
                                                <td class="text-center"><a href="<?php echo DOCBASE . $sys_pages['hotels']['alias'] . '/' . text_format($hotel_data['alias']); ?>"><?php echo $hotel_data['title']; ?></a></td>
                                                <td>
                                                    <form method="post" action="<?php echo DOCBASE . $page['alias']; ?>" class="ajax-form">
                                                        <input type="hidden" name="data_id" value="<?php echo $row['id'] ?>" />
                                                        <a class="sendAjaxForm" data-action="<?php echo DOCBASE . 'templates/' . TEMPLATE . '/common/del_wishlist.php'; ?>" data-refresh="true" href="#"><i class="fa fa-trash"></i></a>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php
                                        } ?>
                                    </tbody>
                                </table>
                            <?php } else { ?>
                                <p class="lead text-center text-muted"><?php echo $texts['NO_WISHLIST_YET']; ?></p>
                            <?php
                            } ?>
                        </div>
                    </div>

                </fieldset>
            <?php } elseif ($view == "wallet" && $user_id > 0) {
                $wallet_info = $db->query("SELECT * FROM pm_wallet WHERE user_id=" . $db->quote($user_id));
                $wallet_result = $wallet_info->fetch();
                $wallet_historys = $db->query("SELECT * FROM pm_wallet_history WHERE user_id=" . $db->quote($user_id) . " ORDER BY id DESC");
            ?>
                <fieldset>
                    <legend><?php echo $texts['WALLET']; ?></legend>
                    <div class="row wallet_wraper">

                        <div class="col-sm-12">
                            <div class="wallet_box">
                                <svg width="55" height="49" xmlns="http://www.w3.org/2000/svg">
                                    <g fill="none" fill-rule="evenodd">
                                        <path d="M26.712 17.665h9.394v.02c-.553.852-.865 1.308-.937 1.367h-2.51c.345.403.58.895.703 1.474h2.676c.033.007.049.02.049.04-.599.898-.905 1.347-.918 1.347h-1.738c-.04.462-.26 1.019-.664 1.67-.534.657-1.045 1.084-1.534 1.28-.911.435-1.783.653-2.617.653v.02c0 .065.205.335.615.81l4.64 5.596v.264c0 .026-.017.039-.05.039h-2.04c-3.673-4.277-5.508-6.426-5.508-6.445v-1.3l.019-.048a8.35 8.35 0 0 0 1.143.068c2.057 0 3.336-.667 3.838-2.002.078-.195.117-.397.117-.605h-5.586c-.026 0-.04-.013-.04-.04.62-.898.935-1.347.948-1.347h4.434v-.02c-.254-.514-.746-.914-1.475-1.2-.52-.17-.99-.254-1.406-.254h-2.48v-.05c.598-.891.907-1.337.927-1.337z" fill="#d6ad39"></path>
                                        <path d="M6.628 28.893l-.333.13V12.418c0-2.577 2.156-4.667 4.78-4.667h5.916l27.98-6.613c2.711-.641 5.012 1.025 5.002 3.605L49.96 7.77c2.438.212 4.35 2.216 4.35 4.648v25.814c0 2.578-2.157 4.667-4.78 4.667H21.452c-1.78 3.439-5.432 5.797-9.649 5.797C5.837 48.696 1 43.975 1 38.15c0-3.993 2.275-7.468 5.628-9.258zm2.192-.879a11.057 11.057 0 0 1 2.983-.407c5.967 0 10.804 4.72 10.804 10.544 0 .865-.107 1.705-.308 2.508l.046-.109H49.53c1.304 0 2.38-1.047 2.38-2.324v-25.74c0-1.281-1.068-2.324-2.38-2.324L11.2 10.094c-1.304 0-2.379 1.047-2.379 2.324v15.596zM26.079 7.751h21.5V5.436c.006-1.372-1.172-2.456-2.608-2.116 0 0-9.419 2.49-18.892 4.43zM6.629 28.893l2.191-.852v-.027c-.77.215-1.503.512-2.192.879zm15.67 11.766l-.936 2.24h.09c.364-.704.65-1.455.846-2.24zm-10.492 5.7c4.641 0 8.403-3.673 8.403-8.202 0-4.53-3.762-8.202-8.403-8.202-4.64 0-8.403 3.672-8.403 8.202 0 4.53 3.763 8.201 8.403 8.201zM6.594 37.86l1.515-1.515 2.958 2.958 4.546-4.546 1.479 1.479-6.061 6.061-4.437-4.437z" stroke="#FFF" stroke-width=".3" fill="#000" opacity=".87"></path>
                                    </g>
                                </svg>
                                <div class="wallet_amount">
                                    <?php echo formatPrice($wallet_result['amount'] * CURRENCY_RATE); ?>
                                </div>
                                <p><?php echo $texts['WALLET_BALANCE']; ?></p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <table class="table table-bordered table-hover table-striped" id="data_table">
                                <thead>
                                    <tr>
                                        <th scope="col">#ID</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Remarks</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <?php
                                if (!empty($wallet_historys)) {
                                ?>
                                    <tbody>
                                        <?php
                                        foreach ($wallet_historys as $k => $wh) { ?>
                                            <tr class="table-success">
                                                <td><?php echo $wh['id']; ?></td>
                                                <td><?php echo gmstrftime(DATE_FORMAT, $wh['c_date']); ?></td>
                                                <td>
                                                    <?php
                                                    switch ($wh['purpose']) {
                                                        case 'cancel':
                                                            echo 'Cancel Booking';
                                                            break;
                                                        case 'booking':
                                                            echo 'New Booking';
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                                <td><span style="<?php echo ($wh['type'] == 'credit') ? 'color:#008000' : 'color:#b70000'; ?>"><?php echo formatPrice($wh['amount'] * CURRENCY_RATE); ?></span> (<?php echo $wh['type']; ?>)</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                <?php } else { ?>
                                    <p class="lead text-center text-muted"><?php echo $texts['NO_WALLET_BALANCE']; ?></p>
                                <?php } ?>
                            </table>
                            <!--<img src="<?php //echo getFromTemplate("images/wallet-1.png"); 
                                            ?>" class="img-responsive" />-->
                        </div>
                    </div>
        </div>
        </fieldset>
    <?php } elseif ($view == "transaction" && $user_id > 0) {
                $transaction_sql = $db->query("SELECT *, pbp.amount as booking_amount FROM pm_booking_payment AS pbp INNER JOIN pm_booking AS pb ON pb.id = pbp.id_booking WHERE pb.id_user = " . $db->quote($user_id) . " ORDER BY pb.add_date DESC");
                $transaction_result = $transaction_sql->fetchAll();
    ?>

        <fieldset>
            <legend><?php echo $texts['TRANSACTION_TEXT']; ?></legend>
            <?php

                if (!empty($transaction_result)) {
            ?>
                <table class="table table-bordered table-hover table-striped" id="data_table">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo $texts['BOOKING_ID_TEXT']; ?></th>
                            <th class="text-center"><?php echo $texts['PAYMENT_METHOD']; ?></th>
                            <th class="text-center"><?php echo $texts['NUM_TRANSACTION']; ?></th>
                            <th class="text-center"><?php echo $texts['TOTAL']; ?></th>
                            <th class="text-center"><?php echo $texts['DATE']; ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($transaction_result as $i => $row) {
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $row['id']; ?></td>
                                <td class="text-center"><?php echo $row['method']; ?></td>
                                <td class="text-center"><?php echo $row['trans']; ?></td>
                                <td class="text-center"><?php echo formatPrice($row['booking_amount'] * CURRENCY_RATE); ?></td>
                                <td class="text-center"><?php echo gmstrftime(DATE_FORMAT, $row['date']); ?></td>
                            </tr>
                        <?php
                        } ?>
                    </tbody>
                </table>
            <?php } else { ?>
                <p class="lead text-center text-muted"><?php echo $texts['NO_TRANSACTION_YET']; ?></p>
            <?php
                } ?>
    </div>
    </fieldset>
    <?php } else {
                if ($user_id == 0) { ?>
        <fieldset>
            <legend><?php echo $texts['ALREADY_HAVE_ACCOUNT']; ?></legend>
            <div class="row wallet_wraper">
                <form method="post" action="<?php echo DOCBASE . $page['alias']; ?>" role="form" class="ajax-form">
                    <div class="alert alert-success" style="display:none;"></div>
                    <div class="alert alert-danger" style="display:none;"></div>
                    <div class="col-sm-6">
                        <div class="row form-group">
                            <label class="col-lg-3 control-label"><?php echo $texts['USERNAME']; ?></label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" name="user" value="<?php echo $login; ?>" />
                                <div class="field-notice" rel="user"></div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <label class="col-lg-3 control-label"><?php echo $texts['PASSWORD']; ?></label>
                            <div class="col-lg-9">
                                <input type="password" class="form-control" name="pass" value="" />
                                <div class="field-notice" rel="pass"></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-7 col-lg-4 col-lg-offset-3 text-left">
                                <a class="popup-modal open-pass-form" href="#user-popup"><?php echo $texts['FORGOTTEN_PASSWORD']; ?></a>
                            </div>
                            <div class="col-sm-5 text-right">
                                <a href="#" class="btn btn-primary sendAjaxForm" data-action="<?php echo getFromTemplate("common/register/login.php"); ?>" data-refresh="true"><i class="fa fa-power-off"></i> <?php echo $texts['LOG_IN']; ?></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </fieldset>
    <?php
                } ?>
    <fieldset>
        <legend><?php echo ($user_id == 0) ? $texts['I_SIGN_UP'] : $texts['MY_ACCOUNT']; ?></legend>
        <div class="row wallet_wraper">
            <form method="post" action="<?php echo DOCBASE . $page['alias']; ?>" role="form" class="ajax-form">
                <div class="alert alert-success" style="display:none;"></div>
                <div class="alert alert-danger" style="display:none;"></div>
                <input type="hidden" name="signup_type" value="complete">
                <div class="col-sm-6">
                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['FIRSTNAME']; ?> <span>*</span></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="firstname" value="<?php echo $firstname; ?>" disabled />
                            <div class="field-notice" rel="firstname"></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['LASTNAME']; ?> <span>*</span></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="lastname" value="<?php echo $lastname; ?>" disabled />
                            <div class="field-notice" rel="lastname"></div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['EMAIL']; ?> <?php echo ($email != "" ? '<span>*</span>' : ''); ?></label>
                        <div class="col-lg-9">
                            <input type="email" class="form-control" name="email" value="<?php echo $email; ?>" <?php echo ($email != "" ? 'readonly' : ''); ?> />
                            <div class="field-notice" rel="email"></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['MOBILE']; ?> <?php echo ($mobile != "" ? '<span>*</span>' : ''); ?></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="mobile" value="<?php echo $mobile; ?>" <?php echo ($mobile != "" ? 'readonly' : ''); ?> />
                            <div class="field-notice" rel="mobile"></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['ADDRESS']; ?> </label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="address" value="<?php echo $address; ?>" disabled />
                            <div class="field-notice" rel="address"></div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" name="password" value="" />
                    <input type="hidden" class="form-control" name="password_confirm" value="" />
                    <!--     
                                 <div class="row form-group">
                                    <label class="col-lg-3 control-label"><?php echo ($user_id > 0) ? $texts['NEW_PASSWORD'] : $texts['PASSWORD']; ?></label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="password" value=""/>
                                        <div class="field-notice" rel="password"></div>
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-lg-3 control-label"><?php echo $texts['PASSWORD_CONFIRM']; ?></label>
                                    <div class="col-lg-9">
                                        <input type="password" class="form-control" name="password_confirm" value=""/>
                                        <div class="field-notice" rel="password_confirm"></div>
                                    </div>
                                </div>-->

                </div>
                <div class="col-sm-6">

                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['POSTCODE']; ?> </label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="postcode" value="<?php echo $postcode; ?>" disabled />
                            <div class="field-notice" rel="postcode"></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['CITY']; ?> </label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="city" value="<?php echo $city; ?>" disabled />
                            <div class="field-notice" rel="city"></div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['COUNTRY']; ?> </label>
                        <div class="col-lg-9">
                            <select class="form-control" name="country" disabled>
                                <option value="0">-</option>
                                <?php
                                $result_country = $db->query("SELECT * FROM pm_country");
                                if ($result_country !== false) {
                                    foreach ($result_country as $i => $row) {
                                        $id_country = $row['id'];
                                        $country_name = $row['name'];
                                        $selected = ($country == $country_name) ? " selected=\"selected\"" : "";

                                        echo "<option value=\"" . $country_name . "\"" . $selected . ">" . $country_name . "</option>";
                                    }
                                } ?>
                            </select>
                            <div class="field-notice" rel="country"></div>
                        </div>
                    </div>
                    <!--              <div class="row form-group">
                                    <label class="col-lg-3 control-label"><?php //echo $texts['PHONE']; 
                                                                            ?> *</label>
                                    <div class="col-lg-9">
                                        <input type="text" class="form-control" name="phone" value="<?php //echo $phone; 
                                                                                                    ?>" disabled/>
                                        <div class="field-notice" rel="phone"></div>
                                    </div>
                                </div>-->

                    <div class="row form-group">
                        <label class="col-lg-3 control-label"><?php echo $texts['COMPANY']; ?></label>
                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="company" value="<?php echo $company; ?>" disabled />
                            <div class="field-notice" rel="company"></div>
                        </div>
                    </div>
                    <!-- <div class="row form-group">
                                    <label class="col-lg-3 control-label"></label>
                                    <div class="col-lg-9">
                                        <input type="checkbox" name="privacy_agreement" value="1"> <?php //echo $texts['PRIVACY_POLICY_AGREEMENT']; 
                                                                                                    ?>
                                        <div class="field-notice" rel="privacy_agreement"></div>
                                    </div>
                                </div>-->
                    <div class="form-group row">
                        <div class="col-sm-12 text-right">
                            <i class="text-muted"> * <?php echo $texts['REQUIRED_FIELD']; ?> </i><br>
                            <a href="JavaScript:Void(0);" class="btn btn-primary cancel_btn" style="display:none;"><i class="fa fa-times"></i>Cancel</a>
                            <a href="JavaScript:Void(0);" class="btn btn-primary sendAjaxForm" data-action="<?php echo getFromTemplate("common/register/account-edit.php"); ?>" <?php if ($user_id == 0) echo " data-clear=\"true\""; ?> style="display:none;" data-refresh="true"><i class="fa fa-edit"></i> <?php echo ($user_id > 0) ? $texts['SAVE'] : $texts['SIGN_UP']; ?></a>
                            <a href="<?php echo DOCBASE; ?>" class="btn btn-primary back_btn"><i class="fa fa-times"></i>Cancel</a>
                            <a href="JavaScript:Void(0);" class="btn btn-primary edit_btn"><i class="fa fa-edit"></i> <?php echo ($user_id > 0) ? $texts['UPDATE'] : $texts['SIGN_UP']; ?></a>
                        </div>
                    </div>
                </div>
            </form>
    </fieldset>
    </div>
<?php
            } ?>
</div>
</div>
</section>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function() {
        $('#data_table').DataTable({
            "order": [
                [0, "desc"]
            ]
        });
    });
    var post_review = function() {
        var form = $('form#post_review');
        //$("#spin2").css('display','block');
        //alert('ssss');
        $.ajax({
            url: '<?php echo getFromTemplate("common/review_ajax.php"); ?>',
            type: 'POST',
            data: form.serialize(),
            success: function(html) {
                if (html == 'success') {
                    swal("Thank you", html, "success");
                    setTimeout(function() {
                        //$("#spin2").css('display','none');
                        location.reload();
                    }, 1000);
                } else {
                    swal("Error occur!!", html, "error");
                }
            }
        });
    }
    $('.edit_btn').on('click', function() {
        $('input[type=text]').removeAttr('disabled');
        $('input[type=email]').removeAttr('disabled');
        $('select').removeAttr('disabled');
        $('.sendAjaxForm').show();
        $('.cancel_btn').show();
        $('.back_btn').hide();
        $(this).hide();
        //$('input[type=email]').attr('readonly');
    });
    $('.cancel_btn').on('click', function() {
        $('input[type=text]').prop('disabled', true);
        $('input[type=email]').prop('disabled', true);
        $('select').prop('disabled', true);
        $('.sendAjaxForm').hide();
        $('.edit_btn').show();
        $('.back_btn').show();
        $(this).hide();
        //$('input[type=email]').attr('readonly');
    });
</script>