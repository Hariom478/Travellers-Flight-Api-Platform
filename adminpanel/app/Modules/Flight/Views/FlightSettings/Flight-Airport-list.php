<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> Flight Settings</h5>
               </div>
               <div class="col-md-8 text-end">
                  <?php if (permission_access("Setting", "add_airport")) { ?>
                  <button class="badge badge-wt" view-data-modal="true" data-controller='flightsettings'
                     data-href="<?php echo site_url('flightsettings/add-airport-template') ?>"><i
                     class="fa-solid fa-add "></i> Add Airport
                  </button>
                  <?php }?>
                  <?php if (permission_access("Setting", "delete_airport")) { ?>
                  <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                     onclick="confirm_delete('formairportlist')"><i class="fa-solid fa-trash"></i> Delete
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
               <form action="<?php echo site_url('flightsettings'); ?>" method="GET" class="row" name="airport-search" onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-select"
                           onchange="tts_searchkey(this,'airport-search')" tts-validatation="Required"
                           tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="<?php echo trim('code');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'code') {
                              echo "selected";
                              
                              } ?>>Airport Code
                           </option>
                           <option value="<?php echo trim('name');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'name') {
                              echo "selected";
                              
                              } ?> >Airport Name
                           </option>
                           <option value="<?php echo trim('city_code');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'city_code') {
                              echo "selected";
                              
                              } ?> >City Code
                           </option>
                           <option value="<?php echo trim('country_name');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'country_name') {
                              echo "selected";
                              
                              } ?> >Country Name
                           </option>
                           <option value="<?php echo trim('country_code');?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'country_code') {
                              echo "selected";
                              
                              } ?> >Country Code
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
                        <a href="<?php echo site_url('flightsettings'); ?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <?php
                  $trash_uri = "flightsettings/remove-airport";
                  
                  ?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                  id="formairportlist">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php if (permission_access("Setting", "delete_airport")) { ?>
                           <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                           </th>
                           <?php }?>
                           <th>Airport Code</th>
                           <th>Airport Name</th>
                           <th>City Code</th>
                           <th>Country Name</th>
                           <th>Country Code</th>
                           <?php if (permission_access("Setting", "edit_airport")) { ?>
                           <th>Action</th>
                           <?php }?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (!empty($airport_list) && is_array($airport_list)) {
                           
                               foreach ($airport_list as $data) { ?>
                        <tr>
                           <?php if (permission_access("Setting", "delete_airport")) { ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox"
                                 value="<?php echo $data['id']; ?>"/></label>
                           </td>
                           <?php }?>
                           <td>
                              <?php echo $data['code']; ?>
                           </td>
                           <td>
                              <?php echo $data['name']; ?>
                           </td>
                           <td><?php echo $data['city_code']; ?></td>
                           <td>
                              <?php echo $data['country_name']; ?>
                           </td>
                           <td>
                              <?php echo $data['country_code']; ?>
                           </td>
                           <?php if (permission_access("Setting", "edit_airport")) { ?>
                           <td>
                              <a href="javascript:void(0);" view-data-modal="true"
                                 data-controller='flightsettings'
                                 data-id="<?php echo dev_encode($data['id']); ?>"
                                 data-href="<?php echo site_url('/flightsettings/edit-airport-template/') . dev_encode($data['id']); ?>"><i
                                 class="fa-solid fa-edit "></i></a>
                           </td>
                           <?php }?>
                        </tr>
                        <?php }
                           } else {
                           
                               echo "<tr> <td  class='text-center'><b>No Airport Found</b></td></tr>";
                           
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
