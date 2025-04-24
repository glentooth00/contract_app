<nav class="sideBAr">
<div class="logo bg-white mb-3 p-0 text-center col-md-12 d-flex align-items-center">
    <div class="col-md-5 p-2">
      <img src="../../../public/images/logo.png" alt="Logo" class="img-fluid">
    </div>
    <div class="col-md-5 ms-3 p-2">
     <h6>Contract Monitoring App</h6>
    </div>
</div>

  <hr class="text-light">
  <ul class="nav flex-column">
    <div class="mt-1">
      <li class="nav-item">
        <a class="nav-link" id="dashboardLink" href="index.php"><i class="fa fa-tachometer p-2" aria-hidden="true"></i>Dashboard</a>
      </li>

      <!-- Contracts Dropdown -->
      <li class="nav-item">
        <a class="nav-link" href="#" id="contractsDropdown">
          <i class="fa fa-files-o p-2" aria-hidden="true"></i>Contracts
        </a>
        <ul class="collapse" id="contractsMenu">
          <li class="nav-item">
            <a class="nav-link" id="contractsLink" href="list.php"><i class="fa fa-file-text-o p-2" aria-hidden="true"></i>
            Active Contracts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="expired.php"><i class="fa fa-file-o p-2" aria-hidden="true"></i>
            Expired Contracts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-file-archive-o p-2" aria-hidden="true"></i>
            Archived Contracts</a>
          </li>
        </ul>
      </li>

        <?php 
          use App\Controllers\UserController;

          if (isset($_SESSION['data']['id'])) {
              $id = $_SESSION['data']['id'];

              $getUser = new UserController();
              $logged_user = $getUser->getUserRolebyId($id); // returns string like 'admin'
        
        ?>

              <?php if ($logged_user == 'Admin'): ?>
                    <li class="nav-item">
                      <a class="nav-link" href="users.php">
                          <i class="fa fa-users p-2" aria-hidden="true"></i>Users
                        </a>
                    </li>              
              <?php  endif; ?>

        <?php
          } else {
              // echo "User session not found.";
          }
        ?>

       <!-- Settings Dropdown -->
       <li class="nav-item">
        <a class="nav-link" href="#" id="settingsDropdown">
          <i class="fa fa-cog p-2" aria-hidden="true"></i>Settings
        </a>
        <ul class="collapse" id="settingsMenu">
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-globe p-2" aria-hidden="true"></i>
            General</a>
          </li>

          <?php 

          if (isset($_SESSION['data']['id'])) {
              $id = $_SESSION['data']['id'];

              $getUser = new UserController();
              $logged_user = $getUser->getUserRolebyId($id); // returns string like 'admin'
        
        ?>

              <?php if ($logged_user == 'Admin'): ?>
                <li class="nav-item">
                  <a class="nav-link" href="department.php"><i class="fa fa-building-o p-2" aria-hidden="true"></i>
                  Departments</a>
                </li>              
              <?php  endif; ?>

              <?php if ($logged_user == 'Admin'): ?>
                <li class="nav-item">
                  <a class="nav-link" href="contract_types.php"><i class="fa fa-file-text-o p-2" aria-hidden="true"></i>
                  Contract Types</a>
                </li>              
              <?php  endif; ?>

        <?php
          } else {
              // echo "User session not found.";
          }
        ?>

          
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-user-secret p-2  " aria-hidden="true"></i>
            </i>Privacy</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-bell p-2" aria-hidden="true"></i>
            Notifications</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../logout.php"><i class="fa fa-sign-out fa-flip-horizontal p-2" aria-hidden="true"></i>
            Logout</a>
          </li>
        </ul>
      </li>

      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
    </div>

    <!-- <i class="fa fa-sign-out fa-flip-vertical" aria-hidden="true"></i> -->
  </ul>
</nav>

<style>
  .sideBar {
    width: 22%;
    padding: 20px;
    height: 110vh;
    box-sizing: border-box;
  }
  .nav {
    margin-left: -1em;
  }

  .collapse {
    display: none;
  }

  .nav-link {
    cursor: pointer;
    color: white;
  }

  .submenu {
    display: none;
    list-style-type: none;
    padding: 0;
    margin: 0;
  }

  .disabled-link {
    pointer-events: none;
    opacity: 1;
    background-color: white;
    color: black;
  }

  .disabled-link:hover, .disabled-link:focus {
    background-color: white;
    color: black;
  }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  $(document).ready(function() {
    // Get the current page URL
    var currentUrl = window.location.href;

    // Disable the link for the current page
    if (currentUrl.indexOf('dashboard.php') !== -1) {
      $('#dashboardLink').addClass('disabled-link');
    }
    if (currentUrl.indexOf('contracts/index.php') !== -1) {
      $('#contractsLink').addClass('disabled-link');
    }

    // JavaScript to toggle the collapse with a smooth slide effect for Contracts
    $('#contractsDropdown').on('click', function(event) {
      event.preventDefault(); 
      var submenu = $('#contractsMenu');
      submenu.stop(true, true).slideToggle(300);
    });

    // JavaScript to toggle the collapse with a smooth slide effect for Settings
    $('#settingsDropdown').on('click', function(event) {
      event.preventDefault(); 
      var submenu = $('#settingsMenu');
      submenu.stop(true, true).slideToggle(300);
    });
  });
</script>
