<?php ?>
<!-- Main Sidebar -->
        <aside class="main-sidebar col-12 col-md-3 col-lg-2 px-0">
          <div class="main-navbar">
            <nav class="navbar align-items-stretch navbar-light bg-white flex-md-nowrap border-bottom p-0">
              <a class="navbar-brand w-100 mr-0" href="#" style="line-height: 25px;">
                <div class="d-table m-auto">
                  <img id="main-logo" class="d-inline-block align-top mr-1" style="max-width: 25px;" src="images/shards-dashboards-logo.svg" alt="Shards Dashboard">
                  <span class="d-none d-md-inline ml-1">Painting App Dashboard</span>
                </div>
              </a>
              <a class="toggle-sidebar d-sm-inline d-md-none d-lg-none">
                <i class="material-icons">&#xE5C4;</i>
              </a>
            </nav>
          </div>
          <form action="#" class="main-sidebar__search w-100 border-right d-sm-flex d-md-none d-lg-none">
            <div class="input-group input-group-seamless ml-3">
              <div class="input-group-prepend">
                <div class="input-group-text">
                  <i class="fas fa-search"></i>
                </div>
              </div>
              <input class="navbar-search form-control" type="text" placeholder="Search for something..." aria-label="Search"> </div>
          </form>

          <div class="nav-wrapper">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'mirrors.php') === false) ? '' : 'active' ?>" href="mirrors.php">
                  <i class="material-icons">edit</i>
                  <span>Mirrors</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'glass.php') === false) ? '' : 'active' ?>" href="glass.php">
                  <i class="material-icons">vertical_split</i>
                  <span>Glass</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'frames.php') === false) ? '' : 'active'; echo (strpos($_SERVER['REQUEST_URI'], 'frames-list.php') === false) ? '' : 'active' ?>" href="frames-list.php">
                  <i class="material-icons">note_add</i>
                  <span>Frames</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'prints.php') === false) ? '' : 'active' ?>" href="prints.php">
                  <i class="material-icons">view_module</i>
                  <span>Prints</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'other_products.php') === false) ? '' : 'active' ?>" href="other_products.php">
                  <i class="material-icons">table_chart</i>
                  <span>Other products</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], 'translations-list.php') === false) ? '' : 'active' ?>" href="translations-list.php">
                  <i class="material-icons">table_chart</i>
                  <span>Translations</span>
                </a>
              </li>
              </ul>
          </div>
        </aside>
        <!-- End Main Sidebar -->
