<?php
if ($details) { ?>


	<div class="modal-header">
		<h1 class="modal-title fs-5"><? echo ' Payment '; ?>Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	</div>
	

		<div class="row m0">
			<div class="col-md-2">
				<div class="vi_mod_dsc">
					<span></span> <span class="primary"> <b></b> </span>
				</div>
			</div>

		</div>
	
	<div class="modal-body">
		<!-- Start of agent Details  Tab Content -->
		<div id="agent_details" class="tab-content current p0">
			<div class="table-responsive">
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
								<th scope="row">
									<span class=" item-text-head"><?php echo ucwords(str_replace('_', ' ', $key)); ?></span>
								</th>
								<td><span class="item-text-value"><?php echo date_created_format($data); ?></span></td>
							</tr>
						<?php } else { ?>
	                        <?php if ($key =='file_name' && $data!=''){ ?>



	                            <tr>
	                                <th scope="row">
	                                    <span class=" item-text-head"><?php echo ucwords(str_replace('_', ' ', $key)); ?></span>
	                                </th>
	                                <td><span class="item-text-value">
	                                <a href="<?php echo root_url . "uploads/make_payment/" . $data; ?>"
	                                   target="_blank"><?php echo 'View ' . $mode; ?>
	                            </span>
	                                </td>
	                            </tr>

	                        <?php } else { ?>
	                            <tr>
	                                <th scope="row">
	                                    <span class=" item-text-head"><?php echo ucwords(str_replace('_', ' ', $key)); ?></span>
	                                </th>
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
	</div>
	<div class="modal-footer">
		<div class="row">
			<div class="tts-col-12">

			</div>
		</div>
	</div>
<?php } else {
	echo "<p class='text_center'>No data is available. Please try again later</p>";
} ?>