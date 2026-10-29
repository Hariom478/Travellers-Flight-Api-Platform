<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Customer Account Logs</span>
                    </div>
                    <div class="tts-col-10 text_right">
                        <a href="<?php echo site_url('customer') ?>" class="badge badge-wt"><i class="tts-icon list"></i> Customer List</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">


            <div class="card-body">
                <div class="vewmodelhed mb_10">

                    <div class="tts_row m0">

                        <div class="tts-col-3">
                            <div class="vi_mod_dsc">
                                <span>Name</span>
                                <span class="primary"> <b><?php echo ucfirst($details['title']) . ' ' . ucfirst($details['first_name']) . ' ' . ucfirst($details['last_name']); ?></b> </span>
                            </div>
                        </div>
                        <div class="tts-col-3">
                            <div class="vi_mod_dsc">
                                <span>Email </span>
                                <span class="primary"> <b><?php echo $details['email_id'] ?> </b> </span>
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="vi_mod_dsc">
                                <span>Mobile No</span>
                                <span class="primary"> <b><?php echo $details['mobile_no']; ?> </b> </span>
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="vi_mod_dsc">
                                <span>Status</span>
                                <span class="primary"> <b><?php echo ucfirst($details['status']); ?> </b> </span>
                            </div>
                        </div>
                        <div class="tts-col-2">
                            <div class="vi_mod_dsc">
                                <span>Available Balance</span>
                                <span class="primary">
                                    <b>
                                        <?php
                                        if(isset( $details['balance'])) {
                                            echo $details['balance'];
                                        }
                                        ?>
                                    </b>
                                </span>
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

