<button class="btn float-end  new-bt-btn new-sidebar-main" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button">
	<i class="fas fa-angle-left fs-3" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"></i>
</button>
<!-- tabs -->
<div class="row">
	<div class="col-md-12">

		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-8">
				<div class="row pa-10">
					<ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
						<li class="nav-item" role="presentation">
							<a class="nav-link" data-bs-toggle="pill" href="#tab3-content"><?= $helper->getHebrewText('frame'); ?></a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link" data-bs-toggle="pill" href="#tab2-content"><?= $helper->getHebrewText('printing'); ?></a>
						</li>
						<li class="nav-item" role="presentation">
							<a class="nav-link active" data-bs-toggle="pill" href="#tab1-content"><?= $helper->getHebrewText('image'); ?></a>
						</li>
					</ul>

					<div class="row pa-10 image-uploader">
						<div class="col-md-3"></div>
						<div class="col-md-3 col-6">
							<div class="variants">
								<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#staticBackdrop" class='file'>
									<label for='input-file'>
										<img src="<?php echo plugins_url('file-plus-512.webp', dirname(__FILE__)); ?>" style="width: 90px;">
									</label>
								</a>
							</div>
							<h5 class="text-center"><?= $helper->getHebrewText('choose_an_image_from_the_library'); ?></h5>
						</div>
						<div class="col-md-3 col-6">
							<div class="variants">
								<a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#staticBackdrop1" class='file'>
									<label for='input-file'>
										<img src="<?php echo plugins_url('av5c8336583e291842624.png', dirname(__FILE__)); ?>" style="width: 95px;">
									</label>
								</a>
							</div>
							<h5 class="text-center"><?= $helper->getHebrewText('upload_a_picture'); ?></h5>
						</div>
						<div class="col-md-3"></div>
					</div>
				</div>
				<div class="row pa-10 image_display">
					<span id="image_content"></span>
					<input type="hidden" name="print_image" id="print_image" value="" />
				</div>
				<!-- Loader will be removed start -->
				<span class="img_loader"><img src="<?php echo plugins_url("ajax-loader.gif", dirname(__FILE__)); ?>"></span>
				<!-- Loader will be removed end -->
				<div class="img-btn-upload pt-5">
					<button class="crop"><?= $helper->getHebrewText('next'); ?></button>
					<p class="open-p"><?= $helper->getHebrewText('preview'); ?></p>
				</div>
				<img class="preview" />
			</div>
			<div class="col-md-2"></div>
		</div>
	</div>
</div>

<div class="offcanvas offcanvas-start w-40  new-content new-sidebar-main" tabindex="-1" id="offcanvas" data-bs-keyboard="false" data-bs-backdrop="false">
	<div class="offcanvas-header">
		<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"> <i class="fas fa-angle-right"></i></button>
	</div>
	<div class="offcanvas-body px-0">
		<div class="sidebar">
			<input type="hidden" name="product_id" id="product_id" value="<?php echo $productID; ?>">
			<input type="hidden" name="frame_selected" id="frame_selected" value="">
			<div class="accordion" id="accordionPanelsStayOpenExample">
				<div class="accordion-item">
					<h2 class="accordion-header" id="panelsStayOpen-headingOne">
						<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne"><?= $helper->getHebrewText('image_fences'); ?></button>
					</h2>
					<div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
						<div class="accordion-body">
							<div class="img-upload">
								<a href="javascript:void(0);">
									<i class="far fa-trash-alt colr"></i>
								</a>
								<div class="content">
									<form id="form1">
										<div class="right">
											<div id="file-details">
												<span class="file-name"></span>
												<span class="file-dimensions"></span>
											</div>
										</div>
										<div class="left">
											<span id="img-uploaded"><img src="<?php echo plugins_url("sidebar-no-image.png", dirname(__FILE__)); ?>" alt="your image" /></span>
										</div>

									</form>
								</div>
							</div>
							<div class="data-cri">
								<a href="">
									<div class="row">
										<div class="col-md-10 col-10">
											<div class="open-side">
												<h5><?= $helper->getHebrewText('the_image_is_of_excellent_quality'); ?></h5>
												<p>300 DPI</p>
											</div>
										</div>
										<div class="col-md-2 col-2">
											<i class="fas fa-check-circle check-icon"></i>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-content" style="padding: 0;">
					<div class="tab-pane active" id="tab1-content">
						<div class="size-sec">
							<h4>מידת הדפסה</h4>
							<p class="pt-3">
								<i class="cor-pix"><?php echo $helper->getHebrewText('cm'); ?></i>
								<span class="show-pi">
									<input name="print_width" class="print_size" id="print_width" type="number" min="0" max="120" onchange="onsizekeyup(this, 0);" onkeyup="onsizekeyup(this, 0);" pattern="[0-9]" value="<?= isset($_POST['width']) ? $_POST['width'] : 0 ?>" disabled />
									<input name="image_width" id="image_width" type="hidden" value="" />
								</span> <?php echo $helper->getHebrewText('width'); ?>
							</p>
							<p class="pt-2">
								<i class="cor-pix"><?php echo $helper->getHebrewText('cm'); ?></i>
								<span class="show-pi">
									<input name="print_height" class="print_size" id="print_height" type="number" min="0" max="140" onchange="onsizekeyup(this, 1);" onkeyup="onsizekeyup(this, 1);" pattern="[0-9]" value="<?= isset($_POST['height']) ? $_POST['height'] : 0 ?>" disabled />
									<input name="image_height" id="image_height" type="hidden" value="" />
								</span> <?php echo $helper->getHebrewText('height'); ?>
							</p>
							<div class="form-group">
								<span class="print_size_error" id="print_size_error"></span>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="tab2-content">
						<div class="accordion-item print_type_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingTwo">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
									<?= $helper->getHebrewText('print_settings') ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-1">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseTwo" class="accordion-collapse" aria-labelledby="panelsStayOpen-collapseTwo">
								<div class="accordion-body">
									<div class="size-sec">
										<h4><?= $helper->getHebrewText('print_size') ?></h4>
										<p class="pt-3"><i class="fas fa-project-diagram cor-pix"></i> <span class="show-pi" id="static_width"><?= isset($_POST['width']) ? $_POST['width'] : 0 ?></span> ס"מ ס"מ</p>
										<p class="pt-2"><i class="fas fa-project-diagram cor-pix"></i> <span class="show-pi" id="static_height"><?= isset($_POST['height']) ? $_POST['height'] : 0 ?></span> חומר להדפס </p>
										<div class="form-group">
											<span class="print_size_error" id="print_size_error"></span>
										</div>
									</div>
									<h4 class="list"><?= $helper->getHebrewText('printable_material'); ?></h4>
									<?php
									$prints = $jsondata['categories']['prints'];
									if (isset($prints) && !empty($prints)) {
									?>
										<?php
										$key = 0;
										foreach ($prints as $index => $print) {
											$print_lang = $helper->getHebrewText($index);
										?>
											<h4 class="list-type"><i class="fas fa-info-circle font-i"></i><?= $print_lang ?> <input type="radio" id="print_type_<?= $key; ?>" name="print_type" class="print_type" value="<?= $index ?>"> </h4>
										<?php
											$key++;
										} ?>
									<?php
									}
									?>
								</div>
							</div>
						</div>
						<div class="accordion-item papertype_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingThree">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
									<?php echo $helper->getHebrewText('choose_paper_type'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-2">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseThree">
								<div class="accordion-body">
									<div id='print_papertype_select'></div>
								</div>
							</div>
						</div>
						<div class="accordion-item thickness_select_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingFour">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
									<?php echo $helper->getHebrewText('choose_thickness'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-3">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseFour">
								<div class="accordion-body">
									<div id='print_thickness_select'></div>
								</div>
							</div>
						</div>
						<div class="accordion-item thickness_input_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingFive">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
									<?php echo $helper->getHebrewText('choose_thickness'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-4">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseFive">
								<div class="accordion-body">
									<p class="pt-3"><i class="fas fa-project-diagram cor-pix"></i> <span class="show-pi" id="thickness_content"></span><?php echo $helper->getHebrewText('thickness'); ?></p>
								</div>
							</div>
						</div>
						<div class="accordion-item thickness_dimensions">
							<h2 class="accordion-header" id="panelsStayOpen-headingSix">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="false" aria-controls="panelsStayOpen-collapseSix">
									<?= $helper->getHebrewText('print_settings'); ?><div class="button-cover">
										<div class="button r accordion-switch" id="button-5">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
						</div>
						<div class="accordion-item finishing_select_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingSeven">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSeven" aria-expanded="false" aria-controls="panelsStayOpen-collapseSeven">
									<?= $helper->getHebrewText('choose_finishing'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-6">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseSeven" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseSeven">
								<div class="accordion-body">
									<div id='print_finishing_select'></div>
								</div>
							</div>
						</div>
						<div class="accordion-item finishing_input_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingEight">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseEight" aria-expanded="false" aria-controls="panelsStayOpen-collapseEight">
									<?php echo $helper->getHebrewText('choose_finishing'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-7">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseEight" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseEight">
								<div class="accordion-body">
									<h4><span id="finishing_content"></span></h4>
									<div class="button-cover mini">
										<input type="checkbox" name="print_finishing_input" class="print_finishing_input" id="print_finishing_input" value="" onclick="onfinishingclick(this);" />
									</div>
								</div>
							</div>
						</div>
						<div class="accordion-item laminate_hanging_select_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingNine">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseNine" aria-expanded="false" aria-controls="panelsStayOpen-collapseNine">
									<?php echo $helper->getHebrewText('choose_lamination'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-8">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseNine" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseNine">
								<div class="accordion-body">
									<div id='print_laminate_hanging_select'></div>
								</div>
							</div>
						</div>
						<div class="accordion-item laminate_hanging_input_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingTen">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTen" aria-expanded="false" aria-controls="panelsStayOpen-collapseTen">
									<?php echo $helper->getHebrewText('laminate'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-9">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseTen" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseTen">
								<div class="accordion-body">
									<h4><span id="laminate_hanging_content"></span></h4>
									<div class="button-cover mini">
										<div class="button2 r" id="button-2">
											<input type="checkbox" name="print_laminate_hanging_input" class="print_laminate_hanging_input" id="print_laminate_hanging_input" value="" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
									<!-- <p class="pt-3"><i class="fas fa-project-diagram cor-pix"></i> <span class="show-pi" id="laminate_hanging_content"></span></p> -->
								</div>
							</div>
						</div>
						<div class="accordion-item field_extra_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingEleven">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseEleven" aria-expanded="false" aria-controls="panelsStayOpen-collapseEleven">
									<?php echo $helper->getHebrewText('select_extras'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-10">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseEleven" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseEleven">
								<div class="accordion-body">
									<div id='frame_extra_select'></div>
								</div>
							</div>
						</div>
						<div class="accordion-item field_extra_thickness_items">
							<h2 class="accordion-header" id="panelsStayOpen-headingTwelve">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwelve" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwelve">
									<?php echo $helper->getHebrewText('select_width'); ?>
									<div class="button-cover">
										<div class="button r accordion-switch" id="button-4">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseTwelve" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-collapseTwelve">
								<div class="accordion-body">
									<!-- <input id="frame_extra_thickness" min="2" max="10" value="2" type="number" name="frame_extra_thickness" onchange="onquantitychange(this)" onkeyup="onextrathicknesschange()" pattern="[0-9]" /> -->
									<div class="wrapper">
										<div class="range-slider">
											<span class="input-label"><?php echo $helper->getHebrewText('cm'); ?></span>
											<output>2</output>
											<input type="range" id="frame_extra_thickness" name="frame_extra_thickness" class="js-range-slider frame_extra_thickness" min="2" max="10" value="2" step="1" oninput="this.previousElementSibling.value = this.value">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="tab3-content">

						<div class="accordion-item frame">
							<h2 class="accordion-header" id="panelsStayOpen-headingThree">
								<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
									הגדרות מסגרת
									<div class="button-cover">
										<div class="button r" id="button-1">
											<input type="checkbox" class="checkbox" />
											<div class="knobs"></div>
											<div class="layer"></div>
										</div>
									</div>
								</button>
							</h2>
							<div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse " aria-labelledby="panelsStayOpen-collapseThree">
								<div class="accordion-body">
									<!-- <h2 class="new-accor">פספרטו
										<div class="button-cover mini">
											<div class="button2 r accordion-switch" id="button-2">
												<input type="checkbox" class="checkbox" />
												<div class="knobs"></div>
												<div class="layer"></div>
											</div>
										</div>
									</h2>
									<h4 class="list">שיטת שילוב </h4>
									<h4 class="list-type"><i class="fas fa-info-circle font-i"></i> Matt - מסגרת על-גבי התמונה <input type="radio" id="html" name="fav_language" value="HTML"></h4>
									<h4 class="list-type"><i class="fas fa-info-circle font-i"></i> BackGround - תמונה על בירקע <input type="radio" id="html" name="fav_language" value="HTML"></h4>
									<h4 class="list">מידה </h4>
									<div class="wrapper">
										<div class="range-slider">
											<span class="input-label"><?php echo $helper->getHebrewText('cm'); ?></span>
											<output>2</output>
											<input type="range" id="" name="" class="js-range-slider" min="2" max="10" value="2" step="1" oninput="this.previousElementSibling.value = this.value">
										</div>
									</div>
									<h4 class="list">צבע </h4>
									<h4 class="list-type"> שמנת <input type="radio" id="html" name="fav_language" value="HTML"></h4>
									<h4 class="list-type">לבן פנינה <input type="radio" id="html" name="fav_language" value="HTML"></h4>
									<h4 class="list-type"> שחור מט <input type="radio" id="html" name="fav_language" value="HTML"></h4>
									<h4 class="list-type"> ירוק <input type="radio" id="html" name="fav_language" value="HTML"></h4>
									<hr height="3px" /> -->
									<h2 class="new-accor"><?php echo $helper->getHebrewText('with_frame'); ?>
										<div class="button-cover mini">
											<div class="button2 r accordion-switch" id="frame_switch">
												<input type="checkbox" name="frame_required" id="frame_required" class="checkbox" value="" onclick="onframerequired(this)" ; />
												<div class="knobs"></div>
												<div class="layer"></div>
											</div>
										</div>
									</h2>
									<div class="frame_grid" style="display:none;">
										<?php include(plugin_dir_path(dirname(__FILE__)) . 'frames/frames-list.php'); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="last-sec">
				<div class="frame_info">
					<div class="info"></div>
				</div>
				<div class="info">
					<?php echo $helper->getHebrewText('shipment_cost_will_be_priced_seperately'); ?>
				</div>
				<h2>₪ <span class="value" id="price">0.00</span></h2>
				<input type="hidden" name="input_price" id="input_price" value="" />
				<a class="back-apply" href=""><?php echo $helper->getHebrewText('add_to_cart'); ?></a>
			</div>
		</div>
	</div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
			</div>
			<div class="modal-body">
				<div class="upload-main-wrapper">
					<div class="upload-sec">
						<h2>גרור ושחרר קובץ תמונה</h2>
						<p><span class="text-file">בחר </span> או </p>
					</div>
					<div class="upload-wrapper">
						<input type="file" id="upload-file1">
						<!-- <img src="<?php echo plugins_url("av5c8336583e291842624.png", dirname(__FILE__)); ?>" style="width: 95px;"> -->
						<img src="<?php echo plugins_url("file-plus-512.webp", dirname(__FILE__)); ?>" style="width: 112px;">
						<div class="file-success-text">
							<svg version="1.1" id="check" x="0px" y="0px" viewBox="0 0 100 100" xml:space="preserve">
								<circle style="fill:rgba(0,0,0,0);stroke:#00a9a6;stroke-width:10;stroke-miterlimit:10;" cx="49.799" cy="49.746" r="44.757" />
							</svg>
						</div>
						<div class="d-flex">
							<p id="file-upload-name" class="fon-berfor"></p>
							<a class="close-img-sec" href="javascript:void(0)">X</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function onextrathicknesschange() {
		calc();
	}

	function getMul(total, num) {
		return total * num;
	}

	function onquantitychange(elem) {
		calc();
	}

	function onframerequired(elem) {
		if ($(elem).is(":checked") == true) {
			$(".frame_grid").css("display", "block");
			//onframechose($(".frame_img:eq(0)"));
		} else {
			$(".frame_grid").css("display", "none");
			$("#frame_selected").val("");
			$(".frame_img").removeClass("selected");
			//$(".frame_grid, .selected_frame_detail").css("display", "none");
			$(".frame_grid").css("display", "none");
			//$("#selected_frame_name, #selected_frame_description, #selected_frame_color, #selected_frame_type, #selected_frame_price").html("");
		}
		calc();
	}

	function onsizekeyup(elem, index) {
		var image_width = $("#image_width").val();
		var image_height = $("#image_height").val();
		var elemVal = $(elem).val();
		var r = gcd(image_width, image_height);
		if (index === 0) {
			var width = elemVal;
			var height = width * r[1] / r[0];
			$("#print_width").val(Math.round(width));
			$("#print_height").val(Math.round(height));
			$("#static_width").text(Math.round(width));
			$("#static_height").text(Math.round(height));
		} else {
			var height = elemVal;
			var width = height * r[0] / r[1];
			$("#print_width").val(Math.round(width));
			$("#print_height").val(Math.round(height));
			$("#static_width").text(Math.round(width));
			$("#static_height").text(Math.round(height));
		}
		calc();
	}

	function calc() {
		var page = $(".print_type:checked").val();
		var print_width = parseInt($("#print_width").val());
		var print_height = parseInt($("#print_height").val());
		var image_width = $("#image_width").val();
		var image_height = $("#image_height").val();
		var thickness = $("#print_thickness_select input[type='radio']:checked").val();
		var print_finishing_select = $("#print_finishing_select.finishing_enabled input[type='radio']:checked").val();
		var print_finishing_check = $("#print_finishing_input.finishing_enabled input[type='checkbox']").is(":checked");
		var print_laminate_hanging_select = $("#print_laminate_hanging_select.laminate_enabled input[type='radio']:checked").val();
		var print_laminate_check = $("#print_laminate_check.laminate_enabled input[type='checkbox']").is(":checked");
		var quantity = 1;
		//var frame_extra = $("#frame_extra").val();
		if ((typeof print_width != 'undefined' && print_width > 0) && (typeof print_height != 'undefined' && print_height > 0)) {
			var r1 = gcd(print_width, print_height);
			var r2 = gcd(image_width, image_height);
			//if(print_width/r1 == image_width/r2 && print_height/r1 == image_height/r2)
			{
				$(".print_size_error").html("");
				$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__)); ?>', function(data) {
					if (typeof data.categories.prints[page] != 'undefined') {
						var max_dimensions = [];
						if (typeof data.categories.prints[page].sizes.max_size_one_side == "number") {
							max_dimensions = data.categories.prints[page].sizes.max_size_one_side;
							if (print_width > max_dimensions || print_height > max_dimensions) {
								$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__)); ?>', function(data) {
									$(".print_size_error").html(data['width_or_height_can_not_be_more_than'] + " " + max_dimensions);
								});
								$("#price").html("");
								$("#input_price").val(0);
								$("#add_to_cart").css("display", "none");
								return false;
							}
						} else {
							max_dimensions[0] = data.categories.prints[page].sizes.max_size_one_side[0];
							max_dimensions[1] = data.categories.prints[page].sizes.max_size_one_side[1];
							if (print_width > max_dimensions[0] || print_height > max_dimensions[1]) {
								$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__)); ?>', function(data) {
									$(".print_size_error").html(data['size_can_not_be_more_than'] + " " + max_dimensions[0] + "x" + max_dimensions[1]);
								});
								$("#price").html("");
								$("#input_price").val(0);
								$("#add_to_cart").css("display", "none");
								return false;
							}
						}
						var calc_price = 0;
						$(".print_size_error").html("");
						$(data.categories.prints[page].sizes.ranges).each(function(index) {
							var size = this;
							var dimension = (print_width * print_height) / 100;
							if (size[1] != 0 && (dimension >= size[0] && dimension <= size[1])) {
								calc_price += dimension * data.categories.prints[page].prices[index] / 100;
								return false;
							} else if (size[1] == 0 && dimension >= size[0]) {
								calc_price += dimension * data.categories.prints[page].prices[index] / 100;
								return false;
							}
						});
						if (typeof print_finishing_select != 'undefined' && print_finishing_select != "") {
							//var find = "_";
							//var re = new RegExp(find, 'g');
							//var print_finishing = print_finishing_select.replace(re, " ");
							$(data.categories.prints[page].finishing).each(function(index) {
								var finishing = this;
								if (print_finishing_select == finishing.type) {
									//if(page == 'paper')
									{
										calc_price += parseInt(finishing.price) * (print_width + print_height) * 2 / 100;
									}
									/* else
									{
										calc_price += finishing.price;
									} */
									return false;
								}
							});
						} else if (print_finishing_check == true) {
							$(data.categories.prints[page].finishing).each(function(index) {
								var finishing = this;
								//meter run
								//if(page == 'paper')
								{
									calc_price += parseInt(finishing.price) * (print_width + print_height) * 2 / 100;
								}
								/* else
								{
									calc_price += finishing.price;
								} */
								return false;
							});
						}

						if (typeof print_laminate_hanging_select != 'undefined' && print_laminate_hanging_select != "") {
							//var find = "_";
							//var re = new RegExp(find, 'g');
							//var print_laminate = print_laminate_hanging_select.replace(re, " ");
							$(data.categories.prints[page].laminate).each(function(index) {
								var laminate = this;
								if (print_laminate_hanging_select == laminate.type) {
									if (this.price_type == 'meter_run') {
										calc_price += parseInt(laminate.price) * (print_width + print_height) * 2 / 100;
									} else if (this.price_type == 'fixed') {
										calc_price += parseInt(laminate.price);
									} else {
										calc_price += parseInt(laminate.price);
									}
									return false;
								}
							});
						} else if (print_laminate_check == true) {
							$(data.categories.prints[page].laminate).each(function(index) {
								var laminate = this;
								//calc_price += laminate.price;
								calc_price += parseInt(laminate.price) * (print_width + print_height) * 2 / 100;
								return false;
							});
						}


						if (typeof data.categories.prints[page].min_price != "undefined" && calc_price < data.categories.prints[page].min_price) {
							calc_price = data.categories.prints[page].min_price;
						}

						$("#price").html(Math.round(calc_price) * quantity);
						$("#input_price").val(Math.round(calc_price) * quantity);
						$("#add_to_cart").css("display", "block");
						window.print_price = Math.round(calc_price) * quantity;
						if (['sticker', 'rug_PVC'].indexOf(page) === -1 && $("#frame_required").is(":checked") == true) {
							frame_calc();
						}

					}
				});
			}
			/*else
			{
				var r = gcd (image_width, image_height);
				$(".print_size_error").html("Width or height should have proportion of "+image_width/r+ ":"+ image_height/r);
				//$("#price").html("");
			}*/
		} else {
			$("#price").html("");
			$("#input_price").val(0);
			$("#add_to_cart").css("display", "none");
		}
	}

	function frame_calc() {
		var frame_type = $("#frame_type").val();
		if (frame_type != "from_catalogue") {
			var page = $(".print_type:checked").val();
			var frame_width = parseInt($("#print_width").val());
			var frame_height = parseInt($("#print_height").val());
			var frame_extra = $(".frame_extra:checked").val();
			//var frame_cover = $("#frame_cover").val();
			//var frame_colors = $("#frame_colors").val();
			//var quantity = $("#print_quantity").val();
			var frame_selected = $("#frame_selected").val();
			var frame_extra_thickness = parseInt($("#frame_extra_thickness").val());
			if (typeof frame_selected != "undefined" && frame_selected != "") {
				if (typeof frame_width != 'undefined' && frame_width > 0 && typeof frame_height != 'undefined' && frame_height > 0) {
					var frame_selected = JSON.parse(frame_selected);
					console.log(frame_selected);
					var price = frame_selected.price;
					console.log(price);
					price = parseInt((frame_width + frame_height) * 2 / 100 * price);
					console.log(price);
					$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__)); ?>', function(data) {
						if (typeof data.categories.frames != 'undefined') {
							//$("#frame_size_error").html("");
							/*if(typeof frame_cover != 'undefined')
							{
								$(data.categories.frames.sizes[0].covers).each(function(index){
									if(typeof this[frame_cover].price != 'undefined')
									{
										price += parseInt(this[frame_cover].price)*(frame_width * frame_height)/10000;
										return false;
									}
								});
							}*/
							//if(price < data.categories.frames.sizes[0].min_price)
							/*{
								price = data.categories.frames.sizes[0].min_price;
							}*/
							/*$(data.categories.frames.sizes[0].extras).each(function(index){
								if(this[frame_extra].length > 0 && (frame_width * frame_height) <= this[frame_extra][0].max_dimensions.reduce(getMul))
								{
									if(typeof this[frame_extra][0].price != 'undefined')
									{
										price += parseInt(this[frame_extra][0].price);
										return false;
									}
								}
								else if(this[frame_extra].length > 0 && (frame_width * frame_height) >= this[frame_extra][0].max_dimensions.reduce(getMul) && (frame_width * frame_height) <= this[frame_extra][1].max_dimensions.reduce(getMul))
								{
									if(typeof this[frame_extra][1].price != 'undefined')
									{
										price += parseInt(this[frame_extra][1].price);
									return false;
									}
								}
								else if(this[frame_extra].length > 0){
									price = 0;
									$("#frame_size_error").html("Size should be maximum "+this[frame_extra][1].max_dimensions[0]+"x"+this[frame_extra][1].max_dimensions[1]);
									return false;
								}
							});*/
							console.log(frame_extra);
							if (typeof frame_extra != 'undefined' && frame_extra != "") {
								var maxdimension = Math.max.apply(null, [frame_width, frame_height]);
								var mindimension = Math.min.apply(null, [frame_width, frame_height]);
								$(data.categories.prints[page].extras).each(function(index) {
									if (frame_extra != "Without" && (typeof this.max_dimensions != "undefined" && maxdimension <= Math.max.apply(null, [this.max_dimensions[0], this.max_dimensions[1]]) && mindimension <= Math.min.apply(null, [this.max_dimensions[0], this.max_dimensions[1]]))) {
										price += parseInt((frame_extra_thickness * 2) * 2 / 100 * price);
										if (typeof this.price != 'undefined') {
											price += parseInt(this.price);
											return false;
										}
									} else if (frame_extra != "Without") {
										if (typeof this.max_dimensions != "undefined") {
											price = 0;
											var self = this;
											$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__)); ?>', function(data) {
												$("#frame_size_error").html(data['size_can_not_be_more_than'] + " " + self.max_dimensions[0] + "x" + self.max_dimensions[1]);
											});
											return false;
										}

									}
								});
							}
							$("#price").html(parseInt(window.print_price) + Math.round(price));
							$("#input_price").val(parseInt(window.print_price) + Math.round(price));
						}

					});
				}
			}
		}
	}

	function gcd($num1, $num2) {
		for ($i = $num2; $i > 1; $i--) {
			if (($num1 % $i) == 0 && ($num2 % $i) == 0) {
				$num1 = $num1 / $i;
				$num2 = $num2 / $i;
			}
		}
		return [$num1, $num2];
	}


	function onframesizechange(elem) {
		var sizeindex = $(elem).val();
		var quantity = $("#print_quantity").val();
		$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__)); ?>', function(data) {
			if (sizeindex != "") {
				var discount = 0;
				var maxArray = [];
				$(data.categories.frames.sizes[1].discount_list).each(function(index) {
					maxArray = $.merge(maxArray, this.quantity);
				});
				maxQuantity = Math.max.apply(null, maxArray);
				if (quantity >= maxQuantity) {
					$(data.categories.frames.sizes[1].discount_list).each(function() {
						if ($.inArray(maxQuantity, this.quantity) != -1) {
							discount = this.discount;
							return false;
						}
					});
				} else {
					$(data.categories.frames.sizes[1].discount_list).each(function() {
						var max_quantity = Math.max.apply(null, this.quantity);
						var min_quantity = Math.min.apply(null, this.quantity);
						if (min_quantity != 0 && quantity >= min_quantity && quantity <= max_quantity) {
							discount = this.discount;
							return false;
						}
					});
				}
				var price = data.categories.frames.sizes[1].price_list[sizeindex] - data.categories.frames.sizes[1].price_list[sizeindex] * discount / 100;
				$("#price").html(parseInt(window.print_price) + Math.round(quantity * price));
				$("#input_price").val(parseInt(window.print_price) + Math.round(quantity * price));
				$("#add_to_cart").css("display", "block");
			} else {
				$("#price").html("");
				$("#input_price").val(0);
				$("#add_to_cart").css("display", "none");
			}
		});
	}

	function setLaminateHanging(item) {
		$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__)); ?>', function(data) {
			if (item.length > 1) {
				$(".laminate_hanging_select_items").css("display", "block");
				$(".laminate_hanging_input_items").css("display", "none");
				var laminate = "";
				$(item).each(function(index) {
					laminate += "<h4 class='list-type'><i class='fas fa-info-circle font-i'></i>" + data[this.type] + "<input type='radio' id='print_laminate_hanging_" + index + "' name='print_laminate_hanging' class='print_laminate_hanging' value=" + this.type + " onclick='onPrintLaminateHangingSelected(this.value);'> </h4>";
				});
				//$("label[for='print_laminate_hanging_select']").html(data['choose_lamination']);
				$("#print_laminate_hanging_input").removeClass("laminate_enabled");
				$("#print_laminate_hanging_select").addClass("laminate_enabled");
				//$(".print_laminate_hanging:eq(0) option").not($(".print_laminate_hanging:eq(0) option:eq(0)")).remove();
				$("#print_laminate_hanging_select input[type='radio']").remove();
				$("#print_laminate_hanging_select").html(laminate);
				$("#print_laminate_hanging_select input[type='radio']:eq(0)").attr("checked", true);

			} else {
				$(".laminate_hanging_input_items").css("display", "block");
				$(".laminate_hanging_select_items").css("display", "none");
				$("#print_laminate_hanging_select").removeClass("laminate_enabled");
				$("#print_laminate_hanging_input").addClass("laminate_enabled");
				$("#print_laminate_hanging_input").val(item[0].type);
				$("#laminate_hanging_content").html(data[item[0].type]);
			}
		});
	}

	function clearLaminateHanging() {
		$(".print_laminate_hanging_select").removeClass("laminate_enabled");
		$(".laminate_hanging_select_items, .laminate_hanging_input_items").css("display", "none");
		$("#print_laminate_hanging_input").val("");
		$("#print_laminate_hanging_select input[type='radio'], #print_laminate_hanging_input").removeAttr("checked");
	}

	function onfinishingclick(elem) {
		var page = $(".print_type:checked").val();
		var value = $(elem).is(':checked');
		if (value == true) {
			$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__)); ?>', function(data) {
				var content = data.categories.prints[page];
				if (typeof content.laminate != "undefined") {
					setLaminate(content.laminate);
				} else {
					clearLaminateHanging();
				}
			});
			calc();
		} else {
			clearLaminateHanging();
			calc();
		}

	}

	function onFrameExtraChange(val) {
		if (val != "without") {
			$(".field_extra_thickness_items").css("display", "block");
			$("#frame_extra_thickness").addClass("field_extra_thickness_enabled");
		} else {
			$(".field_extra_thickness_items").css("display", "none");
			$("#frame_extra_thickness").removeClass("field_extra_thickness_enabled");
		}
		calc();
	}

	function onPaperTypeSelected(val) {
		calc();
	}

	function onPrintThicknessSelected(val) {
		calc();
	}

	function onPrintFinishingSelected(val) {
		calc();
	}

	function onPrintLaminateHangingSelected(val) {
		calc();
	}

	$(document).ready(function() {
		$(".print_type").on("click", function() {
			var val = $(this).val();
			if (val != '') {
				if (['sticker', 'rug_PVC'].indexOf(val) === -1) {
					$(".frame_required").css("display", "block");
				} else {
					$(".frame_required").css("display", "none");
				}

				$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__)); ?>', function(data) {
					var content = data.categories.prints[val];
					if (val == 'paper') {
						$(".papertype_items, .field_extra_items").css("display", "block");
						$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__)); ?>', function(data) {
							var papertypes = "";
							$(content.types).each(function(index) {
								papertypes += "<h4 class='list-type'><i class='fas fa-info-circle font-i'></i>" + data[this.type] + "<input type='radio' id='print_papertype_" + index + "' name='print_papertype' class='print_papertype' value=" + this.type + " onclick='onPaperTypeSelected(this.value)'> </h4>";
							});
							$("#print_papertype_select").html(papertypes);
							var extras = "";
							$(content.extras).each(function(index) {
								extras += "<h4 class='list-type'><i class='fas fa-info-circle font-i'></i>" + data[this.type] + "<input type='radio' id='frame_extra_" + index + "' name='frame_extra' class='frame_extra' value=" + this.type + " onclick='onFrameExtraChange(this.value);'> </h4>";
							});
							$("#frame_extra_select").html(extras);
						});
						//$(".print_papertype option").remove();
						//$(".field_extra option").remove();
					} else {
						$(".papertype_items, .field_extra_items, .field_extra_thickness_items").css("display", "none");
						$("#print_papertype_select input[type='radio'], #print_extra input[type='radio']").remove();
						$(".frame_extra_thickness").val("");
					}
					if (typeof content.thickness_by_mm != "undefined") {
						if (content.thickness_by_mm.length > 1) {
							$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__)); ?>', function(data) {
								$(".thickness_select_items").css("display", "block");
								$(".thickness_input_items").css("display", "none");
								$(".print_thickness_input").val("");
								var thickness_by_mm = "";
								$(content.thickness_by_mm).each(function(index, thickness) {
									thickness_by_mm += "<h4 class='list-type'><i class='fas fa-info-circle font-i'></i>" + thickness + data['mm'] + "<input type='radio' id='print_thickness_" + index + "' name='print_thickness' class='print_thickness' value=" + index + " onclick='onPrintThicknessSelected(this.value)'> </h4>";
									""
								});
								$("#print_thickness_input").removeClass("thickness_enabled");
								$("#print_thickness_select").addClass("thickness_enabled");
								$("#print_thickness_select input[type='radio']").remove();
								$("#print_thickness_select").html(thickness_by_mm);
							});
						} else {
							$(".thickness_input_items").css("display", "block");
							$(".thickness_select_items").css("display", "none");
							$("#print_thickness_select").val("");
							$("#print_thickness_select").removeClass("thickness_enabled");
							$("#print_thicknessinput").addClass("thickness_enabled");
							$("#print_thickness_input").val(content.thickness_by_mm[0]);
							$("#thickness_content").html(content.thickness_by_mm[0] + "mm");
						}
					} else {
						$(".thickness_select_items, .thickness_input_items").css("display", "none");
					}

					if (typeof content.finishing != "undefined") {
						$.getJSON('<?php echo plugins_url('language.json', dirname(__FILE__)); ?>', function(data) {
							if (content.finishing.length > 1) {
								$(".finishing_select_items").css("display", "block");
								$(".finishing_input_items").css("display", "none");
								var finishing = "";
								$(content.finishing).each(function(index) {
									finishing += "<h4 class='list-type'><i class='fas fa-info-circle font-i'></i>" + data[this.type] + "<input type='radio' id='print_finishing_" + index + "' name='print_finishing' class='print_finishing' value=" + this.type + " onclick='onPrintFinishingSelected(this.value)'> </h4>";
								});
								$("#print_finishing_input").removeClass("finishing_enabled");
								$("#print_finishing_select").addClass("finishing_enabled");
								//$(".print_finishing_select option").remove();
								$("#print_finishing_select input[type='radio']:eq(0)").attr("checked", true);
								$("#print_finishing_select").html(finishing);
							} else {
								$(".finishing_input_items").css("display", "block");
								$(".finishing_select_items").css("display", "none");
								$("#print_finishing_select").removeClass("finishing_enabled");
								$("#print_finishing_input").addClass("finishing_enabled");
								$("#finishing_content").html(data[content.finishing[0].type]);
							}
						});
					} else {
						$(".finishing_select_items, .finishing_input_items").css("display", "none");
						$("#choose_frame").css("display", "none");
					}
					//laminate
					if (typeof content.laminate != "undefined") {
						setLaminateHanging(content.laminate);
					} else if (typeof content.hanging_type != "undefined") {
						setLaminateHanging(content.hanging_type);
					} else if (typeof content.laminate != "undefined" && typeof content.hanging_type != "undefined") {
						clearLaminateHanging();
					}
					calc();
				});
				$(".laminate_hanging_select_items, .laminate_hanging_input_items").css("display", "none");
				$("#print_laminate_hanging_select input[type='radio']:checked, #print_laminate_hanging_input, #print_finishing_select input[type='radio']:checked, #print_finishing_input").val("");

				$("#print_laminate_hanging_select input[type='radio']:checked, #print_laminate_hanging_input, #print_finishing_select input[type='radio']:checked, #print_finishing_input").removeAttr("checked");

				$("#print_laminate_hanging_input, .print_laminate_hanging_select, #print_finishing_input, .print_finishing_select").removeClass("laminate_enabled");

				$("#price").html("");
				$("#input_price").val(0);
				$("#add_to_cart").css("display", "none");
			} else {
				$("#print_width").val("");
				$("#print_height").val("");
				$("#price").html("");
				$("#input_price").val(0);
				//$(".fieldsdisplay").css("display", "none");
				$("#add_to_cart").css("display", "none");
			}
		});

		$(".print_thickness").on("click", function() {
			$("#price").html("");
			$("#input_price").val(0);
			$("#add_to_cart").css("display", "none");
			calc();
		});

		$(".print_finishing, .print_laminate_hanging, .frame_extra, #print_quantity").on("click change keyup", function() {
			calc();
		});

		$(".print_size").on("focus", function() {
			$("#price").html("");
			$("#input_price").val(0);
			$("#add_to_cart").css("display", "none");
		});

		$(".print_size").on("keyup blur", function() {
			var id = $(this).attr("id");
			var splittedVal = $("#" + id).val().split(".");
			if (splittedVal.length > 1) {
				$("#" + id).val(splittedVal[0]);
			}
			calc()
		});
	});

	$(document).ready(function() {
		$(".frame_size").on("blur keyup", function() {
			var id = $(this).attr("id");
			var splittedVal = $("#" + id).val().split(".");
			if (splittedVal.length > 1) {
				$("#" + id).val(splittedVal[0]);
			}
			calc();
		});
		$("#frame_type").on("change", function() {
			var frame_type = $(this).val();
			$("#frame_size").val("");
			if (frame_type == "from_catalogue") {
				$(".field_extra, .field_cover, .field_color, .frame_error, .field_extra_thickness ").css("display", "none");
				$(".field_size_dropdown").css("display", "block");
				$.getJSON('<?php echo plugins_url('types.json', dirname(__FILE__)); ?>', function(data) {
					var dimension = "";
					$(data.categories.frames.sizes[1].dimensions).each(function(index) {
						dimension += '<option value="' + index + '">' + this[0] + 'x' + this[1] + '</option>';
					});
					$("#frame_size option").not($("#frame_size option:eq(0)")).remove();
					$("#frame_size").append(dimension);
				});
			} else {
				$(".field_extra, .field_cover, .field_color, .frame_error").css("display", "block");
				$(".field_size_dropdown").css("display", "none");
			}
			calc();
		});

		$("#frame_cover").on("change", function() {
			calc();
		});
		$("#frame_extra").on("change", function() {
			calc();
		});
	});
	$("#add_to_cart").on("click", function() {
		var formdata = $("#print_form").serializeArray();
		return false;
		formdata.push({
			name: 'print_image',
			value: $("#print_image").val()
		});
		formdata.map(function(result) {
			if (result.name == 'frame_required') {
				result.value = $("#frame_required").is(":checked");
			}
			return result;
		})
		//formdata.push({name: 'frame_required', value: $("#frame_required").is(":checked")});
		$.ajax({
			url: "<?php echo plugin_dir_url(__FILE__) . 'add_to_woocommerce.php'; ?>",
			data: formdata,
			type: 'POST',
			success: function(result) {
				/*//$.ajax({
				    url: '?wc-ajax=add_to_cart',
				    data: {product_sku: '', product_id: <?php echo $productID; ?>, quantity: 1},
				    type: 'POST',
				    success: function(result){
				        console.log(result);
				    }
				});*/
				var result = JSON.parse(result);
				if (result.success == "true") {
					if (result.status !== "true") {
						$(".woocommerce-message").css("display", "block");
						$(".cart-contents-count").text(result.count);
						$(".woocommerce-message .message").html('"' + result.name + '" has been added to your cart.');
						$('html, body').animate({
							scrollTop: $(".woocommerce-message").offset().top
						}, 1000)
					} else {
						$(".woocommerce-message").css("display", "block");
						$(".woocommerce-message .message").html('"' + result.name + '" has been updated to your cart.');
						$('html, body').animate({
							scrollTop: $(".woocommerce-message").offset().top
						}, 1000)
					}
				}
			}
		});
	});
</script>
<script>
	$(document).ready(function() {
		$('#staticBackdrop, #staticBackdrop1').on('hide.bs.modal', function() {
			$(".modal-backdrop").remove();
		});
		$('#upload-file1').change(function() {
			var filename = $(this).val();
			console.log(filename);
			$('#file-upload-name').html(filename);
			/* if (filename != "progress-bar") {
				setTimeout(function() {
					// $('.upload-wrapper').addClass("uploaded");
				}, 600);
				setTimeout(function() {
					onFileUploaded();
				}, 1600);
			} */
			if (filename != "") {
				var formData = new FormData();
				formData.append('upload_print', $('#upload-file1')[0].files[0]);
				$.ajax({
					url: '<?php echo plugins_url('upload.php', __FILE__); ?>',
					data: formData,
					type: 'POST',
					enctype: 'multipart/form-data',
					processData: false, // tell jQuery not to process the data
					contentType: false, // tell jQuery not to set contentType
					beforeSend: function() {
						$('.upload-wrapper').addClass("success");
					},
					success: function(res) {
						$('.upload-wrapper').removeClass("success");
						res = JSON.parse(res);
						if (typeof res.error == "undefined") {
							$(".print_size").val();
							$(".image_display").css("display", "block");
							$(".image-uploader").css("display", "none");
							$("#file-details .file-name").html(res.filename);
							$("#file-details .file-dimensions").html(res.width + "x" + res.height + "px (" + res.size + ")");
							$("#image_content, #img-uploaded").html("<img id='print_image' src='" + res.url + "' alt='" + res.filename + "'>");
							$("#print_image").val(res.url);
							$("#image_width").val(res.width);
							$("#image_height").val(res.height);
							$("#print_width, #print_height").removeAttr("disabled");
							$("#staticBackdrop1").modal("hide");
						}

					},
					error: function(err) {
						console.log(err);
					}
				})
			}
		});
	});
</script>

<style>
	.image_display,
	.thickness_items,
	.papertype_items,
	.field_extra_items,
	.field_extra_thickness_items,
	.thickness_select_items,
	.thickness_input_items,
	.laminate_hanging_select_items,
	.laminate_hanging_input_items,
	.finishing_select_items,
	.finishing_input_items,
	.thickness_dimensions {
		display: none;
	}

	/* .image_display #img_text {
		float: <?php echo $helper->getLang() === 'en' ? 'left' : 'right' ?>;
	} */
</style>