<div class="view_head ">
   <div class="row">
      <div class="col-md-12">
         <span>Trip <?php echo  $TripIndicator?></span>
      </div>
   </div>
</div>
<div class="table_title">
   <div class="view_head">
      <div class="row align-items-center">
         <div class="col-md-4">
            <h5 class="m-0">Segment Details</h5>
         </div>
         <div class="col-md-8 text-md-end">
            <button  type  =  "button" class="badge badge-wt" flight-ticket-upload-add-segment-harish ="true"
               tts-method-name="flight-ticket-upload/segment-details"  tripIndicator =  "<?php echo  $TripIndicator?>" segment-indicator  =  "<?php echo $segmentIndicator;  ?>">
               <i class="fa-solid fa-add"></i> Add More Segment
            </button>
         </div>
      </div>
   </div>
</div>
<div class="row" tts-call-put-segment-html-<?php echo  $TripIndicator?>="true">
   <?php echo $segmentview; ?>
</div>