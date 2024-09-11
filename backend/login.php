<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Mira ศูนย์ความงามครบวงจร</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/x-icon" href="assets/images/logo.ico">
  <!-- stylesheet -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Kanit">
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<header class="bg" ></header>
<section class="d-flex align-items-center min-vh-100">
  <div class="container">
    <div class="row justify-content-center">
      <section class="col-lg-6">
        <div class="card shadow p-3 p-md-4">
        <h1 class="text-center font-weight-bold" style="color: #FF69B4;">Mira ศูนย์ความงามครบวงจร</h1>
          <h4 class="text-center">เข้าสู่ระบบหลังบ้าน</h4> 
          <div class="card-body">
            <!-- HTML Form Login --> 
            <form id="formLogin" method="POST" action="service/auth/login.php">
              <div class="form-group col-sm-12">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text px-2">ชื่อผู้ใช้งาน</div>
                  </div>
                  <input type="text" class="form-control" name="username" placeholder="username">
                </div>
              </div>
              <div class="form-group col-sm-12">
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text px-3">รหัสผ่าน</div>
                  </div>
                  <input type="password" class="form-control" name="password" placeholder="password">
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-block"> เข้าสู่ระบบ</button>
            </form>
          </div>
        </div>
      </section>
    </div>
  </div>
</section>

<!-- script -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
