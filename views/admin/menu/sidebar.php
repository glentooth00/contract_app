<nav class="sideBar">
  <div class="logo mb-3 p-2 d-flex align-items-center justify-content-center">
    <div class="me-2">
      <img src="../../../public/images/logo.png" alt="Logo" class="img-fluid" style="max-height: 40px;">
    </div>
    <div>
      <h6 class="m-0 text-dark fw-bold">Contract Monitor</h6>
    </div>
  </div>

  <hr>

  <ul class="nav flex-column">
    <li class="nav-item">
      <a class="nav-link" id="dashboardLink" href="index.php">
        <img width="25px" src="../../../public/images/dashboard.svg">
        <span>Dashboard</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="#" id="contractsDropdown">
        <img width="25px" src="../../../public/images/contracts.svg">
        <span>Contracts</span>
        <i class="fa fa-chevron-down ms-auto small toggle-icon"></i>
      </a>
      <ul class="collapse" id="contractsMenu">
        <li class="nav-item">
          <a class="nav-link" id="contractsLink" href="list.php">
            <img width="25px" src="../../../public/images/active_contracts.svg">
            <span>Active Contracts</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="expired_list.php">
            <img width="25px" src="../../../public/images/expired.svg">
            <span>Expired Contracts</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">
            <img width="25px" src="../../../public/images/archived.svg">
            <span>Archived Contracts</span>
          </a>
        </li>
      </ul>
    </li>

    <?php
    use App\Controllers\UserController;

    $logged_user = null;
    $usersLink = 'IT/users.php';

    if (isset($_SESSION['data']['id'])) {
      $id = $_SESSION['data']['id'];
      $getUser = new UserController();
      $logged_user = $getUser->getUserRolebyId($id);

      $currentDir = basename(dirname($_SERVER['PHP_SELF']));
      if ($currentDir === 'IT') {
        $usersLink = 'users.php';
      }
    }
    ?>


    <li class="nav-item">
      <a class="nav-link" href="#" id="settingsDropdown">
        <img width="27px" src="../../../public/images/settings.svg">
        <span>Settings</span>
        <i class="fa fa-chevron-down ms-auto small toggle-icon"></i>
      </a>
      <ul class="collapse" id="settingsMenu">
        <!-- <li class="nav-item">
          <a class="nav-link" href="#">
            <img width="27px" src="../../../public/images/general.svg">
            <span>General</span>
          </a>
        </li> -->
        <?php if ($department === 'BAC'): ?>
          <li class="nav-item">
            <a class="nav-link" href="procurement.php">
              <img width="27px" src="../../../public/images/mop.svg">
              <span>Mode Of Procurement</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($logged_user === 'Admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $usersLink; ?>">
              <img width="27px" src="../../../public/images/user.svg">
              <span>Users</span>
            </a>
          </li>
        <?php endif; ?>

        <?php if ($logged_user === 'Admin'): ?>
          <li class="nav-item">
            <a class="nav-link" href="department.php">
              <img width="27px" src="../../../public/images/departments.svg"></i>
              <span>Departments</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contract_types.php">
              <img width="27px" src="../../../public/images/contract-types.svg"></i></i><span>Contract Types</span>
            </a>
          </li>
        <?php endif; ?>

        <!-- <li class="nav-item">
          <a class="nav-link" href="#"><img width="28px"
              src="../../../public/images/privacy.svg"><span>Privacy</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><img width="28px"
              src="../../../public/images/notif.svg"><span>Notifications</span></a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link" href="../logout.php">
            <img width="28px" src="../../../public/images/logout.svg">
            <span>Logout</span>
          </a>
        </li>
      </ul>
    </li>
  </ul>
</nav>

<style>
  .sideBar {
    width: 260px;
    height: 100vh;
    background-color: #F7F7F7;
    color: white;
    padding: 1rem;
    position: fixed;
    box-shadow: 2px 0 6px rgba(0, 0, 0, 0.15);
    overflow-y: auto;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .logo {
    background-color: #ecf0f1;
    border-radius: 8px;
    text-align: center;
  }

  .nav-link {
    font-weight: 700;
    color: #393E46;
    font-size: 15px;
    display: flex;
    align-items: center;
    padding: 10px 9px;
    border-radius: 5px;
    margin-bottom: 5px;
    transition: all 0.1s ease;
  }

  .nav-link:hover {
    background-color: #34495e;
    color: white;
    text-decoration: none;
  }

  .nav-link span {
    margin-left: 10px;
  }

  .nav .nav-item .nav-link.active,
  .nav .nav-item .disabled-link {
    background-color: #1abc9c !important;
    color: white !important;
    font-weight: bold;
  }

  .collapse {
    display: none;
    margin-left: 1px;
  }

  .toggle-icon {
    margin-left: auto;
    transition: transform 0.3s ease;
  }

  .expanded .toggle-icon {
    transform: rotate(180deg);
  }

  hr {
    border-top: 1px solid #7f8c8d;
    margin: 1rem 0;
  }

  ul.nav {
    padding-left: 0;
  }

  ul li {
    list-style: none;
  }

  .main-layout {
    display: flex;
    min-height: 100vh;
  }

  .content-area {
    margin-left: 270px;
    /* Same as sidebar width */
    width: calc(100% - 240px);
    background-color: #f8f9fa;
    padding: 20px;
  }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function () {
    const currentUrl = window.location.href;

    if (currentUrl.includes('index.php')) {
      $('#dashboardLink').addClass('active');
    }
    if (currentUrl.includes('list.php')) {
      $('#contractsLink').addClass('active');
    }

    $('#contractsDropdown').click(function (e) {
      e.preventDefault();
      $('#contractsMenu').slideToggle(200);
      $(this).toggleClass('expanded');
    });

    $('#settingsDropdown').click(function (e) {
      e.preventDefault();
      $('#settingsMenu').slideToggle(200);
      $(this).toggleClass('expanded');
    });
  });
</script>