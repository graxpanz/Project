<?php
$menu = [
    [
        "module" => "dashboard",
        "name" => "หน้าหลัก",
        "icon" => "fas fa-address-book",
        "url" => "/dashboard"
    ],
    [
        "module" => "manager",
        "name" => "ผู้ดูแลระบบ",
        "icon" => "fas fa-user-cog",
        "url" => "/manager"
    ],
    [
        "module" => "role",
        "name" => "จัดการสิทธ์การใช้งาน",
        "icon" => "fas fa-user-cog",
        "url" => "/role"
    ],
    // [
    //     "module" => "employee",
    //     "name" => "จัดการข้อมูลพนักงาน",
    //     "icon" => "fas fa-users",
    //     "url" => "/employee"
    // ],
    [
        "module" => "employee-schedule",
        "name" => "ตารางงานของพนักงาน",
        "icon" => "fas fa-calendar-day",
        "url" => "/employee-schedule"
    ],
    [
        "module" => "stock",
        "name" => "จัดการข้อมูลสินค้าและผลิตภัณฑ์ภายในร้าน",
        "icon" => "fas fa-box",
        "url" => "/stock"
    ],
    [
        "module" => "service",
        "name" => "จัดการข้อมูลการบริการ",
        "icon" => "fas fa-air-freshener",
        "url" => "/service"
    ],
    [
        "module" => "estimate",
        "name" => "จัดการข้อมูลการประเมินใบหน้า",
        "icon" => "fas fa-user-circle",
        "url" => "/estimate"
    ],
    [
        "module" => "promotion",
        "name" => "จัดการโปรโมชั่น",
        "icon" => "fas fa-tag",
        "url" => "/promotion"
    ],
    [
        "module" => "comment",
        "name" => "จัดการความคิดเห็น",
        "icon" => "fas fa-comments",
        "url" => "/comment"
    ],
    [
        "module" => "finance",
        "name" => "ข้อมูลทางการเงิน",
        "icon" => "fas fa-wallet",
        "url" => "/finance"
    ],
];
$session = explode(", " , $_SESSION['AD_PERMISSION']);
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
            <a href="/dashboard">
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
    <a href="/dashboard" class="brand-link">
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
                <a href="/manager" class="d-block">
                    <?php echo $_SESSION['AD_FIRSTNAME'] . ' ' . $_SESSION['AD_LASTNAME'] ?>
                </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">จัดการข้อมูล</li>
                <?php foreach ($menu as $item): ?>
                    <?php if (in_array($item['module'], $session)): ?>
                    <li class="nav-item">
                        <a href="<?php echo $item['url'] ?>" class="nav-link <?php echo isActive(ltrim($item['url'], '/')) ?>">
                            <i class="nav-icon <?php echo $item['icon'] ?>"></i>
                            <p><?php echo $item['name'] ?></p>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <!-- Account Section -->
                <li class="nav-header">บัญชีของเรา</li>
                <li class="nav-item">
                    <a href="/logout" id="logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>ออกจากระบบ</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>