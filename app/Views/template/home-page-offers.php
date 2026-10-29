<?php if (isset($top_routes_list)) { ?>
   <section class="route-section ">
      <div class="container">
         <div class="row">
            <div class="col-lg-12">
               <div class="routebox">
                  <div class="row gy-3 ">
                     <?php foreach ($top_routes_list as $routes_list) :

                        $uri = site_url('flight/search') . '?' . http_build_query($routes_list['url']);
                        $currentDate = date("d M Y");
                        $currenttimestamp = strtotime($currentDate);
                        $departtimestamp = strtotime($routes_list['url']['departdate']);
                     ?>
                        <?php if ($currenttimestamp <= $departtimestamp) { ?>
                           <div class="col-lg-3 col-md-6 col-12">
                              <a href="<?= $uri ?>" class="route-list Bangalorebg">
                                 <i class="fa-solid fa-route routes_icons"></i>
                                 <h4>
                                    <?= $routes_list['city_origin'] . ' ' . $routes_list['arrowType'] . ' ' . $routes_list['city_destination'] . '(' . $routes_list['OriginCode'] . ' ' . $routes_list['seperator'] . ' ' . $routes_list['DestinationCode'] . ')' ?>
                                 </h4>
                              </a>
                           </div>
                        <?php } ?>
                     <?php endforeach; ?>

                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
<?php } ?>

<section class="notifi-sec">
   <div class="container">
      <div class="home_heading">
         <h2><span>Important </span><strong>Notifications</strong></h2>
      </div>
      <div class="row gy-4">
         <div class="col-lg-12 d-flex flex-column justify-content-center">
            <?php if (isset($important_notification)) { ?>
               <div class="faq-container">
                  <?php if ($important_notification) {
                     $i = 1;
                     foreach ($important_notification as $index => $general) { ?>
                        <div class="faq-item <?php echo $index === 0 ? 'faq-active' : ''; ?>">
                           <h3><span><?php echo sprintf('%02d', $i++); ?></span> <?php echo $general['title']; ?></h3>
                           <div class="faq-content">
                              <p><?php echo $general['description']; ?></p>
                           </div>
                           <i class="faq-toggle fa fa-chevron-right"></i>
                        </div>
                  <?php }
                  } ?>
               </div>
            <?php } ?>
            <!-- <?php if (isset($important_notification)) { ?>
               <div class="tts-notification">
                  <?php if ($important_notification) {
                        foreach ($important_notification as $general) { ?>
                        <div class="tts-nodetails">
                           <h5 class="">
                              <?php echo $general['title'] ?>
                           </h5>
                           <p>
                              <?php echo $general['description'] ?>
                           </p>
                        </div>
                  <?php }
                     } ?>
               </div> -->
         <?php } ?>
         </div>
      </div>
   </div>
</section>

<section class="deal-offer-section sections-bg">
   <div class="container">
      <div class="home_heading">
         <h2>Deals and Offers</h2>
      </div>
      <div class="row gy-4">
         <div class="col-lg-12">
            <?php if ($offers_list) { ?>
               <div class="row gy-3">
                  <?php foreach ($offers_list as $offer) { ?>
                     <div class="col-lg-3 col-md-6 col-12">
                        <a href="<?php if ($offer['url']) {
                                    echo $offer['url'];
                                 } else
                                    echo 'javascript:void(0);' ?>">
                           <div class="card ">
                              <div class="Offer_img">
                                 <img src="<?php echo root_url . 'uploads/offers/thumbnail/' . $offer['image'] ?>" alt=" <?php echo $offer['title'] ?>" class="img-fluid" />
                                 <img src="<?php echo root_url . 'uploads/offers/thumbnail/' . $offer['image'] ?>" alt=" <?php echo $offer['title'] ?>" class="img-fluid" />
                              </div>
                              <div class="card-description">
                                 <h4 class="card-title">
                                    <?php echo $offer['title'] ?>
                                 </h4>
                                 <p class="card-text">
                                    <?php echo $offer['description'] ?>
                                 </p>
                              </div>
                           </div>
                        </a>
                     </div>
                  <?php } ?>
               </div>
            <?php } ?>
         </div>
      </div>
   </div>
</section>


<script>
   document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle').forEach((faqItem) => {
      faqItem.addEventListener('click', () => {
         // Remove 'faq-active' class and hide content from all .faq-item elements
         document.querySelectorAll('.faq-item').forEach((item) => {
            item.classList.remove('faq-active');
           
         });

         // Add 'faq-active' class and show content to the clicked element's parent
         faqItem.parentNode.classList.add('faq-active');
         
      });
   });
</script>