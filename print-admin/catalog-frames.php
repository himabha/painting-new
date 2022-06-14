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
						<div class="col-12 col-sm-11 text-center text-sm-left mb-0">
                            <div class="col-md-4">
    							<span class="text-uppercase page-subtitle">Overview</span>
    							<h3 class="page-title">Catalog Frames settings</h3>
                            </div>
                            <div class="col-md-2 bg-primary rounded text-white text-sm-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);cursor:pointer;" onclick="window.location.href='frames-list.php'"> Frames Listing</div>
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
                    <h6 class="m-0">Catalog Frames list</h6>
                  </div>
                  <div class="card-body p-0 pb-3 text-center">

                    <table class="table mb-0 example">
                      <thead class="bg-light">
                        <tr>
                          <th scope="col" class="border-0">#</th>
                          <th scope="col" class="border-0">Name</th>
                          <th scope="col" class="border-0">Width</th>
                          <th scope="col" class="border-0">Height</th>
                          <th scope="col" class="border-0">Price</th>
                          <th scope="col" class="border-0">Status</th>
                          <th scope="col" class="border-0">Action</th>
                        </tr>
                      </thead>
                      <tbody id="sortable">
                          <?php
                        // prepare and bind
                        $stmt = mysqli_query($conn, "SELECT * from catalog_frames order by sort desc");
        if (mysqli_num_rows($stmt) === 0) {
            exit('');
        }
        $i = 0;
        $html = "";
        while ($row = $stmt->fetch_assoc()) {
            $html .= "<tr data-id='".$row['id']."'>";
            $html .= "<td>".($i+1)."</td>";
            $html .= "<td>".$row['name']."</td>";
            $html .= "<td>".$row['width']."</td>";
            $html .= "<td>".$row['height']."</td>";
            $html .= "<td>".$row['price']."</td>";
            $html .= "<td>".(($row['active']==1) ? 'Active' : 'In-Active') ."</td>";
            $html .= "<td><button type='button' class='mb-2 btn btn-sm btn-success mr-1 edit-catalog-frame' id='edit-catalog-form-".$row['id']."'>Edit</button>
                                    <button type='button' class='mb-2 btn btn-sm btn-danger mr-1' onclick='deleteCatalogFrame(".$row['id'].");'>Delete</button></td>";
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
            <h6 class="m-0">Update frame Db</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
                    <div class="img_loader"><img src='<?php $http.$_SERVER['HTTP_HOST'].ADMIN_DIR."ajax-loader.gif"?>'/></div>
                          <div class="row">
                              <div class="col-sm-12 col-md-12">
                                  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="edit-catalog-form">
                                      <input type="hidden" id="frame_id" name="frame_id" value=""/>

                                      <div class="form-group">
                                          <label>Frame name: </label>
                                          <input class="form-control" type="text" name="frame_name" id="frame_name" required/>
                                      </div>
                                      <div class="form-group frame_catalog_min_dimensions">
                                          <label for="frame_catalog_dimensions1">Dimensions</label>
                                          <div class="form-group">
                                              <input type="text" class="form-control col-md-2" name="frame_catalog_dimensions1" id="frame_catalog_dimensions1" required/><span class="col-md-1 dimension_symbol">x</span><input class="form-control col-md-2" type="text" name="frame_catalog_dimensions2" id="frame_catalog_dimensions2" required/>
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label>Frame price: </label>
                                          <input class="form-control" type="text" name="frame_price" id="frame_price" required/>
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
    $name = addslashes($_POST['frame_name']);
    $price = $_POST['frame_price'];
    $width = $_POST['frame_catalog_dimensions1'];
    $height = $_POST['frame_catalog_dimensions2'];
    $frame_status = $_POST['frame_status'];
    // Perform a query, check for error
    $stmt1 = mysqli_query($conn, "update catalog_frames set `name` = '".$name."', `width` = '".$width."', `height` = '".$height."', `price` = '".$price."', `active` = ".$frame_status.", `modified_at` = '".date('Y-m-d H:i:s')."' where id = ".$id);

    echo "<script>window.location.href='".$http.$_SERVER['HTTP_HOST'].ADMIN_DIR."catalog-frames.php'</script>";
}
        $conn->close();
    } else {
        header("Location:login.php");
    }

?>
