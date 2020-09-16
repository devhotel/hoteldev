<?php

/**
 * Script called on CMS API
 */

require_once('../../common/lib.php');
require_once('../../common/define.php');

$pageSlug = $_REQUEST['page_slug'];

$result_cms = $db->query('SELECT title, text FROM pm_page WHERE alias = "' . $pageSlug . '" AND lang = 2 AND checked = 1');

if ($result_cms !== false && $db->last_row_count() > 0) {
	$rowCms = $result_cms->fetch();
?>

	<?php if ($pageSlug == "contact") { ?>
		<html>

		<head>
			<meta name="gmaps_api_key" content="AIzaSyDK4rMTf9bUlqpg1g8SF2zUnV4HQmatsVo">

			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" media="all">
			<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" media="all">
			<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" media="all">
			<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
			<script>
				$(document).ready(function(e) {

					$("#contact_submit").click(function(e) {
						//alert();
						var name = $("#name");
						var email = $("#email");
						var address = $("#address");
						var phone = $("#phone");
						var subject = $("#subject");
						var msg = $("#msg");
						//var privacy_agreement    = $("#privacy_agreement");

						var nameVal = name.val();
						var emailVal = email.val();
						var addressVal = address.val();
						var phoneVal = phone.val();
						var subjectVal = subject.val();
						var msgVal = msg.val();
						//var privacyAgreeVal    = privacy_agreement.val();

						var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^< ()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
						var phoneRegex = /^[0-9]*$/;
						var iChars = "!@#$%^&*()+=-[]\\\';,./{}|\":<>?";

						for (var i = 0; i < nameVal.length; i++) {
							if (iChars.indexOf(nameVal.charAt(i)) != -1) {
								$(".error_name").html('Name has special characters. \nThese are not allowed.\n Please remove them and try again!');
								$(".error_email").html('');
								$(".error_address").html('');
								$(".error_phone").html('');
								$(".error_subject").html('');
								$(".error_msg").html('');
								$(".error_privacy").html('');
								name.focus();
								return false;
							} else {
								$(".error_name").html('');
								$(".error_email").html('');
								$(".error_address").html('');
								$(".error_phone").html('');
								$(".error_subject").html('');
								$(".error_msg").html('');
								$(".error_privacy").html('');
							}
						}

						if (nameVal == '' || nameVal.search(/\S/) == -1 || nameVal.charAt(0) == " " || !isNaN(nameVal)) {
							$(".error_name").html('Name Should Not Empty or Space or Number Not Accepted!');
							$(".error_email").html('');
							$(".error_address").html('');
							$(".error_phone").html('');
							$(".error_subject").html('');
							$(".error_msg").html('');
							$(".error_privacy").html('');
							name.focus();
							return false;
						} else if (emailVal == '' || !emailRegex.test(emailVal)) {
							$(".error_name").html('');
							$(".error_email").html('Blank/Invalid Email ID!');
							$(".error_address").html('');
							$(".error_phone").html('');
							$(".error_subject").html('');
							$(".error_msg").html('');
							$(".error_privacy").html('');
							email.focus();
							return false;
						} else if (addressVal == '') {
							$(".error_name").html('');
							$(".error_email").html('');
							$(".error_address").html('Address Should Not Blank!');
							$(".error_phone").html('');
							$(".error_subject").html('');
							$(".error_msg").html('');
							$(".error_privacy").html('');
							address.focus();
							return false;
						} else if (phoneVal == '' || !phoneRegex.test(phoneVal)) {
							$(".error_name").html('');
							$(".error_email").html('');
							$(".error_address").html('');
							$(".error_phone").html('Phone Should Not Blank Or Invalid Phone Number!');
							$(".error_subject").html('');
							$(".error_msg").html('');
							$(".error_privacy").html('');
							phone.focus();
							return false;
						} else if (subjectVal == '') {
							$(".error_name").html('');
							$(".error_email").html('');
							$(".error_address").html('');
							$(".error_phone").html('');
							$(".error_subject").html('Subject Should Not Blank!');
							$(".error_msg").html('');
							$(".error_privacy").html('');
							subject.focus();
							return false;
						} else if (msgVal == '') {
							$(".error_name").html('');
							$(".error_email").html('');
							$(".error_address").html('');
							$(".error_phone").html('');
							$(".error_subject").html('');
							$(".error_msg").html('Message Should Not Blank!');
							$(".error_privacy").html('');
							msg.focus();
							return false;
						} else if ($("#privacy_agreement").prop('checked') == false) {
							$(".error_name").html('');
							$(".error_email").html('');
							$(".error_address").html('');
							$(".error_phone").html('');
							$(".error_subject").html('');
							$(".error_msg").html('');
							$(".error_privacy").html('Agree should be checked!');
							//msg.focus();
							return false;
						} else {
							$(".error_name").html('');
							$(".error_email").html('');
							$(".error_address").html('');
							$(".error_phone").html('');
							$(".error_subject").html('');
							$(".error_msg").html('');
							$(".error_privacy").html('');

							return true;
						}

						//e.preventDefault();
					});

					var gscript = document.createElement('script');
					gscript.type = 'text/javascript';
					gscript.src = '//maps.google.com/maps/api/js?callback=initialize&libraries=geometry,places';
					if ($('meta[name="gmaps_api_key"]').length) gscript.src += '&key=' + $('meta[name="gmaps_api_key"]').attr('content');
					//gscript.src += '&key=AIzaSyDK4rMTf9bUlqpg1g8SF2zUnV4HQmatsVo'
					document.body.appendChild(gscript);

				});

				function initialize() {

					var gmaps_id = 'mapWrapper';
					if ($('#' + gmaps_id).length) {
						var overlayTitle = 'Agencies';
						//var locations = [
						//						['Big Ben', 'London SW1A 0AA','51.500729','-0.124625']
						//					];

						var image = $('#' + gmaps_id).attr('data-marker');
						var map = new google.maps.Map(document.getElementById(gmaps_id), {
							mapTypeId: google.maps.MapTypeId.ROADMAP,
							scrollwheel: false,
							zoomControl: true,
							zoomControlOptions: {
								style: google.maps.ZoomControlStyle.LARGE,
								position: google.maps.ControlPosition.LEFT_CENTER
							},
							streetViewControl: true,
							scaleControl: false,
							zoom: 12
						});
						var myLatlng;
						var marker, i;
						var bounds = new google.maps.LatLngBounds();
						var infowindow = new google.maps.InfoWindow({
							content: 'loading...'
						});
						for (i = 0; i < locations.length; i++) {
							if (locations[i][2] !== undefined && locations[i][3] !== undefined) {
								var content = '<div class="infoWindow">' + locations[i][0] + '<br>' + locations[i][1] + '</div>';
								(function(content) {
									myLatlng = new google.maps.LatLng(locations[i][2], locations[i][3]);
									marker = new google.maps.Marker({
										position: myLatlng,
										icon: image,
										title: overlayTitle,
										map: map
									});
									google.maps.event.addListener(marker, 'click', (function() {
										return function() {
											infowindow.setContent(content);
											infowindow.open(map, this);
										};
									})(this, i));
									if (locations.length > 1) {
										bounds.extend(myLatlng);
										map.fitBounds(bounds);
									} else {
										map.setCenter(myLatlng);
									}
								})(content);
							} else {
								var geocoder = new google.maps.Geocoder();
								var info = locations[i][0];
								var addr = locations[i][1];
								var latLng = locations[i][1];
								(function(info, addr) {
									geocoder.geocode({
										'address': latLng
									}, function(results) {
										myLatlng = results[0].geometry.location;
										marker = new google.maps.Marker({
											position: myLatlng,
											icon: image,
											title: overlayTitle,
											map: map
										});
										var $content = '<div class="infoWindow">' + info + '<br>' + addr + '</div>';
										google.maps.event.addListener(marker, 'click', (function() {
											return function() {
												infowindow.setContent($content);
												infowindow.open(map, this);
											};
										})(this, i));
										if (locations.length > 1) {
											bounds.extend(myLatlng);
											map.fitBounds(bounds);
										} else {
											map.setCenter(myLatlng);
										}
									});
								})(info, addr);
							}
						}
					}

				}

				var locations = [
					<?php
					$result_location = $db->query("SELECT * FROM pm_location WHERE checked = 1 AND pages REGEXP '(^|,)2(,|$)'");
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


			<style>
				form.cont_form .error {
					color: red;
					font-size: 11px;
				}

				#mapWrapper {
					height: 238px;
					margin-top: 30px;
					margin-bottom: 30px;
				}
			</style>
		</head>

		<body>

			<header>
				<?php /*?><h1 class="cms_title"><?php echo $rowCms['title']; ?></h1><?php */ ?>
			</header>


			<?php
			if (isset($_POST['send'])) {

				$name = html_entity_decode($_POST['name'], ENT_QUOTES, 'UTF-8');
				$address = html_entity_decode($_POST['address'], ENT_QUOTES, 'UTF-8');
				$phone = html_entity_decode($_POST['phone'], ENT_QUOTES, 'UTF-8');
				$email = $_POST['email'];
				$msg = html_entity_decode($_POST['msg'], ENT_QUOTES, 'UTF-8');
				$subject = html_entity_decode($_POST['subject'], ENT_QUOTES, 'UTF-8');


				$data = array();
				$data['id'] = '';
				$data['name'] = $name;
				$data['address'] = $address;
				$data['phone'] = $phone;
				$data['email'] = $email;
				$data['subject'] = $subject;
				$data['msg'] = $msg;
				$data['add_date'] = time();
				$data['edit_date'] = null;

				$result_message = db_prepareInsert($db, 'pm_message', $data);
				$result_message->execute();

				$mail = getMail($db, 'CONTACT', array(
					'{name}' => $name,
					'{address}' => $address,
					'{phone}' => $phone,
					'{email}' => $email,
					'{msg}' => nl2br($msg)
				));
$msg_success = '';
$msg_error = '';
				if ($mail !== false && sendMail(CONTACT_US_EMAIL, OWNER, $subject, $mail['content'], $email, $name))
					$msg_success .= $texts['MAIL_DELIVERY_SUCCESS'];
				else
					$msg_error .= $texts['MAIL_DELIVERY_FAILURE'];
				$name = '';
				$address = '';
				$phone = '';
				$email = '';
				$subject = '';
				$msg = '';
			}
			?>


			<section class="cms_content">
				<div id="content" class="clearfix">
					<div class="container">
						<div class="row">
							<div class="col-sm-12">
								<div id="mapWrapper" data-marker="<?php echo getFromTemplate('../../templates/main/images/marker.png'); ?>"></div>
							</div>
						</div>
					</div>

					<div class="clearfix"></div>
					<div class="container pt30 pb15">


						<div class="row">
							<form method="post" action="" class="cont_form">
								<div class="col-sm-4">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon"><i class="fas fa-fw fa-user"></i></div>
											<input type="text" class="form-control" id="name" name="name" value="" placeholder="<?php echo $texts['LASTNAME'] . " " . $texts['FIRSTNAME']; ?> *">
										</div>
										<div class="error error_name"></div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon"><i class="fas fa-fw fa-envelope"></i></div>
											<input type="text" class="form-control" id="email" name="email" value="" placeholder="<?php echo $texts['EMAIL']; ?> *">
										</div>
										<div class="error error_email"></div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon"><i class="fas fa-fw fa-home"></i></div>
											<textarea class="form-control" id="address" name="address" placeholder="<?php echo $texts['ADDRESS'] . ", " . $texts['POSTCODE'] . ", " . $texts['CITY']; ?>"></textarea>
										</div>
										<div class="error error_address"></div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon"><i class="fas fa-fw fa-phone"></i></div>
											<input type="text" class="form-control" id="phone" name="phone" value="" placeholder="<?php echo $texts['PHONE']; ?> *">
										</div>
										<div class="error error_phone"></div>
									</div>
								</div>
								<div class="col-sm-5">
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon"><i class="fas fa-fw fa-question"></i></div>
											<input type="text" class="form-control" id="subject" name="subject" value="" placeholder="<?php echo $texts['SUBJECT']; ?> *">
										</div>
										<div class="error error_subject"></div>
									</div>
									<div class="form-group">
										<div class="input-group">
											<div class="input-group-addon"><i class="fas fa-fw fa-quote-left"></i></div>
											<textarea class="form-control" id="msg" name="msg" placeholder="<?php echo $texts['MESSAGE']; ?> *" rows="4"><?php echo htmlentities($texts['MESSAGE'], ENT_QUOTES, 'UTF-8'); ?></textarea>
										</div>
										<div class="error error_msg"></div>
									</div>
									<div class="form-group">
										<input type="checkbox" id="privacy_agreement" name="privacy_agreement" value="1"> <?php echo $texts['PRIVACY_POLICY_AGREEMENT']; ?>
										<div class="error error_privacy" rel="privacy_agreement"></div>
									</div>
									<div class="form-group row">
										<span class="col-sm-12"><button type="submit" id="contact_submit" class="btn btn-primary" name="send"><i class="fas fa-fw fa-paper-plane"></i> <?php echo $texts['SEND']; ?></button> <i> * <?php echo $texts['REQUIRED_FIELD']; ?></i></span>
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


			<footer>
			</footer>


		</body>


		</html>

	<?php } else { ?>

		<html>

		<head>

		</head>

		<body>

			<section class="cms_content">
				<?php echo $rowCms['text']; ?>
			</section>

		</body>

		<footer>
		</footer>

		</html>

	<?php } ?>

<?php } else { ?>

	<html>

	<head>
	</head>

	<body>

		<header>
			<?php /*?><h1 class="cms_title"></h1><?php */ ?>
		</header>

		<section class="cms_content">
			<?php echo "Page Not Found OR Have Not Permission to Access the Page."; ?>
		</section>

		<footer>
		</footer>


	</body>


	</html>

<?php } ?>