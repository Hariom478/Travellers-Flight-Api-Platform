<style>
   .error-message {
   top: unset !important;
   }
   /*.query-followup {
   text-align: center;
   background: #fff;
   border-bottom: 1px solid #e4e4e4;
   }
   .lm_navigation {
   position: relative;
   display: inline-flex;
   padding: 0 10px;
   text-align: center;
   }
   .lm_navLst.active:before {
   content: "";
   width: 100%;
   position: absolute;
   left: 0;
   bottom: 0;
   height: 3px;
   background: #0167ff;
   }
   .lm_navLst a {
   padding: 15px 15px;
   font-weight: 600;
   font-size: 15px;
   }*/
</style>
<section>
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-body">
                  <div class="query-followup">
                     <ul class="lm_navigation">
                        <li class="lm_navLst <?php if ($service=='Flight'){ echo 'active';} ?>">
                           <a href="<?php echo site_url("sale-result/service-report?q=Flight"); ?>"> <span>Flight Report</span> </a>
                        </li>
                        <li class="lm_navLst <?php if ($service=='Hotel'){ echo 'active';} ?>">
                           <a href="<?php echo site_url("sale-result/service-report?q=Hotel"); ?>">
                           <span> Hotel Report</span>
                           </a>
                        </li>
                        <li class="lm_navLst <?php if ($service=='Bus'){ echo 'active';} ?>">
                           <a href="<?php echo site_url("sale-result/service-report?q=Bus"); ?>">
                           <span> Bus Report</span>
                           </a>
                        </li>
                        <li class="lm_navLst <?php if ($service=='Visa'){ echo 'active';} ?>">
                           <a href="<?php echo site_url("sale-result/service-report?q=Visa"); ?>">
                           <span> Visa Report</span>
                           </a>
                        </li>
                     </ul>
                  </div>
                  <?php echo $html_view;?>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>