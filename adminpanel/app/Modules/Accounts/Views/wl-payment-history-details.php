<?php
if ($details) { ?>


	<div class="modal-header">
		<h1 class="modal-title fs-5"><? echo ' Payment '; ?>Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        
	</div>
	<div class="vewmodelhed">

		<div class="row m0">
			<div class="col-md-4">
				<div class="vi_mod_dsc">
					<span></span> <span class="primary"> <b></b> </span>
				</div>
			</div>

		</div>
	</div>
	<div class="modal-body">
		<!-- Start of agent Details  Tab Content -->
		<div id="agent_details" class="tab-content current p0">
			<table class="table table-bordered">
				<tbody class="lead_details">
				<?php
				foreach ($details as $key => $data) { if ($key == 'payment_mode') {
                    $mode = $data;
                }else{
                    $mode ='Document';
                }?>

					<?php if ($key == 'payment_date') { ?>
						<tr>
							<td>
								<span class=" item-text-head"><b><?php echo ucwords(str_replace('_', ' ', $key)); ?></b></span>
							</td>
							<td><span class="item-text-value"><?php echo date_created_format($data); ?></span>
							</td>
						</tr>
					<?php } else { ?>
                        <?php if ($key =='file_name' && $data!=''){ ?>



                            <tr>
                                <td>
                                    <span class=" item-text-head"><b><?php echo ucwords(str_replace('_', ' ', $key)); ?></b></span>
                                </td>
                                <td><span class="item-text-value">
                                <a href="<?php echo root_url . "uploads/make_payment/" . $data; ?>"
                                   target="_blank"><?php echo 'View ' . $mode; ?>
                            </span>
                                </td>
                            </tr>

                        <?php } else { ?>
                            <tr>
                                <td>
                                    <span class=" item-text-head"><b><?php echo ucwords(str_replace('_', ' ', $key)); ?></b></span>
                                </td>
                                <td><span class="item-text-value"><?php if ($key == 'created') {
                                            echo date_created_format($data);
                                        } else {
                                            echo $data;
                                        } ?></span>
                                </td>
                            </tr>
                        <?php }
                    }
                } ?>
				</tbody>
			</table>
		</div>
	</div>
<?php } else {
	echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>