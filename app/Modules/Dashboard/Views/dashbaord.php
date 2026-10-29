<section class="dashboard-section d-flex align-items-center">
   <div class="container">
      <div class="dashboard-title">
         <h2 class="dashboard_title">
         Welcome to Dashboard</h4>
      </div>
       

      <div class="row">
         <?php if (isset($totle_booking_cont['Flight'])) { ?>
            <div class="col-lg-3">
               <div class="card tile-stats">
                  <a href="<?php echo site_url('flight/bookings?source=dashboard') ?>" class="tile-red">
                     <div class="icon"><i class="fa-solid fa-plane"></i></div>
                     <div class="card-body">
                        <h3>
                           <?php echo isset($totle_booking_cont['Flight']) ? $totle_booking_cont['Flight'] : '0'; ?>
                        </h3>
                        <h5 class="card-title">Flight Bookings</h5>
                     </div>
                  </a>
               </div>
            </div>
         <?php } ?>
 
         <?php if (isset($totle_booking_cont['Holiday'])) { ?>
            <div class="col-md-3">
               <div class="card tile-stats ">
                  <a href="<?php echo site_url('holiday/booking-list?source=dashboard') ?>" class="tile-blue">
                     <div class="icon"><i class="fa-solid fa-route"></i></div>
                     <div class="card-body">
                        <h3 class="">
                           <?php echo isset($totle_booking_cont['Holiday']) ? $totle_booking_cont['Holiday'] : '0'; ?>
                        </h3>
                        <h5 class="card-title">Holiday Bookings</h5>
                     </div>
                  </a>
               </div>
            </div>
         <?php } ?>


      </div>
 



      <!-- row -->
      <div class="row g-3">
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex justify-content-between align-items-baseline mb-3">
                     <h6 class="card-title mb-0">Bookings</h6>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-hover">
                        <thead class="table-active">
                           <tr>
                              <th>#</th>
                              <th>All Bookings </th>
                              <th>Pending </th>
                              <th>Cancelled </th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <?php if (isset($totle_booking_cont['Flight'])) { ?>
                                 <th>Flight</th>
                                 <td><a href="<?php echo site_url('flight/bookings?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Flight']) ? $totle_booking_cont['Flight'] : '0'; ?> </a></td>
                              <?php } ?>
                              <?php if (isset($commonData['Flight'])) { ?>
                                 <td><a href="<?php echo site_url('flight/bookings?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr"><?php echo isset($commonData['Flight']['Processing']) ? $commonData['Flight']['Processing'] : '0'; ?> </a></td>
                                 <td><a href="<?php echo site_url('flight/bookings?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr"><?php echo isset($commonData['Flight']['Cancelled']) ? $commonData['Flight']['Cancelled'] : '0'; ?> </a></td>
                              <?php } ?>
                           </tr>

                           <tr>
                              <?php if (isset($totle_booking_cont['Holiday'])) { ?>
                                 <th>Holiday</th>
                                 <td><a href="<?php echo site_url('holiday/booking-list?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_booking_cont['Holiday']) ? $totle_booking_cont['Holiday'] : '0'; ?> </a>
                                 </td>
                              <?php } ?>
                              <?php if (isset($commonData['Holiday'])) { ?>
                                 <td><a href="<?php echo site_url('holiday/booking-list?source=dashboard&bookingtype=Processing') ?>" class="txt_led_clr">
                                       <?php echo isset($commonData['Holiday']['Processing']) ? $commonData['Holiday']['Processing'] : '0'; ?>
                                    </a></td>
                                 <td><a href="<?php echo site_url('holiday/booking-list?source=dashboard&bookingtype=Cancelled') ?>" class="txt_led_clr">
                                       <?php echo isset($commonData['Holiday']['Cancelled']) ? $commonData['Holiday']['Cancelled'] : '0'; ?>
                                    </a></td>
                              <?php } ?>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
         
         <div class="col-md-6">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex justify-content-between align-items-baseline mb-3">
                     <h6 class="card-title mb-0">Amendment</h6>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-hover">
                        <thead class="table-active">
                           <tr>
                              <th>#</th>
                              <th>All Amendment </th>
                              <th>Requested </th>
                              <th>Approved </th>
                              <th>Rejected </th>
                              <th>Processing </th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <?php if (isset($totle_amendment_cont['Flight'])) { ?>
                                 <th>Flight</th>
                                 <td><a href="<?php echo site_url('flight/flight-amendments?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_amendment_cont['Flight']) ? $totle_amendment_cont['Flight'] : '0'; ?></a></td>
                              <?php } ?>
                              <?php if (isset($CommonDataAmendment['Flight'])) { ?>
                                 <td><a href="<?php echo site_url('flight/flight-amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Flight']['requested']) ? $CommonDataAmendment['Flight']['requested'] : '0'; ?> </a></td>
                                 <td><a href="<?php echo site_url('flight/flight-amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr"> <?php echo isset($CommonDataAmendment['Flight']['approved']) ? $CommonDataAmendment['Flight']['approved'] : '0'; ?></a> </td>
                                 <td><a href="<?php echo site_url('flight/flight-amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Flight']['rejected']) ? $CommonDataAmendment['Flight']['rejected'] : '0'; ?> </a> </td>
                                 <td><a href="<?php echo site_url('flight/flight-amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Flight']['processing']) ? $CommonDataAmendment['Flight']['processing'] : '0'; ?> </a> </td>
                              <?php } ?>
                           </tr>





                           <tr>
                              <?php if (isset($totle_amendment_cont['Holiday'])) { ?>
                                 <th>Holidays</th>
                                 <td><a href="<?php echo site_url('holiday/amendments?source=dashboard') ?>" class="txt_led_clr">
                                       <?php echo isset($totle_amendment_cont['Holiday']) ? $totle_amendment_cont['Holiday'] : '0'; ?>
                                    </a></td>
                              <?php } ?>
                              <?php if (isset($CommonDataAmendment['Holiday'])) { ?>
                                 <td><a href="<?php echo site_url('holiday/amendments?key=amendment_status&key-text=Amendment+Status&value=requested') ?>" class="txt_led_clr">
                                       <?php echo isset($CommonDataAmendment['Holiday']['requested']) ? $CommonDataAmendment['Holiday']['requested'] : '0'; ?>
                                    </a></td>
                                 <td><a href="<?php echo site_url('holiday/amendments?key=amendment_status&key-text=Amendment+Status&value=approved') ?>" class="txt_led_clr">
                                       <?php echo isset($CommonDataAmendment['Holiday']['approved']) ? $CommonDataAmendment['Holiday']['approved'] : '0'; ?>
                                    </a></td>
                                 <td><a href="<?php echo site_url('holiday/amendments?key=amendment_status&key-text=Amendment+Status&value=rejected') ?>" class="txt_led_clr">
                                       <?php echo isset($CommonDataAmendment['Holiday']['rejected']) ? $CommonDataAmendment['Holiday']['rejected'] : '0'; ?>
                                    </a></td>
                                 <td>
                                    <a href="<?php echo site_url('holiday/amendments?key=amendment_status&key-text=Amendment+Status&value=processing') ?>" class="txt_led_clr"><?php echo isset($CommonDataAmendment['Holiday']['processing']) ? $CommonDataAmendment['Holiday']['processing'] : '0'; ?> </a>
                                 </td>
                              <?php } ?>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>

         <div class="col-md-6">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex justify-content-between align-items-baseline mb-3">
                     <h6 class="card-title mb-0">Refunds</h6>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-hover">
                        <thead class="table-active">
                           <tr>
                              <th>#</th>
                              <th>All Refunds </th>
                              <th>Opened Refund </th>
                              <th>Closed Refund </th>
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <?php if (isset($totle_amendment_cont['Flight'])) { ?>
                                 <th>Flight</th>
                                 <td><a href="<?php echo site_url('flight/flight-refunds?source=dashboard') ?>" class="txt_led_clr"><?php echo isset($totle_amendment_cont['Flight']) ? $totle_amendment_cont['Flight'] : '0'; ?></a></td>
                              <?php } ?>
                              <?php if (isset($CommonDatarefund_status['Flight'])) { ?>
                                 <td><a href="<?php echo site_url('flight/flight-refunds?key=refund_status&key-text=Refund+Status&value=Open') ?>" class="txt_led_clr"> <?php echo isset($CommonDatarefund_status['Flight']['Open']) ? $CommonDatarefund_status['Flight']['Open'] : '0'; ?></a></td>
                                 <td><a href="<?php echo site_url('flight/flight-refunds?key=refund_status&key-text=Refund+Status&value=Close') ?>" class="txt_led_clr"> <?php echo isset($CommonDatarefund_status['Flight']['Close']) ? $CommonDatarefund_status['Flight']['Close'] : '0'; ?></a></td>
                              <?php } ?>
                           </tr>
 

                           <tr>
                              <?php if (isset($totle_amendment_cont['Holiday'])) { ?>
                                 <th>Holidays</th>
                                 <td><a href="<?php echo site_url('holiday/refunds?source=dashboard') ?>" class="txt_led_clr">
                                       <?php echo isset($totle_amendment_cont['Holiday']) ? $totle_amendment_cont['Holiday'] : '0'; ?>
                                    </a></td>
                              <?php } ?>
                              <?php if (isset($CommonDatarefund_status['Holiday'])) { ?>
                                 <td><a href="<?php echo site_url('holiday/refunds?key=refund_status&key-text=Refund+Status&value=Open') ?>" class="txt_led_clr">
                                       <?php echo isset($CommonDatarefund_status['Holiday']['Open']) ? $CommonDatarefund_status['Holiday']['Open'] : '0'; ?>
                                    </a></td>
                                 <td><a href="<?php echo site_url('holiday/refunds?key=refund_status&key-text=Refund+Status&value=Close') ?>" class="txt_led_clr">
                                       <?php echo isset($CommonDatarefund_status['Holiday']['Close']) ? $CommonDatarefund_status['Holiday']['Close'] : '0'; ?>
                                    </a></td>
                              <?php } ?>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row mt-4">
         <div class="col-lg-12 col-xl-12 stretch-card">
            <div class="card">
               <div class="card-body">
                  <div class="d-flex justify-content-between align-items-baseline mb-3">
                     <h6 class="card-title mb-0">Last 10 Account Logs</h6>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-hover">
                        <thead class="table-active">
                           <tr>
                              <th>Ref.No.</th>
                              <th>Booking CNF. No</th>
                              <th>Remark</th>
                              <th>Credit</th>
                              <th>Debit</th>
                              <th>Balance</th>
                              <th>Payments Type</th>
                              <th>Created</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                           if (!empty($account_logs) && is_array($account_logs)) {
                              foreach ($account_logs as $data) {
                                 $prefix_booking_ref_number = '';

                                 $booking_id = $data['booking_ref_no'];
                                 if ($data['service'] == 'flight') {
                                    $prefix_booking_ref_number = $data['flight_booking_ref_number'];
                                 }
                                 if ($data['service'] == 'hotel') {
                                    $prefix_booking_ref_number = $data['hotel_booking_ref_number'];
                                 }

                                 if ($data['service'] == 'bus') {
                                    $prefix_booking_ref_number = $data['bus_booking_ref_number'];
                                 }

                                 if ($data['service'] == 'holiday') {
                                    $prefix_booking_ref_number = $data['holiday_booking_ref_number'];
                                 }

                                 if ($data['service'] == 'visa') {
                                    $prefix_booking_ref_number = $data['visa_booking_ref_number'];
                                 }

                                 if ($data['service'] == 'cruise') {
                                    $prefix_booking_ref_number = $data['cruise_booking_ref_number'];
                                 }

                                 if ($data['service'] == 'car') {
                                    $prefix_booking_ref_number = $data['car_booking_ref_number'];
                                 }
                           ?>
                                 <tr>
                                    <td>
                                       <?php
                                       if ($data['action_type'] == 'booking' || $data['action_type'] == 'refund') { ?>
                                          <a href="<?php echo service_log_link($data['service'], $prefix_booking_ref_number) ?>">
                                             <?php echo $prefix_booking_ref_number; ?>
                                          </a>
                                          <?php } else {
                                          if ($data['booking_ref_no']) { ?>
                                             <a href="<?php echo service_log_link($data['service'], $prefix_booking_ref_number) ?>">
                                                <?php echo $prefix_booking_ref_number; ?>
                                             </a>
                                       <?php } else {
                                             echo '-';
                                          }
                                       } ?>
                                    </td>
                                    <!--  <td><?php echo $data['acc_ref_number']; ?></td> -->
                                    <td>
                                       <?php echo $data['booking_confirmation_number']; ?>
                                    </td>
                                    <td>
                                       <b>
                                          <?php
                                          if ($data['action_type'] == 'booking') {
                                             if ($data['service_log']) {
                                                $service_log = json_decode($data['service_log'], true);

                                                echo service_log($data['service'], $data['action_type'], $service_log);
                                             } else {
                                                echo ucfirst($data['service']) . ' ' . ucfirst($data['action_type']);
                                             }
                                          } else {
                                             echo ucfirst($data['action_type']);
                                          }
                                          ?>
                                       </b><br />
                                       <span data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $data['remark']; ?>">
                                          <?php echo limitTextChars($data['remark'], 50, true, true); ?>
                                       </span>
                                    </td>
                                    <td>
                                       <?php echo $data['credit']; ?>
                                    </td>
                                    <td>
                                       <?php echo $data['debit']; ?>
                                    </td>
                                    <td>
                                       <?php echo $data['balance']; ?>
                                    </td>
                                    <td>
                                       <?php
                                       $transaction_id = '';
                                       if (isset($data['transaction_id'])) {
                                          $transaction_id = '-' . $data['transaction_id'];
                                       }

                                       echo ucfirst($data['action_type']) . "-";
                                       echo $data['payment_mode'] != "" ? "<b></b> " . $data['payment_mode'] . $transaction_id . "<br/>" : "Wallet"; ?>
                                    </td>
                                    <td>
                                       <?php
                                       echo date_created_format($data['created']);
                                       ?>
                                    </td>
                                 </tr>
                           <?php
                              }
                           } else {
                              echo "<tr> <td colspan='11' class='text_center'><b>No Account Logs Found</b></td></tr>";
                           } ?>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- row -->
   </div>
</section>