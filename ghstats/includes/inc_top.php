<?php debug_backtrace() || die ("Direct access not permitted"); ?>
<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?php echo DOCBASE; ?>/"><?php echo SITE_TITLE; ?><span class="hidden-xs"></span></a> <span id="tiny" class="navbar-brand"><i class="fas fa-fw fa-th"></i></span>
        <div class="pull-right hidden-xs" id="info-header">
            <?php echo $texts['CONNECTED_AS']; ?> <i class="fas fa-fw fa-user"></i>
             <ul class="nav navbar-nav">
                 <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <?php echo "<b>".$_SESSION['ghs']['login']."</b> (".$_SESSION['ghs']['type'].")"; ?>&nbsp;
                    <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                          <li> <a href="<?php echo DOCBASE; ?>ghstats/login.php?action=logout"><i class="fas fa-fw fa-power-off"></i> <?php echo $texts['LOG_OUT']; ?></a></li>
                    </ul>
                 </li>              
            </ul>
            
           
        </div>
    </div>
    
</nav>
