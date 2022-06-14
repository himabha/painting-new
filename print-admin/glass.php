<?php
    session_start();
    if (isset($_SESSION['active']) && $_SESSION['active'] == 1) {
        if (isset($_POST['submit'])) {
            //Load the file
            $contents = file_get_contents(ROOT_DIRECTORY.'/types.json');
            //Decode the JSON data into a PHP array.
            $contentsDecoded = json_decode($contents, true);

            if (isset($_POST['glass_types']) && !empty($_POST['glass_types']) && isset($_POST['glass_thickness']) && !empty($_POST['glass_thickness'])) {
                foreach ($contentsDecoded['categories']['glass'][$_POST['glass_types']]['sizes'] as $key_s => $size) {
                    if ($size['thickness_by_mm'] == $_POST['glass_thickness']) {
                        $contentsDecoded['categories']['glass'][$_POST['glass_types']]['sizes'][$key_s]['min_price'] = (int)$_POST['glass_type_min_price'];
                        $contentsDecoded['categories']['glass'][$_POST['glass_types']]['sizes'][$key_s]['price'] = (int)$_POST['glass_type_price'];
                        $contentsDecoded['categories']['glass'][$_POST['glass_types']]['sizes'][$key_s]['min_dimensions'][0] = (int)$_POST['glass_dimensions_min_range1'];
                        $contentsDecoded['categories']['glass'][$_POST['glass_types']]['sizes'][$key_s]['min_dimensions'][1] = (int)$_POST['glass_dimensions_min_range2'];
                        $contentsDecoded['categories']['glass'][$_POST['glass_types']]['sizes'][$key_s]['max_dimensions'][0] = (int)$_POST['glass_dimensions_max_range1'];
                        $contentsDecoded['categories']['glass'][$_POST['glass_types']]['sizes'][$key_s]['max_dimensions'][1] = (int)$_POST['glass_dimensions_max_range2'];
                        break;
                    }
                }
            }
            //Modify the counter variable.
            //Encode the array back into a JSON string.
            $json = json_encode($contentsDecoded, JSON_PRETTY_PRINT);
            //Save the file.
            file_put_contents('../types.json', $json);
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
                <h3 class="page-title">Glass settings</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-8 mb-4">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Update glass data</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col-sm-12 col-md-8">
                          <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
							<div class="form-group">
							<label>Select glass type: </label>
                                <select name="glass_types" id="glass_types" class="form-control">
                                  <?php
                                        $options = "<option value=''>Choose one</option>";
        $glass = array_keys($jsondata['categories']['glass']);
        if (isset($glass) && !empty($glass)) {
            foreach ($glass as $key_frame => $glass) {
                $new_glass= ucfirst(str_replace("_", " ", $glass));
                $options .= '<option value="'.$glass.'">'.$new_glass.'</option>';
            }
        }
        echo $options; ?>
									</select>
                              </div>
								<div class="form-group glass_thickness_group">
									<label>Select thickness: <span id="extra_label"></span></label>
									<select name="glass_thickness" id="glass_thickness" class="form-control" onchange="triggerGlassChange()">
									</select>
								</div>

								<div class="form-group glass_min_dimensions">
									<label for="glass_dimensions_min_range1">Min dimensions</label>
									<div class="form-group">
										<input type="text" class="form-control col-md-2" name="glass_dimensions_min_range1" id="glass_dimensions_min_range1"/><span class="col-md-1 dimension_symbol">x</span><input class="form-control col-md-2" type="text" name="glass_dimensions_min_range2" id="glass_dimensions_min_range2"/>
									</div>
								</div>

								<div class="form-group glass_max_dimensions">
									<label for="glass_dimensions_max_range1">Max dimensions</label>
									<div class="form-group">
										<input type="text" class="form-control col-md-2" name="glass_dimensions_max_range1" id="glass_dimensions_max_range1"/><span class="col-md-1 dimension_symbol">x</span><input class="form-control col-md-2" type="text" name="glass_dimensions_max_range2" id="glass_dimensions_max_range2"/>
									</div>
								</div>

								<div class="form-group glass_type_price_group">
									<label>Price for <span class="glass_type_price_label"></span></label>
									<input class="form-control" type="text" name="glass_type_price" id="glass_type_price"/>
								</div>

								<div class="form-group glass_type_min_price_group">
									<label>Min price for <span class="glass_type_price_label"></span></label>
									<input class="form-control" type="text" name="glass_type_min_price" id="glass_type_min_price"/>
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
