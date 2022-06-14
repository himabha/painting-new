<?php

    session_start();

    if (isset($_SESSION['active']) && $_SESSION['active'] == 1) {

        if (isset($_POST['submit'])) {

            //Load the file

            $contents = file_get_contents(ROOT_DIRECTORY.'/types.json');

            //Decode the JSON data into a PHP array.

            $contentsDecoded = json_decode($contents, true);



            if (isset($_POST['frame_types']) && $_POST['frame_types'] != 'from_catalogue') {

                if (isset($_POST['frame_covers']) && !empty($_POST['frame_covers'])) {

                    $contentsDecoded['categories']['frames']['sizes'][0]['covers'][$_POST['frame_covers']]['price'] = (int)$_POST['frame_covers_price'];

                }

                if (isset($_POST['frame_extras']) && !empty($_POST['frame_extras'])) {

                    $contentsDecoded['categories']['frames']['sizes'][0]['extras'][$_POST['frame_extras']][0]['price'] = (int)$_POST['frame_extras_price'];

                    $contentsDecoded['categories']['frames']['sizes'][0]['extras'][$_POST['frame_extras']][0]['max_dimensions'] = array((int)$_POST['frame_extras_max_dimensions_width'], (int)$_POST['frame_extras_max_dimensions_height']);

                }

            } else {

                if (isset($_POST['frame_dimensions']) && !empty($_POST['frame_dimensions'])) {

                    $contentsDecoded['categories']['frames']['sizes'][1]['price_list'][$_POST['frame_dimensions']] = (int)$_POST['frame_dimensions_price'];

                }

                if (isset($_POST['frame_discount']) && !empty($_POST['frame_discount'])) {

                    $contentsDecoded['categories']['frames']['sizes'][1]['discount_list'][$_POST['frame_discount']]['discount'] = (int)$_POST['frame_dimensions_discount'];

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

<?php

    include_once('header.php');

        $db = new DbConnection;

        $conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {

            $http = "https://";

        } else {

            $http = "http://";

        } ?>



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

							<h3 class="page-title">Frames settings</h3>

						</div>

					</div>

					<!-- End Page Header -->

					<div class="row">

						<div class="col-lg-6 mb-4">

							<div class="card card-small mb-4">

								<div class="card-header border-bottom">

									<h6 class="m-0">Add Frame for Catalog</h6>

								</div>

								<ul class="list-group list-group-flush">

									<li class="list-group-item p-3">

										<div class="row">

											<div class="col-sm-12 col-md-8">

												<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

													<div class="form-group">

														<label>Frame name: </label>

														<input class="form-control" type="text" name="frame_catalog_name" id="frame_catalog_name" required/>

													</div>

                                                    <div class="form-group frame_catalog_min_dimensions">

                    									<label for="frame_catalog_dimensions1">Dimensions</label>

                    									<div class="form-group">

                    										<input type="text" class="form-control col-md-2" name="frame_catalog_dimensions1" id="frame_catalog_dimensions1" required/><span class="col-md-1 dimension_symbol">x</span><input class="form-control col-md-2" type="text" name="frame_catalog_dimensions2" id="frame_catalog_dimensions2" required/>

                    									</div>

                    								</div>

                                                    <div class="form-group">

														<label>Frame price: </label>

														<input class="form-control" type="text" name="frame_catalog_price" id="frame_catalog_price" required/>

													</div>

													<div class="form-group">

														<input name="ct_submit" type="submit" class="bg-primary rounded text-white text-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);" />

													</div>

												</form>

											</div>

										</div>

									</li>

								</ul>

							</div>

						</div>

						<div class="col-lg-6 mb-4">

							<div class="card card-small mb-4">

								<div class="card-header border-bottom">

									<h6 class="m-0">Add Frame for Grid</h6>

								</div>

								<ul class="list-group list-group-flush">

									<li class="list-group-item p-3">

										<div class="row">

											<div class="col-sm-12 col-md-8">

												<form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

													<!--<div class="form-group">

														<label>Select frame source: </label>

														<select name="frame_types" id="frame_types" class="form-control">

															<option value="from_grid">Grid</option>

															<option value="from_grid">Catalogue</option>

														</select>

													</div>-->

													<div class="form-group">

														<label>Frame name: </label>

														<input class="form-control" type="text" name="frame_name" id="frame_name" required/>

													</div>

                                                    <div class="form-group">

														<label>Frame color: </label>

														<select class="form-control" name="frame_color" id="frame_color" required>

                                                        <option value="AliceBlue">AliceBlue</option>

                                                        <option value="AntiqueWhite">AntiqueWhite</option>

                                                        <option value="Aqua">Aqua</option>

                                                        <option value="Aquamarine">Aquamarine</option>

                                                        <option value="Azure">Azure</option>

                                                        <option value="Beige">Beige</option>

                                                        <option value="Bisque">Bisque</option>

                                                        <option value="Black">Black</option>

                                                        <option value="BlanchedAlmond">BlanchedAlmond</option>

                                                        <option value="Blue">Blue</option>

                                                        <option value="BlueViolet">BlueViolet</option>

                                                        <option value="Brown">Brown</option>

                                                        <option value="BurlyWood">BurlyWood</option>

                                                        <option value="CadetBlue">CadetBlue</option>

                                                        <option value="Chartreuse">Chartreuse</option>

                                                        <option value="Chocolate">Chocolate</option>

                                                        <option value="Coral">Coral</option>

                                                        <option value="CornflowerBlue">CornflowerBlue</option>

                                                        <option value="Cornsilk">Cornsilk</option>

                                                        <option value="Crimson">Crimson</option>

                                                        <option value="Cyan">Cyan</option>

                                                        <option value="DarkBlue">DarkBlue</option>

                                                        <option value="DarkCyan">DarkCyan</option>

                                                        <option value="DarkGoldenRod">DarkGoldenRod</option>

                                                        <option value="DarkGray">DarkGray</option>

                                                        <option value="DarkGrey">DarkGrey</option>

                                                        <option value="DarkGreen">DarkGreen</option>

                                                        <option value="DarkKhaki">DarkKhaki</option>

                                                        <option value="DarkMagenta">DarkMagenta</option>

                                                        <option value="DarkOliveGreen">DarkOliveGreen</option>

                                                        <option value="DarkOrange">DarkOrange</option>

                                                        <option value="DarkOrchid">DarkOrchid</option>

                                                        <option value="DarkRed">DarkRed</option>

                                                        <option value="DarkSalmon">DarkSalmon</option>

                                                        <option value="DarkSeaGreen">DarkSeaGreen</option>

                                                        <option value="DarkSlateBlue">DarkSlateBlue</option>

                                                        <option value="DarkSlateGray">DarkSlateGray</option>

                                                        <option value="DarkSlateGrey">DarkSlateGrey</option>

                                                        <option value="DarkTurquoise">DarkTurquoise</option>

                                                        <option value="DarkViolet">DarkViolet</option>

                                                        <option value="DeepPink">DeepPink</option>

                                                        <option value="DeepSkyBlue">DeepSkyBlue</option>

                                                        <option value="DimGray">DimGray</option>

                                                        <option value="DimGrey">DimGrey</option>

                                                        <option value="DodgerBlue">DodgerBlue</option>

                                                        <option value="FireBrick">FireBrick</option>

                                                        <option value="FloralWhite">FloralWhite</option>

                                                        <option value="ForestGreen">ForestGreen</option>

                                                        <option value="Fuchsia">Fuchsia</option>

                                                        <option value="Gainsboro">Gainsboro</option>

                                                        <option value="GhostWhite">GhostWhite</option>

                                                        <option value="Gold">Gold</option>

                                                        <option value="GoldenRod">GoldenRod</option>

                                                        <option value="Gray">Gray</option>

                                                        <option value="Grey">Grey</option>

                                                        <option value="Green">Green</option>

                                                        <option value="GreenYellow">GreenYellow</option>

                                                        <option value="HoneyDew">HoneyDew</option>

                                                        <option value="HotPink">HotPink</option>

                                                        <option value="IndianRed ">IndianRed </option>

                                                        <option value="Indigo  ">Indigo  </option>

                                                        <option value="Ivory">Ivory</option>

                                                        <option value="Khaki">Khaki</option>

                                                        <option value="Lavender">Lavender</option>

                                                        <option value="LavenderBlush">LavenderBlush</option>

                                                        <option value="LawnGreen">LawnGreen</option>

                                                        <option value="LemonChiffon">LemonChiffon</option>

                                                        <option value="LightBlue">LightBlue</option>

                                                        <option value="LightCoral">LightCoral</option>

                                                        <option value="LightCyan">LightCyan</option>

                                                        <option value="LightGoldenRodYellow">LightGoldenRodYellow</option>

                                                        <option value="LightGray">LightGray</option>

                                                        <option value="LightGrey">LightGrey</option>

                                                        <option value="LightGreen">LightGreen</option>

                                                        <option value="LightPink">LightPink</option>

                                                        <option value="LightSalmon">LightSalmon</option>

                                                        <option value="LightSeaGreen">LightSeaGreen</option>

                                                        <option value="LightSkyBlue">LightSkyBlue</option>

                                                        <option value="LightSlateGray">LightSlateGray</option>

                                                        <option value="LightSlateGrey">LightSlateGrey</option>

                                                        <option value="LightSteelBlue">LightSteelBlue</option>

                                                        <option value="LightYellow">LightYellow</option>

                                                        <option value="Lime">Lime</option>

                                                        <option value="LimeGreen">LimeGreen</option>

                                                        <option value="Linen">Linen</option>

                                                        <option value="Magenta">Magenta</option>

                                                        <option value="Maroon">Maroon</option>

                                                        <option value="MediumAquaMarine">MediumAquaMarine</option>

                                                        <option value="MediumBlue">MediumBlue</option>

                                                        <option value="MediumOrchid">MediumOrchid</option>

                                                        <option value="MediumPurple">MediumPurple</option>

                                                        <option value="MediumSeaGreen">MediumSeaGreen</option>

                                                        <option value="MediumSlateBlue">MediumSlateBlue</option>

                                                        <option value="MediumSpringGreen">MediumSpringGreen</option>

                                                        <option value="MediumTurquoise">MediumTurquoise</option>

                                                        <option value="MediumVioletRed">MediumVioletRed</option>

                                                        <option value="MidnightBlue">MidnightBlue</option>

                                                        <option value="MintCream">MintCream</option>

                                                        <option value="MistyRose">MistyRose</option>

                                                        <option value="Moccasin">Moccasin</option>

                                                        <option value="NavajoWhite">NavajoWhite</option>

                                                        <option value="Navy">Navy</option>

                                                        <option value="OldLace">OldLace</option>

                                                        <option value="Olive">Olive</option>

                                                        <option value="OliveDrab">OliveDrab</option>

                                                        <option value="Orange">Orange</option>

                                                        <option value="OrangeRed">OrangeRed</option>

                                                        <option value="Orchid">Orchid</option>

                                                        <option value="PaleGoldenRod">PaleGoldenRod</option>

                                                        <option value="PaleGreen">PaleGreen</option>

                                                        <option value="PaleTurquoise">PaleTurquoise</option>

                                                        <option value="PaleVioletRed">PaleVioletRed</option>

                                                        <option value="PapayaWhip">PapayaWhip</option>

                                                        <option value="PeachPuff">PeachPuff</option>

                                                        <option value="Peru">Peru</option>

                                                        <option value="Pink">Pink</option>

                                                        <option value="Plum">Plum</option>

                                                        <option value="PowderBlue">PowderBlue</option>

                                                        <option value="Purple">Purple</option>

                                                        <option value="RebeccaPurple">RebeccaPurple</option>

                                                        <option value="Red">Red</option>

                                                        <option value="RosyBrown">RosyBrown</option>

                                                        <option value="RoyalBlue">RoyalBlue</option>

                                                        <option value="SaddleBrown">SaddleBrown</option>

                                                        <option value="Salmon">Salmon</option>

                                                        <option value="SandyBrown">SandyBrown</option>

                                                        <option value="SeaGreen">SeaGreen</option>

                                                        <option value="SeaShell">SeaShell</option>

                                                        <option value="Sienna">Sienna</option>

                                                        <option value="Silver">Silver</option>

                                                        <option value="SkyBlue">SkyBlue</option>

                                                        <option value="SlateBlue">SlateBlue</option>

                                                        <option value="SlateGray">SlateGray</option>

                                                        <option value="SlateGrey">SlateGrey</option>

                                                        <option value="Snow">Snow</option>

                                                        <option value="SpringGreen">SpringGreen</option>

                                                        <option value="SteelBlue">SteelBlue</option>

                                                        <option value="Tan">Tan</option>

                                                        <option value="Teal">Teal</option>

                                                        <option value="Thistle">Thistle</option>

                                                        <option value="Tomato">Tomato</option>

                                                        <option value="Turquoise">Turquoise</option>

                                                        <option value="Violet">Violet</option>

                                                        <option value="Wheat">Wheat</option>

                                                        <option value="White">White</option>

                                                        <option value="WhiteSmoke">WhiteSmoke</option>

                                                        <option value="Yellow">Yellow</option>

                                                        <option value="YellowGreen">YellowGreen</option>

                                                    </select>

													</div>

                                                    <div class="form-group">

														<label>Frame type: </label>

														<select class="form-control" name="frame_type" id="frame_type" required>

                                                          <option value="Classic">Classic</option>

                                                          <option value="Modern">Modern</option>

                                                          <option value="Decorated">Decorated</option>

                                                        </select>

													</div>



                                                    <div class="form-group">

														<label for='upload_frame'>Frame image: </label>

														<input class="form-inline" type="file" name="upload_frame[]" id="upload_frame" required multiple/>

                                                        <input type="hidden" name="tmp_path" id="tmp_path" value=""/>

                                                        <span class="img_loader"><img src='<?php $http.$_SERVER['HTTP_HOST'].ADMIN_DIR."/ajax-loader.gif"?>'/></span>

                                                        <div class="image_upload_error"></div>
													</div>

                                                    <div class="form-group image_display" >

                                                		<p id="img_text">Your have uploaded below images with size <span id="image_size"></span></p>

                                                		<span id="image_content"></span>

                                                	</div>



                                                    <div class="form-group">

														<label>Frame price: </label>

														<input class="form-control" type="text" name="frame_price" id="frame_price" required/>

													</div>

													<div class="form-group">

														<label>Frame description: </label>

														<textarea class="form-control" name="frame_description" id="frame_description" col="20" required></textarea>

													</div>

													<div class="form-group">

														<input name="db_submit" type="submit" class="bg-primary rounded text-white text-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);" />

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

			</main>

		</div>

	</div>

	<?php include_once('footer.php'); ?>

</body>

</html>

<?php

        if (isset($_POST['db_submit'])) {

            $name = $_POST['frame_name'];

            $type = $_POST['frame_type'];

            $color = $_POST['frame_color'];

            $description = $_POST['frame_description'];

            $tmp_path = $_POST['tmp_path'];

            $price = $_POST['frame_price'];

            $images = $_POST["upload_frame"];
            $db_images = (isset($_POST["upload_frame"]) && !empty($_POST["upload_frame"]) ? json_encode($_POST["upload_frame"]) : null);

            $stmt = mysqli_query($conn, "SELECT MAX(`sort`) as sort from frames");

            if (mysqli_num_rows($stmt) === 0) {

                exit('');

            }

            $sort = $stmt->fetch_assoc();

            $stmt->close();

            $stmt1 = mysqli_query($conn, "Insert into frames(name, description, color, type, img_path, price, sort, active, created_at, modified_At) values('".$name."', '".$description."', '".$color."', '".$type."', '', '".$price."', ".(isset($sort['sort']) ? ((int)$sort['sort']+1) : 1).", 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");



            if ($stmt1 === true) {

                $id = mysqli_insert_id($conn);

                if (!file_exists(ROOT_DIRECTORY."/frames/images/".$id)) {

                    if (mkdir(ROOT_DIRECTORY."/frames/images/".$id, 0777)) {

                        if ($db_images){
                            for($i=0; $i < count($images); $i++){
                                if(rename(ROOT_DIRECTORY."/frames/images/tmp/".$tmp_path."/".$images[$i], ROOT_DIRECTORY."/frames/images/".$id."/".$images[$i])) {
                                    $new_img_path[] = "/frames/images/".$id."/".$images[$i];
                                    if($i == count($images)-1){
                                        $stmt2 = mysqli_query($conn, "Update frames set `img_path` = '".json_encode($new_img_path)."' where id=".$id);
                                        if (mysqli_affected_rows($conn) > 0) {
                                            echo "<script>window.location.href='".$http.$_SERVER['HTTP_HOST'].ADMIN_DIR."frames-list.php'</script>";
                                        }
                                    }
                                }
                            }
                        }
                    }

                }

            }

        } elseif (isset($_POST['ct_submit'])) {

            $name = $_POST['frame_catalog_name'];

            $width = $_POST['frame_catalog_dimensions1'];

            $height = $_POST['frame_catalog_dimensions2'];

            $price = $_POST['frame_catalog_price'];

            $stmt = mysqli_query($conn, "SELECT MAX(`sort`) as sort from catalog_frames");

            if (mysqli_num_rows($stmt) === 0) {

                exit('');

            }

            $sort = $stmt->fetch_assoc();

            $stmt->close();

            $stmt1 = mysqli_query($conn, "Insert into catalog_frames(name, width, height, price, sort, active, created_at, modified_At) values('".$name."', '".$width."', '".$height."', '".$price."', ".(isset($sort['sort']) ? ((int)$sort['sort']+1) : 1).", 1, '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')");

            if ($stmt1 === true) {

                $id = mysqli_insert_id($conn);

                if (mysqli_affected_rows($conn) > 0) {

                    echo "<script>window.location.href='".$http.$_SERVER['HTTP_HOST'].ADMIN_DIR."frames-list.php'</script>";

                }

            }

        } else {

            header("Location:login.php");

        }

    }



?>

