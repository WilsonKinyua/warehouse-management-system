<?php
// $conn = mysqli_connect('localhost','root','','premierdb') or die($conn->error);
include 'conn.php';
$username_err = $pass_err = "";
$valid = true;
if (isset($_POST['login'])) {


	if (empty($_POST['username'])) {
		$username_err = "Enter your username";
		$valid = false;
	}else{
		$username = trim($_POST['username']);
	}
	if (empty($_POST['password'])) {
		$pass_err = "Enter your password";
		$valid = false;
	}else{
		$password = trim($_POST['password']);
	}

	// Check the Db for credentials
	$res = $conn->query("SELECT * FROM users WHERE username='$username' ") or die($conn->error);
	if (mysqli_num_rows($res) == 1) {
		$row = $res->fetch_assoc();
		$hashed_password = $row['password'];
		if (md5($password) == $hashed_password) {
		// if (password_verify($password, $hashed_password)) {
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['group'] = $row['user_group'];
			$_SESSION['userId'] = $row['id'];
			header('location: ../../index.php');
		}else{
			$pass_err = "Incorrect Password";
		}
	}else{
		$username_err = "Username not found";
	}

}
?>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Premiersoft Warehouse</title>
  <link rel="icon" type="image/x-icon" href="../../dist/img/favicon.ico">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="../../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="../../plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="../../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../../plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="../../plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style type="text/css">
  	body{
  		max-width: 1000px;
  		background: url('http://premier.dinero-legends.co.ke/dist/img/1.jpg');
  	}

  </style>
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-9" style="text-align: center;">
				<div style="display: inline-block;">
				<h3 style="color: #fff; font-size: 50px; font-weight: bold; margin-top: 4em;">Premier Warehouse</h3>
				<p style="font-style: italic;color: gold;">Premiersoft Technologies Ltd</p>
				</div>
			</div>
			<div class="col-md-3">
				<div class="login-box" style="margin: 4em;">
				  <div class="card">
				    <div class="card-body login-card-body">
				      <p class="login-box-msg">Sign in to start your session</p>
				      	<div class="lockscreen-item">
						    <!-- lockscreen image -->
						    <div class="" style="text-align: center;">
						      <img src="http://premier.dinero-legends.co.ke/dist/img/favicon.png" alt="User Image"  style="border-radius: 50%; width: 100px;">
						    </div>
						    <!-- /.lockscreen-image -->
				  		</div>
					  	<hr>
					      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
					        <div class="input-group mb-3">
					          <input type="text" class="form-control" placeholder="Username" name="username">
					          <div class="input-group-append">
					            <div class="input-group-text">
					              <span class="fas fa-envelope"></span>
					            </div>
					          </div>
					        </div>
					        <span style="color: red;"><?php echo $username_err; ?></span>
					        <div class="input-group mb-3">
					          <input type="password" class="form-control" placeholder="Password" name="password">
					          <div class="input-group-append">
					            <div class="input-group-text">
					              <span class="fas fa-lock"></span>
					            </div>
					          </div>
					        </div>
					        <span style="color: red;"><?php echo $pass_err; ?></span>
					        <div class="row">
					          <div class="col-8">
					            <div class="icheck-primary">
					              <input type="checkbox" id="remember">
					              <label for="remember">
					                Remember Me
					              </label>
					            </div>
					          </div>
					          <!-- /.col -->
					          <div class="col-4">
					            <button type="submit" class="btn btn-primary btn-block" name="login">Sign In</button>
					          </div>
					          <!-- /.col -->
					        </div>
					      </form>

				      <p class="mb-1">
				        <a href="">I forgot my password</a>
				      </p>
				    </div>
				    <!-- /.login-card-body -->
				  </div>
				</div>
				<!-- /.login-box -->
			</div>
		</div>
	</div>

  <!-- jQuery -->
  <script src="../../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery UI -->
  <script src="../../plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="../../dist/js/demo.js"></script>



</body></html>
