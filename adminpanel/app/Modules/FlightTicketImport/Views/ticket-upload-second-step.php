<div class="content">
   <div class="page-content">
      <div class="page-content-area">
         <div class="card-header">
            <div class="table_title">
               <div class="topbar">
                  <h5 class="m0"> <?php $i = 0;
                     echo $title ?> </h5>
               </div>
            </div>
         </div>
         <div class="card-body">
            <form name="web-partner" tts-form='true'
               action="<?php echo site_url('flight-ticket-import/save-passenger'); ?>"
               method="POST" id="flight-upload-ticket">
               <div class="table_title">
                  <div class="view_head">
                     <div class="row">
                        <div class="col-md-2">
                           <span>Passenger Details</span>
                        </div>
                     </div>
                  </div>
               </div>
               <input type  =  "hidden"  name  =  "temptripSegmentId"  value  =  "<?php echo  $SegmentInfokey; ?>">
               <div class="row" tts-call-put-passenger-Adult-html="true">
                  <?php echo  $passengerDetailinfoView; ?>
               </div>
               <div class="row m0">
                  <?php  echo   $importticketdeal; ?>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <a class="btn btn-primary" href  =  "<?php echo  site_url('flight-ticket-import/import-pnr-details?segmentinfokey='.$SegmentInfokey) ?>"> Previous</a>
                  </div>
                  <div class="col-md-6 text-md-right">
                     <input class="btn btn-primary" type="submit" value="Review Details">
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>