<section class="dashboard-section d-flex align-items-center ">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="table_title">
               <div class="sale_bar">
                  <div class="row">
                     <div class="col-md-4">
                        <h5 class="m-0"> Invoice Report</h5>
                     </div>
                  </div>
               </div>
               <div class="card">
                  <div class="card-body">
                     <!----------Start Search Bar ----------------->
                     <form action="<?php echo site_url('invoice/get-report'); ?>" method="POST" class=" mb-3 tts-dis-content" tts-form='true' id="invoice-export" name="invoice-search" onsubmit="return searchvalidateForm()">
                        <div class="row align-items-center gy-3">
                           <div class="col-md-3">
                              <div class="form-group form-mb-20">
                                 <label class="form-label">From Date</label><input type="text" data-searchbar-from="true" name="from_date" id="from_date" value="<?php if (isset($search_bar_data['from_date'])) {
                                                                                                                                                                     echo $search_bar_data['from_date'];
                                                                                                                                                                  } else {
                                                                                                                                                                     echo date('d M Y');
                                                                                                                                                                  }  ?>" placeholder="Select From Date" class="form-control" tts-error-msg="Please select from date" readonly />
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group form-mb-20">
                                 <label class="form-label">To Date</label><input type="text" data-searchbar-to="true" name="to_date" id="to_date" value="<?php if (isset($search_bar_data['to_date'])) {
                                                                                                                                                            echo $search_bar_data['to_date'];
                                                                                                                                                         } else {
                                                                                                                                                            echo date('d M Y');
                                                                                                                                                         }  ?>" placeholder="Select To Date" class="form-control" tts-error-msg="Please select to date" readonly />
                              </div>
                           </div>
                           <div class="col-md-2 align-self-end">
                              <div class="form-group form-mb-20">
                                 <button type="submit" class="btn btn-primary btn-search">Download PDF<i class="fa fa-file-pdf-o"></i></button>
                              </div>
                           </div>
                           <div class="col-md-2">
                              <? if (isset($search_bar_data['key'])) : ?>
                                 <div class="search-reset-btn">
                                    <a href="<?php echo site_url('invoice'); ?>">Reset</a>
                                 </div>
                              <? endif ?>
                           </div>
                        </div>
                     </form>
                     <!----------End Search Bar ----------------->
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>