<div class="modal-header">
   <h5 class="modal-title">Add Holiday Gallery</h5>
   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
   <form action="<?php echo site_url('holiday/add-holiday-gallery/') . dev_encode($holiday_list_id); ?>" method="post" tts-form="true" name="add_class" enctype="multipart/form-data">
      <div class="row">
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Image Title</label>
               <input class="form-control" type="text" name="image_title" placeholder="Image Title">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Image * </label>
               <input class="form-control" type="file" name="images" placeholder="Image">
            </div>
         </div>
         <div class="col-md-4">
            <div class="form-group form-mb-20">
               <label>Status *</label>
               <select class="form-select" name="status" placeholder="Status">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
               </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
               <!-- <input class="badge badge-md badge-primary" type="submit" value="Save"> -->
               <button type="submit" class="btn btn-primary">Submit</button>
            </div>
         </div>
      </div>
   </form>
   <div class="content ">
      <div class="page-actions-panel">
         <div class="row">
            <div class="col-md-4">
               <h5 class="m0">Holiday Gallery List</h5>
            </div>
            <div class="col-md-8 text-end">
               <button class="badge badge-wt" onclick="confirm_change_status('gallery_status_change')">
               <i class="fa-solid fa-exchange"></i> Change Status
               </button>
               <button class="badge badge-danger badge-wt danger disable_badge" tts-disable_badge
                  onclick="confirm_delete('formlist')"><i class="fa-solid fa-trash"></i> Delete
               </button>
            </div>
         </div>
      </div>
      <div class="page-content p-0">
         <div class="page-content-area">
            <div class="card-body" style="padding: unset;">
               <?php $trash_uri = "holiday/remove-holiday-gallery"; ?>
               <form action="<?php echo site_url($trash_uri); ?>" method="POST" tts-form="true" id="formlist">
                  <div class="table-responsive" tts-table-html="true">
                     <table class="table table-bordered table-hover">
                        <thead class="table-active">
                           <tr>
                              <th>  </th>
                              <th>Image</th>
                              <th>Title</th>
                              <th>Status</th>
                              <th>Created</th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php
                              if (!empty($list) && is_array($list)) {
                                  foreach ($list as $data) {
                                      if ($data['status'] == 'active') {
                                          $status_class = 'active-status';
                                      } else {
                                          $status_class = 'inactive-status';
                                      }
                                      ?>
                           <tr>
                              <td>
                                 <label><input type="checkbox" name="checklist[]"
                                    class="checkbox"
                                    value="<?php echo $data['id']; ?>"/></label>
                              </td>
                              <td>
                                 <img src="<?php echo root_url . "uploads/holiday/thumbnail/" . $data['images']; ?>"
                                    alt="<?php echo $data['image_title']; ?>"
                                    class="tts-blog-image">
                              </td>
                              <td>
                                 <?php echo ucfirst($data['image_title']); ?>
                              </td>
                              <td>
                                 <div class="<?php echo $status_class ?>">
                                    <?php echo ucfirst($data['status']); ?>
                                 </div>
                              </td>
                              <td>
                                 <?php echo date_created_format($data['created']); ?>
                              </td>
                           </tr>
                           <?php }
                              } else {
                                  echo "<tr class='text-center'> <td colspan='11' class='text-center'><b>No Holiday Gallery Found</b></td></tr>";
                              } ?>
                        </tbody>
                     </table>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>