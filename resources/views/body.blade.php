<div class="main main-raised">
	<div class="container mainn-raised" style="width:100%;">

		<div id="myCarousel" class="carousel slide" data-ride="carousel">
			<!-- Indicators -->


			<!-- Wrapper for slides -->
			<div class="carousel-inner">

				<div class="item active">
					<img src="img/banners.jpg" alt="Los Angeles" style="width:100%;">

				</div>

				<div class="item">
					<img src="img/banners (2).jpg" style="width:100%;">

				</div>

				<div class="item">
					<img src="img/banners (3).png" alt="New York" style="width:100%;">
				</div>
				<div class="item">
					<img src="img/product02.png" alt="New York" style="width:100%;">
				</div>
				<div class="item">
					<img src="img/banners (5).png" alt="New York" style="width:100%;">
				</div>

				<!-- Left and right controls -->
				<a class="left carousel-control _26sdfg" href="#myCarousel" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left"></span>
					<span class="sr-only">Previous</span>
				</a>
				<a class="right carousel-control _26sdfg" href="#myCarousel" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right"></span>
					<span class="sr-only">Next</span>
				</a>
			</div>
		</div>






		<!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title">New Products</h3>
							<div class="section-nav">
								<ul class="section-tab-nav tab-nav">
									<li class="active"><a data-toggle="tab" href="#tab1">Nugget</a></li>
									<li><a data-toggle="tab" href="#tab1">Sosis</a></li>
									<li><a data-toggle="tab" href="#tab1">Daging</a></li>
									<li><a data-toggle="tab" href="#tab1">Bakso</a></li>
								</ul>
							</div>
						</div>
					</div>
					<!-- /section title -->

					<!-- Products tab & slick -->
					<div class="col-md-12 mainn mainn-raised">
						<div class="row">
							<div class="products-tabs">
								<!-- tab -->
								<div id="tab1" class="tab-pane active">
									<div class="products-slick" data-nav="#slick-nav-1">

										<?php
										include 'db.php';


										$product_query = "SELECT * FROM products,categories WHERE product_cat=cat_id AND product_id BETWEEN 70 AND 75";
										$run_query = pg_query($con, $product_query);
										if (pg_num_rows($run_query) > 0) {

											while ($row = pg_fetch_assoc($run_query)) {
												$pro_id = $row['product_id'];
												$pro_cat = $row['product_cat'];
												$pro_brand = $row['product_brand'];
												$pro_title = $row['product_title'];
												$pro_price = $row['product_price'];
												$pro_image = $row['product_image'];

												$cat_name = $row["cat_title"];

												echo "
				
                        
                                
								<div class='product'>
									<a href='product.php?p=$pro_id'><div class='product-img'>
										<img src='product_images/$pro_image' style='max-height: 170px;' alt=''>
										<div class='product-label'>
											<span class='sale'>-30%</span>
											<span class='new'>NEW</span>
										</div>
									</div></a>
									<div class='product-body'>
										<p class='product-category'>$cat_name</p>
										<h3 class='product-name header-cart-item-name'><a href='product.php?p=$pro_id'>$pro_title</a></h3>
										<h4 class='product-price header-cart-item-info'>$pro_price<del class='product-old-price'>Rp 4.000.000</del></h4>
										<div class='product-rating'>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
											<i class='fa fa-star'></i>
										</div>
										<div class='product-btns'>
											<button class='add-to-wishlist'><i class='fa fa-heart-o'></i><span class='tooltipp'>add to wishlist</span></button>
											<button class='add-to-compare'><i class='fa fa-exchange'></i><span class='tooltipp'>add to compare</span></button>
											<button class='quick-view'><i class='fa fa-eye'></i><span class='tooltipp'>quick view</span></button>
										</div>
									</div>
									<div class='add-to-cart'>
										<button pid='$pro_id' id='product' class='add-to-cart-btn block2-btn-towishlist' href='#'><i class='fa fa-shopping-cart'></i> add to cart</button>
									</div>
								</div>
                               
							
                        
			";
											};
										}
										?>
										<!-- product -->


										<!-- /product -->


										<!-- /product -->
									</div>
									<div id="slick-nav-1" class="products-slick-nav"></div>
								</div>
								<!-- /tab -->
							</div>
						</div>
					</div>
					<!-- Products tab & slick -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- HOT DEAL SECTION -->
		<div id="hot-deal" class="section mainn mainn-raised">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<div class="hot-deal">
							<ul class="hot-deal-countdown">
								<li>
									<div>
										<h3>00</h3>
										<span>Days</span>
									</div>
								</li>
								<li>
									<div>
										<h3>01</h3>
										<span>Hours</span>
									</div>
								</li>
								<li>
									<div>
										<h3>17</h3>
										<span>Mins</span>
									</div>
								</li>
								<li>
									<div>
										<h3>25</h3>
										<span>Secs</span>
									</div>
								</li>
							</ul>
							<h2 class="text-uppercase">hot deal this week</h2>
							<p>All New Products Up to 50% OFF</p>
							<a class="primary-btn cta-btn" href="store.php">Shop now</a>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /HOT DEAL SECTION -->
	</div>
</div>
</div>

</div>
<!-- /row -->
</div>
<!-- /container -->
</div>
<!-- /SECTION -->
</div>