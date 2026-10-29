<div style="width:850px; height: auto;margin: 0px auto; ">
    <center style="width: 600px;border: 1px solid #C7C7C7; height: auto; position: absolute; top: 50%; left: 50%; background: #fff;  transform: translate(-50%, -50%);  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); font-family: 'Poppins', sans-serif;">
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 10px;">
                    <div style="text-align: center;">
                        <img src="<?php echo site_url('webroot/img/home-logo.png') ?>" alt="logo.png" class="CToWUd"
                             data-bit="iit"
                             style="margin-bottom:10px; width:250px; height: 100px; object-fit: contain;">
                    </div>

                </td>
            </tr>

        </table>
        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 10px 6px;">


                    <p style="margin: 0; text-align: center; text-transform: uppercase; font-size: 18px;">Amendment request from :  <?php echo $company_name; ?>,</p>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <table style="width: 100%;">
                        <tr>
                            <th style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;">S.No.</th>
                            <th style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;">Name</th>
                            <th style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;">Type</th>
                            <th style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;">PNR</th>
                            <th style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;">Seat No.</th>
                        </tr>
                        <?php foreach ($paxs as $key=>$pax){?>
                            <tr>
                                <td style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;"><?php echo $key+1?></td>
                                <td style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;"><?php echo $pax['title'].' '.$pax['first_name'].' '.$pax['last_name']; ?></td>

                                <td style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;"><?php echo $type?></td>
                                <td style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;"><?php echo $pnr?></td>
                                <td style="font-size: 14px;padding: 5px 8px;border: 1px solid gray;"><?php echo $pax['seat_name']?></td>
                            </tr>
                        <?php }?>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 100%; padding: 10px;">
                    <div>
                        <p style="margin: 0; text-align: left; "><strong>Remark: </strong><?php echo $remark ?></p>
                    </div>
                </td>
            </tr>
        </table>

        <table style=" text-align: left; width:100%; padding: 10px; border-collapse: collapse;">
            <tr>
                <td style="width: 100%; padding: 10px;">
                    <p style="text-align: left; margin: 0;  padding-bottom: 0px;">
                        <span> <span>Thanks &amp; Regards,</span> <br> <span>Team <span
                                        class="il"><?php echo super_admin_website_setting['company_name'] ?></span></span> </span>
                    </p>
                    <h4 style="text-align: center; "> Note: This is an auto generated email, please do not reply </h4>
                </td>
            </tr>
        </table>
        <table align="left" border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%;">
            <tr>
                <td style="width:100%;">
                    <table style="width: 100%; background-color: #3e3e3e;">
                        <tbody>
                        <tr>
                            <td style=" padding: 10px 10px 10px 10px; color: #ff1010;  font-weight: normal; text-align: center; ">
                                <h3 style="color: #fff; margin-top: 0px; text-align: center; margin-bottom: 0;">
                                    <span> <span style="color: #fff; font-size: 12px; font-weight: 500;"> Need More help? We are Here, Ready to Talk. </span> </span>
                                </h3>
                                <h2 style="margin: 0px; text-align: center; padding: 0; font-weight: 500; line-height: 1;">
                                    <span style="font-size: 12px; color: #fff; margin-right: 30px;">
                                        <a href="tel:<?php echo super_admin_website_setting['support_no'] ?>"
                                           style="color: #fff;" target="_blank">
                                            <img src="<?php echo root_url . 'uploads/icons/' . 'call-icon.png' ?>"
                                                 style="vertical-align: middle; margin-right: 5px; width: 15px;"
                                                 alt="phone.png" class="CToWUd"
                                                 data-bit="iit"> <?php echo super_admin_website_setting['support_no'] ?>
                                        </a>
                                    </span>
                                    <span>
                                        <a href="mailto:<?php echo super_admin_website_setting['support_email'] ?>"
                                           style="color: #fff; font-size: 12px;" target="_blank"> <img
                                                    src="<?php echo root_url . 'uploads/icons/' . 'email-icon.png' ?>"
                                                    style="vertical-align: middle; margin-right: 5px; width: 15px;"
                                                    alt="mail.png" class="CToWUd" data-bit="iit"> <span
                                                    class="il"><?php echo super_admin_website_setting['support_email'] ?></span>.
                                        </a>
                                    </span>
                                </h2>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </center>

</div>