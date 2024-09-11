<?php
function isActive($data)
{
    $array = explode('/', $_SERVER['REQUEST_URI']);
    $key = array_search("pages", $array);
    $name = $array[$key + 1];
    return $name === $data ? 'active' : '';
}
?>
<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars fa-2x"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto ">
        <li class="nav-item d-md-none d-block">
            <a href="../dashboard/">
                <img src="../../assets/images/logo.ico" alt="Admin Logo" width="50px" class="img-circle elevation-3">
                <span class="font-weight-light pl-1" style="color: pink;">Mira ศูนย์ความงามครบวงจร</span>
            </a>
        </li>
        <li class="nav-item d-md-block d-none">
            <a class="nav-link">เข้าสู่ระบบครั้งล่าสุด: <?php echo $_SESSION['AD_LOGIN'] ?> </a>
        </li>
    </ul>
</nav>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="../dashboard/" class="brand-link">
        <img src="../../assets/images/logo.ico" alt="Admin Logo" class="brand-image img-circle elevation-3" width="50px"
            height="50px" style="vertical-align: middle; margin-bottom: 5px;">
        <span class="brand-text font-weight-light">MIRA<br>
            ศูนย์ความงามครบวงจร
        </span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../../assets/images/avatar5.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="../manager/" class="d-block">
                    <?php echo $_SESSION['AD_FIRSTNAME'] . ' ' . $_SESSION['AD_LASTNAME'] ?> </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="../dashboard/" class="nav-link <?php echo isActive('dashboard') ?>">
                        <i class="nav-icon fas fa-address-book"></i>
                        <p>หน้าหลัก</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../manager/" class="nav-link <?php echo isActive('manager') ?>">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>ผู้ดูแลระบบ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../employee/" class="nav-link <?php echo isActive('employee') ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>จัดการข้อมูลพนักงาน</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../table/" class="nav-link <?php echo isActive('table') ?>">
                        <i class="nav-icon fas fa-calendar-day"></i>
                        <p>ตารางงานของพนักงาน</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../customer/" class="nav-link <?php echo isActive('customer') ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>จัดการข้อมูลลูกค้า</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../stock/" class="nav-link <?php echo isActive('stock') ?>">
                        <i class="nav-icon fas fa-box"></i>
                        <p>จัดการข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../service/" class="nav-link <?php echo isActive('service') ?>">
                        <i class="nav-icon fas fa-air-freshener"></i>
                        <p>จัดการข้อมูลการบริการ</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../estimate/" class="nav-link <?php echo isActive('estimate') ?>">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>จัดการข้อมูลการประเมินใบหน้า</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../promotion/" class="nav-link <?php echo isActive('promotion') ?>">
                        <i class="nav-icon fas fa-tag"></i>
                        <p>จัดการโปรโมชั่น</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../comment/" class="nav-link <?php echo isActive('comment') ?>">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>จัดการความคิดเห็น</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../finance/" class="nav-link <?php echo isActive('finance') ?>">
                        <i class="nav-icon fas fa-wallet"></i>
                        <p>ข้อมูลทางการเงิน</p>
                    </a>
                </li>
                <li class="nav-header">บัญชีของเรา</li>
                <li class="nav-item">
                    <a href="../logout.php" id="logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>ออกจากระบบ</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>