<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> API Flight Fare Type</h5>
               </div>
               <div class="col-md-8 text-end">
                  <?php if (permission_access("Setting", "add_flight_fare_type")) { ?>
                     <button class="badge badge-wt" view-data-modal="true" data-controller='flightsettings'
                        data-href="<?php echo site_url('flightfaretype/add-faretype-template') ?>"><i
                           class="fa-solid fa-add "></i> Add Fare Type
                     </button>
                  <?php } ?>
                  <?php if (permission_access("Setting", "delete_flight_fare_type")) { ?>
                     <!-- <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                        onclick="confirm_delete('formfaretypelist')"><i class="fa-solid fa-trash"></i> Delete
                     </button> -->
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
      <div class="page-content-area">
         <div class="card-body">
            <div class="row ">

               <!----------Start Search Bar ----------------->
               <form action="<?php echo site_url('flightfaretype'); ?>" method="GET" class="row"
                  name="faretype-search" onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-select" onchange="tts_searchkey(this,'faretype-search')"
                           tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>
                           <option value="<?php echo trim('supplier_fare_type'); ?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'supplier_fare_type') {
                                 echo "selected";

                              } ?>>Supplier Fare Type
                           </option>
                           <option value="<?php echo trim('api_fare_type'); ?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'api_fare_type') {
                                 echo "selected";

                              } ?>>API Fare Type
                           </option>
                           <option value="<?php echo trim('api_supplier'); ?>" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'api_supplier') {
                                 echo "selected";

                              } ?>>API Supplier
                           </option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if (isset($search_bar_data['key-text'])) {
                        echo trim($search_bar_data['key-text']);

                     } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>
                           <?php if (isset($search_bar_data['key'])) {
                              echo $search_bar_data['key-text'] . " *";

                           } else {

                              echo "Value";

                           } ?>
                        </label>
                        <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                           echo $search_bar_data['value'];

                        } ?>" class="form-control" tts-validatation="Required" tts-error-msg="Please enter value" />
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">

                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i
                              class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <? if (isset($search_bar_data['key'])): ?>
                  <div class="col-md-3">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('flightfaretype'); ?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </form>
            </div>

            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <?php
               $trash_uri = "flightfaretype/remove-faretype";

               ?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formfaretypelist">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <?php if (permission_access("FlightFareType", "delete_flight_fare_type")) { ?>
                           <th><label><input type="checkbox" name="check_all" id="selectall" /></label>
                           </th>
                           <?php } ?>
                           <th>Supplier Fare Type</th>
                           <th>API Fare Type</th>
                           <th>API Supplier</th>
                           <!--<th>Color</th>-->
                           <?php if (permission_access("Setting", "edit_flight_fare_type")) { ?>
                           <th>Action</th>
                           <?php } ?>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        if (!empty($lists) && is_array($lists)) {

                           foreach ($lists as $data) { ?>
                        <tr>
                           <?php if (permission_access("Setting", "delete_flight_fare_type")) { ?>
                           <td>
                              <label><input type="checkbox" name="checklist[]" class="checkbox"
                                    value="<?php echo $data['id']; ?>" /></label>
                           </td>
                           <?php } ?>
                           <td>
                              <?php echo $data['supplier_fare_type']; ?>
                           </td>
                           <td>
                              <?php echo $data['api_fare_type']; ?>
                           </td>
                           <td>
                              <?php echo $data['api_supplier']; ?>
                           </td>
                         <!--  <td>
                              <?php echo $data['color']; ?>
                           </td>-->
                           <?php if (permission_access("Setting", "edit_flight_fare_type")) { ?>
                           <td>
                              <a href="javascript:void(0);" view-data-modal="true" data-controller='flightsettings'
                                 data-id="<?php echo dev_encode($data['id']); ?>"
                                 data-href="<?php echo site_url('/flightfaretype/edit-faretype-template/') . dev_encode($data['id']); ?>"><i
                                    class="fa-solid fa-edit "></i></a>
                           </td>
                           <?php } ?>
                        </tr>
                        <?php }
                        } else {

                           echo "<tr> <td  class='text-center'><b>No API Fare Type Found</b></td></tr>";

                        } ?>
                     </tbody>
                  </table>
               </form>
            </div>
            <div class="row pagiantion_row align-items-center">
               <div class="col-md-6 mb-3 mb-lg-0">
                  <p class="pagiantion_text">Page
                     <?= $pager->getCurrentPage() ?>
                     of
                     <?= $pager->getPageCount() ?>, total
                     <?= $pager->getTotal() ?> records found
                  </p>
               </div>
               <div class="col-md-6">
                  <?php if ($pager): ?>
                  <?= $pager->links() ?>
                  <?php endif ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
