<?php echo view('Modules/Payment/Views\payment-loading.php'); ?>
<form method="POST"  name="redirect"  action="https://api.razorpay.com/v1/checkout/embedded">
  <input type="hidden" name="key_id" value="<?php echo $Paytm_details['key'];?>">
  <input type="hidden" name="order_id" value="<?php echo $Paytm_details['ID'];?>">
  <input type="hidden" name="name" value="<?php echo  super_admin_website_setting['company_name']?>">
  <input type="hidden" name="notes[customer_id]" value="<?php echo $Paytm_details['customer_id'];?>">
  <input type="hidden" name="notes[extraparmeter]" value="<?php echo $Paytm_details['extraparmeter'];?>">
  <input type="hidden" name="extraparmeter" value="<?php echo $Paytm_details['extraparmeter'];?>">
  <input type="hidden" name="prefill[name]" value="<?php echo $Paytm_details['name'];?>">
  <input type="hidden" name="prefill[contact]" value="<?php echo $Paytm_details['contact'];?>">
  <input type="hidden" name="prefill[email]" value="<?php echo $Paytm_details['email'];?>">
  <input type="hidden" name="method" value="<?php echo $Paytm_details['method'];?>">
  <input type="hidden" name="callback_url" value="<?php echo $Paytm_details['returnUrl'];?>">
</form>
<script language='javascript'>document.redirect.submit();</script>
