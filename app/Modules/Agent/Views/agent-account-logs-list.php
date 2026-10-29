<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Agent Account Logs</span>
                    </div>
                    <div class="tts-col-10 text_right">
                        <a href="<?php echo site_url('agent') ?>" class="badge badge-wt"><i class="tts-icon list"></i> Agent List</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">


            <div class="card-body">
                <div class="vewmodelhed mb_10">

                    <div class="tts_row m0">
                        <div class="tts-col-2">
                            <div class="vi_mod_dsc">
                                <span> Company Name</span>
                                <span class="primary"> <b><?php echo $details['company_name']; ?></b> </span>
                            </div>
                        </div>
                        <div class="tts-col-3">
                            <div class="vi_mod_dsc">
                                <span>Agent Name</span>
                                <span class="primary"> <b><?php echo $details['title'] . ' ' . $details['first_name'] . ' ' . $details['last_name']; ?></b> </span>
                            </div>
                        </div>
                        <div class="tts-col-4">
                            <div class="vi_mod_dsc">
                                <span>Agent Email </span>
                                <span class="primary"> <b><?php echo $details['email_id'] ?> </b> </span>
                            </div>
                        </div>
                        <div class="tts-col-3">
                            <div class="vi_mod_dsc">
                                <span>Agent Mobile No</span>
                                <span class="primary"> <b><?php echo $details['mobile_number']; ?> </b> </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="responcive_table table_box_shadow">

                        <table class="table-strip divName">
                            <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Balance</th>
                                <th>Remark</th>
                                <th>Created	</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count=1;
                            if (!empty($account_logs) && is_array($account_logs)) {
                                foreach ($account_logs as $data) {

                                   ?>

                                    <tr>
                                        <td><?php echo $count;?></td>
                                        <td>
                                            <?php echo $data['credit']; ?>
                                        </td>
                                        <td>
                                            <?php echo $data['debit']; ?>
                                        </td>
                                        <td><?php echo $data['balance']; ?></td>

                                        <td>
                                            <?php echo $data['remark']; ?>
                                        </td>

                                        <td><?php
                                            if(isset($data['created'])) {
                                                echo date_created_format($data['created']);
                                            }
                                            ?></td>
                                    </tr>
                                <?php $count++; }
                            } else {
                                echo "<tr> <td colspan='11' class='text_center'><b>No Account Logs Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table>


                    <div class="d-flex justify-content-end">
                        <div class="tts_row">
                            <div class="tts-col-6">
                                <p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
                                    of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
                            </div>
                            <div class="tts-col-6">
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
</div>


<div id="view_agent" class="modal">
    <div class="modal-content" data-modal-view="view_modal_data"></div>
</div>

