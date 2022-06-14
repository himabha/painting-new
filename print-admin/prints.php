<?php
    session_start();
    if (isset($_SESSION['active']) && $_SESSION['active'] == 1) {
        if (isset($_POST['submit'])) {
            //Load the file
            $contents = file_get_contents(ROOT_DIRECTORY.'/types.json');
            //Decode the JSON data into a PHP array.
            $contentsDecoded = json_decode($contents, true);

            if (isset($_POST['print_types']) && $_POST['print_types'] != 'from_catalogue') {
                if (isset($_POST['print_covers']) && !empty($_POST['print_covers'])) {
                    $contentsDecoded['categories']['prints']['sizes'][0]['covers'][$_POST['print_covers']]['price'] = (int)$_POST['print_covers_price'];
                }
                if (isset($_POST['print_extras']) && !empty($_POST['print_extras'])) {
                    $contentsDecoded['categories']['prints']['sizes'][0]['extras'][$_POST['print_extras']][0]['price'] = (int)$_POST['print_extras_price'];
                    $contentsDecoded['categories']['prints']['sizes'][0]['extras'][$_POST['print_extras']][0]['max_dimensions'] = array((int)$_POST['print_extras_max_dimensions_width'], (int)$_POST['print_extras_max_dimensions_height']);
                }
            } else {
                if (isset($_POST['print_dimensions']) && !empty($_POST['print_dimensions'])) {
                    $contentsDecoded['categories']['prints']['sizes'][1]['price_list'][$_POST['print_dimensions']] = (int)$_POST['print_dimensions_price'];
                }
                if (isset($_POST['print_discount']) && !empty($_POST['print_discount'])) {
                    $contentsDecoded['categories']['prints']['sizes'][1]['discount_list'][$_POST['print_discount']]['discount'] = (int)$_POST['print_dimensions_discount'];
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
                <h3 class="page-title">Print settings</h3>
              </div>
            </div>
            <!-- End Page Header -->
            <div class="row">
              <div class="col-lg-8 mb-4">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Update print data</h6>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                      <div class="row">
                        <div class="col-sm-12 col-md-8">
                          <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
							<div class="form-group">
							<label>Select print type: </label>
                                <select name="print_types" id="print_types" class="form-control">
                                  <?php
                                        $options = "<option value=''>Choose one</option>";
        $prints = array_keys($jsondata['categories']['prints']);
        if (isset($prints) && !empty($prints)) {
            foreach ($prints as $key_frame => $print) {
                $new_print= ucfirst(str_replace("_", " ", $print));
                $options .= '<option value="'.$print.'">'.$new_print.'</option>';
            }
        }
        echo $options; ?>
									</select>
                              </div>
							<div class="form-group print_range_group">
								<label>Select range for <span id="print_label"></span></label>
                                <select name="print_range" id="print_range" onchange="triggerPrintChange()" class="form-control"></select>
							</div>
							<div class="form-group print_price_group">
								<label>Price for <span class="print_label"></span></label>
								<input class="form-control" type="text" name="print_price" id="print_price"/>
							</div>
							<div class="form-group print_min_price_group">
								<label>Min. Price for <span class="print_label"></span></label>
								<input class="form-control" type="text" name="print_min_price" id="print_min_price"/>
							</div>
							<div class="form-group print_finish_group">
								<label>Select finish for <span class="print_label"></span></label>
                                <select name="print_finishes" id="print_finishes" onchange="triggerPrintFinishingChange()" class="form-control"></select>
							</div>
							<div class="form-group print_finish_price_group">
								<label>Price for finishing - <span class="print_finish_label"></span></label>
								<input class="form-control" type="text" name="print_finish_price" id="print_finish_price"/>
							</div>
							<div class="form-group print_laminate_group">
								<label>Select laminate for <span class="print_label"></span></label>
                                <select name="print_laminate" id="print_laminate" onchange="triggerPrintLaminateChange()" class="form-control"></select>
							</div>
							<div class="form-group print_laminate_price_group">
									<label>Price for laminate - <span class="print_laminate_label"></span></label>
								<input class="form-control" type="text" name="print_laminate_price" id="print_laminate_price"/>
							</div>

							<div class="form-group print_extras_group">
								<label>Select extras for <span class="print_label"></span></label>
                                <select name="print_extras" id="print_extras" onchange="triggerPrintExtrasChange()" class="form-control"></select>
							</div>
							<div class="form-group print_extras_price_group">
								<label>Price for extras - <span class="print_extras_label"></span></label>
								<input class="form-control" type="text" name="print_extras_price" id="print_extras_price"/>
							</div>
							<div class="form-group print_extras_max_dimensions_group">
								<label>Max dimensions for extras <span class="print_extras_label"></span></label>
								<div class="form-group">
									<input class="form-control col-md-2" type="text" name="print_extras_max_dimensions1" id="print_extras_max_dimensions1"/><span class="col-md-1 dimension_symbol">x</span><input class="form-control col-md-2" type="text" name="print_extras_max_dimensions2" id="print_extras_max_dimensions2"/>
								</div>
							</div>

							<div class="form-group submit_group">
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
