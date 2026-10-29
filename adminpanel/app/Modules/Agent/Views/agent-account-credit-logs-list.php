<div class="content ">
   <div class="page-content">
      <div class="table_title">
         <div class="sale_bar">
            <div class="row align-items-center">
               <div class="col-md-4">
                  <h5 class="m-0"> Credit Limit Balance Sheet </h5>
               </div>
            </div>
         </div>
      </div>
      <div class="card">
         <div class="card-body">


             <div class="vewmodelhed mb-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="vi_mod_dsc">
                                <span> Company Name</span>
                                <span class="primary"> <b><?php echo $details['company_name']; ?></b> </span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="vi_mod_dsc">
                                <span>Agent Name</span>
                                <span class="primary"> <b><?php echo $details['title'] . ' ' . $details['first_name'] . ' ' . $details['last_name']; ?></b> </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="vi_mod_dsc">
                                <span>Agent Email </span>
                                <span class="primary"> <b><?php echo $details['login_email'] ?> </b> </span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="vi_mod_dsc">
                                <span>Agent Mobile No</span>
                                <span class="primary"> <b><?php echo $details['mobile_no']; ?> </b> </span>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="row mb_10">
               <!----------Start Search Bar ----------------->
               <form action="<?php echo site_url('agent/agent-account-credit-logs/' . dev_encode($details['id'])); ?>" method="GET" class="row" name="agent-account-credit-search" onsubmit="return searchvalidateForm()">
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label>Select key to search by *</label>
                        <select name="key" class="form-control" onchange="tts_searchkey(this,'agent-account-credit-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                           <option value="">Please select</option>    
                           <option value="credit_limit" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'credit_limit') {
                              echo "selected";
                              
                              } ?> >Credit Limit
                           </option>
                           <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                              echo "selected";
                              
                              } ?>>Date Range
                           </option>
                        </select>
                     </div>
                     <input type="hidden" name="key-text" value="<?php if(isset($search_bar_data['key-text'])){ echo trim($search_bar_data['key-text']); } ?>">
                  </div>
                  <div class="col-md-3">
                     <div class="form-group form-mb-20">
                        <label><?php if(isset($search_bar_data['key']) && $search_bar_data['key']!='date-range') { echo $search_bar_data['key-text']. " *"; } else { echo "Value"; } ?> </label>
                        <input type="text" name="value" placeholder="Value"  value="<?php if(isset($search_bar_data['value'])){ echo $search_bar_data['value']; } ?>" class="form-control" <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') { echo "disabled"; } ?> <?php if(isset($search_bar_data['key']) && $search_bar_data['key']=='date-range') {  } else { echo 'tts-validatation="Required"'; } ?>   tts-error-msg="Please enter value" />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if(isset($search_bar_data['from_date'])){ echo $search_bar_data['from_date']; } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                     </div>
                  </div>
                  <div class="col-md-2">
                     <div class="form-group form-mb-20">
                        <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if(isset($search_bar_data['to_date'])){ echo $search_bar_data['to_date']; } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
                     </div>
                  </div>
                  <div class="col-md-2 align-self-end">
                     <div class="form-group form-mb-20">
                        
                        <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                     </div>
                  </div>
                  <? if(isset($search_bar_data['key'])): ?>
                  <div class="col-md-3 mb-3">
                     <div class="search-reset-btn">
                        <a href="<?php echo site_url('agent/agent-account-credit-logs/' . dev_encode($details['id']));?>">Reset Search</a>
                     </div>
                  </div>
                  <? endif ?>
               </form>
            </div>
            <!----------End Search Bar ----------------->
            <div class="table-responsive">
               <form action="" method="POST" id="formagentlist">
                  <table class="table table-bordered table-hover">
                     <thead class="table-active">
                        <tr>
                           <th>Credit limit</th>
                           <th>Credit Expire</th>
                           <th>Credit Expire Date</th>
                           <th>Remark</th>
                           <th>Created</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (!empty($account_logs)) {
                           foreach ($account_logs as $data) {
                           
                           ?>
                        <tr>
                           <td>
                              <?php echo custom_money_format($data['credit_limit']); ?>
                           </td>
                           <td>
                              <?php echo $data['credit_expire']; ?>
                           </td>
                           <td>
                              <?php echo date('d  M  Y', $data['credit_expire_date']); ?>
                           </td>
                           <td>
                              <?php echo $data['remark']; ?>
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
                           echo "<tr> <td colspan='5' class='text-center'><b>No Account Logs Found</b></td></tr>";
                           } ?>
                     </tbody>
                  </table>
               </form>
            </div>
            <div class="row pagiantion_row align-items-center">
               <div class="col-md-6">
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