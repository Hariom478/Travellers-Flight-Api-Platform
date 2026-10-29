<?php
if ($data) { ?>

    <div class="modal-header">
        <h5 class="modal-title">
            <? echo 'Remark'; ?>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        </button>
    </div>

    <div class="modal-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-active">
                    <tr>
                        <th>Remark</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php
                            if ($data['action_type'] == 'booking') {
                                if ($data['service_log']) {
                                    $service_log = json_decode($data['service_log'], true);

                                    echo "Booking Info : " . service_log($data['service'], $data['action_type'], $service_log);
                                } else {
                                    echo "Booking Info : " . ucfirst($data['service']) . ' ' . ucfirst($data['action_type']);
                                }
                            } else {
                                echo "Action Type: " . ucfirst($data['action_type']);
                            }
                            ?>
                            </b><br />
                            <?php echo $data['remark']; ?>
                            <br />
                            <?php echo "<b>Transaction Type  : </b>" . ucfirst($data['transaction_type']); ?>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    <?php } else {
    echo "<p class='text_center'>No data is available. Please try again later</p>";

} ?>