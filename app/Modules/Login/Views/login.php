<!-- <div class="loginpage">
   <div class="loginwrap">
      

      <div class="loginform-content">
         <div class="loginmain-form">
            <div class="login-inner-wrap">

     

               <div style="text-align: center;padding: 30px 2px;">
               <a class="login-logo" href="<?php echo site_url(); ?>"> 
               <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>" class="img-fluid">
               
               </a>
               <br/><br/>
               <h2 class="login-page-title">Agent Login</h2>
               </div>

               <div tts-login-form="true">
                  <form action="<?php echo site_url("login"); ?>" method="POST">
                     <div class="login-form-step">
                        
                        <?= csrf_field() ?>
                        <div class="form-group ">
                           <input class="form-control" type="text" name="user_email" placeholder="Email"
                              value="<?php echo set_value('user_email'); ?>">
                           <?php if ($validation->getError('user_email')): ?>
                           <span class="error-message"> <?= $validation->getError('user_email') ?></span>
                           <?php endif; ?>
                           <i class="fa fa-envelope"></i>
                        </div>
                        <div class="form-group ">
                           <input class="form-control" type="password" name="user_password"
                              placeholder="Password" value="<?php echo set_value('user_password'); ?>">
                           <?php if ($validation->getError('user_password')): ?>
                           <span class="error-message">
                           <?= $validation->getError('user_password') ?></span>
                           <?php endif; ?>
                           <i class="fa fa-lock"></i>
                        </div>
                        <div class="form-group ">
                           <div class="col-12 col-md-12">
                              <input class="login-btn-fill" type="submit" value="Log in"/>
                           </div>
                        </div>
                        <div class="form-group ">
                           <p class="m-0">Forgot your password?
                              <a href="javascript:void(0);" title="forget password "
                                 class="forgot_passwd " id="forgotpaasword"
                                 tts-forgot-password="true">
                              Reset Here
                              <i class="fa fa-lock"></i>
                              </a>
                           </p>
                           <p class="m-0">Don’t have any account?
                              <a href="<?php echo site_url('login/register-agency'); ?>"
                                 class="" title="Sign up here">
                              Sign up here
                              <i class="fa fa-sign-in"></i>
                              </a>
                           </p>
                        </div>
                       
                       
                     </div>
                  </form>
               </div>
               <div class="hide" forgot-password-form="true">
                  <form action="<?php echo site_url("login/forgot-password"); ?>" method="POST"
                     tts-forgot-form="true" name="forgot_password">
                     <?= csrf_field() ?>
                     <div class="mb-3">
                        <h3>Forgot password</h3>
                     </div>
                     <div class="form-group ">
                        <input class="form-control" type="email" name="registered_email"
                           placeholder="Enter your email"
                           value="<?php echo set_value('registered_email'); ?>">
                        <span class="symbol-input100">
                        <i class="fa fa-envelope" ></i>
                        </span>
                     </div>
                     <div class="form-group ">
                        <div class="col-12">
                           <input class="login-btn-fill" type="submit" value="Get OTP"/>
                        </div>
                     </div>
                     <div class="form-group ">
                        <div class="col-12">
                           <p>Login here Your account
                              <a href="javascript:void(0);" title="login here"
                                 class="forgot_passwd " id="forgotpaasword" tts-login="true">
                              login
                              <i class="fa fa-lock" ></i>
                              </a>
                           </p>
                        </div>
                     </div>
                  </form>
               </div>
               <div class="hide" forgot-password-otp-form="true">
                  <form action="<?php echo site_url("login/validate-otp-password"); ?>" method="POST"
                     tts-forgot-form="true" name="otp_validate">
                     <?= csrf_field() ?>
                     <div class=" ">
                        <div class="mb-3">
                           <h3 class="text-white">Reset Password</h3>
                        </div>
                        <div class="input-group mb-3">
                           <input class="form-control" type="text" name="OTP" autocomplete="off"
                              placeholder="Enter OTP">
                        </div>
                        <div class="input-group mb-3">
                           <input class="form-control" type="password" name="new_password"
                              placeholder="Enter New Password">
                        </div>
                        <div class="form-group ">
                           <div class="col-12">
                              <input class="loginbtn w-100" type="submit" value="Reset Password"/>
                           </div>
                        </div>
                        <div class="form-group ">
                           <div class="col-12">
                              <p>Login here Your account
                                 <a href="javascript:void(0);" title="login here"
                                    class="forgot_passwd " id="forgotpaasword" tts-login="true">
                                 login
                                 <i class="fa fa-lock" ></i>
                                 </a>
                              </p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
               
            </div>

         </div>
      </div>
   </div>
</div>

<div class="container-fluid loginpage p0">

<div style="text-align: center;background: #c8c7c7;padding: 30px;height: 150px;">
              
<div class="loginheading-content">

<ul class="socials">
               <?php if (web_partner_details['facebook_link']) { ?>
                  <li class="facebook">
                     <a href="<?php echo web_partner_details['facebook_link']; ?>" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                  </li>
                  <?php } ?>
                  <?php if (web_partner_details['twitter_link']) { ?>
                  <li class="twitter">
                     <a href="<?php echo web_partner_details['twitter_link']; ?>" title="twitter"><i class="fab fa-twitter"></i></a>
                  </li>
                  <?php } ?>
                  <?php if (web_partner_details['linkedin_link']) { ?>
                  <li class="linkedin">
                     <a href="<?php echo web_partner_details['linkedin_link']; ?>" title="linkedin"><i class="fab fa-linkedin-in"></i></a>
                  </li>
                  <?php } ?>
                  <?php if (web_partner_details['instagram_link']) { ?>
                  <li class="pinterest">
                     <a href="<?php echo web_partner_details['instagram_link']; ?>" title="pinterest"><i class="fa-brands fa-instagram"></i></a>
                  </li>
                  <?php } ?>
               </ul>

               </div>
              
               <div class="copyright-text">Copyright © 2023 <?php echo web_partner_details['company_name']; ?>. All Rights Reserved</div>
            </div>
            


</div> -->



<style>
   /*========= login ==========*/


   .login-6 {
      z-index: 0;
      min-height: 100vh;
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      background-image: linear-gradient(to bottom, #db3390, #d73131c2);
      padding: 30px 0;
   }

   .login-6 #particles-js {
      background-size: cover;
      background-position: 50% 50%;
      position: fixed;
      min-height: 100vh;
      width: 100%;
      z-index: -999;
   }

   .login-6 .form-section {
      max-width: 500px;
      margin: 0 auto;
      width: 100%;
      background: #fff;
      padding: 50px;
      border-radius: 30px;
      box-shadow: 0 0 35px rgb(0 0 0 / 10%);
      text-align: left;
      position: relative;
      z-index: 0;
   }

   .login-6 .form-section:before {
      content: "";
      width: 100%;
      height: 91px;
      position: absolute;
      top: 0;
      right: 0;
      background: url(../../webroot/img/img-6.png) top left repeat;
      background-size: cover;
      z-index: -1;
      border-radius: 30px 30px 0 0;
   }

   .login-6 .logo {
      margin-bottom: 15px;
   }

   .login-6 .form-section a {
      text-decoration: none;
   }

   .login-6 .logo img {
      height: 120px;
   }

   .login-6 .typing>* {
      overflow: hidden;
      white-space: nowrap;
      animation: typingAnim 3s steps(50);
   }

   @keyframes typingAnim {
      0% {
         width: 0;
      }

      100% {
         width: 100%;
      }
   }

   .login-6 h1 {
      margin: 0 0 35px;
      font-size: 23px;
      color: #040404;
      font-weight: 400;
   }

   .login-inner-form {
      width: 100%;
   }

   .login-6 .login-inner-form .form-group {
      margin-bottom: 35px;
   }

   .login-6 .login-inner-form .form-box {
      float: left;
      width: 100%;
      position: relative;
   }

   .login-6 .login-inner-form .form-control {
      padding: 14.5px 0;
      font-size: 16px;
      outline: none;
      background: transparent !important;
      color: #535353;
      font-weight: 500;
      border: none;
      border-bottom: solid 2px #bdbdbd;
      border-radius: 0;
   }

   .login-6 .login-inner-form .form-box .form-control {
      float: left;
      width: 100%;
      padding: 13px 15px 13px 30px;
   }

   .login-6 .login-inner-form .form-box i {
      position: absolute;
      top: 15px;
      left: 0;
      font-size: 23px;
      color: #777575;
   }

   .login-6 .login-inner-form .checkbox {
      margin-bottom: 35px;
      font-size: 15px;
   }

   .login-6 .login-inner-form .form-check {
      float: left;
      margin-bottom: 0;
   }

   /* color: #535353; */
   .login-6 .login-inner-form .checkbox a {
      font-size: 16px;
      float: right;
   }

   .login-6 .btn-theme {
      border: none;
      color: #fff;
      cursor: pointer;
      font-size: 17px;
      font-weight: 400;
      width: 100%;
      font-family: 'Jost', sans-serif;
      border-radius: 3px;
   }

   .login-6 .btn-primary {
      background-image: linear-gradient(to bottom, #38b64c, #38b64c);
   }

   .login-6 .btn-primary:hover {
      background-image: linear-gradient(to bottom, #38b64c, #3a8fc6)
   }

   .login-6 .btn-lg {
      padding: 0 50px;
      line-height: 50px;
   }

   .login-6 .btn {
      box-shadow: none !important;
   }

   .login-6 .social-list {
      margin-bottom: 20px;
      text-align: center;
   }

   .login-6 .social-list .social-list-inner {
      padding: 0;
      list-style: none;
      margin: 0;
   }

   .login-6 .social-list .social-list-inner li {
      display: inline-block;
      margin: 0 2px 5px 0;
      position: relative;
      font-size: 20px;
   }

   .login-6 .social-list .social-list-inner a {
      display: inline-block;
   }

   .login-6 .form-section a {
      text-decoration: none;
   }

   .login-6 .social-list .social-list-inner a:before {
      transform: scale(1);
      -ms-transform: scale(1);
      -webkit-transform: scale(1);
      content: " ";
      width: 60px;
      height: 60px;
      border-radius: 100%;
      display: block;
      background: linear-gradient(45deg, #38b64c, #38b64c);
      transition: all 265ms ease-out;
   }

   .login-6 .social-list .social-list-inner a:hover:before {
      transform: scale(0);
      transition: all 265ms ease-in;
   }

   .login-6 .social-list .social-list-inner a.facebook:before {
      background: #234ca1;
   }

   .login-6 .social-list .social-list-inner a.twitter:before {
      background: #000000;
   }

   .login-6 .social-list .social-list-inner a.linkedin:before {
      background: #0082ca;
   }

   .login-6 .social-list .social-list-inner a.instagram:before {
      background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
   }

   .login-6 .social-list .social-list-inner i {
      color: #ffffff;
      position: absolute;
      top: 21px;
      left: 21px;
      transition: all 265ms ease-out;
   }

   .login-6 .social-list .social-list-inner a:hover i {
      transform: scale(1.5);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      transition: all 265ms ease-in;
   }


   .login-6 .social-list .social-list-inner a.facebook:hover i {
      background: #234ca1;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
   }

   .login-6 .social-list .social-list-inner a.twitter:hover i {
      background: #000000;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
   }

   .login-6 .social-list .social-list-inner a.linkedin:hover i {
      background: #0082ca;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
   }

   .login-6 .social-list .social-list-inner a.instagram:hover i {
      background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
   }


   @media only screen and (max-width: 600px) {
      .login-6 .social-list .social-list-inner a:before {
         width: 50px;
         height: 50px;
      }

      .login-6 .social-list .social-list-inner i {
         top: 15px;
         left: 15px;
      }

      .login-6 .login-inner-form .checkbox a,
      .login-6 .login-inner-form .checkbox a {
         float: left !important;
      }
   }

   /*========= login end ==========*/
</style>


<div class="login-6">
   <div id="particles-js"><canvas class="particles-js-canvas-el" width="1800" height="902"
         style="width: 100%; height: 100%;"></canvas></div>
   <div class="container">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="form-section">
               <div class="logo">
                  <a href="<?php echo site_url(); ?>">
                     <img src="<?php echo root_url . 'uploads/logo/' . web_partner_details['company_logo'] ?>"
                        alt="logo">
                  </a>
               </div>
               <div class="typing">
                  <h1>Sign Into Your Account</h1>
               </div>
               <div class="login-inner-form">
                  <div tts-login-form="true">
                     <form action="<?php echo site_url("login"); ?>" method="POST">
                        <div class="login-form-step">
                           <?= csrf_field() ?>
                           <div class="form-group clearfix">
                              <div class="form-box">
                                 <input class="form-control" type="text" name="user_email" placeholder="Email Address"
                                    value="<?php echo set_value('user_email'); ?>">
                                 <?php if ($validation->getError('user_email')): ?>
                                    <span class="error-message"> <?= $validation->getError('user_email') ?></span>
                                 <?php endif; ?>
                                 <i class="fa fa-envelope"></i>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <div class="form-box">
                                 <input class="form-control" type="password" name="user_password" placeholder="Password"
                                    value="<?php echo set_value('user_password'); ?>">
                                 <?php if ($validation->getError('user_password')): ?>
                                    <span class="error-message">
                                       <?= $validation->getError('user_password') ?></span>
                                 <?php endif; ?>
                                 <i class="fa fa-lock"></i>
                              </div>
                           </div>
                           <div class="checkbox form-group clearfix">
                              <div class="float-start">
                                 <a href="javascript:void(0);" title="forget password " class="forgot_passwd "
                                    id="forgotpaasword" tts-forgot-password="true">
                                    Forgot your password?
                                    <i class="fa fa-lock"></i>
                                 </a>
                              </div>
                              <a href="<?php echo site_url('login/register-agency'); ?>" class="float-end"
                                 title="Sign up here"> Register here <i class="fa fa-sign-in"></i></a>
                           </div>
                           <div class="form-group clearfix">
                              <button type="submit" class="btn btn-primary btn-lg btn-theme"
                                 value="Log in">Login</button>
                           </div>

                        </div>
                     </form>
                  </div>
                  <div class="hide" forgot-password-form="true">
                     <form action="<?php echo site_url("login/forgot-password"); ?>" method="POST"
                        tts-forgot-form="true" name="forgot_password">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                           <h3>Forgot password</h3>
                        </div>
                        <div class="form-group clearfix">
                           <div class="form-box">
                              <input class="form-control" type="email" name="registered_email"
                                 placeholder="Enter your email" value="<?php echo set_value('registered_email'); ?>">
                              <span class="symbol-input100">
                                 <i class="fa fa-envelope"></i>
                              </span>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <div class="form-box">
                              <button type="submit" class="btn btn-primary btn-lg btn-theme"
                                 value="Get OTP">Submit</button>
                           </div>
                        </div>
                        <div class="checkbox form-group clearfix">
                           <p>Login here Your account
                              <a href="javascript:void(0);" title="login here" class="forgot_passwd green-text"
                                 id="forgotpaasword" tts-login="true" coler>
                                 login
                                 <i class="fa fa-lock"></i>
                              </a>
                           </p>
                        </div>
                     </form>
                  </div>
                  <div class="hide" forgot-password-otp-form="true">
                     <form action="<?php echo site_url("login/validate-otp-password"); ?>" method="POST"
                        tts-forgot-form="true" name="otp_validate">
                        <?= csrf_field() ?>
                        <div class=" ">
                           <div class="mb-3">
                              <h3 class="text-white">Reset Password</h3>
                           </div>
                           <div class="input-group mb-3">
                              <input class="form-control" type="text" name="OTP" autocomplete="off"
                                 placeholder="Enter OTP">
                           </div>
                           <div class="input-group mb-3">
                              <input class="form-control" type="password" name="new_password"
                                 placeholder="Enter New Password">
                           </div>
                           <div class="form-group ">
                              <div class="col-12">
                                 <input class="loginbtn w-100" type="submit" value="Reset Password" />
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <div class="col-12">
                                 <p>Login here Your account
                                    <a href="javascript:void(0);" title="login here" class="forgot_passwd "
                                       id="forgotpaasword" tts-login="true">
                                       login
                                       <i class="fa fa-lock"></i>
                                    </a>
                                 </p>
                              </div>
                           </div>
                        </div>
                     </form>
                  </div>
                  <div class="social-list">
                     <ul class="social-list-inner">
                        <?php if (web_partner_details['facebook_link']) { ?>
                           <li class="facebook">
                              <a href="<?php echo web_partner_details['facebook_link']; ?>" class="facebook"
                                 title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                           </li>
                        <?php } ?>
                        <?php if (web_partner_details['twitter_link']) { ?>
                           <li class="twitter">
                              <a href="<?php echo web_partner_details['twitter_link']; ?>" class="twitter"
                                 title="twitter"><i class="fa-brands fa-x-twitter"></i></a>
                           </li>
                        <?php } ?>
                        <?php if (web_partner_details['linkedin_link']) { ?>
                           <li class="linkedin">
                              <a href="<?php echo web_partner_details['linkedin_link']; ?>" class="linkedin"
                                 title="linkedin"><i class="fa-brands fa-linkedin-in"></i></a>
                           </li>
                        <?php } ?>
                        <?php if (web_partner_details['instagram_link']) { ?>
                           <li class="pinterest">
                              <a href="<?php echo web_partner_details['instagram_link']; ?>" class="instagram"
                                 title="instagram"><i class="fa-brands fa-instagram"></i></a>
                           </li>
                        <?php } ?>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<?php if (!empty(session()->get("Message"))):
   $message = session()->get("Message"); ?>
   <div class="message  <?= $message['Class'] ?>" onclick="this.classList.add('hide');"><?php echo $message['Message']; ?>
   </div>
<?php endif ?>
<div data-message="true"></div>
<style>
   <?php echo whitelabel['custom_css']; ?>
</style>
<script>
   $(document).on("submit", "[tts-forgot-form='true']", function (e) {
      e.preventDefault();
      var form = $(this);
      var url = form.attr('action');
      var method = form.attr('method');
      var name = form.attr('name');
      $("[data-message]").removeClass().html("");
      $(".error-message").removeClass().html("");
      var buttontxt;
      if ($("input[type=submit]", form).attr('value')) {
         buttontxt = $("input[type=submit]", form).attr('value');
         $("input[type=submit]", form).attr('disabled', true).val('Loading...');
      } else {
         buttontxt = $("button[type=submit]", form).text();
         $("button[type=submit]", form).attr('disabled', true).html('Loading...');
      }
      $("span.error-message", form).replaceWith("");

      $.ajax({
         url: url,
         method: method,
         data: new FormData(this),
         contentType: false,
         cache: false,
         processData: false,
         success: function (resp) {
            if ($("input[type=submit]", form).attr('value')) {
               $("input[type=submit]", form).attr('disabled', false).val(buttontxt);
            } else {
               $("button[type=submit]", form).attr('disabled', false).html(buttontxt);
            }
            if (resp.StatusCode == 1) {
               $.each(resp.ErrorMessage, function (key, val) {

                  $('[name="' + key + '"],[textarea="' + key + '"]', form).after('<span class="error-message">' + val + '</span>');

               });
            } else if (resp.StatusCode == 0) {
               if (resp.Reload && resp.Reload == 'true') {
                  window.location.reload();
               } else {
                  $("[forgot-password-form]").addClass('hide');
                  $("[forgot-password-otp-form]").removeClass('hide');

                  $("[data-message]").addClass(resp.Class).attr('onClick', "this.classList.add('hide')").html(resp.Message);
               }

            }
         },
         error: function (res) {
            alert("Unexpected error! Try again.");
            // location.reload();
         }
      });


   });
</script>
<script>
   $(document).on("click", ".error-message", function () {
      $(this).remove();
   });

   $(document).on("click", "[tts-login = 'true']", function () {
      $("[tts-login-form]").removeClass('hide');
      $("[forgot-password-form]").addClass('hide');

      $("[forgot-password-otp-form]").addClass('hide');
   });

   $(document).on("click", "[tts-forgot-password = 'true']", function () {

      $("[tts-login-form]").addClass('hide');
      $("[forgot-password-form]").removeClass('hide');
      $("[forgot-password-otp-form]").addClass('hide');
   });
</script>
<script src="<?php echo site_url('webroot'); ?>/js/slick.min.js"></script>
<script>
   $(document).ready(function () {
      $('.clients-slider').slick({
         centerMode: true,
         centerPadding: '60px',
         slidesToShow: 5,
         autoplay: true,
         autoplaySpeed: 2000,
         responsive: [
            {
               breakpoint: 768,
               settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '10px',
                  slidesToShow: 5
               }
            },
            {
               breakpoint: 480,
               settings: {
                  arrows: false,
                  centerMode: true,
                  centerPadding: '10px',
                  slidesToShow: 1
               }
            }
         ]
      });
   });
</script>

<script src="webroot/js/particles.js"></script>
<script src="webroot/js/app.js"></script>