<?php require(getFromTemplate("common/header.php", false)); ?>
<section id="page">
    <?php include(getFromTemplate("common/page_header.php", false)); ?>
    <section id="content" class="pt30 pb30">
        <div class="container">
            <div class="alert alert-success text-center lead" style="display:none;"></div>
		<div class="alert alert-danger text-center lead" style="display:none;"></div>
        <?php if(isset($_GET['action']) && $_GET['action']=='confirm' ){ 
         if(isset($_SESSION['msg'])){?>
             <div class="alert alert-success lead">
                 <?php echo $_SESSION['msg']; ?>
             </div>
        <?php }
        }?>
        </div>
    </section>
</section>
