<section id="breadcrumbs" class="breadcrumbs">
   <div class="container">
      <div class="d-flex justify-content-between align-items-center">
         <h2>Contacts</h2>
         <ol>
            <li>
               <a href="<?php echo site_url();?>">Home</a>
            </li>
            <li>Contacts</li>
         </ol>
      </div>
   </div>
</section>

<section class="contactus">
   <div class="container ">
      <div class="section-title">
         <h2>Contacts</h2>
      </div>
      <div class="row g-4">
         <div class="col-lg-6">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p><?php echo super_admin_website_setting['address']; ?>, <?php echo super_admin_website_setting['city']; ?>, <?php echo super_admin_website_setting['state']; ?>,  <?php echo super_admin_website_setting['pincode']; ?>, <?php echo super_admin_website_setting['country']; ?></p>
            </div>
         </div>
         <div class="col-lg-3 col-md-6">
            <div class="info-box">
               <i class="fa fa-envelope"></i>
               <h3>Email Us</h3>
               <p><a class="nav-link"  href="mailto:<?php echo super_admin_website_setting['support_email'] ?>"><?php echo super_admin_website_setting['support_email'] ?>
                        </a></p>
            </div>
         </div>
         <div class="col-lg-3 col-md-6">
            <div class="info-box">
               <i class="fa fa-phone"></i>
               <h3>Call Us</h3>
               <p><a class="nav-link" aria-current="page" href="tel:<?php echo super_admin_website_setting['support_no'] ?>"><?php echo super_admin_website_setting['support_no'] ?>
                        </a></p>
            </div>
         </div>
         <div class="col-lg-6 ">  
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62456.81866434251!2d79.77232821927322!3d11.93629230259166!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a5361ab8e49cfcf%3A0xcc6bd326d2f0b04e!2sPuducherry!5e0!3m2!1sen!2sin!4v1691413634847!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
         </div>
         <div class="col-lg-6">
            <form tts-action="tts__contact-us-save"  method="post" role="form" tts-form="true"  class="php-email-form">
               <div class="row">
                  <div class="col-md-6 form-group">
                      <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" > 
                  </div>
                  <div class="col-md-6 form-group mt-3 mt-md-0">
                     <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" >
                  </div>
               </div>
               <div class="form-group mt-3">
                  <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" >
               </div>
               <input type="hidden" name="type" value="contact_us">
               <div class="form-group mt-3">
                  <textarea class="form-control" name="message" rows="5" placeholder="Message" ></textarea>
               </div>
               <div class="text-center"><button type="submit">Send Message</button></div>
            </form>
         </div>
      </div>
   </div>
</section>

<section class="contactus">
   <div class="container">
      <div class="home_heading">
         <h2>Corporate Office</h2>
      </div>
      <div class="row gy-4">
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>514, M.G Road. Puducherry 605001</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>Old No 30. New No :31. Sarojini Street.Stuartpet, Arakkonom-631001</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>Door no -1/17. Srimathi Complex.Sethuraman pillal colony, (Near bus stop).Pudukkottal Main raod. Trichy</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>#726/2(Shop #2). RM Nagar Main Road.Banaswadi. Bangalore-560043</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>155, Rue du Faubourg Saint-Denis 75010 PARIS France</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>B-Markt 24B 4875CE Etten-Leur Netherlands</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>128, Vauxhall Street Colombo 02. Sri Lanka</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>Opposite Susan Roses Plant, 
                  Behind Shiva Aluminum Fabricators, Garacharma PO, Garacharma, Port Blair, south andaman, 744105
               </p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>
                  No 107/2 Ashok Nagar Road, Kodambakkam, Chennai 600024 
                  Land mark near Bing Bang theory.
               </p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>
                 No.TF-304, 3 Floor,
                  Smart Plaza, Coles Road, Fraser Town, Bangalore 560005
               </p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Our Address</h3>
               <p>
                  No 107/2 Ashok Nagar Road, Kodambakkam, Chennai 600024 
                  Land mark near Bing Bang theory.
               </p>
            </div>
         </div>
      </div>
   </div>
</section>


<section class="contactus">
   <div class="container">
      <div class="home_heading">
         <h2>Franchise</h2>
      </div>
       <div class="row gy-4">
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Coimbatore Franchise </h3>
               <p>248 Airport Road Peelamedu, Civil Aerodrome Post Airport Service road, post, near Check, Coimbatore, Tamil Nadu 641014</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Pondycherry franchise </h3>
               <p>Landmark, 77, Perumal Kovil Street, Mahatma Gandhi Rd, near Perumal Kovil, Puducherry, 60500</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Chennai Korattur franchise </h3>
               <p>No 780L, North Ave, TNHB Colony, Korattur, Chennai, Tamil Nadu 600080</p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Trichy franchise</h3>
               <p>No 4 /16 shastri road Thillai nagar Trichy </p>
            </div>
         </div>
         <div class="col-lg-3">
            <div class="info-box">
               <i class="fa-solid fa-map-marker"></i>
               <h3>Namakkal</h3>
               <p>Namakal Franchise no 96,sultanpet, near panjamuga vinayagar temple,Paramathi velur, namakkal d638182</p>
            </div>
         </div>
      </div>
   </div>
</section>