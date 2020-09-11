<?php debug_backtrace() || die("Direct access not permitted"); ?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>
    <?php
    //echo TITLE_ELEMENT;
    if (defined("TITLE_ELEMENT")) echo TITLE_ELEMENT;
    if (defined("SITE_TITLE")) echo " | " . SITE_TITLE; ?>
</title>

<?php
if (defined("TEMPLATE")) { ?>
    <link rel="icon" type="image/png" href="<?php echo DOCBASE; ?>templates/<?php echo TEMPLATE; ?>/images/favicon.png">
<?php
} ?>

<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.2.0/css/bootstrap.min.css">
<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,700">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo DOCBASE; ?>common/css/shortcodes.css">
<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.6/css/all.css">
<link rel="stylesheet" href="<?php echo DOCBASE . ADMIN_FOLDER; ?>/css/<?php echo  ADMIN_THEME; ?>">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.0/css/lightbox.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Montserrat:100,200,300,400,500,600,700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="<?php echo DOCBASE . ADMIN_FOLDER; ?>/js/jquery-ui.js"></script>
<script src="<?php echo DOCBASE; ?>common/js/modernizr-2.6.1.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//use.fontawesome.com/releases/v5.0.3/js/all.js"></script>
<script src="<?php echo DOCBASE; ?>common/js/custom.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>




<script>
    $(function() {
        <?php
        if (isset($_SESSION['msg_error']) && isset($_SESSION['msg_success']) && isset($_SESSION['msg_notice'])) {

            if (!is_array($_SESSION['msg_error'])) $_SESSION['msg_error'] = array();
            if (!is_array($_SESSION['msg_success'])) $_SESSION['msg_success'] = array();
            if (!is_array($_SESSION['msg_notice'])) $_SESSION['msg_notice'] = array();

            $_SESSION['msg_error'] = array_unique($_SESSION['msg_error']);
            $_SESSION['msg_success'] = array_unique($_SESSION['msg_success']);
            $_SESSION['msg_notice'] = array_unique($_SESSION['msg_notice']); ?>

            var msg_error = '<?php echo str_replace(addslashes("\n"), "\n", addslashes(implode("<br>", $_SESSION['msg_error']))); ?>';
            var msg_success = '<?php echo str_replace(addslashes("\n"), "\n", addslashes(implode("<br>", $_SESSION['msg_success']))); ?>';
            var msg_notice = '<?php echo str_replace(addslashes("\n"), "\n", addslashes(implode("<br>", $_SESSION['msg_notice']))); ?>';

            var button_close = '<button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>';
            if (msg_error != '') $('.alert-container .alert-danger').html(msg_error + button_close).show();
            if (msg_success != '') $('.alert-container .alert-success').html(msg_success + button_close).show();
            if (msg_notice != '') $('.alert-container .alert-warning').html(msg_notice + button_close).show();
        <?php
        } ?>

        $('[data-toggle="tooltip"]').tooltip();

        $('select[data-filter]').each(function() {
            var target = $(this);
            var currval = $(this).val();
            var curropt = $('option[value="' + currval + '"]', target);
            var input = $('select').filter('<?php if (defined("MODULE")) : ?>[name^="<?php echo MODULE; ?>_' + target.attr('data-filter') + '"],<?php endif; ?>[name="' + target.attr('data-filter') + '"]');
            input.on('change', function() {
                var val = $(this).val();
                $('option[value!=""]', target).hide().prop('selected', false);
                $('option[rel="' + val + '"]', target).show();
                if (curropt.attr('rel') == val) curropt.prop('selected', true);
            });
            input.trigger('change');
        });

        $(window).on('resize', function() {
            var h = $(this).height();
            $('.side-nav').css('min-height', h - 50);
            $('.side-nav').css('max-height', '100%');
        });
        $(window).trigger('resize');
    })
    $(document).ready(function() {
        $('.id_table').DataTable({
            "order": [0, 'desc']
        });
    });
</script>
<style type="text/css">
    svg:not(:root).svg-inline--fa {
        overflow: visible
    }

    .svg-inline--fa {
        display: inline-block;
        font-size: inherit;
        height: 1em;
        overflow: visible;
        vertical-align: -.125em
    }

    .svg-inline--fa.fa-lg {
        vertical-align: -.225em
    }

    .svg-inline--fa.fa-w-1 {
        width: .0625em
    }

    .svg-inline--fa.fa-w-2 {
        width: .125em
    }

    .svg-inline--fa.fa-w-3 {
        width: .1875em
    }

    .svg-inline--fa.fa-w-4 {
        width: .25em
    }

    .svg-inline--fa.fa-w-5 {
        width: .3125em
    }

    .svg-inline--fa.fa-w-6 {
        width: .375em
    }

    .svg-inline--fa.fa-w-7 {
        width: .4375em
    }

    .svg-inline--fa.fa-w-8 {
        width: .5em
    }

    .svg-inline--fa.fa-w-9 {
        width: .5625em
    }

    .svg-inline--fa.fa-w-10 {
        width: .625em
    }

    .svg-inline--fa.fa-w-11 {
        width: .6875em
    }

    .svg-inline--fa.fa-w-12 {
        width: .75em
    }

    .svg-inline--fa.fa-w-13 {
        width: .8125em
    }

    .svg-inline--fa.fa-w-14 {
        width: .875em
    }

    .svg-inline--fa.fa-w-15 {
        width: .9375em
    }

    .svg-inline--fa.fa-w-16 {
        width: 1em
    }

    .svg-inline--fa.fa-w-17 {
        width: 1.0625em
    }

    .svg-inline--fa.fa-w-18 {
        width: 1.125em
    }

    .svg-inline--fa.fa-w-19 {
        width: 1.1875em
    }

    .svg-inline--fa.fa-w-20 {
        width: 1.25em
    }

    .svg-inline--fa.fa-pull-left {
        margin-right: .3em;
        width: auto
    }

    .svg-inline--fa.fa-pull-right {
        margin-left: .3em;
        width: auto
    }

    .svg-inline--fa.fa-border {
        height: 1.5em
    }

    .svg-inline--fa.fa-li {
        width: 2em
    }

    .svg-inline--fa.fa-fw {
        width: 1.25em
    }

    .fa-layers svg.svg-inline--fa {
        bottom: 0;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 0
    }

    .fa-layers {
        display: inline-block;
        height: 1em;
        position: relative;
        text-align: center;
        vertical-align: -12.5%;
        width: 1em
    }

    .fa-layers svg.svg-inline--fa {
        -webkit-transform-origin: center center;
        transform-origin: center center
    }

    .fa-layers-counter,
    .fa-layers-text {
        display: inline-block;
        position: absolute;
        text-align: center
    }

    .fa-layers-text {
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        -webkit-transform-origin: center center;
        transform-origin: center center
    }

    .fa-layers-counter {
        background-color: #ff253a;
        border-radius: 1em;
        color: #fff;
        height: 1.5em;
        line-height: 1;
        max-width: 5em;
        min-width: 1.5em;
        overflow: hidden;
        padding: .25em;
        right: 0;
        text-overflow: ellipsis;
        top: 0;
        -webkit-transform: scale(.25);
        transform: scale(.25);
        -webkit-transform-origin: top right;
        transform-origin: top right
    }

    .fa-layers-bottom-right {
        bottom: 0;
        right: 0;
        top: auto;
        -webkit-transform: scale(.25);
        transform: scale(.25);
        -webkit-transform-origin: bottom right;
        transform-origin: bottom right
    }

    .fa-layers-bottom-left {
        bottom: 0;
        left: 0;
        right: auto;
        top: auto;
        -webkit-transform: scale(.25);
        transform: scale(.25);
        -webkit-transform-origin: bottom left;
        transform-origin: bottom left
    }

    .fa-layers-top-right {
        right: 0;
        top: 0;
        -webkit-transform: scale(.25);
        transform: scale(.25);
        -webkit-transform-origin: top right;
        transform-origin: top right
    }

    .fa-layers-top-left {
        left: 0;
        right: auto;
        top: 0;
        -webkit-transform: scale(.25);
        transform: scale(.25);
        -webkit-transform-origin: top left;
        transform-origin: top left
    }

    .fa-lg {
        font-size: 1.33333em;
        line-height: .75em;
        vertical-align: -.0667em
    }

    .fa-xs {
        font-size: .75em
    }

    .fa-sm {
        font-size: .875em
    }

    .fa-1x {
        font-size: 1em
    }

    .fa-2x {
        font-size: 2em
    }

    .fa-3x {
        font-size: 3em
    }

    .fa-4x {
        font-size: 4em
    }

    .fa-5x {
        font-size: 5em
    }

    .fa-6x {
        font-size: 6em
    }

    .fa-7x {
        font-size: 7em
    }

    .fa-8x {
        font-size: 8em
    }

    .fa-9x {
        font-size: 9em
    }

    .fa-10x {
        font-size: 10em
    }

    .fa-fw {
        text-align: center;
        width: 1.25em
    }

    .fa-ul {
        list-style-type: none;
        margin-left: 2.5em;
        padding-left: 0
    }

    .fa-ul>li {
        position: relative
    }

    .fa-li {
        left: -2em;
        position: absolute;
        text-align: center;
        width: 2em;
        line-height: inherit
    }

    .fa-border {
        border: solid .08em #eee;
        border-radius: .1em;
        padding: .2em .25em .15em
    }

    .fa-pull-left {
        float: left
    }

    .fa-pull-right {
        float: right
    }

    .fa.fa-pull-left,
    .fab.fa-pull-left,
    .fal.fa-pull-left,
    .far.fa-pull-left,
    .fas.fa-pull-left {
        margin-right: .3em
    }

    .fa.fa-pull-right,
    .fab.fa-pull-right,
    .fal.fa-pull-right,
    .far.fa-pull-right,
    .fas.fa-pull-right {
        margin-left: .3em
    }

    .fa-spin {
        -webkit-animation: fa-spin 2s infinite linear;
        animation: fa-spin 2s infinite linear
    }

    .fa-pulse {
        -webkit-animation: fa-spin 1s infinite steps(8);
        animation: fa-spin 1s infinite steps(8)
    }

    @-webkit-keyframes fa-spin {
        0% {
            -webkit-transform: rotate(0);
            transform: rotate(0)
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg)
        }
    }

    @keyframes fa-spin {
        0% {
            -webkit-transform: rotate(0);
            transform: rotate(0)
        }

        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg)
        }
    }

    .fa-rotate-90 {
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg)
    }

    .fa-rotate-180 {
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg)
    }

    .fa-rotate-270 {
        -webkit-transform: rotate(270deg);
        transform: rotate(270deg)
    }

    .fa-flip-horizontal {
        -webkit-transform: scale(-1, 1);
        transform: scale(-1, 1)
    }

    .fa-flip-vertical {
        -webkit-transform: scale(1, -1);
        transform: scale(1, -1)
    }

    .fa-flip-horizontal.fa-flip-vertical {
        -webkit-transform: scale(-1, -1);
        transform: scale(-1, -1)
    }

    :root .fa-flip-horizontal,
    :root .fa-flip-vertical,
    :root .fa-rotate-180,
    :root .fa-rotate-270,
    :root .fa-rotate-90 {
        -webkit-filter: none;
        filter: none
    }

    .fa-stack {
        display: inline-block;
        height: 2em;
        position: relative;
        width: 2em
    }

    .fa-stack-1x,
    .fa-stack-2x {
        bottom: 0;
        left: 0;
        margin: auto;
        position: absolute;
        right: 0;
        top: 0
    }

    .svg-inline--fa.fa-stack-1x {
        height: 1em;
        width: 1em
    }

    .svg-inline--fa.fa-stack-2x {
        height: 2em;
        width: 2em
    }

    .fa-inverse {
        color: #fff
    }

    .sr-only {
        border: 0;
        clip: rect(0, 0, 0, 0);
        height: 1px;
        margin: -1px;
        overflow: hidden;
        padding: 0;
        position: absolute;
        width: 1px
    }

    .sr-only-focusable:active,
    .sr-only-focusable:focus {
        clip: auto;
        height: auto;
        margin: 0;
        overflow: visible;
        position: static;
        width: auto
    }

    .ullist {
        width: 100%;
        margin: 5px 0 15px;
        padding: 0 11px;
    }

    .ullist ul {
        width: 100%;
        margin: 0;
        padding: 6px 0 0;
        border-bottom: 3px solid #fff;
    }

    .ullist ul li {
        list-style: none;
        display: inline-block;
        margin: 0;
        padding: 10px 25px;
    }

    .ullist ul li a {
        display: block;
        font-size: 15px;
        font-weight: normal;
        color: #000;
    }

    .ullist ul li a:hover {
        color: #3C8DBC;
    }

    .ullist ul li.active a {
        color: #3C8DBC;
    }

    .ullist ul li.active {
        background: #fff;
    }

    .ullist ul li:hover {
        background: #fff;
    }

    .pagewrappernew {
        padding: 55px 10px 20px 10px !important;
    }

    /*.pagewrappernew .form_wrapper {margin-top: 72px;}*/
    .pagewrappernew .page-header {
        top: inherit;
        box-shadow: none;
        background: none;
        position: inherit;
        left: inherit;
        width: 100%;
    }

    .pagewrappernew .page-header .container-fluid {
        padding: 0 17px;
    }
</style>