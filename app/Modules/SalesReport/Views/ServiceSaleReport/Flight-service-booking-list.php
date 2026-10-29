<style>
   .select2-container--default .select2-selection--multiple{
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;

   }
</style>
<div class="table_title">
   <div class="sale_bar p0 mt-3">
      <div class="row">
         <div class="col-md-4">
            <h5 class="m-0"> Flight Sales Report</h5>
         </div>
      </div>
   </div>
   <form action="<?php echo site_url('sale-result/service-report'); ?>" method="GET" class=" mb-3  col-12" id="sales-export" name="markup-search" onsubmit="return searchvalidateForm()">
      <input type="hidden" name="q" value="Flight">
      <div class="row align-items-center mb-3">
         <div class="col-md-3">
            <div class="form-group form-mb-20">
               <label class="form-label">Select key to search by *</label>
               <select name="key[]" class="form-select select_search " onchange="tts_searchkey(this,'markup-search')" tts-validatation="Required" tts-error-msg="Please select search key" multiple>
                  <?php foreach ($getOptions as $key => $val) : ?>
                  <option value="<?= $key ?>" <?= (in_array($key, $search_bar_data['key'])) ? 'selected' : '' ?>><?= $val ?></option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date" id="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                  echo $search_bar_data['from_date'];
                  } else {
                  
                  echo date('d M Y');
                  }  ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
            </div>
         </div>
         <div class="col-md-2">
            <div class="form-group form-mb-20">
               <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date" id="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                  echo $search_bar_data['to_date'];
                  } else {
                  
                  echo date('d M Y');
                  }  ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
            </div>
         </div>
         <div class="col-md-2 align-self-end">
            <div class="form-group form-mb-20">
               <button type="submit" data-url="<?php echo site_url('sale-result/service-report'); ?>" praveen-method="get" class="btn btn-primary export-praveen badge_search">Search <i class="fa fa-search"></i></button>
            </div>
         </div>
         <div class="col-md-2 align-self-end">
            <? if (isset($search_bar_data['key'])) : ?>
            <div class="search-reset-btn">
               <a href="<?php echo site_url('sale-result/service-report?q=Flight'); ?>">Reset Search</a>
            </div>
            <? endif ?>
         </div>
         <div class="col-md-1 text-md-end align-self-end">
            <div class="form-group form-mb-20">
               <button type="submit" data-url="<?php echo site_url('sale-result/get-service-report'); ?>" praveen-method="post" id="export-data-btn" class="btn btn_excel export-praveen">
               <img src="<?php echo site_url('webroot/img/excel.svg'); ?>" class="img_fluid">
               </button>
            </div>
         </div>
         <input type="hidden" name="service" value="Flight">
      </div>
   </form>
</div>
<div class="table-responsive">
   <table class="table table-bordered table-hover">
      <?php $colArray = array_values($search_bar_data['key']);
         ?>
      <thead class="table-active">
         <tr>
            <?php
               foreach ($colArray as $key => $val) :
               
               ?>
            <th><?= $getOptions[$val] ?></th>
            <?php endforeach; ?>
            <th>Summary</th>
         </tr>
      </thead>
      <tbody>
         <?php
            if (!empty($list) && is_array($list)) {
            
            ?>
         <?php
            foreach ($list as $key => $val) :
            
               if (isset($val['booking_status'])) {
            
                  if ($val['booking_status'] == 'Confirmed') {
            
                     $class = 'tts-text-success';
                  } else {
            
                     $class = 'tts-text-danger';
                  }
               }
            
               if (isset($val['payment_status'])) {
            
                  if ($val['payment_status'] == 'Successful') {
            
                     $payment_class = 'tts-text-success';
                  } else {
            
                     $payment_class = 'tts-text-danger';
                  }
               }
            
               if (isset($val['segments'])) {
            
                  $segment = json_decode($val['segments'], true);
            
                  if (count($segment) == 1) {
            
                     $first_segment = current($segment[0]);
            
                     $last_segment = end($segment[0]);
                  } else {
            
                     $first_segment = current($segment);
            
                     $first_segment = current($first_segment);
            
            
            
                     $last_segment = end($segment);
            
                     $last_segment = end($last_segment);
                  }
            
            
            
                  $flightNo = (isset($first_segment['Airline']['FlightNumber'])) ? $first_segment['Airline']['FlightNumber'] : 0;
            
                  $AirLineName = (isset($first_segment['Airline']['AirlineName'])) ? $first_segment['Airline']['AirlineName'] : 'N/A';
               }
            
               if (isset($val['agent_fare_break_up'])) {
            
                  $fareBreakupArray = json_decode($val['agent_fare_break_up'], true);
            
            
            
                  $FareBreakUp = array(
            
                     "FareBreakup" => array(
            
                        "BaseFare" => array("Value" => $fareBreakupArray['BaseFare'], "LabelText" => "Base Fare"),
            
                        "Taxes" => array("Value" => $fareBreakupArray['Tax'], "LabelText" => "Taxes"),
            
                        "ServiceAndOtherCharge" => array("Value" => $fareBreakupArray['OtherCharges'] + $fareBreakupArray['ServiceCharges'], "LabelText" => "Other & Service Charges"),
            
                        /* "PublishedPrice" =>   array("Value" => $fareBreakupArray['PublishedPrice'], "LabelText" => "Published Price"), */
            
                        "MealBaggageCharge" => array("Value" => 0, "LabelText" => "Meal & Baggage Charges"),
            
                        /*   "OfferedPrice" => array("Value" => $fareBreakupArray['OfferedPrice'], "LabelText" => "Offered Price"), */
            
                        "CommEarned" => array("Value" => $fareBreakupArray['AgentCommission'], "LabelText" => "Comm Earned (-)"),
            
                        "Discount" => array("Value" => $fareBreakupArray['Discount'], "LabelText" => "Discount (-)"),
            
                        "TDS" => array("Value" => $fareBreakupArray['TDS'], "LabelText" => "TDS (+)")
            
                     ),
            
                     "TotalAmount" => array("Value" => $fareBreakupArray['TDS'] + $fareBreakupArray['OfferedPrice'], "LabelText" => "Total Amount"),
            
                     "GSTDetails" => $fareBreakupArray['GST']
            
                  );
               }
            
            
            
            ?>
         <tr>
            <?php foreach ($colArray as $key => $data) :
               ?>
            <?php if ($data == "booking_ref_number") : ?>
            <td>
               <a href="<?php echo site_url('/flight/details/') . $val['booking_ref_number']; ?>" target="_blank"><?php echo $val['booking_ref_number']; ?></a>
            </td>
            <?php elseif ($data == 'is_refundable') : ?>
            <td>
               <span class="<?php echo $val['is_refundable'] == 1 ? "tts-text-success" : "tts-text-danger"; ?>"><?php echo $val['is_refundable'] == 1 ? "Yes" : "NO"; ?></span>
            </td>
            <?php elseif ($data == 'agent_fare_break_up') : ?>
            <td>
               <?php foreach ($FareBreakUp['FareBreakup'] as $fare) { ?>
               <b><?php echo $fare['LabelText']; ?>:</b>
               ₹ <?php echo $fare['Value']; ?>
               <br />
               <?php } ?>
               <b><?php echo $FareBreakUp['TotalAmount']['LabelText']; ?>:</b>
               ₹ <?php echo $FareBreakUp['TotalAmount']['Value']; ?>
            </td>
            <?php elseif ($data == 'departure_date') : ?>
            <td><?php echo display_custom_date_format($val['departure_date']); ?></td>
            <?php elseif ($data == 'date_of_birth') : ?>
            <td><?php echo display_custom_date_format($val['date_of_birth']); ?></td>
            <?php elseif ($data == 'segments') : ?>
            <td><?php echo $AirLineName; ?></td>
            <?php elseif ($data == 'created') : ?>
            <td><?php echo date_created_format($val['created']); ?></td>
            <?php else : ?>
            <td><?= $val[$data] ?></td>
            <?php endif; ?>
            <?php endforeach; ?>
            <td> <a href="<?php echo site_url('/flight/confirmation/') . $ticketData = dev_encode(json_encode(array($val['id']))); ?>" target="_blank"><i class="fa fa-eye"></i> View</a></td>
         </tr>
         <?php
            endforeach; ?>
         <?php
            } else {
            
               echo "<tr> <td colspan='21' class='text_center'><b>No Booking Found</b></td></tr>";
            } ?>
      </tbody>
   </table>
</div>
<div class="row mt-3">
   <div class="col-md-12 d-flex align-items-center justify-content-between">
      <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
         of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records
         found
      </p>
      <span>
      <?php if ($pager) : ?>
      <?= $pager->links() ?>
      <?php endif ?>
      </span>
   </div>
</div>