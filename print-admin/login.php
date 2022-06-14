<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);
if (isset($_POST['submit'])) {
    if ($_POST['username'] == 'admin' && $_POST['password'] == '123456') {
        session_start();
        $_SESSION['active'] = 1;
        header("Location:mirrors.php");
    } else {
        $_SESSION['active'] = 0;
    }
}
?>
<!doctype html>
<html class="no-js h-100" lang="en">
  <?php include_once('header.php'); ?>
  <body class="h-100">
    <div class="container-fluid icon-sidebar-nav h-100">
      <div class="row h-100">
	  <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
          <div class="nav-wrapper">
            </div>
        </aside>
        <!-- End Main Sidebar -->
        <main class="main-content col">
          <div class="main-content-container container-fluid px-4 my-auto h-100">
            <div class="row no-gutters h-100">
              <div class="col-lg-3 col-md-5 auth-form mx-auto my-auto">
                <div class="card">
                  <div class="card-body">
                    <img class="auth-form__logo d-table mx-auto mb-3" src="images/shards-dashboards-logo.svg" alt="Shards Dashboards - Register Template">
                    <h5 class="auth-form__title text-center mb-4">Access Your Account</h5>
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="username" placeholder="Enter email">
                      </div>
                      <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password">
                      </div>
                      <!--<div class="form-group mb-3 d-table mx-auto">
                        <div class="custom-control custom-checkbox mb-1">
                          <input type="checkbox" class="custom-control-input" id="customCheck2">
                          <label class="custom-control-label" for="customCheck2">Remember me for 30 days.</label>
                        </div>
                      </div>-->
                      <input name="submit" type="submit" class="btn btn-pill btn-accent d-table mx-auto" value="Access Account"></input>
                    </form>
                  </div>
                  <div class="card-footer border-top">
                    <ul class="auth-form__social-icons d-table mx-auto">
                      <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                      <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                      <li><a href="#"><i class="fab fa-github"></i></a></li>
                      <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                    </ul>
                  </div>
                </div>
                <div class="auth-form__meta d-flex mt-4">
                  <a href="forgot-password.html">Forgot your password?</a>
                  <a class="ml-auto" href="register.html">Create new account?</a>
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
