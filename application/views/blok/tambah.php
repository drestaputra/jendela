<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Tambah Blok | <?php echo function_lib::get_config_value('website_name'); ?></title>
		<meta name="keywords" content="Dashboard Admin - Jendela BPS" />
		<meta name="description" content="<?php echo function_lib::get_config_value('website_seo'); ?>">
		<meta name="author" content="Drestaputra - Inolabs">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		<!-- Web Fonts  -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap/css/bootstrap.css" />

		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/font-awesome/css/font-awesome.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

		<!-- Specific Page Vendor CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/jquery-ui/css/ui-lightness/jquery-ui-1.10.4.custom.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/morris/morris.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />
		
		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme-custom.css">


		<!-- Head Libs -->
		<script src="<?php echo base_url(); ?>assets/vendor/modernizr/modernizr.js"></script>
	</head>
	<body>
		<section class="body">

			<?php $this->load->view('header'); ?>

			<div class="inner-wrapper">
				<!-- start: sidebar -->
				<?php $this->load->view('left_bar'); ?>
				<!-- end: sidebar -->

				<section role="main" class="content-body">
					<header class="page-header">
						<h2>Blok</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.html">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Blok</span></li>
							</ol>
					
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>
					</header>
					<div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Tambah Blok</h3>
                                <div class="panel-actions">
                                            <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                                            <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
                                        </div>
                        </div>
                           <div class="panel panel-body">
                            <?php if (trim($this->input->get('status'))!=""): ?>
                                <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
                            <?php endif ?>
                               <form id="form" method="POST" class="form-horizontal">
                                <section class="panel">
                                    
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Nama Blok <span class="required">*</span></label>
                                            <div class="col-sm-10">
                                                                                                    
                                                    <input type="text" name="nama_blok" value="<?php echo ($this->input->post('nama_blok')!="")?$this->input->post('nama_blok'):""; ?>" class="form-control" placeholder="Nama blok.." required/>
                                                
                                            </div>                                            
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Deskripsi Blok <span class="required">*</span></label>
                                            <div class="col-sm-10">
                                                  <textarea id="ckeditor" name="deskripsi_blok"><?php echo ($this->input->post('deskripsi_blok')!="")?$this->input->post('deskripsi_blok'):""; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                                <label class="col-md-2 control-label">Jumlah Tempat Duduk</label>
                                                <div class="col-md-7">
                                                    <div data-plugin-spinner data-plugin-options='{ "value":9, "min": 9, "max": 23}'>
                                                        <div class="input-group" style="width:150px;">
                                                            <input type="text" class="spinner-input form-control" name="jumlah_tempat_duduk" maxlength="3" readonly>
                                                            <div class="spinner-buttons input-group-btn">
                                                                <button type="button" class="btn btn-default spinner-up">
                                                                    <i class="fa fa-angle-up"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-default spinner-down">
                                                                    <i class="fa fa-angle-down"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p>
                                                        <code>Jumlah tempat duduk</code> min 9 max 23
                                                    </p>
                                                </div>
                                            </div>
                                    </div>
                                    <footer class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-9 col-sm-offset-3">
                                                <button class="btn btn-primary">Submit</button>
                                                <button type="reset" class="btn btn-default pull-right">Reset</button>
                                            </div>
                                        </div>
                                    </footer>
                                </section>
                            </form>
                           </div>
                    </div>
					
				</section>
			</div>

			<?php $this->load->view('right_bar'); ?>
		</section>
<!-- Vendor -->
        <script src="<?php echo base_url(); ?>assets/vendor/jquery/jquery.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap/js/bootstrap.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/nanoscroller/nanoscroller.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/magnific-popup/magnific-popup.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
        
        <!-- Specific Page Vendor -->
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/fuelux/js/spinner.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/jquery-validation/jquery.validate.js"></script>
     
		<!-- Theme Initialization Files -->
        <!-- Theme Base, Components and Settings -->
        <script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
        
        <!-- Theme Custom -->
        <script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
        
        <!-- Theme Initialization Files -->
        <script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>
		
        <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
        <script src="<?php echo base_url(); ?>assets/javascripts/forms/examples.validation.js"></script>        
        <script>
            CKEDITOR.replace( 'ckeditor' );
        </script>
	</body>
</html>