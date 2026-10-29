<!DOCTYPE html>
<html>
   <head>
      <title><?= $title ?> </title>
      <link rel="shortcut icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" type="image/x-icon" />
      <link rel="alternate" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" hreflang="en" />
      <link rel="icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>" type="image/x-icon" />
      <link rel="apple-touch-icon" href="<?php echo root_url . 'uploads/favicon/' . super_admin_website_setting['company_favicon'] ?>">

      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/css/custom.css">
      <link rel="stylesheet" href="<?php echo site_url('webroot'); ?>/vendor/bootstrap/css/bootstrap.css<?php echo last_modifytime(FCPATH . 'webroot/vendor/bootstrap/css/bootstrap.css'); ?>">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
      <script type="text/javascript" src="<?php echo site_url('webroot'); ?>/js/jquery.min.js<?php echo last_modifytime(FCPATH . 'webroot/js/jquery.min.js'); ?>"></script>
   </head>
   <body>
      <div>
         <div class="starsec"></div>
         <div class="starthird"></div>
         <div class="starfourth"></div>
         <div class="starfifth"></div>
      </div>
      <?php if(! empty(session()->get("Message"))) :  $message = session()->get("Message"); ?>
      <div class="message  <?= $message['Class'] ?>" onclick="this.classList.add('hide');"><?php echo $message['Message']; ?></div>
      <?php endif ?>
      <section  class="login">

         <div class="container-fluid">
            <div class="login_box" tts-login-form="true">
               <form action="<?php echo site_url('login'); ?>" method="POST" class="contact100-form validate-form">
                  <div class="login_form ">
                     <div class="login_form_content">
                        <h3 class="login-login-title">Admin Login</h3>
                        <p class="login-form-text">Sign in to continue..</p>
                     </div>
                     <?= csrf_field() ?>
                     <div class="form-group position-relative mb-3">
                        <label class="form-label">Email Id</label>
                        <input class="form-control" type="text" name="user_email" placeholder="Email Id" value="<?php echo set_value('user_email'); ?>">
                           <?php if ($validation->getError('user_email')): ?>
                           <span class="error-message"> <?= $validation->getError('user_email') ?></span>
                           <?php endif; ?>
                     </div>
                     <div class="form-group position-relative mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control" type="password" name="user_password" placeholder="Password" value="<?php echo set_value('user_password'); ?>">
                           <?php if ($validation->getError('user_password')): ?>
                           <span class="error-message"> <?= $validation->getError('user_password') ?></span>
                           <?php endif; ?>
                         <p class="text-end mt-2 d-flex align-items-center justify-content-between">Forgot your password?
                           <a href="javascript:void(0);" title="forget password " class="forgot_passwd text-muted" id="forgotpaasword" tts-forgot-password="true">
                           Reset Here
                           <i class="fa fa-lock" aria-hidden="true"></i>
                           </a>
                        </p>
                     </div>
                     <div class="">
                        <button class="loginformbtn" type="submit">
                        <span>Submit <i class="fa fa-arrow-right"></i></span>
                        </button>
                     </div>
                  </div>
                  
               </form>
            </div>
            <div class="hide" forgot-password-form="true">
               <div class="login_box">
                  <form action="<?php echo site_url('login/forgot-password'); ?>" method="POST"
                     tts-forgot-form="true" name="forgot_password">
                     <?= csrf_field() ?>
                     <div class="login_form">
                        <div class="login_form_content">
                           <h3 class="login-login-title">Forgot password</h3>
                           <p class="text-muted">Reset password with your Email </p>
                        </div>
                        <div class=" form-group position-relative mb-3">
                           <label class="form-label">Email Id</label>
                           <input class="form-control" type="email" name="registered_email" placeholder="Enter your email" value="<?php echo set_value('registered_email'); ?>">
                        </div>
                        <div class="form-group position-relative mb-3">
                           <div class="col-12">
                              <input class="loginformbtn" type="submit" value="Get OTP"/>
                           </div>
                        </div>
                        <div class="form-group position-relative mb-3">
                           <div class="col-12">
                              <p>Login here Your account
                                 <a href="javascript:void(0);" title="login here" class="forgot_passwd text-muted" id="forgotpaasword" tts-login="true"> login
                                  <i class="fa fa-lock" aria-hidden="true"></i>
                                 </a>
                              </p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="hide" forgot-password-otp-form="true">
               <div class="login_box">
                  <form action="<?php echo site_url('login/validate-otp-password'); ?>" method="POST"
                     tts-forgot-form="true" name="otp_validate">
                     <?= csrf_field() ?>
                     <div class="login_form">
                        <div class="login_form_content">
                           <h3 class="login-login-title">Reset Password</h3>
                        </div>
                        <div class=" form-group position-relative mb-3">
                           <label class="form-label">Enter OTP</label>
                           <input class="form-control" type="text" name="OTP" autocomplete="off"  placeholder="Enter OTP">
                        </div>
                        <div class=" form-group position-relative mb-3">
                           <label class="form-label">New Password</label>
                           <input class="form-control" type="password" name="new_password"
                              placeholder="Enter New Password">
                        </div>
                        <div class="form-group position-relative mb-3">
                           <div class="col-12">
                              <input class="loginbtn loginformbtn" type="submit" value="Reset Password"/>
                           </div>
                        </div>
                        <div class="form-group position-relative mb-3">
                           <div class="col-12">
                              <p>Login here Your account
                                 <a href="javascript:void(0);" title="login here"
                                    class="forgot_passwd text-muted" id="forgotpaasword" tts-login="true">
                                 login
                                 <i class="fa fa-lock" aria-hidden="true"></i>
                                 </a>
                              </p>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
         <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
      </section>
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
   </body>
</html>