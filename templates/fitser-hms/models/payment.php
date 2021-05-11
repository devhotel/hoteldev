<?php
if (!isset($_SESSION['book']) || count($_SESSION['book']) == 0) {
    header('Location: ' . DOCBASE . $sys_pages['booking']['alias']);
    exit();
} else
    $_SESSION['book']['step'] = 'payment';

$msg_error = '';
$msg_success = '';
$field_notice = array();

$paypal_email = '';
if (ENABLE_MULTI_VENDORS == 1) {
    $result_hotel = $db->query('SELECT paypal_email FROM pm_hotel WHERE id = ' . $_SESSION['book']['hotel_id']);
    if ($result_hotel !== false && $db->last_row_count() > 0) {
        $row = $result_hotel->fetch();
        $paypal_email = $row['paypal_email'];
    }
}

$payment_arr = array_map('trim', explode(',', PAYMENT_TYPE));
if (ENABLE_MULTI_VENDORS == 1 && $paypal_email != '') {
    $payment_type = 'paypal';
    $handle = true;
} elseif (count($payment_arr) == 1) {
    $payment_type = PAYMENT_TYPE;
    $handle = true;
} elseif (isset($_POST['payment_type'])) {
    $payment_type = $_POST['payment_type'];
    $handle = true;
} else {
    $payment_type = PAYMENT_TYPE;
    $handle = false;
}

if (isset($_SESSION['book']['id'])) {
    $result_booking = $db->query('SELECT * FROM pm_booking WHERE id = ' . $_SESSION['book']['id'] . ' AND status != 1 AND trans != \'\'');
    if ($result_booking !== false && $db->last_row_count() > 0) {
        unset($_SESSION['book']);
        header('Location: ' . DOCBASE . $sys_pages['booking']['alias']);
        exit();
    }
}

$total = $_SESSION['book']['total'];
$gtotal = 0;
//$payed_amount = (ENABLE_DOWN_PAYMENT == 1 && $_SESSION['book']['down_payment'] > 0) ? $_SESSION['book']['down_payment'] : $total;
//$payed_amount = ($_SESSION['book']['payable_amount'] > 0) ? $_SESSION['book']['down_payment'] : $total; 

$users = '';
$result_owner = $db->query('SELECT users FROM pm_hotel WHERE id = ' . $_SESSION['book']['hotel_id']);
if ($result_owner !== false && $db->last_row_count() > 0) {
    $row = $result_owner->fetch();
    $users = $row['users'];
}
$hotel_owners = array();
$result_owner = $db->query('SELECT * FROM pm_user WHERE id IN (' . $users . ')');
if ($result_owner !== false && $db->last_row_count() > 0)
    $hotel_owners = $result_owner->fetchAll();

if ($handle) {
    $_SESSION['book']['room_type'] = "";
    $_SESSION['book']['added_date'] = time();
    $payed_amount = ($_SESSION['book']['payable_amount'] > 0) ? $_SESSION['book']['payable_amount'] : $total;
    if (!isset($_SESSION['book']['id']) || is_null($_SESSION['book']['id'])) {

        $data = array();
        $data['id'] = null;
        $data['id_hotel'] = $_SESSION['book']['hotel_id'];
        $data['id_destination'] = db_getFieldValue($db, 'pm_hotel', 'id_destination', $_SESSION['book']['hotel_id'], $lang = 0);
        $data['id_user'] = $_SESSION['book']['id_user'];
        $data['firstname'] = $_SESSION['book']['firstname'];
        $data['lastname'] = $_SESSION['book']['lastname'];
        $data['email'] = $_SESSION['book']['email'];
        $data['company'] = $_SESSION['book']['company'];
        $data['address'] = $_SESSION['book']['address'];
        $data['postcode'] = $_SESSION['book']['postcode'];
        $data['city'] = $_SESSION['book']['city'];
        $data['phone'] = $_SESSION['book']['phone'];
        $data['mobile'] = $_SESSION['book']['mobile'];
        $data['country'] = $_SESSION['book']['country'];
        $data['gstin'] = $_SESSION['book']['gstin'];
        $data['govid_type'] = $_SESSION['book']['govid_type'];
        $data['govid'] = $_SESSION['book']['govid'];
        $data['comments'] = $_SESSION['book']['comments'];
        $data['from_date'] = $_SESSION['book']['from_date'];
        $data['to_date'] = $_SESSION['book']['to_date'];
        $data['nights'] = $_SESSION['book']['nights'];
        $data['adults'] = $_SESSION['book']['adults'];
        $data['children'] = $_SESSION['book']['children'];
        $data['amount'] = number_format($_SESSION['book']['amount_rooms'], 2, '.', '');
        //$data['tourist_tax'] = number_format($_SESSION['book']['tourist_tax'], 2, '.', '');
        $data['total'] = number_format($total, 2, '.', '');
        if ($payment_type != 'arrival') $data['down_payment'] = number_format($_SESSION['book']['down_payment'], 2, '.', '');
        $data['add_date'] = time();
        $data['edit_date'] = null;
        $data['status'] = 1;
        $data['discount'] = number_format($_SESSION['book']['discount_amount'], 2, ".", "");
        $data['payment_option'] = $payment_type;
        $data['users'] = $users;
        $data['source'] = 'website';
        if (isset($_SESSION['book']['taxslab'])) {
            $tax_amount = $_SESSION['book']['slab_tax_amount'];
        } else {
            $tax_amount = $_SESSION['book']['tax_rooms_amount'] + $_SESSION['book']['tax_activities_amount'] + $_SESSION['book']['tax_services_amount'];
        }

        $data['tax_amount'] = number_format($tax_amount, 2, '.', '');
        $data['ex_tax'] = number_format($total - $tax_amount, 2, '.', '');

        $result_booking = db_prepareInsert($db, 'pm_booking', $data);

        $gtotal = (($_SESSION['book']['total'] - $tax_amount) + $_SESSION['book']['discount_amount']);

        if ($result_booking->execute() !== false) {

            $_SESSION['book']['id'] = $db->lastInsertId();
            $_SESSION['booking_id'] = $db->lastInsertId();
            if (isset($_SESSION['book']['id']) && $_SESSION['book']['id'] > 0) {
                if (isset($_SESSION['book']['sessid']))
                    $db->query('DELETE FROM pm_room_lock WHERE sessid = ' . $db->quote($_SESSION['book']['sessid']));
            }

            if (isset($_SESSION['book']['id']) && $_SESSION['book']['id'] > 0) {
                if (isset($_SESSION['book']['offer_id']) && $_SESSION['book']['offer_id'] > 0) {
                    $result_offer = $db->query('SELECT * FROM pm_offer WHERE id=' . $_SESSION['book']['offer_id'] . ' AND checked = 1 AND lang = ' . LANG_ID . ' LIMIT 1');
                    if ($result_offer !== false && $db->last_row_count() > 0) {
                        $offer = $result_offer->fetch(PDO::FETCH_ASSOC);
                        $data = array();
                        $data['id'] = null;
                        $data['id_booking'] = $_SESSION['book']['id'];
                        $data['id_offer'] = $_SESSION['book']['offer_id'];
                        $data['id_room'] = $offer['id_room'];
                        $data['id_hotel'] = $offer['id_hotel'];
                        $data['title'] = $offer['name'];
                        $data['facilities'] = $offer['facilities'];
                        $data['num'] = $offer['no_day_night'];
                        $data['adults'] = $offer['max_adults'];
                        $data['children'] = $offer['max_children'];
                        $data['amount'] = number_format($offer['offer_price'], 2, '.', '');
                        $result = db_prepareInsert($db, 'pm_booking_offer', $data);
                        $result->execute();
                    }
                }
            }

            if (isset($_SESSION['book']['id']) && $_SESSION['book']['id'] > 0) {
                if (isset($_SESSION['book']['rooms']) && count($_SESSION['book']['rooms']) > 0) {
                    foreach ($_SESSION['book']['rooms'] as $id_room => $rooms) {
                        foreach ($rooms as $index => $room) {
                            $data = array();
                            $data['id'] = null;
                            $data['id_booking'] = $_SESSION['book']['id'];
                            $data['id_room'] = $id_room;
                            $data['id_hotel'] = $_SESSION['book']['hotel_id'];
                            $data['title'] = $_SESSION['book']['hotel'] . ' - ' . $room['title'];
                            $data['adults'] = $room['adults'];
                            $data['children'] = $room['children'];
                            $data['amount'] = number_format($room['amount'], 2, '.', '');
                            $data['chk'] = 1;
                            if (isset($room['duty_free'])) $data['ex_tax'] = number_format($room['duty_free'], 2, '.', '');
                            if (isset($room['tax_rate'])) $data['tax_rate'] = $room['tax_rate'];

                            $result = db_prepareInsert($db, 'pm_booking_room', $data);
                            $result->execute();
                        }
                    }
                }
            }
            if (isset($_SESSION['book']['id']) && $_SESSION['book']['id'] > 0) {
                if (isset($_SESSION['book']['activities']) && count($_SESSION['book']['activities']) > 0) {
                    foreach ($_SESSION['book']['activities'] as $id_activity => $activity) {
                        $data = array();
                        $data['id'] = null;
                        $data['id_booking'] = $_SESSION['book']['id'];
                        $data['id_activity'] = $id_activity;
                        $data['title'] = $activity['title'];
                        $data['adults'] = $activity['adults'];
                        $data['children'] = $activity['children'];
                        $data['duration'] = $activity['duration'];
                        $data['amount'] = number_format($activity['amount'], 2, '.', '');
                        $data['date'] = $activity['session_date'];
                        if (isset($activity['duty_free'])) $data['ex_tax'] = number_format($activity['duty_free'], 2, '.', '');
                        if (isset($activity['tax_rate'])) $data['tax_rate'] = $activity['tax_rate'];

                        $result = db_prepareInsert($db, 'pm_booking_activity', $data);
                        $result->execute();
                    }
                }
            }
            if (isset($_SESSION['book']['id']) && $_SESSION['book']['id'] > 0) {
                if (isset($_SESSION['book']['extra_services']) && count($_SESSION['book']['extra_services']) > 0) {
                    foreach ($_SESSION['book']['extra_services'] as $id_service => $service) {
                        $data = array();
                        $data['id'] = null;
                        $data['id_booking'] = $_SESSION['book']['id'];
                        $data['id_service'] = $id_service;
                        $data['title'] = $service['title'];
                        $data['qty'] = $service['qty'];
                        $data['amount'] = number_format($service['amount'], 2, '.', '');
                        if (isset($service['duty_free'])) $data['ex_tax'] = number_format($service['duty_free'], 2, '.', '');
                        if (isset($service['tax_rate'])) $data['tax_rate'] = $service['tax_rate'];

                        $result = db_prepareInsert($db, 'pm_booking_service', $data);
                        $result->execute();
                    }
                }
            }
            if (isset($_SESSION['book']['id']) && $_SESSION['book']['id'] > 0) {
                if (isset($_SESSION['book']['taxslab'])) {
                    $data = array();
                    $data['id'] = null;
                    $data['id_booking'] = $_SESSION['book']['id'];
                    $data['id_tax'] = $_SESSION['book']['taxslab'];
                    $data['name'] = db_getFieldValue($db, 'pm_tax', 'name', $_SESSION['book']['stax_id'], $lang = 0);
                    $data['amount'] = number_format($_SESSION['book']['slab_tax_amount'], 2, '.', '');
                    $result = db_prepareInsert($db, 'pm_booking_tax', $data);
                    $result->execute();
                } else {
                    if (isset($_SESSION['book']['taxes']) && count($_SESSION['book']['taxes']) > 0) {
                        $tax_id = 0;
                        $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = ' . LANG_ID . ' ORDER BY rank');
                        $result_tax->bindParam(':tax_id', $tax_id);
                        foreach ($_SESSION['book']['taxes'] as $tax_id => $taxes) {
                            $tax_amount = 0;
                            foreach ($taxes as $amount) $tax_amount += $amount;
                            if ($tax_amount > 0) {
                                if ($result_tax->execute() !== false && $db->last_row_count() > 0) {
                                    $row = $result_tax->fetch();
                                    $data = array();
                                    $data['id'] = null;
                                    $data['id_booking'] = $_SESSION['book']['id'];
                                    $data['id_tax'] = $tax_id;
                                    $data['name'] = $row['name'];
                                    $data['amount'] = number_format($tax_amount, 2, '.', '');

                                    $result = db_prepareInsert($db, 'pm_booking_tax', $data);
                                    $result->execute();
                                }
                            }
                        }
                    }
                }
            }
            $_SESSION['tmp_book'] = $_SESSION['book'];
        } else {
        }
    }

    if (isset($_SESSION['book']['id']) && $_SESSION['book']['id'] > 0) {
        $data = array();
        $data['id'] = $_SESSION['book']['id'];
        $data['payment_option'] = $payment_type;
        if ($payment_type == 'arrival') {
            $data['status'] = 1;
            $data['payment_mode'] = $_POST['payment_mode'];
        }

        if ($payment_type == 'wallet') {
            $data['status'] = 4;
            $data['trans'] = randTransaction();
            $data['payment_date'] = time();
            $data['payment_mode'] = $_POST['payment_mode'];
        }

        if (isset($_POST['wallet']) && $_POST['wallet'] == 'wallet') {
            $amount = $_SESSION['book']['wallet'];
            $data_a = array();
            $data_a['id'] = null;
            $data_a['id_booking'] = $_SESSION['book']['id'];
            $data_a['date'] = time();
            $data_a['descr'] = 'Payment has been success by Wallet';
            $data_a['trans'] = randTransaction();
            $data_a['method'] = 'wallet';
            $data_a['amount'] = number_format($amount, 2, '.', '');
            $result_payment = db_prepareInsert($db, 'pm_booking_payment', $data_a);
            $result_payment->execute();
            $id_booking = $_SESSION['book']['id'];
            $id_user = $_SESSION['book']['id_user'];
            $type = 'debit';
            $purpose = 'booking';
            walletUpdate($id_booking, $id_user, $amount, $type, $purpose);
        }

        $result_booking = db_prepareUpdate($db, 'pm_booking', $data);
        $result_booking->execute();
    }


    if (isset($_SESSION['book']['id']) && $_SESSION['book']['id'] > 0) {
        if ($payment_type == 'wallet' || $payment_type == 'arrival') {

            $room_content = '';
            $room_content .= '<table cellpadding="0" cellspacing="0" style="width: 100%;border:10px solid #fafafa;border-top:3px;">';
            $room_content .= '<tbody>';

            $room_content .= '<tr>';
            $room_content .= '<td style="padding: 20px;background: #fff;">';
            $room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">No. of Guests</h2>';
            $room_content .= '</td>';
            $room_content .= '<td style="padding: 20px;background: #fff;text-align: center;">';
            $room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">No. of Rooms</h2>';
            $room_content .= '</td>';
            $room_content .= '<td style="padding: 20px;background: #fff;text-align: right;">';
            $room_content .= '<h2 style="font-weight: bold;color: #000;font-size: 18px;margin: 0 0 20px;">Room Type</h2>';
            $room_content .= '</td>';
            $room_content .= '</tr>';

            if (isset($_SESSION['book']['rooms']) && count($_SESSION['book']['rooms']) > 0) {
                foreach ($_SESSION['book']['rooms'] as $id_room => $rooms) {
                    $room_content .= '<tr>';
                    $room_ids = array();
                    $adult = 0;
                    $kid = 0;
                    $key = 0;
                    end($rooms);
                    $lastkey = key($rooms);
                    $row_content = '';
                    foreach ($rooms as $index => $room) {

                        $_SESSION['book']['room_type'] = '';
                        $adult = $adult + $room['adults'];
                        $kid = $kid + $room['children'];


                        if ($lastkey === $index) {
                            $room_content .= '<td style="padding: 20px;background: #fff;">';
                            $room_content .= '<p style="font-size: 16px;color: #000;margin: 0;">';
                            $room_content .=  $adult;
                            $room_content .= ' Adult,';

                            $room_content .= $kid;
                            $room_content .= ' Kid';
                            $room_content .= '</p>';
                            $room_content .= '</td>';
                        }

                        if (!in_array($id_room, $room_ids)) {
                            $room_ids[] = $id_room;

                            $row_content .= '<td style="padding: 20px;background: #fff;text-align: center;">';
                            $row_content .= '<p style="font-size: 16px;color: #000;margin: 0;">' . count($rooms) . '</p>';
                            $row_content .= '</td>';
                            $row_content .= '<td style="padding: 20px;background: #fff;text-align: right;">';
                            $row_content .= '<p style="font-size: 16px;color: #000;margin: 0;">' . $_SESSION['book']['hotel'] . ' - ' . $room['title'] . '</p>';
                            $row_content .= '</td>';
                        }
                        if ($lastkey === $index) {
                            $room_content .= $row_content;
                        }

                        /* $room_content .= '<p><b>'.$_SESSION['book']['hotel'].' - '.$room['title'].'</b><br>
                    '.($room['adults']+$room['children']).' '.$texts['PERSONS'].' - 
                    '.$texts['ADULTS'].': '.$room['adults'].' / 
                    '.$texts['CHILDREN'].': '.$room['children'].'<br>
                    '.$texts['PRICE'].' : '.formatPrice($room['amount']*CURRENCY_RATE).'</p>';
                    */
                    }
                    $room_content .= '</tr>';
                }
            }

            $room_content .= '</tbody>';
            $room_content .= '</table>';

            $service_content = '';
            if (isset($_SESSION['book']['extra_services']) && count($_SESSION['book']['extra_services']) > 0) {
                foreach ($_SESSION['book']['extra_services'] as $id_service => $service)
                    $service_content .= $service['title'] . ' x ' . $service['qty'] . ' : ' . formatPrice($service['amount'] * CURRENCY_RATE) . ' ' . $texts['INCL_VAT'] . '<br>';
            }

            $activity_content = '';
            if (isset($_SESSION['book']['activities']) && count($_SESSION['book']['activities']) > 0) {
                foreach ($_SESSION['book']['activities'] as $id_activity => $activity) {
                    $activity_content .= '<p><b>' . $activity['title'] . '</b> - ' . $activity['duration'] . ' - ' . gmstrftime(DATE_FORMAT . ' ' . TIME_FORMAT, $activity['session_date']) . '<br>
                ' . ($activity['adults'] + $activity['children']) . ' ' . $texts['PERSONS'] . ' - 
                ' . $texts['ADULTS'] . ': ' . $activity['adults'] . ' / 
                ' . $texts['CHILDREN'] . ': ' . $activity['children'] . '<br>
                ' . $texts['PRICE'] . ' : ' . formatPrice($activity['amount'] * CURRENCY_RATE) . '</p>';
                }
            }

            if (isset($_SESSION['book']['taxslab'])) {
                $tax_content = '';
                $tax_content .= db_getFieldValue($db, 'pm_tax', 'name', $_SESSION['book']['stax_id'], $lang = 0) . ': ' . formatPrice($_SESSION['book']['slab_tax_amount'] * CURRENCY_RATE) . '<br>';
            } else {
                $tax_id = 0;
                $tax_content = '';
                $result_tax = $db->prepare('SELECT * FROM pm_tax WHERE id = :tax_id AND checked = 1 AND value > 0 AND lang = ' . LANG_ID . ' ORDER BY rank');
                $result_tax->bindParam(':tax_id', $tax_id);
                foreach ($_SESSION['book']['taxes'] as $tax_id => $taxes) {
                    $tax_amount = 0;
                    foreach ($taxes as $amount) $tax_amount += $amount;
                    if ($tax_amount > 0) {
                        if ($result_tax->execute() !== false && $db->last_row_count() > 0) {
                            $row = $result_tax->fetch();
                            $tax_content .= $row['name'] . ': ' . formatPrice($tax_amount * CURRENCY_RATE) . '<br>';
                        }
                    }
                }
            }

            $payment_notice = '';
            if ($payment_type == 'wallet') $payment_notice .= str_replace('{amount}', '<b>' . formatPrice($payed_amount * CURRENCY_RATE) . ' ' . $texts['INCL_VAT'] . '</b>', $texts['PAYMENT_CHECK_NOTICE']);
            if ($payment_type == 'arrival') $payment_notice .= str_replace('{amount}', '<b>' . formatPrice($payed_amount) . ' ' . $texts['INCL_VAT'] . '</b>', $texts['PAYMENT_ARRIVAL_NOTICE']);
            //SMS Notification
            if (isset($_SESSION['book']['mobile'])) {
                $mobile =  $_SESSION['book']['mobile'];
                $message = 'Hi ' . $_SESSION['book']['firstname'] . ' Your booking # ' . $_SESSION['book']['id'] . ' is successfully Confirmed';
                httpPost($mobile, $message);
            }
            $payment_notice = '';
            $mail = getMail($db, 'BOOKING_CONFIRMATION', array(
                '{booking_id}' => $_SESSION['book']['id'],
                '{booking_date}' => gmstrftime(DATE_FORMAT, $_SESSION['book']['added_date']),
                '{firstname}' => $_SESSION['book']['firstname'],
                '{lastname}' => $_SESSION['book']['lastname'],
                '{company}' => $_SESSION['book']['company'],
                '{address}' => $_SESSION['book']['address'],
                '{postcode}' => $_SESSION['book']['postcode'],
                '{city}' => $_SESSION['book']['city'],
                '{country}' => $_SESSION['book']['country'],
                '{phone}' => $_SESSION['book']['phone'],
                '{mobile}' => $_SESSION['book']['mobile'],
                '{email}' => $_SESSION['book']['email'],
                '{Check_in}' => gmstrftime(DATE_FORMAT, $_SESSION['book']['from_date']),
                '{Check_out}' => gmstrftime(DATE_FORMAT, $_SESSION['book']['to_date']),
                '{num_nights}' => $_SESSION['book']['nights'],
                '{num_guests}' => ($_SESSION['book']['adults'] + $_SESSION['book']['children']),
                '{num_adults}' => $_SESSION['book']['adults'],
                '{num_children}' => $_SESSION['book']['children'],
                '{rooms}' => $room_content,
                '{room_type}' => $_SESSION['book']['room_type'],
                '{extra_services}' => $service_content,
                '{activities}' => $activity_content,
                '{comments}' => nl2br($_SESSION['book']['comments']),
                '{total_cost}' => formatPrice($gtotal * CURRENCY_RATE),
                '{tourist_tax}' => formatPrice($_SESSION['book']['tourist_tax'] * CURRENCY_RATE),
                '{discount}' => '- ' . formatPrice($_SESSION['book']['discount_amount'] * CURRENCY_RATE),
                '{taxes}' => $tax_content,
                '{down_payment}' => formatPrice($_SESSION['book']['down_payment'] * CURRENCY_RATE),
                '{total}' => formatPrice($total * CURRENCY_RATE),
                '{payment_notice}' => $payment_notice
            ));

            $from_email = 'bookings@hms.com';
            $from_name = 'HMS';
            $from_subject = 'New Booking Received';
            if ($mail !== false) {
                foreach ($hotel_owners as $owner) {
                    if ($owner['email'] != EMAIL)
                        sendMail($owner['email'], $owner['firstname'], $from_subject, $mail['content'], $_SESSION['book']['email'], $_SESSION['book']['firstname'] . ' ' . $_SESSION['book']['lastname'], $from_email, $from_name);
                }
                sendMail(EMAIL, OWNER, $from_subject, $mail['content'], $_SESSION['book']['email'], $_SESSION['book']['firstname'] . ' ' . $_SESSION['book']['lastname'], $from_email, $from_name);
                sendMail($_SESSION['book']['email'], $_SESSION['book']['firstname'] . ' ' . $_SESSION['book']['lastname'], $mail['subject'], $mail['content'], '', '', $from_email, $from_name);
            }

            // Add activity log
            $logPurpose = 'Booking Id ' . $_SESSION['book']['id'] . ' created on ' . date('d-m-Y') . ' - online mode';
            add_activity_log($_SESSION['book']['id_user'], $_SESSION['book']['id'], 'booking', 'create', $logPurpose);
            unset($_SESSION['book']);
        }
    }
}

/* ==============================================
 * CSS AND JAVASCRIPT USED IN THIS MODEL
 * ==============================================
 */
if ($payment_type == 'cards')
    $javascripts[] = 'https://www.2checkout.com/static/checkout/javascript/direct.min.js';

require(getFromTemplate('common/header.php', false)); ?>

<section id="page">

    <?php include(getFromTemplate('common/page_header.php', false)); ?>

    <div id="content" class="pt30 pb30">
        <div class="container">

            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>

            <div class="row mb30" id="booking-breadcrumb">
                <div class="col-sm-2 col-sm-offset-<?php echo (isset($_SESSION['tmp_book']['activities']) || isset($_SESSION['book']['activities'])) ? '1' : '2'; ?>">
                    <a href="<?php echo DOCBASE . $sys_pages['booking']['alias']; ?>">
                        <div class="breadcrumb-item done">
                            <i class="fas fa-fw fa-check"></i>
                            <span><?php echo $sys_pages['booking']['name']; ?></span>
                        </div>
                    </a>
                </div>
                <?php
                if (isset($_SESSION['tmp_book']['activities']) || isset($_SESSION['book']['activities'])) { ?>
                    <div class="col-sm-2">
                        <a href="<?php echo DOCBASE . $sys_pages['booking-activities']['alias']; ?>">
                            <div class="breadcrumb-item done">
                                <i class="fas fa-fw fa-check"></i>
                                <span><?php echo $sys_pages['booking-activities']['name']; ?></span>
                            </div>
                        </a>
                    </div>
                <?php
                } ?>
                <div class="col-sm-2">
                    <a href="<?php echo DOCBASE . $sys_pages['details']['alias']; ?>">
                        <div class="breadcrumb-item done">
                            <i class="fas fa-fw fa-check"></i>
                            <span><?php echo $sys_pages['details']['name']; ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-sm-2">
                    <a href="<?php echo DOCBASE . $sys_pages['summary']['alias']; ?>">
                        <div class="breadcrumb-item done">
                            <i class="fas fa-fw fa-check"></i>
                            <span><?php echo $sys_pages['summary']['name']; ?></span>
                        </div>
                    </a>
                </div>
                <div class="col-sm-2">
                    <div class="breadcrumb-item active">
                        <i class="fas fa-fw fa-check"></i>
                        <span><?php echo $sys_pages['payment']['name']; ?></span>
                    </div>
                </div>
            </div>

            <?php echo $page['text']; ?>

            <?php
            if ($payment_type == 'paypal') { ?>
                <div class="text-center">
                    <?php echo $texts['PAYMENT_PAYPAL_NOTICE']; ?><br>
                    <img src="<?php echo getFromTemplate('images/paypal-cards.png'); ?>" alt="PayPal" class="img-responsive mt10 mb30">
                    <form action="https://www.<?php if (PAYMENT_TEST_MODE == 1) echo 'sandbox.'; ?>paypal.com/cgi-bin/webscr" method="post">
                        <input type='hidden' value="<?php echo number_format(str_replace(',', '.', $payed_amount), 2, '.', ''); ?>" name="amount">
                        <input name="currency_code" type="hidden" value="<?php echo DEFAULT_CURRENCY_CODE; ?>">
                        <input name="shipping" type="hidden" value="0.00">
                        <input name="tax" type="hidden" value="0.00">
                        <input name="return" type="hidden" value="<?php echo getUrl(true) . DOCBASE . $sys_pages['booking']['alias'] . '?action=confirm'; ?>">
                        <input name="cancel_return" type="hidden" value="<?php echo getUrl(true) . DOCBASE . $sys_pages['booking']['alias'] . '?action=cancel'; ?>">
                        <input name="notify_url" type="hidden" value="<?php echo getUrl(true) . DOCBASE . 'includes/payments/paypal_notify.php'; ?>">
                        <input name="cmd" type="hidden" value="_xclick">
                        <input name="business" type="hidden" value="<?php echo (ENABLE_MULTI_VENDORS == 1 && $paypal_email != '') ? $paypal_email : PAYPAL_EMAIL; ?>">
                        <input name="item_name" type="hidden" value="<?php echo addslashes($_SESSION['tmp_book']['hotel'] . ' - ' . gmstrftime(DATE_FORMAT, $_SESSION['tmp_book']['from_date']) . ' > ' . gmstrftime(DATE_FORMAT, $_SESSION['tmp_book']['to_date']) . ' - ' . $_SESSION['tmp_book']['nights'] . ' ' . $texts['NIGHTS'] . ' - ' . ($_SESSION['tmp_book']['adults'] + $_SESSION['tmp_book']['children']) . ' ' . $texts['PERSONS']); ?>">
                        <input name="no_note" type="hidden" value="1">
                        <input name="lc" type="hidden" value="<?php echo strtoupper(LANG_TAG); ?>">
                        <input name="bn" type="hidden" value="PP-BuyNowBF">
                        <input name="custom" type="hidden" value="<?php echo $_SESSION['tmp_book']['id']; ?>">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg"><i class="fab fa-fw fa-paypal"></i> <?php echo $texts['PAY']; ?></button>
                    </form>
                </div>
            <?php
            } elseif ($payment_type == '2checkout') { ?>
                <div class="text-center">
                    <?php echo $texts['PAYMENT_CARDS_NOTICE']; ?><br>
                    <img src="<?php echo getFromTemplate('images/2checkout-cards.png'); ?>" alt="2Checkout.com" class="img-responsive mt10 mb30">
                    <form action="https://<?php if (PAYMENT_TEST_MODE == 1) echo 'sandbox';
                                            else echo 'www'; ?>.2checkout.com/checkout/purchase" method="post">
                        <input type="hidden" name="sid" value="<?php echo VENDOR_ID; ?>">
                        <input type="hidden" name="currency_code" value="<?php echo DEFAULT_CURRENCY_CODE; ?>">
                        <input type="hidden" name="lang" value="<?php echo LANG_TAG; ?>">
                        <input type="hidden" name="mode" value="2CO">
                        <input type="hidden" name="merchant_order_id" value="<?php echo $_SESSION['tmp_book']['id']; ?>">
                        <input type="hidden" name="li_0_type" value="product">
                        <input type="hidden" name="li_0_name" value="<?php echo addslashes($_SESSION['tmp_book']['hotel'] . ' - ' . gmstrftime(DATE_FORMAT, $_SESSION['tmp_book']['from_date']) . ' > ' . gmstrftime(DATE_FORMAT, $_SESSION['tmp_book']['to_date']) . ' - ' . $_SESSION['tmp_book']['nights'] . ' ' . $texts['NIGHTS'] . ' - ' . ($_SESSION['tmp_book']['adults'] + $_SESSION['tmp_book']['children']) . ' ' . $texts['PERSONS']); ?>">
                        <input type="hidden" name="li_0_price" value="<?php echo number_format(str_replace(',', '.', $payed_amount), 2, '.', ''); ?>">
                        <input type="hidden" name="card_holder_name" value="<?php echo $_SESSION['book']['lastname'] . ' ' . $_SESSION['book']['lastname']; ?>">
                        <input type="hidden" name="street_address" value="<?php echo $_SESSION['book']['address']; ?>">
                        <input type="hidden" name="street_address2" value="">
                        <input type="hidden" name="city" value="<?php echo $_SESSION['book']['city']; ?>">
                        <input type="hidden" name="state" value="">
                        <input type="hidden" name="zip" value="<?php echo $_SESSION['book']['postcode']; ?>">
                        <input type="hidden" name="country" value="<?php echo $_SESSION['book']['country']; ?>">
                        <input type="hidden" name="email" value="<?php echo $_SESSION['book']['email']; ?>">
                        <input type="hidden" name="phone" value="<?php echo $_SESSION['book']['phone']; ?>">
                        <input type="hidden" name="x_receipt_link_url" value="<?php echo getUrl(true) . DOCBASE . 'includes/payments/2checkout_notify.php'; ?>">
                        <button type="submit" name="submit" class="btn btn-primary btn-lg"><i class="fas fa-fw fa-credit-card"></i> <?php echo $texts['PAY']; ?></button>
                    </form>
                </div>
            <?php
            } elseif ($payment_type == 'braintree') { //echo $payed_amount 
            ?>
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="text-center">
                            <?php //echo $texts['PAYMENT_CARDS_NOTICE']; 
                            ?><br>
                            <!--   <img src="<?php //echo getFromTemplate('images/braintree-cards.png'); 
                                                ?>" alt="Braintree" class="img-responsive mt10 mb30"> -->
                            <!--               <form action="<? php // echo DOCBASE; 
                                                                ?>includes/payments/braintree/checkout.php" method="post">-->
                            <!--	<div id="dropin"></div>-->
                            <!--	<input type="hidden" name="amount" value="<?php //echo number_format(str_replace(',', '.', $payed_amount), 2, '.', ''); 
                                                                                ?>">-->
                            <!--	<input type="hidden" name="id_booking" value="<?php //echo $_SESSION['tmp_book']['id']; 
                                                                                    ?>">-->
                            <!--	<button type="submit" class="btn btn-primary btn-lg" id="braintree_btn"><i class="fas fa-fw fa-credit-card"></i> <?php //echo $texts['PAY']; 
                                                                                                                                                        ?></button>-->
                            <!--</form>-->
                        </div>
                    </div>
                </div>
                <?php
            } else {
                if (isset($_SESSION['book'])) {
                    $response = '';
                    $_SESSION['book']['wallet'] = 0;
                    $_SESSION['book']['payable_amount'] = 0;
                ?>

                    <div class="text-center lead pt20 pb20">
                        <div class="row">
                            <!--<div class="col-xs-2"></div>-->
                            <div class="col-xs-12">

                                <style>
                                    #netbanking ul {
                                        width: 100% !important;
                                        display: flex;
                                    }

                                    #netbanking ul li {
                                        flex-grow: 1;
                                        display: inline-block;
                                    }

                                    #netbanking ul li button {
                                        height: 100px;
                                        background-position: center !important;
                                        width: 102px;
                                        background-size: cover;
                                    }

                                    #walletupi ul {
                                        width: 100% !important;
                                        display: flex;
                                    }

                                    #walletupi ul li {
                                        flex-grow: 1;
                                        display: inline-block;
                                    }

                                    #walletupi ul li button {
                                        height: 100px;
                                        background-position: center !important;
                                        width: 102px;
                                        background-size: cover;
                                    }

                                    #onarrival ul {
                                        width: 100% !important;
                                    }

                                    #onarrival ul li {
                                        display: block;
                                        text-align: center;
                                        width: 100%;
                                    }

                                    #arrival-on-pay button {
                                        font-size: 17px;
                                        padding: 15px !important;
                                    }

                                    section.paymenttab_wrapper.payment-bg-paypg {
                                        padding: 0 0 60px 0;
                                    }
                                </style>

                                <section class="paymenttab_wrapper payment-bg-paypg">

                                    <?php
                                    $response .= '<div class="row amount">
                                                                        <div class="col-xs-12 ">
                                                                            <h3>' . $texts['TOTAL'] . ' <small>(' . $texts['INCL_TAX'] . ')</small> ' . formatPrice($_SESSION['book']['total'] * CURRENCY_RATE) . '</h3>
                                                                        </div>
                                                                       
                                                                    </div>';

                                    echo $response;


                                    ?>
                                    <hr>
                                    <div class="mb10 choose_payment">
                                        <?php echo $texts['CHOOSE_PAYMENT']; ?>
                                    </div>
                                    <div class="container">
                                        <div class="payment_tab_new">
                                            <ul class="nav nav-tabs" role="tablist">
                                                <!--<li role="presentation" class="active"><a href="#creditcard" aria-controls="home" role="tab" data-toggle="tab">Credit/Debit Card</a></li>
                        				<li role="presentation"><a href="#netbanking" aria-controls="profile" role="tab" data-toggle="tab">Net Banking</a></li>
                        				<li role="presentation"><a href="#walletupi" aria-controls="messages" role="tab" data-toggle="tab">Wallet/ UPI</a></li>-->
                                                <li role="presentation" class="active"><a href="#onarrival" aria-controls="settings" role="tab" data-toggle="tab">On Arrival</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane" id="creditcard">
                                                    <div class="payment-method-options">
                                                        <button id="braintree-paypal-button" class="paypal is-active" title="Pay with PayPal"><span class="paypal-button-logo"></span></button>
                                                    </div>
                                                    <div class="form-container">
                                                        <div class="add-payment-method-form-view">
                                                            <div class="text-center">
                                                                <?php echo $texts['PAYMENT_CARDS_NOTICE']; ?><br>
                                                                <img src="<?php echo getFromTemplate('images/braintree-cards.png'); ?>" alt="Braintree" class="img-responsive mt10 mb30">
                                                                <form action="<?php echo DOCBASE; ?>includes/payments/braintree/checkout.php" method="post">
                                                                    <div id="dropin"></div>
                                                                    <input type="hidden" name="amount" value="<?php echo number_format(str_replace(',', '.', $payed_amount), 2, '.', ''); ?>">
                                                                    <input type="hidden" name="id_booking" value="<?php echo $_SESSION['tmp_book']['id']; ?>">
                                                                    <button type="submit" class="btn btn-primary btn-lg" id="braintree_btn"><i class="fas fa-fw fa-credit-card"></i> <?php echo $texts['PAY']; ?></button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div role="tabpanel" class="tab-pane banks_list" id="netbanking">
                                                    <ul>
                                                        <li><button type="submit" name="payment_mode" value="state_bank" class="bankopt" id="state_bank">SBI </button></li>
                                                        <li><button type="submit" name="payment_mode" value="axis_bank" class="bankopt" id="axis_bank">AXIS</button></li>
                                                        <li><button type="submit" name="payment_mode" value="hdfc_bank" class="bankopt" id="hdfc_bank"> HDFC</button></li>
                                                        <li><button type="submit" name="payment_mode" value="icici_bank" class="bankopt" id="icici_bank">ICICI</button></li>
                                                    </ul>
                                                </div>
                                                <div role="tabpanel" class="tab-pane upimodal " id="walletupi">
                                                    <ul>
                                                        <li><button type="submit" name="payment_mode" value="google_pay" class="payotp" id="google_pay"></button></li>
                                                        <li><button type="submit" name="payment_mode" value="phone_pay" class="payotp" id="phone_pay"></button></li>
                                                        <li><button type="submit" name="payment_mode" value="paytm" class="payotp" id="paytm"></button></li>
                                                    </ul>
                                                </div>
                                                <div role="tabpanel" class="tab-pane active" id="onarrival">
                                                    <div class="onarrival">
                                                        <h4 class="modal-title"><b>Pay On Arrival</b>
                                                            <div class="pricehtml"></div>
                                                        </h4>
                                                    </div>
                                                    <div class="paymodal">
                                                        <form method="post" action="<?php echo DOCBASE . $sys_pages['payment']['alias']; ?>">
                                                            <input type="hidden" name="payment_type" value="arrival">
                                                            <ul id="arrival-on-pay">
                                                                <p>You might be required to confirm your booking over a phone call, few days before your checkin date</p>
                                                                <li class="proceed_to_book"><button type="submit" name="payment_mode" value="cash_pay" class="paymentopt" id="cash_pay">Proceed to Book</button></li>
                                                                <!--<li><button type="submit" name="payment_mode"  value="credit_card"  class="paymentopt" id="credit_card" > Credit Card </button></li>
                                    		        	<li><button type="submit" name="payment_mode"  value="debit_card"  class="paymentopt" id="debit_card" > Debit Card </button></li>
                                    		        	<li><button type="submit" name="payment_mode"  value="Net_banking"  class="paymentopt" id="net_banking" > Net Banking </button></li>-->
                                                            </ul>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>

                    <?php
                }
                $url = getUrl(true) . DOCBASE . 'confirm?action=confirm';
                if ($payment_type == 'wallet' || $payment_type == 'arrival') {
                    $_SESSION['msg'] = '<h2>Your booking confirmation id  <b>' . $_SESSION['booking_id'] . '</b></h2><p>' . str_replace('{amount}', '<b>' . formatPrice($payed_amount, DEFAULT_CURRENCY_SIGN) . ' ' . $texts['INCL_VAT'] . '</b>', $texts['PAYMENT_CHECK_NOTICE']) . '</p>';
                    echo '<script>window.location = "' . $url . '";</script>';
                }
                //if($payment_type == 'wallet') echo str_replace('{amount}', '<b>'.formatPrice($payed_amount, DEFAULT_CURRENCY_SIGN).' '.$texts['INCL_VAT'].'</b>', $texts['PAYMENT_CHECK_NOTICE']);

                //if($payment_type == 'arrival') echo str_replace('{amount}', '<b>'.formatPrice($payed_amount*CURRENCY_RATE).' '.$texts['INCL_VAT'].'</b>', $texts['PAYMENT_ARRIVAL_NOTICE']); 
                    ?>

                    </div>

                    <div class="clearfix"></div>
                    <!--<a class="btn btn-default btn-lg pull-left" href="<?php //echo DOCBASE.$sys_pages['summary']['alias']; 
                                                                            ?>"><i class="fas fa-fw fa-angle-left"></i> <?php echo $texts['MORE_BOOKING']; ?></a> -->

                <?php
            } ?>
        </div>
    </div>
</section>

<script src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script>
    $(function() {
        $.ajax({
            dataType: 'text',
            type: 'POST',
            data: {
                action: 'generateclienttoken'
            },
            url: '<?php echo DOCBASE; ?>includes/payments/braintree/checkout.php',
            success: function(req) {
                braintree.setup(
                    req,
                    'dropin', {
                        container: 'dropin',
                        onReady: function() {},
                        onError: function(error) {}
                    });
            },
            error: function() {}
        });
    });
</script>
<script>
    $('.paymenttab_wrapper ul li a').click(function() {
        //alert('dasd'); 
        var id = $(this).attr('href');
        $('.tab-content>.tab-pane').removeClass('active');
        $('.paymenttab_wrapper ul li').removeClass('active');
        $(id).addClass('active');
        $(this).parent().addClass('active');
    });
</script>

<?php
if ($payment_type == 'braintree') { ?>
    <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
    <script>
        $(function() {
            $.ajax({
                dataType: 'text',
                type: 'POST',
                data: {
                    action: 'generateclienttoken'
                },
                url: '<?php echo DOCBASE; ?>includes/payments/braintree/checkout.php',
                success: function(req) {
                    braintree.setup(
                        req,
                        'dropin', {
                            container: 'dropin',
                            onReady: function() {},
                            onError: function(error) {}
                        });
                },
                error: function() {}
            });
        });
    </script>
<?php
} ?>