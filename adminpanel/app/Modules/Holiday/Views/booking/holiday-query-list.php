<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row aling-items-center">
                    <div class="col-md-4">
                        <h5 class="m0"> Holiday Query List</h5>
                    </div>
                    <div class="col-md-8 text-md-right">

                    </div>
                </div>
            </div>
            <div class="page-content-area">
                <div class="card-body">
                  
                        <!----------Start Search Bar ----------------->
                        <form action="<?php echo site_url('holiday/query-list'); ?>" method="GET" class="row" name="holiday-search" onsubmit="return searchvalidateForm()">
                            <div class="col-md-3">
                                <div class="form-group form-mb-20">
                                    <label>Select key to search by *</label>
                                    <select name="key" class="form-select" onchange="tts_searchkey(this,'holiday-search')" tts-validatation="Required" tts-error-msg="Please select search key">
                                        <option value="">Please select</option>

                                        <option value="booking_ref_number" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_ref_number') {
                                            echo "selected";
                                        } ?> >Ref No
                                        </option>


                                        <option value="first_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'first_name') {
                                            echo "selected";
                                        } ?> >First Name
                                        </option>
                                        <option value="last_name" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'last_name') {
                                            echo "selected";
                                        } ?> >Last Name
                                        </option>


                                        <option value="booking_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'booking_status') {
                                            echo "selected";
                                        } ?> >Booking Status
                                        </option>
                                        <option value="payment_status" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'payment_status') {
                                            echo "selected";
                                        } ?> >Payment Status
                                        </option>

                                        <option value="date-range" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                            echo "selected";
                                        } ?>>Date Range
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
                                    <input type="text" name="value" placeholder="Value" value="<?php if (isset($search_bar_data['value'])) {
                                        echo $search_bar_data['value'];
                                    } ?>" class="form-control" <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                        echo "disabled";
                                    } ?> <?php if (isset($search_bar_data['key']) && $search_bar_data['key'] == 'date-range') {
                                    } else {
                                        echo 'tts-validatation="Required"';
                                    } ?> tts-error-msg="Please enter value"/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-mb-20">
                                    <label>From Date</label><input type="text" data-searchbar-from="true" name="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                        echo $search_bar_data['from_date'];
                                    } ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-mb-20">
                                    <label>To Date</label><input type="text" data-searchbar-to="true" name="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                        echo $search_bar_data['to_date'];
                                    } ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly/>
                                </div>
                            </div>
                            <div class="col-md-2 align-self-end">
                                <div class="form-group form-mb-20">
                                    
                                    <button type="submit" class="badge badge-md badge-primary badge_search">Search <i class="fa fa-search"></i></button>
                                </div>
                            </div>
                             
                            <? if (isset($search_bar_data['key'])): ?>
                               <div class="col-md-3 mb-3">
                                    <div class="search-reset-btn">
                                        <a href="<?php echo site_url('holiday/query-list'); ?>">Reset Search</a>
                                    </div>
                                 </div>
                            <? endif ?>
                           
                        </form>
                  


                    <!----------End Search Bar ----------------->

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr>
                                <th>Sr No.</th>
								<th>Web Partner</th>
                                <th>Name</th>
								<th>Email</th>
								<th>Mobile</th>
								<th>Travel Date</th>
								<th>Travellers</th>
								<th>Nights</th>
                                <th>Package</th>
								<th>Message</th>
                                <th>Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $key=>$data) {?>
                                    <tr>
                                        <td><?php echo $key+1; ?></td>
										<td><?php echo $data['company_name'];?></td>
                                        <td>
                                            <?php
                                            $name = ucfirst($data['name']);
                                            echo $name;
                                            ?>
                                        </td>

                                        <td><?php echo $data['email']; ?></td>
                                        <td><?php echo $data['mobile']; ?></td>
                                        <td><?php echo $data['travel_date']; ?></td>


                                        <td><?php echo $data['no_of_person']; ?></td>


                                        <td><?php echo $data['no_of_nights']; ?></td>
                                        <td>

                                                <?php echo $data['holiday_name']; ?>

                                        </td>
										<td>

											<?php echo $data['comment']; ?>

										</td>
                                        <td>
                                            <?php echo date_created_format($data['created']); ?>
                                        </td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text-center'><b>No Query Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="row pagiantion_row aling-items-center">
                            <div class="col-md-6 mb-3 mb-lg-0">
                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                                           of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                            </div>
                            <div class="col-md-6">
                                <?php if ($pager) : ?>
                                    <?= $pager->links() ?><?php endif ?>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

