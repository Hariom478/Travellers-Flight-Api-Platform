<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5 class="m0">Super Admin Flight Top Routes </h5>
                    </div>
                    <div class="col-md-8 text-end">
                <?php if (permission_access_error("Flight", "add_flight_top_routes")) { ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='flight'
                                data-href="<?php echo site_url('flight-top-routes/flight-top-routes-view') ?>"><i
                                    class="fa fa-add"></i> Add Flight Top Routes
                        </button> 
                        <?php }?>
                        <?php if (permission_access_error("Flight", "flight_top_routes_status_change")) { ?>
                        <button class="badge badge-wt" onclick="confirm_change_status('status_change')"><i
                                    class="fa fa-exchange"></i> Change Status
                        </button>
                        <?php } ?>

                        <?php if (permission_access_error("Flight", "remove_top_routes_List")) { ?>
                      
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formdiscountlist')"><i class="fa-solid fa-trash"></i> Delete
                        </button>

                        <?php } ?>
                      
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body"> 
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('flight-top-routes'); ?>" method="GET" class="tts-dis-content" name="discount-search" onsubmit="return searchvalidateForm()">
                    <div class="row ">  
                    <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label>Select key to search by *</label>
                                <select name="key" class="form-select" onchange="tts_searchkey(this,'discount-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                    <option value="">Please select</option>
                                    <option value="origin_code" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='origin_code'){ echo "selected";} ?>>Origin Code</option>
                                    <option value="destination_code" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='destination_code'){ echo "selected";} ?>>Destination Code</option>
                                    <option value="journeytype" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='journeytype'){ echo "selected";} ?>  >Journey Type</option>  

                                     
                                </select>
                            </div>
                            <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-mb-20">
                                <label><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                                <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                            </div>
                        </div>
                        
                         
                        <div class="col-md-4 align-self-end">
                            <div class="form-group form-mb-20">
                                <label></label><br />
                                <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <div class="col-md-2">
                        <? if(isset($search_bar_data['key'])): ?>
                            
                                <div class="search-reset-btn">
                                    <a href="<?php echo site_url('flight-top-routes');?>">Reset Search</a>
                                </div>
                           
                        <? endif ?>
                         </div>
                         </div>
                    </form>
               

                <!----------End Search Bar ----------------->

                
                    <?php
                    $trash_uri = "flight-top-routes/removed-flight-top-routes";
                    ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formdiscountlist">
                       <div class="table-responsive"> 
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <?php if (permission_access("Flight", "remove_top_routes_List") || permission_access("Flight", "flight_top_routes_status_change")) { ?>

                                    <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php }?>

                                <th>Direct Flight</th>
                                <th>Origin</th>
                                <th>Origin Code</th>
                                <th>Destination</th>
                                <th>Destination Code</th>
                                <th>Journey Type</th>
                                <th>Depart Date</th>
                                <th>Return Date</th>
                                <th>Adult</th>
                                <th>Child</th>
                                <th>Infant</th>
                                <th>Cabin Class</th>
                                <th>Price</th>
                               
                                <th>Status</th>
                               
                                <?php if (permission_access("Flight", "edit_flight_top_routes")) { ?>
                                <th>Action</th>
                               <?php }?>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($list) && is_array($list)) {

                                foreach ($list as $data) {

                                    if ($data['status'] == 'active') {
                                        $class = 'active-status';
                                    } else {
                                        $class = 'inactive-status';
                                    } 
                                    ?>
                                    <tr>
                                        <?php if (permission_access("Flight", "remove_top_routes_List") || permission_access("Flight", "flight_top_routes_status_change")) { ?>
                                        
                                            <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php } ?>
                                        <td><?php echo ucfirst($data['direct_flight']); ?></td>
                                        <td><?php echo ucfirst($data['origin']); ?></td>
                                        <td><?php echo ucfirst($data['origin_code']); ?></td>
                                        <td><?php echo ucfirst($data['destination']); ?></td>
                                        <td><?php echo ucfirst($data['destination_code']); ?></td>
                                        <td><?php echo ucfirst($data['journeytype']); ?></td>
                                        <td><?php echo ucfirst($data['depart_date']); ?></td>
                                        <td><?php echo ucfirst($data['return_date']); ?></td>
                                        <td><?php echo ucfirst($data['adult']); ?></td>
                                        <td><?php echo ucfirst($data['child']); ?></td>
                                        <td><?php echo ucfirst($data['infant']); ?></td>
                                        <td> <?php echo $data['cabin_class']; ?> </td> 
                                        <td> <?php echo $data['price']; ?> </td> 
                                        <td>
                                            <span class="<?php echo $class ?>">
                                            <?php echo ucfirst($data['status']); ?>
                                            </span>
                                        </td>
                                       <?php if (permission_access("Flight", "edit_flight_top_routes")) { ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true" data-controller='flight' data-id="<?php echo dev_encode($data['id']); ?>" data-href="<?php echo site_url('/flight-top-routes/edit-flight-top-routes-template/') . dev_encode($data['id']); ?>"><i class="fa-solid fa-edit"></i></a>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='15' class='text-center'><b>No Flight Discount Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>
                        </div> 
                    </form>

               
                <div class="row pagiantion_row align-items-center">
                            <div class="col-md-6 mb-3 mb-lg-0">
                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                    of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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


<!-- status status change content -->
<div id="status_change" class="modal fade"  tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <form action="<?php echo site_url('flight-top-routes/flight-top-routes-change-routes'); ?>" method="post" tts-form="true"
              name="form_change_status">
            
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-mb-20"> 
                            <select class="form-select" name="status">
                                <option value="" selected="selected">Select Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <input type="hidden" name="checkedvalue">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="submit" >Save</button>
                
            </div>
            </form>
        </div>
    </div>
</div>