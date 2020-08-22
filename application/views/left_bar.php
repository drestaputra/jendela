<?php $cont=$this->uri->segment(1, 0); ?>
<aside id="sidebar-left" class="sidebar-left">
				
					<div class="sidebar-header">
						<div class="sidebar-title">
							Navigation
						</div>
						<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
							<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
						</div>
					</div>
				
					<div class="nano">
						<div class="nano-content">
							<nav id="menu" class="nav-main" role="navigation">
								<ul class="nav nav-main">
									<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="dashboard"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('dashboard'); ?>">
											<i class="fa fa-home" aria-hidden="true"></i>
											<span>Dashboard</span>
										</a>
									</li>
									<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="survei"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('survei'); ?>">											
											<i class="fa fa-list" aria-hidden="true"></i>
											<span>Survei</span>
										</a>
									</li>
									<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="kategori_buku"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('kategori_buku'); ?>">											
											<i class="fa fa-list-alt" aria-hidden="true"></i>
											<span>Kategori Buku</span>
										</a>
									</li>
									<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="buku"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('buku'); ?>">											
											<i class="fa fa-book" aria-hidden="true"></i>
											<span>Buku</span>
										</a>
									</li>
									<li <?php if (isset($cont) AND trim($cont)!="" AND $cont=="site_configuration"): ?>
										 class="nav-active"
									<?php endif ?>>
										<a href="<?php echo base_url('site_configuration/edit'); ?>">							
											<i class="fa fa-cog" aria-hidden="true"></i>
											<span>Pengaturan</span>
										</a>
									</li>

								</ul>
							</nav>
				
							<hr class="separator" />
				
							
				
							<hr class="separator" />
				
							
						</div>
				
					</div>
				
				</aside>