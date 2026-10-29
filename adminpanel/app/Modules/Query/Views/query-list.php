<div class="content ">
    <div class="page-content">
        <div class="table_title">
            <div class="sale_bar">
                <div class="row align-items-center">
                    <div class="col-md-4 mb-3 mb-lg-0">
                        <h5 class="m0"> Contact Us</h5>
                    </div>
                    <div class="col-md-8 text-md-end">  

                        <?php if ($pager->getTotal()!=0) : ?>
                        <button class="badge badge-wt" onclick="ttsopenmodel('tts_export_modal')"> <i class="fa-solid fa-download"></i> Export </button>
                        <?php endif ?>

                        <?php if (permission_access("Slider", "delete_slider")) { ?>
                        <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                                onclick="confirm_delete('formsliderlist')"><i class="fa-solid fa-trash"></i> Delete
                        </button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">


            <div class="card-body">

                <div class="table-responsive">
                    <?php $trash_uri = "query/remove-query"; ?>
                    <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true"
                          id="formsliderlist">
                        <table class="table table-bordered table-hover">
                            <thead class="table-active">
                            <tr> 
                                <th><label><input type="checkbox" name="check_all" id="selectall"/></label></th>  
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th> 
                                <th>Created Date</th>  

                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            if (!empty($list) && is_array($list)) {
                                foreach ($list as $data) { 
                                    ?> 
                                    <tr>
                                        <td> <label><input type="checkbox" name="checklist[]" class="checkbox"value="<?php echo $data['id']; ?>"/></label> </td> 
                                        <td> <?php echo ucfirst($data['name']); ?> </td>
                                        <td> <?php echo ucfirst($data['email']); ?> </td>
                                        <td><?php echo ucfirst($data['subject']); ?></td>
                                        <td><?php echo ucfirst($data['message']); ?></td> 

                                        <td><?php echo date_created_format($data['created_date']); ?></td>  
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr> <td colspan='6' class='text-center'><b>No Query Found</b></td></tr>";
                            } ?>
                            </tbody>
                        </table> 
                    </form>

                </div>
                    <div class="row pagiantion_row align-items-center">
                        <div class="col-md-6 mb-3 mb-lg-0">
                            <p class="pagiantion_text">Page  <?= $pager->getCurrentPage() ?> of <?= $pager->getPageCount() ?>, total <?= $pager->getTotal() ?> records found </p>
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



<!-- start export modal content -->
<div id="tts_export_modal" class="modal fade" tabindex="1">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Export Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <form action="<?php echo site_url('query/export-query'); ?>" method="post" tts-form="true">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>From Date *</label>
                        <input class="form-control" type="text" name="from_date" data-export-from="true" placeholder="Select From Date" readonly>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>To Date *</label>
                        <input class="form-control borl0" type="text" name="to_date" data-export-to="true" placeholder="Select To Date" readonly>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit">Export</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- end export modal content -->
 
