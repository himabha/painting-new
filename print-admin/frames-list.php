<?php

    session_start();

    if (isset($_SESSION['active']) && $_SESSION['active'] == 1) {

        ?>

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

				<div class="main-content-container container-fluid px-4">

					<!-- Page Header -->

					<div class="page-header row no-gutters py-4 mb-3 border-bottom">

						<div class="row col-12 col-sm-11 text-center text-sm-left mb-0">

                            <div class="col-md-4">

    							<span class="text-uppercase page-subtitle">Overview</span>

    							<h3 class="page-title">Frames settings</h3>

                            </div>

                            <div class="col-md-2 bg-primary rounded text-white text-sm-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);cursor:pointer;" onclick="window.location.href='catalog-frames.php'">Catalog Frames</div>

						</div>

                        <div class="col-12 col-sm-1">

                            <div class="bg-primary rounded text-white text-sm-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);cursor:pointer;" onclick="window.location.href='frames.php'">Add frame</div>

                        </div>

                        <div class="col-12 col-sm-11 text-center text-sm-center mb-0"><div class="header_message"></div></div>

					</div>

					<!-- End Page Header -->

					<div class="row">



              <div class="col">

                <div class="card card-small mb-4">

                  <div class="card-header border-bottom">

                    <h6 class="m-0">Frames list</h6>

                  </div>

                  <div class="card-body p-0 pb-3 text-center">



                    <table class="table mb-0 example">

                      <thead class="bg-light">

                        <tr>

                          <th scope="col" class="border-0">#</th>

                          <th scope="col" class="border-0">Name</th>

                          <th scope="col" class="border-0">Description</th>

                          <th scope="col" class="border-0">Color</th>

                          <th scope="col" class="border-0">Type</th>

                          <th scope="col" class="border-0">Image</th>

                          <th scope="col" class="border-0">Price</th>

                          <th scope="col" class="border-0">Status</th>

                          <th scope="col" class="border-0">Action</th>

                        </tr>

                      </thead>

                      <tbody id="sortable">

                          <?php

                        // prepare and bind

                        $stmt = mysqli_query($conn, "SELECT * from frames order by sort desc");

        if (mysqli_num_rows($stmt) === 0) {

            exit('');

        }

        $i = 0;

        $html = "";

        while ($row = $stmt->fetch_assoc()) {

            $html .= "<tr data-id='".$row['id']."'>";

            $html .= "<td>".($i+1)."</td>";

            $html .= "<td>".$row['name']."</td>";

            $html .= "<td>".$row['description']."</td>";

            $html .= "<td>".$row['color']."</td>";

            $html .= "<td>".$row['type']."</td>";

            $images = json_decode($row['img_path']);
            $needle = '-p';
            $ret = key(array_filter($images, function($var) use ($needle){
                return strpos($var, $needle) !== false;
            }));
            $html .= "<td>".(isset($row['img_path']) ? "<img width='50' height='50' src='".$http.$_SERVER['HTTP_HOST'].ROOT_PATH.$images[$ret]."'/>" : '') ."</td>";

            $html .= "<td>".$row['price']."</td>";

            $html .= "<td>".(($row['active']==1) ? 'Active' : 'In-Active') ."</td>";

            $html .= "<td><button type='button' class='mb-2 btn btn-sm btn-success mr-1 edit-frame' id='edit-form-".$row['id']."'>Edit</button>

                                    <button type='button' class='mb-2 btn btn-sm btn-danger mr-1' onclick='deleteFrame(".$row['id'].");'>Delete</button></td>";

            $html .= "</tr>";

            $i++;

        }

        $stmt->close();



        echo $html; ?>







                      </tbody>

                    </table>

                  </div>

                </div>

              </div>

            </div>

				</div>

			</main>

		</div>

	</div>





<div class="container">

  <!-- Trigger the modal with a button -->



  <!-- Modal -->

  <div class="modal fade" id="myModal" role="dialog">

    <div class="modal-dialog">

      <!-- Modal content-->

      <div class="modal-content">

        <div class="modal-header">

            <h6 class="m-0">Update frame Db and images</h6>

            <button type="button" class="close" data-dismiss="modal">&times;</button>

        </div>

        <div class="modal-body">

                    <div class="img_loader"><img src='<?php $http.$_SERVER['HTTP_HOST'].ADMIN_DIR."ajax-loader.gif"?>'/></div>

                          <div class="row">

                              <div class="col-sm-12 col-md-12">

                                  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="edit-form">

                                      <input type="hidden" id="frame_id" name="frame_id" value=""/>

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

                                          <span class="img_loader"><img src='<?php $http.$_SERVER['HTTP_HOST'].ADMIN_DIR."ajax-loader.gif"?>'></span>

                                      </div>

                                      <div class="form-group image_display" >

                                          <p id="img_text">Your have uploaded below image with size <span id="image_size"></span></p>

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

                                          <label>Status </label>

                                          <select class="form-control" name="frame_status" id="frame_status">

                                              <option value="0">In-Active</option>

                                              <option value="1">Active</option>

                                          </select>

                                      </div>

                                      <div class="form-group">

                                          <input name="db_submit" type="submit" class="bg-primary rounded text-white text-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);" />

                                      </div>

                                  </form>

                              </div>

                          </div>



        </div>

        <div class="modal-footer">

          <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->

        </div>

      </div>



    </div>

  </div>

</div>

	<?php include_once('footer.php'); ?>

    <style>

        .edit-form{

            display: none;

        }

    </style>

</body>

<script>

    $(document).ready(function(){

        if(typeof window.sessionStorage.getItem("deleteMessage") != "undefined")

        {

            $(".header_message").html(window.sessionStorage.getItem("deleteMessage"));

            window.sessionStorage.removeItem("deleteMessage");

            setTimeout(function(){

                $(".header_message").html("");

            }, 5000)

        }



        if(typeof window.sessionStorage.getItem("orderMessage") != "undefined")

        {

            $(".header_message").html(window.sessionStorage.getItem("orderMessage"));

            window.sessionStorage.removeItem("orderMessage");

            setTimeout(function(){

                $(".header_message").html("");

            }, 5000)

        }





});

</script>

</html>

<?php



if (isset($_POST['db_submit'])) {

    $id = $_POST['frame_id'];

    $name = $_POST['frame_name'];
    $type = $_POST['frame_type'];
    $color = $_POST['frame_color'];
    $description = $_POST['frame_description'];
    $tmp_path = $_POST['tmp_path'];
    $price = $_POST['frame_price'];
    $images = $_POST["upload_frame"];
    $db_images = (isset($_POST["upload_frame"]) && !empty($_POST["upload_frame"]) ? json_encode($_POST["upload_frame"]) : null);
    $frame_status = $_POST['frame_status'];

    // Perform a query, check for error

    $stmt1 = mysqli_query($conn, "update frames set `name` = '".$name."', `description` = '".$description."', `color` = '".$color."', `type` = '".$type."', `price` = '".$price."', `active` = ".$frame_status.", `modified_at` = '".date('Y-m-d H:i:s')."' where id = ".$id);

    if (!file_exists(ROOT_DIRECTORY."/frames/images/".$id)) {

        if (mkdir(ROOT_DIRECTORY."/frames/images/".$id, 0777)) {

            if ($db_images){
                for($i=0; $i < count($images); $i++){
                    if(rename(ROOT_DIRECTORY."/frames/images/tmp/".$tmp_path."/".$images[$i], ROOT_DIRECTORY."/frames/images/".$id."/".$images[$i])) {
                        $new_img_path[] = "/frames/images/".$id."/".$images[$i];
                        if($i == count($images)-1){
                            $stmt2 = mysqli_query($conn, "Update frames set `img_path` = '".json_encode($new_img_path)."' where id=".$id);
                        }
                    }
                }
            }
        }

    } else {

        foreach (new DirectoryIterator(ROOT_DIRECTORY."/frames/images/".$id) as $fileInfo) {

            if ($fileInfo->isDot()) {

                continue;

            }

            $filewithinfo = pathinfo($fileInfo->getFileName());

            if (isset($filewithinfo['extension'])) {

                unlink(ROOT_DIRECTORY."/frames/images/".$id."/".$filewithinfo['basename']);

            }

        }

        if ($db_images){
            for($i=0; $i < count($images); $i++){
                if(rename(ROOT_DIRECTORY."/frames/images/tmp/".$tmp_path."/".$images[$i], ROOT_DIRECTORY."/frames/images/".$id."/".$images[$i])) {
                    $new_img_path[] = "/frames/images/".$id."/".$images[$i];
                    if($i == count($images)-1){
                        $stmt2 = mysqli_query($conn, "Update frames set `img_path` = '".json_encode($new_img_path)."' where id=".$id);
                    }
                }
            }
        }
    }

    echo "<script>window.location.href='".$http.$_SERVER['HTTP_HOST'].ADMIN_DIR."frames-list.php'</script>";

}

        $conn->close();

    } else {

        header("Location:login.php");

    }



?>

