<?php
    session_start();
    if (isset($_SESSION['active']) && $_SESSION['active'] == 1) {
        if (isset($_POST['submit'])) {
            //Load the file
            $contents = file_get_contents(ROOT_DIRECTORY.'/types.json');
            //Decode the JSON data into a PHP array.
            $contentsDecoded = json_decode($contents, true);

            $other_product_dimensions = (isset($_POST['other_product_dimensions']) && !empty($_POST['other_product_dimensions'])) ? explode("x", $_POST['other_product_dimensions']): null;
            foreach ($contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'] as $key_s => $size) {
                if ((isset($_POST['other_product_thickness']) && !empty($_POST['other_product_thickness'])) && $size['thickness_by_mm'] == $_POST['other_product_thickness']) {
                    foreach ($size['dimensions'] as $key_d => $dimension) {
                        if ($dimension[0] == $other_product_dimensions[0] && $dimension[1] == $other_product_dimensions[1]) {
                            foreach ($size['prices'] as $key_p => $price) {
                                if (isset($_POST['other_product_glue']) && !empty($_POST['other_product_glue'])) {
                                    if ($price['with_glue'] == $_POST['other_product_glue']) {
                                        $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['prices'][$key_p]['price_list'][$key_d] = (int)$_POST['other_product_type_price'];
                                        if (isset($_POST['other_product_type_quantity']) && !empty($_POST['other_product_type_quantity'])) {
                                            $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['prices'][$key_p]['quantity_threshold'][$key_d] = (int)$_POST['other_product_type_quantity'];
                                        }
                                        break;
                                    }
                                } else {
                                    $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['prices'][$key_d] = (int)$_POST['other_product_type_price'];
                                }
                            }
                            break;
                        }
                    }
                    $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['min_price'] = (int)$_POST['other_product_type_min_price'];
                    break;
                } else {
                    if (isset($size['dimensions']) && !empty($size['dimensions'])) {
                        foreach ($size['dimensions'] as $key_d => $dimension) {
                            if ($dimension[0] == $other_product_dimensions[0] && $dimension[1] == $other_product_dimensions[1]) {
                                foreach ($size['prices'] as $key_p => $price) {
                                    {
                                    $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['prices'][$key_d] = (int)$_POST['other_product_type_price'];
                                }
                                }
                                break;
                            }
                        }
                        $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['min_price'] = (int)$_POST['other_product_type_min_price'];
                    } elseif (isset($size['thickness_range_by_mm']) && !empty($size['thickness_range_by_mm'])) {
                        $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['manual_dimensions']['max_dimensions'][0] = (int)$_POST['other_product_dimensions_range1'];
                        $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['manual_dimensions']['max_dimensions'][1] = (int)$_POST['other_product_dimensions_range2'];
                        $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['thickness_range_by_mm'][0] = (int)$_POST['other_product_thickness_range1'];
                        $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['thickness_range_by_mm'][1] = (int)$_POST['other_product_thickness_range2'];
                        $contentsDecoded['categories']['other_products'][$_POST['other_product_types']]['sizes'][$key_s]['prices'][0] = (int)$_POST['other_product_type_price'];
                    }
                }
            }
            //Modify the counter variable.

            //Encode the array back into a JSON string.
            $json = json_encode($contentsDecoded, JSON_PRETTY_PRINT);

            //Save the file.
            file_put_contents(ROOT_DIRECTORY.'/types.json', $json);
        } ?>
<!doctype html>
<html class="no-js h-100" lang="en">
  <?php include_once('header.php'); ?>
  <body class="h-100">
    <div class="container-fluid">
      <div class="row">
        <!-- Main Sidebar -->
        <?php include_once('sidebar.php'); ?>
		<!-- End Main Sidebar -->
        <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
          <?php include_once('navbar.php'); ?>
          <!--<div class="alert alert-accent alert-dismissible fade show mb-0" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">Ã—</span>
            </button>
            <i class="fa fa-info mx-2"></i>
            <strong>How you doin'?</strong> I'm just a friendly, good-looking notification message and I come in all the colors you can see below. Pretty cool, huh? </div>-->
          <div class="main-content-container container-fluid px-4">
            <!-- Page Header -->
            <div class="page-header row no-gutters py-4 mb-3 border-bottom">
              <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                <span class="text-uppercase page-subtitle">Overview</span>
                <h3 class="page-title">Other products settings</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-8 mb-4">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Update other products data</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col-sm-12 col-md-8">
                          <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
							<div class="form-group">
							<label>Select other products type: </label>
                                <select name="other_product_types" id="other_product_types" class="form-control">
                                  <?php
                                        $options = "<option value=''>Choose one</option>";
        $other_products = array_keys($jsondata['categories']['other_products']);
        if (isset($other_products) && !empty($other_products)) {
            foreach ($other_products as $key_other_product => $other_product) {
                $new_other_product= ucfirst(str_replace("_", " ", $other_product));
                $options .= '<option value="'.$other_product.'">'.$new_other_product.'</option>';
            }
        }
        echo $options; ?>
									</select>
                              </div>
							  <div class="form-group other_product_thickness_group">
								<label>Select thickness: <span id="extra_label"></span></label>
                                <select name="other_product_thickness" id="other_product_thickness" class="form-control">
								 <?php
                                        $options = "<option value=''>Choose one</option>";
        $thickness = $jsondata['categories']['other_products']['capa']['sizes'];
        if (isset($thickness) && !empty($thickness)) {
            foreach ($thickness as $key_thick => $thick) {
                $newthick= ucfirst(str_replace("_", " ", $thick['thickness_by_mm']));
                $options .= '<option value="'.$newthick.'">'.$newthick.'mm</option>';
            }
        }
        echo $options; ?>
									</select>
								</div>

								<div class="form-group other_product_dimensions_group">
								  <label for"other_product_dimensions"> Select dimensions: </label>
									<select name="other_product_dimensions" id="other_product_dimensions" class="form-control" onchange="triggerOtherProductsChange()">
									</select>
								</div>

								<div class="form-group other_product_with_glue">
									<input type="checkbox" name="other_product_glue" id="other_product_glue" value="1"/>
									<label for="other_product_glue">With glue</label>
								</div>

								<div class="form-group other_product_max_dimensions">
									<label for="other_product_dimensions_range1">Max dimensions</label>
									<div class="form-group">
										<input type="text" class="form-control col-md-2" name="other_product_dimensions_range1" id="other_product_dimensions_range1"/><span class="col-md-1 dimension_symbol">x</span><input class="form-control col-md-2" type="text" name="other_product_dimensions_range2" id="other_product_dimensions_range2"/>
									</div>
								</div>

								<div class="form-group other_product_thickness_range">
									<label for="other_product_thickness_range1">Thickness range</label>
									<div class="form-group">
										<input type="text" class="form-control col-md-2" name="other_product_thickness_range1" id="other_product_thickness_range1"/><span class="col-md-2 dimension_symbol">to</span><input type="text" class="form-control col-md-2" name="other_product_thickness_range2" id="other_product_thickness_range2"/>
									</div>
								</div>

								<div class="form-group other_product_type_price_group">
									<label>Price for <span class="other_product_type_price_label"></span></label>
									<input class="form-control" type="text" name="other_product_type_price" id="other_product_type_price"/>
								</div>

								<div class="form-group other_product_type_min_price_group">
									<label>Min. Price for <span class="other_product_type_min_price_label"></span></label>
									<input class="form-control" type="text" name="other_product_type_min_price" id="other_product_type_min_price"/>
								</div>

								<div class="form-group other_product_type_quantity_group">
									<label>Quantity for <span class="other_product_type_quantity_label"></span></label>
									<input class="form-control" type="text" name="other_product_type_quantity" id="other_product_type_quantity"/>
								</div>

								<div class="form-group">
									<input name="submit" type="submit" class="bg-primary rounded text-white text-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);"/>
								</div>
                          </form>
                        </div>
						</div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!--<footer class="main-footer d-flex p-2 px-3 bg-white border-top">
            <ul class="nav">
              <li class="nav-item">
                <a class="nav-link" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Products</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Blog</a>
              </li>
            </ul>
            <span class="copyright ml-auto my-auto mr-2">Copyright Â© 2018
              <a href="https://designrevision.com" rel="nofollow">DesignRevision</a>
            </span>
          </footer>-->
        </main>
      </div>
    </div>
    <!--<div class="promo-popup animated">
      <a href="http://bit.ly/shards-dashboard-pro" class="pp-cta extra-action">
        <img src="https://dgc2qnsehk7ta.cloudfront.net/uploads/sd-blog-promo-2.jpg"> </a>
      <div class="pp-intro-bar"> Need More Templates?
        <span class="close">
          <i class="material-icons">close</i>
        </span>
        <span class="up">
          <i class="material-icons">keyboard_arrow_up</i>
        </span>
      </div>
      <div class="pp-inner-content">
        <h2>Shards Dashboard Pro</h2>
        <p>A premium & modern Bootstrap 4 admin dashboard template pack.</p>
        <a class="pp-cta extra-action" href="http://bit.ly/shards-dashboard-pro">Download</a>
      </div>
    </div>-->
    <?php include_once('footer.php'); ?>
	</body>
</html>
<?php
    } else {
        header("Location:login.php");
    }
?>
