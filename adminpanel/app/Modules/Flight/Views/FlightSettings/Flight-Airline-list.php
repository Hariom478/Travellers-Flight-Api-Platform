<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> Flight Settings</h5>
               </div>
               <div class="col-md-8 text-end">
                  <?php if (permission_access("Setting", "add_airlines")) { ?>
                  <button class="badge badge-wt" view-data-modal="true" data-controller='flightsettings'
                     data-href="<?php echo site_url('flightsettings/add-airline-template') ?>">
                     <i class="fa-solid fa-add "></i> Add Airline
                  </button>
                  <?php }?>
                  <?php if (permission_access("Setting", "delete_airlines")) { ?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                     onclick="confirm_delete('formairlinelist')"><i class="fa-solid fa-trash"></i> Delete
                  </button>
                  <?php }?>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="query-followup">
            <ul class="lm_navigation">
                <?php if (permission_access("Setting", "airport_list")) { ?>
                  <li class="lm_navLst <?php echo active_list_mod("FlightSettings", "index"); ?>">
                     <a href="<?php echo site_url("flightsettings"); ?>"> <span> Airports List</span> </a>
                  </li>
               <?php } ?>
               <?php if (permission_access("Setting", "airlines_list")) { ?>
               <li class="lm_navLst <?php echo active_list_mod("FlightSettings", "airlines_list"); ?>">
                  <a href="<?php echo site_url("flightsettings/flight-airlines-list"); ?>">
                  <span>Airlines List</span>
                  </a>
               </li>
               <?php } ?>
            </ul>
         </div>
         <div class="card-body">
            <div class="row ">
               <!----------Start Search Bar ----------------->
               <form action="<?php echo site_url('flightsettings/flight-airlines-list'); ?>" method="GET" class="row"
                  name="airline-search" onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-select"
                           onchange="tts_searchkey(this,'airline-search')" tts-validatation="Required"
                           tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="<?php echo trim('airline_code');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_code') {
                              echo "selected";
                              
                              } ?>>Airline Code
                           </option>
                           <option value="<?php echo trim('airline_name');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_name') {
                              echo "selected";
                              
                              } ?> >Airline Name
                           </option>
                           <option value="<?php echo trim('airline_contact_no');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'airline_contact_no') {
                              echo "selected";
                              
                              } ?> >Airline Contact No
                           </option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                        echo trim($search_bar_data['key-text']);
                        
                        } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label><?php if (isset($search_bar_data['key']) && $search_bar_data['key'] != 'date-range') {
                           echo $search_bar_data['key-text'] . " *";
                           
                           } else {
                           
                           echo "Value";
                           
                           } ?> </label>
                        <input type="text" name="value" placeholder="Value"
                           value="<?php if (isset($search_bar_data['value'])) {
                              echo $search_bar_data['value'];
                              
                              } ?>"
                           class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "disabled";
                              
                              } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              } else {
                              
                                  echo 'tts-validatation="Required"';
                              
                              } ?> tts-error-msg="Please enter value"/>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">
                        
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <? if (isset($search_bar_data['key'])): ?>
                  <div class="col-md-3 align-self-center">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('flightsettings/flight-airlines-list'); ?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <?php
                  $trash_uri = "flightsettings/remove-airline";
                  
                  ?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                  id="formairlinelist">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php if (permission_access("Setting", "delete_airlines")) { ?>
                           <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                           </th>
                           <?php }?>
                           <th>Airline Code</th>
                           <th>Airline Name</th>
                           <th>Airline Contact No</th>
                           <?php if (permission_access("Setting", "edit_airlines")) { ?>
                           <th>Action</th>
                           <?php }?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (!empty($aiport_list) && is_array($aiport_list)) {
                           
                               foreach ($aiport_list as $data) { ?>
                        <tr>
                           <?php if (permission_access("Setting", "delete_airlines")) { ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox"
                                 value="<?php echo $data['id']; ?>"/></label>
                           </td>
                           <?php }?>
                           <td>
                              <?php echo $data['airline_code']; ?>
                           </td>
                           <td>
                              <?php echo ucfirst($data['airline_name']); ?>
                           </td>
                           <td>
                              <?php echo $data['airline_contact_no']; ?>
                           </td>
                           <?php if (permission_access("Setting", "edit_airlines")) { ?>
                           <td>
                              <a href="javascript:void(0);" view-data-modal="true"
                                 data-controller='flightsettings'
                                 data-id="<?php echo dev_encode($data['id']); ?>"
                                 data-href="<?php echo site_url('/flightsettings/edit-airline-template/') . dev_encode($data['id']); ?>"><i
                                 class="fa-solid fa-edit "></i></a>
                           </td>
                           <?php }?>
                        </tr>
                        <?php }
                           } else {
                           
                               echo "<tr> <td colspan='11' class='text-center'><b>No Airport Found</b></td></tr>";
                           
                           } ?>
                     </tbody>
                  </table>
               </form>
            </div>
            <div class="row pagiantion_row align-items-center">
               <div class="col-md-6 mb-3 mb-lg-0">
                  <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                     of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found 
                  </p>
               </div>
               <div class="col-md-6">
                  <?php if ($pager) : ?>
                  <?= $pager->links() ?>
                  <?php endif ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<!-- Show Detail Lead Modal content -->