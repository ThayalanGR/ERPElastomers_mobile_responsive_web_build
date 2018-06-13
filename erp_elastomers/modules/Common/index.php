<table border="0" cellspacing="0" cellpadding="0" class="home_title " id="home_title" style="width:100%;height:450px; ">
	<tr>
    	<td>
        	<center>
                <img src="/images/company_logo.png" class="img img-responsive" style="margin-bottom:10px; margin-top:100px;" />
                <div style="font-weight:bold;font-size:24px;"><?php  echo $_SESSION['app']['comp_name'];?></div>
                <hr width="45%" style="margin-bottom:15px;margin-top:15px;" />
                <address style="font-style:normal;font-size:14px;">
                    <?php echo @getCompanyDetails('address'); ?><br />
                    Ph: <?php echo @getCompanyDetails('phone'); ?>, Mobile: <?php echo @getCompanyDetails('mobile'); ?><br />
                    email: <a href="mailto:<?php echo @getCompanyDetails('email'); ?>" style="font-size:14px;" target="_blank"><?php echo @getCompanyDetails('email'); ?></a>, Web: <a href="http://<?php echo @getCompanyDetails('website'); ?>/" style="font-size:14px;" target="_blank"><?php echo @getCompanyDetails('website'); ?></a>
                </address>
            </center>
        </td>
    </tr>
</table>
