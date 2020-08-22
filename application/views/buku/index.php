<!doctype html>
<html class="fixed">
	<head>

		<!-- Basic -->
		<meta charset="UTF-8">
		<title>Buku | <?php echo function_lib::get_config_value('website_name'); ?></title>
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
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendor/select2/select2.css" />

		<!-- Theme CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/theme.css" />

		<!-- Skin CSS -->
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/stylesheets/skins/default.css" />
		
		<!-- flexigrid -->

		<link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/flexigrid/css/flexigrid.css" />
        <link type="text/css" rel="stylesheet" href="<?php echo base_url(); ?>assets/flexigrid/button/style.css" />

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
						<h2>Buku</h2>
					
						<div class="right-wrapper pull-right">
							<ol class="breadcrumbs">
								<li>
									<a href="index.html">
										<i class="fa fa-home"></i>
									</a>
								</li>
								<li><span>Buku</span></li>
							</ol>
					
							<a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
						</div>
					</header>
					<div class="row">
						<?php if (trim($this->input->get('status'))!=""): ?>
                                <?php echo function_lib::response_notif($this->input->get('status'),$this->input->get('msg')); ?>
                            <?php endif ?>
						<div class="col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4><i class="fa fa-search-plus"> Pencarian</i></h4>
								</div>
								<div class="panel-body">
									<form>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-md-3">Judul Buku</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control input-sm" name="judul_buku" id="judul_buku">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3">Deskripsi</label>
                                                    <div class="col-md-9">
                                                        <input type="text" class="form-control input-sm" name="deskripsi_buku" id="deskripsi_buku">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                 <div class="form-group">
                                                    <label class="col-md-3">Survei</label>
                                                    <div class="col-md-9">
                                                    <select class="form-control select2" id="survei" onchange="getKategori($(this));">
                                                        <option value=""> Pilih survei</option>
                                                        <?php foreach ($survei as $key => $value): ?>
                                                            <option value="<?php echo $value['id_survei'] ?>"><?php echo $value['nama_survei']; ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3">Kategori</label>
                                                    <div class="col-md-9">
                                                        <select class="form-control select2" id="kategori_buku">
                                                            <option value="">Semua kategori</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
										<br>
                                       
										<div class="form-group">
											<button class="btn btn-primary pull-right" onclick="grid_reload();return false;"><i class="fa fa-search"></i> Cari</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default">

						<div class="panel-body">
							<table id="gridview" style="display:none;"></table>
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
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="<?php echo base_url(); ?>assets/vendor/jquery-appear/jquery.appear.js"></script>
        <script src="<?php echo base_url(); ?>assets/vendor/select2/select2.js"></script>
				
		
		
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="<?php echo base_url(); ?>assets/javascripts/theme.init.js"></script>		
		<!-- flexigrid -->
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/flexigrid/js/flexigrid.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/flexigrid/js/json2.js"></script>
        
		<!-- Examples -->
		<script type="text/javascript">
			         $('.select2').select2();
                     function getKategori(selectedVal){
                        $('#kategori_buku').empty().trigger('change');
                        var selected = selectedVal.val();                        
                        var data = {
                            id: "",
                            text: "Semua kategori"
                        };
                        var newOptionDefault = new Option(data.text, data.id, false, false);                                
                        $('#kategori_buku').append(newOptionDefault).trigger('change');                                
                        $.getJSON('<?php echo base_url('buku/getKategori') ?>', {id_survei: selected}, function(json, textStatus) {
                            json.data.forEach( function(element, index) {
                                var data = {
                                    id: element['id_kategori_buku'],
                                    text: element['nama_kategori_buku']
                                };

                                var newOption = new Option(data.text, data.id, false, false);                                
                                $('#kategori_buku').append(newOption).trigger('change');                                
                            });
                        });

                     }
                    $("#gridview").flexigrid({
                        dataType: 'json',
                        colModel: [
						
                            { display: 'No', name: 'no', width: 30, sortable: true, align: 'right' },
                            { display: 'Aksi', name: 'actions', width: 100, sortable: false, align: 'center' },
                            { display: 'Judul', name: 'judul_buku', width: 120, sortable: true, align: 'center' },
                            // { display: 'Rating', name: 'rating_buku', width: 70, sortable: true, align: 'center' },
                            { display: 'Deskripsi', name: 'deskripsi_buku', width: 120, sortable: true, align: 'center' },
                            { display: 'View', name: 'jumlah_view', width: 70, sortable: true, align: 'center' },
                            { display: 'Favorit', name: 'jumlah_favorit', width: 70, sortable: true, align: 'center' },
                            { display: 'Gambar Buku', name: 'gambar_buku', width: 200, sortable: true, align: 'center' },
                            { display: 'Kategori', name: 'kategori_buku', width: 200, sortable: true, align: 'center' },
                           
                        ],
                        buttons: [
                            { display: '<i class="fa fa-plus"> Tambah</i>', name: 'add', bclass: '', onpress: tambah },
                            { separator: true },                            
                            
                        ],
                        buttons_right: [
                        ],
                      
                        sortname: "id",
                        sortorder: "asc",
                        usepager: true,
                        title: ' ',
                        useRp: true,
                        rp: 50,
                        showTableToggleBtn: false,
                        showToggleBtn: true,
                        width: 'auto',
                        height: '300',
                        resizable: false,
                        singleSelect: false
                    });
                    function tambah(){
                    	window.location='<?php echo base_url('buku/tambah'); ?>';
                    }
                    
                    function act_delete(com, grid) {
                        var grid_id = $(grid).attr('id');
                        grid_id = grid_id.substring(grid_id.lastIndexOf('grid_') + 5);

                        if($('.trSelected', grid).length > 0) {
                            var title = '';
                            if (com == 'delete') {
                                title = 'Hapus';
                            }

                            var conf = confirm(title + ' ' + $('.trSelected', grid).length + ' data?');
                            if(conf == true) {
                                var arr_id = [];
                                var i = 0;
                                $('.trSelected', grid).each(function() {
                                    var id = $(this).attr('data-id');
                                    arr_id.push(id);
                                    i++;
                                });
                                $.ajax({
                                    type: 'POST',
                                    url: '<?php echo base_url().'admin/product/act_delete';?>',
                                    data: com + '=true&item=' + JSON.stringify(arr_id),
                                    dataType: 'json',
                                    success: function(response) {
                                        $("div.alert").show();
                                        if(response['status']==200)
                                        {
                                            $("div.alert").removeClass('alert-error');
                                            $("div.alert").addClass('alert-success');
                                        }
                                        else
                                        {
                                            $("div.alert").removeClass('alert-success');
                                            $("div.alert").addClass('alert-error');
                                        }
                                        $("div.alert").find('.message').html(response['message']);
                                        grid_reload();
                                        return false;

                                    },
                                    error:function(){
                                        alert('an error has occurred, please try again');
                                        grid_reload();    
                                    }
                                });
                            }
                        }
                    }


                    $(document).ready(function() {
                        grid_reload();
                    });

                   

                    function grid_reload() {
                        var judul_buku=$("#judul_buku").val();
                        var deskripsi=$("#deskripsi_buku").val();
                        var survei=$("#survei").val();
                        var kategori_buku=$("#kategori_buku").val();
                        
                        var url_service="?judul_buku="+judul_buku+
                        "&deskripsi="+deskripsi+
                        "&survei="+survei+
                        "&kategori_buku="+kategori_buku;
                        $("#gridview").flexOptions({url:'<?php echo base_url(); ?>buku/get_data'+url_service}).flexReload();
                    }
                    
                    function delete_transaction(id)
                    {
                        if(confirm('Delete?'))
                        {
                             var jqxhr=$.ajax({
                            url:'<?php echo base_url()?>admin/product/delete/'+id,
                            type:'get',
                            dataType:'json',
                            
                        });
                        jqxhr.success(function(response){
                            $("div.alert").show();

                            if(response['status']==200)
                            {
                                $("div.alert").removeClass('alert-error');
                                $("div.alert").addClass('alert-success');
                            }
                            else
                            {
                                $("div.alert").removeClass('alert-success');
                                $("div.alert").addClass('alert-error');
                            }
                            $("div.alert").find('.message').html(data_arr['message']);

                            grid_reload();
                            return false;

                        });
                        jqxhr.error(function(){
                            alert('an error has occurred, please try again.');
                            grid_reload();
                            return false;
                        });
                        }
                        return false;
                       
                    }

                    
                </script>  
          
	</body>
</html>