<div class="page-content">
   <!--    <div class="row">
      <div class="col-lg-12">
         <h2 class="dashboard_title">Welcome to Dashboard</h2>
      </div>
   </div> -->
 

   <div class="row">
      <div class="col-md-6">
         <div class="page-content-area card p-0 mb-3 ">
            <div class="bg_white">
               <div class="card-header">
                  <div class="table_title">
                     <div class="topbardash">
                        <h5>Bookings</h5>
                     </div>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table table-bordered table-striped ">
                     <thead>
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
         <?php // pr($CommonDataAmendment['Flight']); 
         ?>
         <div class="page-content-area card p-0 mb-3 ">
            <div class="bg_white">
               <div class="card-header">
                  <div class="table_title">
                     <div class="topbardash">
                        <h5>Amendment</h5>
                     </div>
                  </div>
               </div>
               <div class="table-responsive ">
                  <table class="table table-bordered table-striped">
                     <thead>
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
         
         <div class="page-content-area card p-0 mb-3 ">
            <div class="bg_white">
               <div class="card-header">
                  <div class="table_title">
                     <div class="topbardash">
                        <h5>Refunds</h5>
                     </div>
                  </div>
               </div>
               <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                     <thead>
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
                                    <td><a href="<?php echo site_url('holiday/refunds?key=refund_status&key-text=Refund+Status&value=Open')?>" class="txt_led_clr">
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
</div>