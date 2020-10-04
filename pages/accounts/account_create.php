<?php
session_start();
include 'process.php';
if (!isset($_SESSION['group'])) {
  header('location: ../utils/logout.php');
}else{
  $username = $_SESSION['username'];
  $group = $_SESSION['group'];
}
 ?>
<!DOCTYPE  html>
<html>
<!--head-->
<?php
include __DIR__.'/../partials/head.php';
?>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <?php include __DIR__.'/../partials/navbar.php'; ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <?php include __DIR__.'/../partials/sidebar.php'; ?>
    <!--    end sidebar-->


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
          <?php
          if (isset($_SESSION['error'])){ ?>
              <div class="alert alert-danger col-12 alert-dismissible fade show">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  <?= $_SESSION['error'] ?>
              </div>
              <?php unset($_SESSION['error']); }
          ?>
        <div class="row mb-2">
            <div class="col-12">
                <?php
                if (isset($_SESSION['error'])){ ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $_SESSION['error'] ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php unset($_SESSION['error']); }
                elseif (isset($_SESSION['success'])){ ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $_SESSION['success'] ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['success']); }
                ?>
            </div>
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Accounts</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Accounts</a></li>
              <li class="breadcrumb-item active">Add</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

<!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Add an account</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="card-body">
                  <span style="color: red;">Sections marked with * must be filled</span>
                  <div class="row">
                      <div class="col-md-12">
                          <div class="form-group">
                              <label for="">Account Id: <span style="color: red;">*</span></label>
                              <?php
                              $query = $conn->query('SELECT id FROM coa ORDER BY id DESC LIMIT 1');
                              $last = $query->fetch_assoc();
                              $lastId = isset($last['id']) ? (int)$last['id'] : 0;
                              ?>
                              <input type="number" value="<?= $lastId + 1 ?>" min="0" name="account_id" class="form-control" placeholder="mpesa, john-23" required/>
                              <span style="color: red;"><?php echo $id_err; ?></span>
                          </div>
                        <div class="form-group">
                          <label for="">Account Group: <span style="color: red;">*</span></label>
                          <select class="form-control" name="category">
                              <option value="">Select a group</option>
                              <?php
                              $stores = $conn->query("SELECT * FROM coa_group") or die($conn->error);
                              while ($row=$stores->fetch_assoc()) {
                                  echo "<option value=".$row['cat_id'].">".$row['name'] ." - ".$row['type']."</option>";
                              }
                              ?>
                          </select>
                          <span style="color: red;"><?php echo $cat_err; ?></span>
                      </div>
                        <div class="form-group">
                            <label for="">Account Name: <span style="color: red;">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="mpesa, john-23" required/>
                            <span style="color: red;"><?php echo $name_err; ?></span>
                        </div>

                    </div>
                  </div>

                <!-- /.card-body -->

                <div class="card-footer text-center">
                  <button type="submit" class="btn btn-primary" name="account_create">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">All Accounts</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Account Name</th>
                                <th>Account Group</th>
                                <th colspan="2">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $number =1;
                            $res = $conn->query("SELECT coa.*, coa_group.name as group_name FROM 
                                                 coa JOIN 
                                                     coa_group on 
                                                         coa.catId = coa_group.cat_id ORDER BY 
                                                                                               id DESC") or die($conn->error);
                            while ($row= $res->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo $number; ?></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['group_name']; ?></td>
                                    <td style="width: 40px;"><a href="expense_categories.php?cat_edit=<?php echo $row['id'];?>"><button class="btn btn-success">Edit</button></a></td>
                                    <td style="width: 40px;""><a href="<?= $_SERVER["PHP_SELF"] ?>?acc_delete=<?php echo $row['id'];?>"><button class="btn btn-danger">Delete</button></a></td>

                                </tr>
                                <?php
                                $number++;
                            endwhile;
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

    <!--    footer-->
    <?php
        include __DIR__.'/../partials/footer.php';
    ?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!--    footer-->
<?php
include __DIR__.'/../partials/scripts.php';
?>
<!-- fullCalendar 2.2.5 -->
<script src="../../plugins/moment/moment.min.js"></script>

</script>
</body>
</html>
