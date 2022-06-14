<?php
    session_start();
    if (isset($_SESSION['active']) && $_SESSION['active'] == 1) {
        ?>
<!doctype html>
<html class="no-js h-100" lang="en">
<?php
    include_once('header.php');
        $db = new DbConnection;
        $conn = $db->getConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);				mysqli_set_charset($conn, 'utf8');		
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
						<div class="col-12 col-sm-10 text-center text-sm-left mb-0">
							<span class="text-uppercase page-subtitle">Overview</span>
							<h3 class="page-title">Translations settings</h3>
						</div>
                        <div class="col-12 col-sm-2">
                            <div class="bg-primary rounded text-white text-sm-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);cursor:pointer;" id="add-translation">Add translation</div>
                        </div>
                        <div class="col-12 col-sm-11 text-center text-sm-center mb-0"><div class="header_message"></div></div>
					</div>
					<!-- End Page Header -->
					<div class="row">

              <div class="col">
                <div class="card card-small mb-4">
                  <div class="card-header border-bottom">
                    <h6 class="m-0">Translations list</h6>
                  </div>
                  <div class="card-body p-0 pb-3 text-center">

                    <table class="table mb-0 example">
                      <thead class="bg-light">
                        <tr>
                          <th scope="col" class="border-0">#</th>
                          <th scope="col" class="border-0">Code</th>
                          <th scope="col" class="border-0">English</th>
                          <th scope="col" class="border-0">Hebrew</th>
                          <th scope="col" class="border-0">Status</th>
                          <th scope="col" class="border-0">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                          <?php
                        // prepare and bind
                        $stmt = mysqli_query($conn, "SELECT * from translations order by id desc");
        if (mysqli_num_rows($stmt) === 0) {
            exit('');
        }
        $i = 0;
        $html = "";
        while ($row = $stmt->fetch_assoc()) {
            $html .= "<tr data-id='".$row['id']."'>";
            $html .= "<td>".($i+1)."</td>";
            $html .= "<td>".$row['code']."</td>";
            $html .= "<td>".$row['lang_en']."</td>";
            $html .= "<td>".$row['lang_hb']."</td>";
            $html .= "<td>".(($row['active']==1) ? 'Active' : 'In-Active')."</td>";
            $html .= "<td><button type='button' class='mb-2 btn btn-sm btn-success mr-1 edit-translation' id='edit-form-".$row['id']."'>Edit</button>
                                    <button type='button' class='mb-2 btn btn-sm btn-danger mr-1' onclick='deleteTranslation(".$row['id'].");'>Delete</button></td>";
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
  <div class="modal fade" id="editTranslation" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <h6 class="m-0">Update translation</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
                    <div class="img_loader"><img src='<?php $http.$_SERVER['HTTP_HOST'].ADMIN_DIR."ajax-loader.gif"?>'/></div>
                          <div class="row">
                              <div class="col-sm-12 col-md-12">
                                  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="edit-form" onsubmit="return checkTranslation(true)">
                                      <input type="hidden" id="edit_translation_id" name="edit_translation_id" value=""/>
                                      <div class="form-group">
                                          <label>English: </label>
                                          <input class="form-control" autocomplete="off" type="text" name="edit_translation_lang_en" id="edit_translation_lang_en" required/>
                                          <p class="edit_lang_en_error"></p>
                                      </div>
                                      <div class="form-group">
                                          <label>Hebrew: </label>
                                          <input class="form-control" autocomplete="off" type="text" name="edit_translation_lang_hb" id="edit_translation_lang_hb" required/>
                                      </div>
                                      <div class="form-group">
                                          <label>Status </label>
                                          <select class="form-control" name="edit_translation_status" id="edit_translation_status">
                                              <option value="1">Active</option>
                                              <option value="0">In-Active</option>
                                          </select>
                                      </div>
                                      <div class="form-group">
                                          <input name="db_edit_submit" type="submit" class="bg-primary rounded text-white text-center p-2" style="box-shadow: inset 0 0 5px rgba(0,0,0,.2);" />
                                      </div>
                                  </form>
                              </div>
                          </div>

        </div>
      </div>

    </div>
  </div>
</div>

<div class="container">
  <!-- Trigger the modal with a button -->

  <!-- Modal -->
  <div class="modal fade" id="addTranslation" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <h6 class="m-0">Add translation</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
                    <div class="img_loader"><img src='<?php $http.$_SERVER['HTTP_HOST']. ADMIN_DIR."ajax-loader.gif"?>'/></div>
                          <div class="row">
                              <div class="col-sm-12 col-md-12">
                                  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" class="add-form" onsubmit="return checkTranslation(false)">
                                      <div class="form-group">
                                          <label>English: </label>
                                          <input class="form-control" autocomplete="off" type="text" name="translation_lang_en" id="translation_lang_en" value="" required/>
                                          <p class="lang_en_error"></p>
                                      </div>
                                      <div class="form-group">
                                          <label>Hebrew: </label>
                                          <input class="form-control" autocomplete="off" type="text" name="translation_lang_hb" id="translation_lang_hb" required/>
                                      </div>
                                      <div class="form-group">
                                          <label>Status </label>
                                          <select class="form-control" name="translation_status" id="translation_status">
                                              <option value="1">Active</option>
                                              <option value="0">In-Active</option>
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
    $code = addslashes(strtolower(str_replace(" ", "_", trim($_POST['translation_lang_en']))));
    $lang_en = addslashes(trim($_POST['translation_lang_en']));
    $lang_hb = addslashes(trim($_POST['translation_lang_hb']));
    $translation_status = $_POST['translation_status'];
    // Perform a query, check for error
    $stmt1 = mysqli_query($conn, "insert into translations(code, lang_en, lang_hb, active, created_at, modified_at) values('".$code."', '".$lang_en."', '".$lang_hb."',  $translation_status, '".date('Y-m-d H:i:s')."','".date('Y-m-d H:i:s')."')");

    if ($stmt1 === true) {
        $id = mysqli_insert_id($conn);
        echo "<script>window.location.href='".$http.$_SERVER['HTTP_HOST'].ADMIN_DIR."translations-list.php'</script>";
    }
}

        if (isset($_POST['db_edit_submit'])) {
            $code = addslashes(strtolower(str_replace(" ", "_", trim($_POST['edit_translation_lang_en']))));
            $lang_en = addslashes(trim($_POST['edit_translation_lang_en']));
            $lang_hb = addslashes(trim($_POST['edit_translation_lang_hb']));
            $translation_status = $_POST['edit_translation_status'];
            // Perform a query, check for error
            $stmt1 = mysqli_query($conn, "update translations set `code` = '".$code."', `lang_en` = '".$lang_en."', `lang_hb` = '".$lang_hb."', `active` = $translation_status, `modified_at` = '".date('Y-m-d H:i:s')."' where id = ".$_POST['edit_translation_id']);
            if (mysqli_affected_rows($conn) > 0) {
                echo "<script>window.location.href='".$http.$_SERVER['HTTP_HOST'].ADMIN_DIR."translations-list.php'</script>";
            }
        }
        $conn->close();
    } else {
        header("Location:login.php");
    }

?>
