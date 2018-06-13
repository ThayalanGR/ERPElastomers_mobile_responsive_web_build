<?php
	if(ISO_SHOW_DESIGN == true) { ?>
<!DOCTYPE html>

<html lang="en-US">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <link rel="stylesheet" type="text/css" href="<?php //echo ISO_REWRITE_URL; ?>style/jquery-ui-1.8.9.custom.css" />
        <link rel="stylesheet" type="text/css" href="<?php //echo ISO_REWRITE_URL; ?>style/jquery.ui.all.css"/>
        <link rel="stylesheet" type="text/css" href="<?php //echo ISO_REWRITE_URL; ?>style/jquery.ui.tooltip.css"/>
        <link rel="stylesheet" type="text/css" href="<?php //echo ISO_REWRITE_URL; ?>style/jquery.ui.filters.css"/> -->
        <!-- <link rel="stylesheet" href="<?php //echo ISO_REWRITE_URL; ?>style/style.css" media="all" /> -->
        <!-- <link rel="shortcut icon" href="<?php //echo ISO_REWRITE_URL; ?>favicon.ico" /> -->
        <link rel="stylesheet" href="./fontawesome/web-fonts-with-css/css/fontawesome-all.css">
        <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="./mobile.css">

        <script src="<?php echo ISO_REWRITE_URL; ?>script/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/jquery.ui.min.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/jquery.xslt.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/jquery.combobox.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/jquery.tooltip.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/jquery.filters.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/jquery.monthpicker.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/jquery.others.js" type="text/javascript"></script>

        <script src="<?php echo ISO_REWRITE_URL; ?>script/functions.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/general.js" type="text/javascript"></script>
        <script src="<?php echo ISO_REWRITE_URL; ?>script/incvar.js" type="text/javascript"></script>
		<script src="<?php echo ISO_REWRITE_URL; ?>script/TableFilter/tablefilter.js" type="text/javascript"></script>
       
        <?php if(ISO_SUB_PAGE == ''):?>    
        <script src="<?php echo ISO_BASE_URL; ?>_bin/.run" type="text/javascript"></script><?php endif; ?>
        <title><?php  echo $_SESSION['app']['comp_name'];?></title>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-sm-12" >
                    <div id="header">
                    <?php include_once 'header.php' ?>
                    </div>
                    <div id="content" class="" style="">  
                        <?php if(ISO_LOAD_MODULE != "login"){?><span id="right_col"><?php }?>
                            <?php include ISO_RELATIVE_FILE; ?>
                        <?php if(ISO_LOAD_MODULE != "login"){?></span><?php }?>
                    </div>
                    <div id="footer">
                        <div class="row justify-content-center mt-5">
                            <?php include_once 'footer.php'?>
                        </div>
                    </div>
                    <?php
                    }
                    else
                        include ISO_RELATIVE_FILE;
                    ?>
                </div>
            </div>
        </div>
        <?php if(ISO_LOAD_MODULE != "login"): ?> 
            <script src="./bootstrap/js/popper.min.js" type="text/javascript"></script>
            <script src="./bootstrap/js/jquery-3.3.1.min.js" type="text/javascript"></script>
            <script src="./bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <?php endif; ?>
    </body>
    </html>