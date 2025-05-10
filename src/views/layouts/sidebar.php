<?php
$menu = [
    // [
    //     "module" => "dashboard",
    //     "name" => "หน้าหลัก",
    //     "icon" => "fas fa-address-book",
    //     "url" => "/dashboard"
    // ],
    [
        "module" => "work-calendar",
        "name" => "ปฏิทินการปฏิบัติงาน",
        "icon" => "fas fa-calendar",
        "url" => "/work-calendar"
    ],
    [
        "module" => "user",
        "name" => "จัดการผู้ใช้",
        "icon" => "fas fa-user-cog",
        "url" => "/user"
    ],
    [
        "module" => "role",
        "name" => "จัดการสิทธ์ผู้ใช้",
        "icon" => "fas fa-user-shield",
        "url" => "/role"
    ],
    [
        "module" => "customer",
        "name" => "จัดการข้อมูลลูกค้า",
        "icon" => "fas fa-users",
        "url" => "/customer"
    ],
    [
        "module" => "service_type",
        "name" => "จัดการประเภทของบริการ",
        "icon" => "fas fa-list",
        "url" => "/service_type"
    ],
    [
        "module" => "service",
        "name" => "จัดการข้อมูลการบริการ",
        "icon" => "fas fa-concierge-bell",
        "url" => "/service"
    ],
    [
        "module" => "promotion",
        "name" => "จัดการโปรโมชั่น",
        "icon" => "fas fa-tag",
        "url" => "/promotion"
    ],
    [
        "module" => "booking",
        "name" => "จัดการข้อมูลการจอง",
        "icon" => "fas fa-calendar-check",
        "url" => "/booking"
    ],
    [
        "module" => "feedback",
        "name" => "จัดการข้อมูลความคิดเห็น",
        "icon" => "fas fa-comment",
        "url" => "/feedback"
    ],
    [
        "module" => "estimate",
        "name" => "จัดการข้อมูลการประเมิน",
        "icon" => "fas fa-clipboard-list",
        "url" => "/estimate"
    ],
    [
        "module" => "finance",
        "name" => "จัดการรายรับ-รายจ่าย",
        "icon" => "fas fa-money-bill-wave",
        "url" => "/finance"
    ],
    [
        "module" => "supply",
        "name" => "จัดการวัสดุสิ้นเปลือง",
        "icon" => "fas fa-boxes",
        "url" => "/supply"
    ],
        [
        "module" => "stock",
        "name" => "จัดการคลังสินค้า",
        "icon" => "fas fa-warehouse",
        "url" => "/stock"
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
            <a class="nav-link">เข้าสู่ระบบครั้งล่าสุด: <?= $_SESSION['AD_LOGIN'] ?> </a>
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
                <img src="<?= !empty($_SESSION['AD_IMAGE']) && $_SESSION['AD_IMAGE'] ? '/assets/uploads/user/' . $_SESSION['AD_IMAGE'] : '/assets/images/avatar.png' ?>" class="img-circle elevation-2" style="width: 2.1rem; height: 2.1rem;">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?= $_SESSION['AD_FIRSTNAME'] . ' ' . $_SESSION['AD_LASTNAME'] ?>
                </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-header">จัดการข้อมูล</li>
                <?php foreach ($menu as $item): ?>
                    <?php if (in_array($item['module'], $session)): ?>
                    <li class="nav-item">
                        <a href="<?= $item['url'] ?>" class="nav-link <?= isActive(ltrim($item['url'], '/')) ?>">
                            <i class="nav-icon <?= $item['icon'] ?>"></i>
                            <p><?= $item['name'] ?></p>
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