<style>
	.error-message {
		top: 59px;
	}

	.firms_selected input {
		width: 15px;
		height: 15px;
	}

	.firms_selected strong {
		font-size: 16px;

	}
</style>


<section class="page-section">
	<div class="container ">
		<div class="row m0">
			<div class="col-md-12 section-bg p-5">
				<?php if (!empty($details)) { ?>
					<div class="section-title">
						<h1 class="nh_color page_content_header">
							<span class="fz30"><?php echo $details['title']; ?></span>
						</h1>
					</div>
					<div class="row">
						<div class="page_content_details w-100">
							<p><?php echo $details['content']; ?></p>
						</div>
					</div>
				<?php } else { ?>
				</div>
			</div>
			<div class="text-center p-5">
				<img src="<?php echo root_url . 'uploads/img/error.svg' ?>" class="img-fluid" />
				<h4>Page not found</h4>
				<p>The page you are looking for doesn't exist</p>
				<a href="<?php echo site_url(); ?>" class="btn btn-primary"> Got to Home </a>
			</div>
		<?php } ?>
	</div>
</section>
<!-- <style>
		.error-message{
			top: 59px;
		}
		.firms_selected input{
			width: 15px;
			height: 15px;
		}

		.firms_selected strong {
			font-size: 16px;

		}
	</style>

<?php if ($details) { ?>
<main class="my-5 ">
	<div class="container my-5">
		<div class="text-center"><h1 class="nh_color pt-3 pb-3 position-relative page_content_header">
				<span class="fz30"><?php echo $details['title']; ?></span></h1></div>
		<div class="row px-5">
			<div class="page_content_details w-100">
				<p><?php echo $details['content']; ?></p>
			</div>
		</div>
	</div>
</main>
<?php } else { ?>
	<main class="my-5 ">
	<div class="container my-5">
		<div class="text-center"><h1 class="nh_color pt-3 pb-3 position-relative page_content_header">
				
		<div class="row px-5">
			<div class="page_content_details w-100">
			
			</div>
		</div>
	</div>
</main>
 <?php } ?>	-->