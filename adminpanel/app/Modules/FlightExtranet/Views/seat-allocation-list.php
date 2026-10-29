<div class="content">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m0"> Manage Private Fare</h5>
               </div>
               <div class="col-md-8 text-md-end">
               <?php if (permission_access("FlightExtranet", "add_seat_allocation")) { ?>
                  <button class="badge badge-wt" view-data-modal="true" data-controller='private-fare' data-href="<?php echo site_url('private-fare/add-seat-allocation-template/') . dev_encode($private_fare_id) ?>"><i class="fa-solid fa-add"></i> Add Seat</button>
                  <?php } ?>
                  <?php if (permission_access("FlightExtranet", "delete_seat_allocation")) { ?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge onclick="confirm_delete('seat-allocation')"><i class="fa-solid fa-trash"></i> Delete</button>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">
            <div class="row">
               <div class="body_manage dash-padding dashboard__main__content">
                  <div class="segment_body">
                     <?php if ($segment_details) {
                        
                        foreach ($segment_details as $key => $segments) {
                           foreach ($segments as $key => $segment) {
                              $airline_code = isset($segment['airline_code'])?explode('-', $segment['airline_code']):"";
                        ?>
                     <div class="segment__info">
                        <div class="segment_body-airlogo d-flex align-items-center w-25">
                           <span class="airline-logo domAirLogo  size-28 x<?php echo $airline_code[0]; ?>"></span>
                           <p class="m-0"><span class="airline-code"><?php echo $airline_code[0]; ?>-<?php echo $segment['flight_number'] ?></span>
                           </p>
                        </div>
                        <div class="segment_body-airsource">
                           <p class="m-0"><span class="air_sourcr-none"><?php echo $segment['origin_airport_code'] ?></span>
                           </p>
                           <p class="m-0"><?php echo $segment['departure_time'] ?></p>
                        </div>
                        <div class="artts_row_right-sm"></div>
                        <div class="segment_body-airdestination">
                           <p class="m-0"><span class="air_sourcr-none"><?php echo $segment['destination_airport_code'] ?></span>
                           </p>
                           <p class="m-0"><?php echo $segment['arrival_time'] ?></p>
                        </div>
                     </div>
                     <?php }
                        }
                        } ?>
                  </div>
               </div>
               <div class="col-md-12 mt-3">
                  <div class="listComponent__wrapper-box table-responsive">
                     <?php $trash_uri = "private-fare/remove-seat-allocation"; ?>
                     <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="seat-allocation">
                        <table class="table table-bordered table-hover">
                           <thead class="table-active">
                           <tr>
                           <?php if (permission_access("FlightExtranet", "delete_seat_allocation")) { ?>
                                 <th><label class="m0"><input type="checkbox" name="check_all" id="selectall" /></label></th>
                                 <?php } ?>
                                 <th>Adult Price</th>
                                 <th>Child Price</th>
                                 <th>Infant Price</th>
                                 <th> Onward Date</th>
                                 <th> Return  Date</th>
                                 <th> Airline PNR</th>
                                 <th>Seats Remaining</th>
                                 <th>Seats Sold</th>
                                 <th>Update</th>
                              </tr>
                           </thead>
                           <tbody class="table__body-list-font">
                              <?php
                                 if (!empty($list) && is_array($list)) {  
                                    foreach ($list as $data) {
                                 ?>
                       <tr>
                              <?php if (permission_access("FlightExtranet", "delete_seat_allocation")) { ?>
                                 <td>
                                    <label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php echo $data['id']; ?>" /></label>
                                 </td>
                                 <?php } ?>
                                 <td class="ellipses"><?php echo $data['adult_base_fare'] ?></td>
                                 <td class="ellipses"><?php echo $data['child_base_fare'] ?></td>
                                 <td class="ellipses"><?php echo $data['infant_base_fare'] ?></td>
                                 <td class="ellipses"><?php echo date_to_custom_date($data['date']); ?></td>
                                 <td class="no-ellipses"><?php echo $data['date_return']!="0000-00-00"?date_to_custom_date($data['date_return']):"-"; ?></td>
                                 <td> <?php echo $data['pnr'] ?></td>
                                 <td class="ellipses"> <?php echo $data['available_seats']; ?></td>
                                 <td><a href="<?php echo site_url('flight/get-seat-bookings/'). dev_encode($data['id'])?>"><i
                                    class="fa fa-eye"></i></a> <?php echo $data['booked_seats']?>
                                 </td>
                                 <?php if (permission_access("FlightExtranet", "edit_seat_allocation")) { ?>
                                 <td><a href="javascript:void(0);" view-data-modal="true" data-controller='private-fare' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('private-fare/edit-seat-allocation-template/') . dev_encode($data['id']); ?>"><i class="fa fa-edit"></i>
                                    <span class="link_container-link"></span></a>
                                 </td>
                                 <?php } ?>
                              </tr>
                              <?php }
                                 } ?>
                           </tbody>
                        </table>
                     </form>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="row pagiantion_row align-items-center">
                     <div class="col-md-6 mb-3 mb-lg-0">
                        <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>,
                           total <?= $pager->getTotal() ?> records found 
                        </p>
                     </div>
                     <div class="col-md-6">
                        <?php if ($pager) : ?> <?= $pager->links() ?> <?php endif ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div> 