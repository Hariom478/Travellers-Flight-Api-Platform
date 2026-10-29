
<main class="container ptb30 plr0 pt15m">
<div class="row">
    <div class="col-md-12">
        <div class="listing-loader-block">
            <div class="tts-showcenter">
                <div class="tts-loading-box">
                    <h5> Please Wait... </h5>
                    <p class="mb-2">Please Do Not Close Or Refresh This Window</p>
                    <span class="fw-bold">
                    <div  class="loader mt-3 mb-3">
                        <div role="status" class="spinner-grow text-primary">
                            <span class="sr-only">Loading...</span></div>
                        <div role="status" class="spinner-grow text-secondary">
                            <span class="sr-only">Loading...</span></div>
                        <div role="status" class="spinner-grow text-danger">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <div role="status" class="spinner-grow text-dark">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if($paymentdata && $payment_token){ ?>
<form action="<?php echo site_url('flight/initiate-booking'); ?>" method="POST" name="pacpayment" >
<input type="hidden" name="payment_token" value="<?php echo $payment_token; ?>">
</form>	
<?php }else{ ?>
<div> There Is No Package Option For This Details </div>	
<?php } ?>
</main>	
<script type="text/javascript">
	$( document ).ready(function() {
document.forms['pacpayment'].submit()
});
</script>