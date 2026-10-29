<div class="content ">
	<div class="page-content">
		<div class="table_title">
			<div class="sale_bar">
				<div class="tts_row">
					<div class="tts-col-3">
						<span> <?php echo $title . ' '; ?>List</span>
					</div>
					<div class="tts-col-9 text_right">
						<!--       <?php /*if (permission_access("BankAccounts", "add_bank_accounts")) { */ ?>
                        <button class="badge badge-wt" view-data-modal="true" data-controller='bankaccounts'
                                data-href="<?php /*echo site_url('bankaccounts/add-account-template') */ ?>"><i
                                    class="tts-icon add "></i> Add Bank Account
                        </button>
                        --><?php /*}*/ ?>
						<?php /*if (permission_access("BankAccounts", "delete_bank_accounts")) { */ ?><!--
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formbankaccountslist')"><i class="tts-icon delete "></i> Delete
                        </button>
                        --><?php /*}*/ ?>

					</div>
				</div>
			</div>
		</div>
		<div class="page-content-area">


			<div class="card-body">


				<div class="table-responsive">
					<?php

					$trash_uri = "bankaccounts/remove-account";
					?>
					<form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formbankaccountslist">
						<table class="table table-bordered table-hover">
							<thead class="table-active">
							<tr>
								<!-- <?php /*if (permission_access("BankAccounts", "delete_bank_accounts")) { */ ?>
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label>
                                </th>
                                --><?php /*}*/ ?>

								<th>Bank Name</th>
								<th>Branch Name</th>
								<th>Account Holder Name</th>
								<th>Account No</th>
								<th>IFSC Code</th>
								<th>SWIFT Code</th>
								<!--<th>Created Date</th>-->
								<?php /*if (permission_access("BankAccounts", "edit_bank_accounts")) { */ ?><!--
                                <th>Action</th>
                                --><?php /*}*/ ?>

							</tr>
							</thead>
							<tbody>
							<?php

							if (!empty($list) && is_array($list)) {
								foreach ($list as $data) {

									?>

									<tr>
										<?php /*if (permission_access("BankAccounts", "delete_bank_accounts")) { */?><!--
											<td>
												<label><input type="checkbox" name="checklist[]" class="checkbox" value="<?php /*echo $data['id']; */?>"/></label>
											</td>
										--><?php /*} */?>

										<td>
											<?php echo ucfirst($data['bank_name']); ?></a>
										</td>
										<td><?php echo ucfirst($data['branch_name']); ?></td>
										<td><?php echo ucfirst($data['account_holder_name']); ?></td>
										<td>
											<?php echo $data['account_no']; ?>
										</td>
										<td>
											<?php echo $data['ifsc_code']; ?>
										</td>
										<td>
											<?php echo $data['swift_code']; ?>
										</td>

										<!--<td><?php /*echo date_created_format($data['created']); */?></td>-->

										<!-- <?php /*if (permission_access("BankAccounts", "edit_bank_accounts")) { */ ?>
                                        <td>
                                            <a href="javascript:void(0);" view-data-modal="true"
                                               data-controller='bankaccounts'
                                               data-id="<?php /*echo dev_encode($data['id']); */ ?>"
                                               data-href="<?php /*echo site_url('/bankaccounts/edit-bank-template/') . dev_encode($data['id']); */ ?>"><i
                                                        class="tts-icon edit "></i></a>
                                        </td>
                                        --><?php /*}*/ ?>
									</tr>
								<?php }
							} else {
								echo "<tr> <td colspan='11' class='text_center'><b>No Bank Account Found</b></td></tr>";
							} ?>
							</tbody>
						</table>

					</form>
				</div>
				<div class="tts_row">
						<div class="tts-col-6">
							<p class="pagiantion_text">Page <?= $pager->getCurrentPage() ?>
													   of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
						</div>
						<div class="tts-col-6">
							<?php if ($pager) : ?>
								<?= $pager->links() ?><?php endif ?>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>

<div id="view_bankaccounts" class="modal">
	<div class="modal-content" data-modal-view="view_modal_data"></div>
</div>