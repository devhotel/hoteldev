<?php
$msg_error = '';
$msg_success = '';
$field_notice = array();

if (isset($_POST['send'])) {

    if (CAPTCHA_PKEY != '' && CAPTCHA_SKEY != '') {
        require(SYSBASE . 'includes/recaptchalib.php');

        $secret = CAPTCHA_SKEY;
        $response = null;
        $reCaptcha = new ReCaptcha($secret);
        if (isset($_POST['g-recaptcha-response']))
            $response = $reCaptcha->verifyResponse($_SERVER['REMOTE_ADDR'], $_POST['g-recaptcha-response']);
        if ($response == null || !$response->success) $field_notice['captcha'] = $texts['INVALID_CAPTCHA_CODE'];
    }

    $name = html_entity_decode($_POST['name'], ENT_QUOTES, 'UTF-8');
    $address = html_entity_decode($_POST['address'], ENT_QUOTES, 'UTF-8');
    $phone = html_entity_decode($_POST['phone'], ENT_QUOTES, 'UTF-8');
    $email = $_POST['email'];
    $msg = html_entity_decode($_POST['msg'], ENT_QUOTES, 'UTF-8');
    $subject = html_entity_decode($_POST['subject'], ENT_QUOTES, 'UTF-8');
    $privacy_agreement = isset($_POST['privacy_agreement']) ? true : false;

    if (!$privacy_agreement) $field_notice['privacy_agreement'] = $texts['REQUIRED_FIELD'];
    if ($name == '') $field_notice['name'] = $texts['REQUIRED_FIELD'];
    if ($msg == '') $field_notice['msg'] = $texts['REQUIRED_FIELD'];
    if ($subject == '') $field_notice['subject'] = $texts['REQUIRED_FIELD'];

    if ($email == '' || !preg_match('/^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$/i', $email)) $field_notice['email'] = $texts['INVALID_EMAIL'];
    if ($phone == '' || !preg_match('/^(\+){0,1}(91){0,1}(-|\s){0,1}[0-9]{10}$/i', $phone)) $field_notice['phone'] = $texts['INVALID_PHONE'];
    if (count($field_notice) == 0) {

        $data = array();
        $data['id'] = '';
        $data['name'] = $name;
        $data['address'] = $address;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['subject'] = $subject;
        $data['msg'] = $msg;
        $data['add_date'] = time() + 19800;
        $mail = getMail($db, 'CONTACT', array(
            '{name}' => $name,
            '{address}' => $address,
            '{phone}' => $phone,
            '{email}' => $email,
            '{msg}' => nl2br($msg)
        ));
        //print_r($mail); die;
        //if($mail !== false && sendMail(EMAIL, OWNER, $subject, $mail['content'], $email, $name))
        if ($mail !== false && sendMail(CONTACT_US_EMAIL, OWNER, $subject, $mail['content'], $email, $name)) {
            $result_message = db_prepareInsert($db, 'pm_message', $data);
            $result_message->execute();
            $msg_success .= $texts['MAIL_DELIVERY_SUCCESS'];
        } else {
            $msg_error .= $texts['MAIL_DELIVERY_FAILURE'];
        }
        $name = '';
        $address = '';
        $phone = '';
        $email = '';
        $subject = '';
        $msg = '';
    } else
        $msg_error .= $texts['FORM_ERRORS'];
} else {
    $name = '';
    $address = '';
    $phone = '';
    $email = '';
    $subject = '';
    $msg = '';
    $privacy_agreement = false;
}
require(getFromTemplate('common/header.php', false)); ?>

<script>
    var locations = [
        <?php
        $result_location = $db->query("SELECT * FROM pm_location WHERE checked = 1 AND pages REGEXP '(^|,)" . $page_id . "(,|$)'");
        if ($result_location !== false) {
            $nb_locations = $db->last_row_count();
            foreach ($result_location as $i => $row) {
                $location_name = $row['name'];
                $location_address = $row['address'];
                $location_lat = $row['lat'];
                $location_lng = $row['lng'];

                echo "['" . addslashes($location_name) . "', '" . addslashes($location_address) . "', '" . $location_lat . "', '" . $location_lng . "']";
                if ($i + 1 < $nb_locations) echo ',';
            }
        } ?>
    ];
</script>

<section id="page">

    <?php include(getFromTemplate('common/page_header.php', false)); ?>

    <div id="content" class="clearfix">
        <div id="mapWrapper" data-marker="<?php echo getFromTemplate('images/marker.png'); ?>"></div>
        <div class="container pt30 pb15">

            <?php
            if ($page['text'] != '') { ?>
                <div class="clearfix mb20"><?php echo $page['text']; ?></div>
            <?php
            } ?>

            <div class="alert alert-success" style="display:none;"></div>
            <div class="alert alert-danger" style="display:none;"></div>

            <div class="row">
                <form method="post" action="<?php echo DOCBASE . $page['alias']; ?>">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-user"></i></div>
                                <input type="text" class="form-control" name="name" value="<?php echo htmlentities($name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $texts['LASTNAME'] . " " . $texts['FIRSTNAME']; ?> *">
                            </div>
                            <div class="field-notice" rel="name"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-envelope"></i></div>
                                <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $texts['EMAIL']; ?> *">
                            </div>
                            <div class="field-notice" rel="email"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-home"></i></div>
                                <textarea class="form-control" name="address" placeholder="<?php echo $texts['ADDRESS'] . ", " . $texts['POSTCODE'] . ", " . $texts['CITY']; ?> *"><?php echo htmlentities($address, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="field-notice" rel="address"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-phone"></i></div>
                                <input type="text" class="form-control" name="phone" value="<?php echo htmlentities($phone, ENT_QUOTES, 'UTF-8'); ?>" placeholder="<?php echo $texts['PHONE']; ?> *">
                            </div>
                            <div class="field-notice" rel="phone"></div>
                        </div>
                        <i class="required rContact"> * <?php echo $texts['REQUIRED_FIELD']; ?></i>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-question"></i></div>
                                <select class="form-control" name="subject">
                                    <option value=""><?php echo $texts['SUBJECT']; ?> *</option>
                                    <option value="Suggestion">Suggestion</option>
                                    <option value="Feedback">Feedback</option>
                                    <option value="Complain">Complain</option>
                                    <option value="Rating">Rating</option>
                                </select>
                            </div>
                            <div class="field-notice" rel="subject"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fas fa-fw fa-quote-left"></i></div>
                                <textarea class="form-control" name="msg" placeholder="<?php echo $texts['MESSAGE']; ?> *" rows="4"><?php echo htmlentities($msg, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div class="field-notice" rel="msg"></div>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="privacy_agreement" value="1" <?php if ($privacy_agreement) echo ' checked="checked"'; ?>> <?php echo $texts['PRIVACY_POLICY_AGREEMENT']; ?>
                            <div class="field-notice" rel="privacy_agreement"></div>
                        </div>
                        <?php
                        if (CAPTCHA_PKEY != '' && CAPTCHA_SKEY != '') { ?>
                            <div class="form-group">
                                <div class="input-group mb5"></div>
                                <div class="g-recaptcha" data-sitekey="<?php echo CAPTCHA_PKEY; ?>"></div>
                            </div>
                        <?php
                        } ?>
                        <div class="form-group row">
                            <span class="col-sm-12"><button type="submit" class="btn btn-primary" name="send"><i class="fas fa-fw fa-paper-plane"></i> <?php echo $texts['SEND']; ?></button></span>
                        </div>
                    </div>
                </form>
                <div class="col-sm-3">
                    <div class="hotBox" itemscope itemtype="http://schema.org/Corporation">
                        <h2 itemprop="name"><?php echo OWNER; ?></h2>
                        <address>
                            <p>
                                <?php if (ADDRESS != '') : ?><span class="fas fa-fw fa-map-marker"></span> <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo nl2br(ADDRESS); ?></span><br><?php endif; ?>
                                <?php if (PHONE != "") : ?><span class="fas fa-fw fa-phone"></span> <a href="tel:<?php echo PHONE; ?>" itemprop="telephone" dir="ltr"><?php echo PHONE; ?></a><br><?php endif; ?>
                                <?php if (FAX != '') : ?><span class="fas fa-fw fa-fax"></span> <span itemprop="faxNumber" dir="ltr"><?php echo FAX; ?></span><br><?php endif; ?>
                                <?php if (EMAIL != '') : ?><span class="fas fa-fw fa-envelope"></span> <a itemprop="email" dir="ltr" href="mailto:<?php echo EMAIL; ?>"><?php echo EMAIL; ?></a><?php endif; ?>
                            </p>
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>