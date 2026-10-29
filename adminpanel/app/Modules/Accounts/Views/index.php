<style>
    .error-message{
        top: unset !important;
    }
</style>
<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row gy-4 align-items-center">
                    <div class="col-md-4">
                        <h5 class="m-0">Web Partner Accounts</h5>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="page-content-area">

            <div class="card-body">
                <div class="row">
                    <!----------Start Search Bar ----------------->
                    <form action="<?php echo site_url('webpartneraccounts'); ?>" method="GET" class="tts-dis-content" name="web-partner-search" onsubmit="return searchvalidateForm()">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label"> Search by /Companyid/Company Name/ Web Partner Name *</label>
                                <input type="text"  class="form-control"  name="search-text" value="<?php if (isset($search_bar_data['search-text'])) {
                                                                            echo trim($search_bar_data['search-text']);
                                                                        } ?>" tts-get-web-partner-info="true"   tts-validatation="Required" tts-error-msg="Please enter search type" placeholder  =  "Search by /Companyid/Company Name/ Web Partner Name"> 
                                                                         <input type="hidden" name="key-value"  tts-web-partner-info-id = "true" value="<?php if (isset($search_bar_data['key-value'])) {
                                                                            echo trim($search_bar_data['key-value']);
                                                                        } ?>">
                            </div>
                           
                        </div>
                        <div class="tts-col-1">
                            <div class="form-group">
                                
                                <button type="submit" class="badge badge-md badge-primary">Search</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!----------End Search Bar ----------------->
                <?php if ($webpartnerInfo) {  ?>
                    <div class="table-responsive">
                       
                        <form action="<?php echo site_url("webpartneraccounts/web-account-info/" . dev_encode($webpartnerInfo['id'])); ?>" method="POST" tts-form="true" name="web-partner-accounts">
                            <table class="table table-bordered">
                                <tbody class="lead_details">
                                    <tr>
                                        <th scope="row"><span class=" item-text-head"><b>Name</b></span></th>
                                        <td>
                                            <span class="item-text-value">
                                                <?php echo $webpartnerInfo['pan_name']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head"><b>Company Name</b></span></th>
                                        <td>
                                            <span class="item-text-value">
                                                <?php echo $webpartnerInfo['company_name']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row"><span class=" item-text-head"><b>Available Balance</b></span></th>
                                        <td>
                                            <span class="item-text-value">
                                                <?php
                                                if (isset($webpartnerInfo['balance'])) {
                                                    echo $webpartnerInfo['balance'];
                                                }
                                                ?>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                                    <tbody class="lead_details">
                                  
                                        <tr>
                                            <th scope="row"><span class=" item-text-head"><b>Transaction Type</b></span></th>
                                            <td>
                                                <label>
                                                    <input type="radio" name="transaction_type" tts-from-any="true" value="debit" class="Lead" tts-validatation="Required" tts-error-msg="Please select transaction type" checked>Debit
                                                </label>
                                                <label>
                                                    <input type="radio" name="transaction_type" tts-from-any="true" value="credit" tts-validatation="Required" tts-error-msg="Please select transaction type" class="Lead">Credit
                                                </label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><span class=" item-text-head"><b>Action Type *</b></span></th>
                                            <td>
                                                <select name="action_type" class="form-control" tts-validatation="Required" tts-error-msg="Please select action type">
                                                    <option value="">Please select</option>
                                                    <option value="refund">Refund</option>
                                                    <option value="deduct">Deduct</option>
                                                    <option value="recharge">Recharge</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><span class=" item-text-head"><b>Service</b></span></th>
                                            <td>
                                                <select name="service" class="form-select" tts-error-msg="Please select service">
                                                    <option value="">Please select</option>
                                                    <option value="flight">Flight</option>
                                                    <option value="hotel">Hotel</option>
                                                    <option value="holiday">Holidays</option>
                                                    <option value="cruise">Cruise</option>
                                                    <option value="visa">Visa</option>
                                                    <option value="Car">car</option>
                                                    <option value="bus">Bus</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><span class=" item-text-head"><b>Service Booking Reference Number</b></span></th>
                                            <td>
                                                <input class="form-control" type="text" name="booking_reference_number" placeholder="Service booking reference number">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><span class=" item-text-head"><b>Amount*</b></span></th>
                                            <td>
                                            <input class="form-control" type="text" name="amount" tts-validatation="Required" tts-error-msg="Please enter amount" placeholder="Amount">
                                  
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><span class=" item-text-head"><b>Remark*</b></span></th>
                                            <td>
                                                <textarea class="form-control" type="file" name="remark" placeholder="Remark" rows="2" spellcheck="false" tts-validatation="Required" tts-error-msg="Please enter remark"></textarea>
                                            </td>
                                        <tr>
                                            <th scope="row"><span class=" item-text-head"><b>Update Amount</b></span></th>
                                            <td><input class="badge badge-md badge-primary" type="submit" value="Update  Amount"></td>
                                        </tr>
                                        </form>
                                    </tbody>
                              
                            </table>
                        </div>
                    </div>
                <?php  } ?>

            </div>
        </div>
    </div>
</div>