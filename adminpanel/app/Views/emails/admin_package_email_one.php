<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
        <meta content="utf-8" http-equiv="encoding">
        <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/bootstrap/css/bootstrap.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/css/bootstrap.css'); ?>">
        <script src="<?php echo site_url('webroot'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
    </head>
    <body style="background: #f7f7f7;padding: 4% 1%;">
<?php //echo $name; ?>

        <table  style="width:700px;border:1px solid rgba(130,130,141,0.44);box-shadow: 0px 0px 3px 0px #ccc;border-radius: 3px;background-color: white;padding: 15px 12px 26px 17px;" cellspacing="0" cellpadding="0" align="center">
            <tbody>
            <tr>
                <td width="198" style="border-bottom:dotted 1px #7ba4c7;padding:10px;text-align:left">
                    <img src="<?php echo root_url . 'uploads/logo/' . super_admin_website_setting['logo'] ?>" width="200px" alt="<?php echo super_admin_website_setting['company_name'] ?>"></td>
                <td width="452" style="border-bottom:dotted 1px #7ba4c7;font-family:Arial,Helvetica,sans-serif; font-size:15px;padding:10px; color:#000; text-align:right;">
                    <b style="color: #215a9c;">Customer Support : </b> <?php echo super_admin_website_setting['support_no'] ?>
                    <br/>
                    <a href="mailto:<?php echo super_admin_website_setting['support_email'] ?>" style="color:#000;text-decoration:none" target="_blank"><b style="color: #215a9c;">Email Support : </b>  <?php echo super_admin_website_setting['support_email'] ?></a> </td>
            </tr>
            <tr>
                <td colspan="2" style="padding:20px 10px 0;font-family:Arial,Helvetica,sans-serif;font-size: 18px;text-align: center;color: #c90000;">New Activity Query Received</td>
            </tr>
            <tr style="">
                <td colspan="2"> 
                    <table class="table table-striped table-hover" style="width:auto; margin-left:41px; margin-top:30px;  font-family:Arial,Helvetica,sans-serif;">
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;">Customer Name</td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><?php echo $name; ?></td>
                        </tr>
						<?php  if($email!="") { ?>
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;">Customer Email</td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><?php echo $email; ?></td>
                        </tr>
						<?php } ?>
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;">Customer Mobile</td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><?php echo $phone; ?></td>
                        </tr>
						 
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;">Nubmer Of Adult</td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><?php echo $adult; ?></td>
                        </tr>
				 
                        <?php  if($child !="") { ?>
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;">Nubmer Of Child</td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><?php echo $child; ?></td>
                        </tr>
						<?php } ?>
                        <?php  if($query_type !="") { ?>
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;">Query Type</td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><?php echo $query_type; ?></td>
                        </tr>
						<?php } ?>
                        <?php  if($activity_price !="") { ?>
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;">Activity Price</td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><?php echo $activity_price; ?></td>
                        </tr>
						<?php } ?>
						<?php  if($activity_name!="") { ?>
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;">Activity Name</td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><a href="<?php echo base_url(); ?>/activity/activity-details/<?php echo $activity_slug; ?>" style="text-decoration:none;"><?php echo $activity_name; ?></a></td>
                        </tr>
						<?php  } ?>
                        <?php  if($activity_name!="") { ?>
                        <tr>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;" >View Package </td>
                            <td style="font-size: 18px;padding: 11px 19px;border: 1px solid gray;"><a href="<?php echo base_url(); ?>/activity/activity-details/<?php echo $activity_slug; ?>" style="text-decoration:none;">Click Here </a></td>
                        </tr>
						<?php  } ?>
                    </table>
                </td>            
            </tr><br/><br/>
            <tr>
                <td colspan="2" style="padding:0 0 5px 10px;font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#838382"> Thanks,</td>
            </tr>
            <tr>
                <td colspan="2" style="padding:0 0 5px 10px;font-family:Arial,Helvetica,sans-serif;font-size: 14px;color:#838382;"> <?php echo super_admin_website_setting['company_name'] ?> <br/>Support Team</td>
            </tr>
        </tbody>
    </table>
</body>
</html>

