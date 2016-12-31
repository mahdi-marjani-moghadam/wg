
		<!-- Page Sub Menu
		============================================= -->
		<div id="page-menu" class="no-sticky">

			<div id="page-menu-wrap">

				<div class="container clearfix">

					<div class="menu-title">Portfolio <span>Variations</span></div>

					<nav>
						<ul>
							<li class="current"><a href="portfolio-6.html"><div>With Margin</div></a></li>
							<li><a href="portfolio-6-nomargin.html"><div>No Margin</div></a></li>
							<li><a href="portfolio-6-notitle.html"><div>No Title</div></a></li>
							<li><a href="portfolio-6-title-overlay.html"><div>Title Overlay</div></a></li>
							<li><a href="portfolio-6-fullwidth.html"><div>Full Width</div></a></li>
							<li><a href="portfolio-6-fullwidth-notitle.html"><div>Full Width - No Title</div></a></li>
						</ul>
					</nav>

					<div id="page-submenu-trigger"><i class="icon-reorder"></i></div>

				</div>

			</div>

		</div><!-- #page-menu end -->



		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap">

				<div class="container clearfix">

					<!-- Portfolio Filter
					============================================= -->
					<ul id="portfolio-filter" class="portfolio-filter clearfix" data-container="#portfolio">

						<li class="activeFilter"><a href="#" data-filter="*">Show All</a></li>
						<li><a href="#" data-filter=".pf-icons">Icons</a></li>
						<li><a href="#" data-filter=".pf-illustrations">Illustrations</a></li>
						<li><a href="#" data-filter=".pf-uielements">UI Elements</a></li>
						<li><a href="#" data-filter=".pf-media">Media</a></li>
						<li><a href="#" data-filter=".pf-graphics">Graphics</a></li>

					</ul><!-- #portfolio-filter end -->

					<div id="portfolio-shuffle" class="portfolio-shuffle" data-container="#portfolio">
						<i class="icon-random"></i>
					</div>

					<div class="clear"></div>

					<!-- Portfolio Items
					============================================= -->
					<div id="portfolio" class="portfolio grid-container portfolio-6 clearfix">
                        <? foreach ($list['list'] as $k => $v):?>
                            <article class="portfolio-item pf-icons pf-illustrations">
                                <div class="portfolio-image">
                                    <div class="fslider" data-arrows="false" data-speed="400" data-pause="4000">
                                        <div class="flexslider">
                                            <div class="slider-wrap">
                                                <div class="slide"><a href="portfolio-single-gallery.html"><img src="images/portfolio/4/4.jpg" alt="Morning Dew"></a></div>
                                                <div class="slide"><a href="portfolio-single-gallery.html"><img src="images/portfolio/4/4-1.jpg" alt="Morning Dew"></a></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portfolio-overlay" data-lightbox="gallery">
                                        <a href="images/portfolio/full/4.jpg" class="left-icon" data-lightbox="gallery-item"><i class="icon-line-stack-2"></i></a>
                                        <a href="images/portfolio/full/4-1.jpg" class="hidden" data-lightbox="gallery-item"></a>
                                        <a href="portfolio-single-gallery.html" class="right-icon"><i class="icon-line-ellipsis"></i></a>
                                    </div>
                                </div>
                                <div class="portfolio-desc">
                                    <h3><a href="portfolio-single-gallery.html"><?=$v['title']?></a></h3>
                                    <span><a href="#"><a href="#">Icons</a>, <a href="#">Illustrations</a></span>
                                </div>
                            </article>
                        <? endforeach;?>








					</div><!-- #portfolio end -->

				</div>

			</div>

		</section><!-- #content end -->
