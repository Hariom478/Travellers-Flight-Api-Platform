<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center ">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0"> Package Price List<h5>
                    </div>
                    <div class="col-md-8 text-end">


                        <?php if (permission_access("Holiday", "holiday_price_add")) { ?>

                        <button class="badge badge-wt" view-data-modal="true" data-controller='holiday'
                                data-href="<?php echo site_url("holiday/add-holiday-price-template/") . dev_encode($holiday_list_id); ?>">
                            <i class="fa-solid fa-add"></i> Add Package Price
                        </button>
                        <?php } ?>
                        <?php if (permission_access("Holiday", "holiday_price_delete")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formagentlist')"><i class="fa-solid fa-trash "></i> Delete
                        </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
            <div class="card-body">
                
                    <div class="row m0">
                        <div class="tts-col-12">
                            <div class="vi_mod_dsc">
                                <span> Package Name</span>
                                <span class="primary"> <b><?php if (isset($list[0]['holiday_name'])){ echo  $list[0]['holiday_name']; }?></b> </span>
                            </div>
                        </div>
                    </div>
                
                <div class="table-responsive">
                    <?php $trash_uri = "holiday/remove-holiday-price"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formagentlist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active"> 
                            <tr>
                                <?php if (permission_access("Holiday", "holiday_price_delete")) { ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                <?php } ?>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Standard Adult Price</th>
                                <th>Deluxe Adult Price</th>
                                <th>Luxury Adult Price</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) {
                                    if (isset($data['start_date']) && $data['start_date'] != '') {
                                        $data['start_date'] = timestamp_to_date($data['start_date']);
                                    }
                                    if (isset($data['end_date']) && $data['end_date'] != '') {
                                        $data['end_date'] = timestamp_to_date($data['end_date']);
                                    }

                                    ?>

                                    <tr>
                                   <?php if (permission_access("Holiday", "holiday_price_delete")) { ?>
                                        <td>
                                            <label><input type="checkbox" name="checklist[]" class="checkbox"
                                                          value="<?php echo $data['id']; ?>"/></label>
                                        </td>
                                        <?php } ?>


                                        <td>
                                            <?php echo $data['start_date']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['end_date']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['standard_adult_price']; ?>
                                        </td>
                                        <td><?php echo $data['deluxe_adult_price']; ?></td>
                                        <td><?php echo $data['luxury_adult_price']; ?></td>
                                        <td><?php echo date_created_format($data['created']); ?></td>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true"
                                               data-controller='holiday'
                                               data-id="<?php echo dev_encode($data['id']); ?>"
                                               data-href="<?php echo site_url('/holiday/edit-holiday-price-template/') . dev_encode($data['id']); ?>"><i
                                                        class="fa fa-edit "></i></a>
                                        </td>


                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='11' class='text-center'><b>No Package Price Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>

                    </form>

                    
                </div>

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
</div>