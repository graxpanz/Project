<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title ?? 'Mira ศูนย์ความงามครบวงจร'; ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="/assets/images/logo.ico">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="/plugins/bootstrap-toggle/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="/assets/css/adminlte.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <?php echo $additionalHead ?? ''; ?>

    <!-- Scripts -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="/assets/js/adminlte.min.js"></script>
    <script src="/plugins/bootstrap-toggle/bootstrap-toggle.min.js"></script>
    <script src="/plugins/toastr/toastr.min.js"></script>
    <!-- DataTables -->
    <script src="/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <!-- OPTIONAL SCRIPTS -->
    <script src="/plugins/chart.js/Chart.min.js"></script>
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="content-wrapper pt-3">
            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>
    </div>
    <?php echo $additionalScripts ?? ''; ?>
    <?php 
    $flash = Session::getFlash();
    if ($flash): 
    ?>
    <script>
        Swal.fire({
            icon: '<?php echo $flash['type']; ?>',
            title: '<?php echo $flash['message']; ?>',
            showConfirmButton: false,
            timer: 1500
        });
    </script>
    <?php endif; ?>
</body>

</html>