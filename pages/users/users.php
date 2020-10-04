<?php include '../utils/conn.php';
session_start();
if (!isset($_SESSION['group'])) {
  header('location: ../utils/logout.php');
}else{
  $username = $_SESSION['username'];
  $group = $_SESSION['group'];
}
 ?>
<!DOCTYPE  html>
<html>
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
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-11">
            &nbsp;
          </div>
          <div class="col-md-1">
            <a href="users_create.php" class="btn btn-primary">Add</a>
          </div>
        </div>
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Username</th>
                      <th>Name</th>
                      <th>Role</th>
                      <th>Phone</th>
                      <th style="display: none;">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    $users = $conn->query("SELECT * FROM users INNER JOIN security_groups ON users.user_group=security_groups.id ") or die($conn->error);
                      while ($row=$users->fetch_assoc()) :
                     ?>
                     <tr>
                       <td><?php echo $row['username']; ?></td>
                       <td><?php echo $row['name']; ?></td>
                       <td><?php echo $row['group_name']; ?></td>
                       <td><?php echo $row['mobile']; ?></td>
                      <td style="display:none;">
                        <a href="users.php?user_delete=<?php echo $row['id']; ?>" class="btn btn-xs btn-danger delete_user_button"><i class="fas fa-trash-alt"></i> Delete</button>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th style="display: none;">Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
          </div>
        </div>
          </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

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

<?php
include __DIR__.'/../partials/scripts.php';
?>

<!-- DataTables -->
<script src="../../plugins/datatables/jquery.dataTables.js"></script>
<script src="../../plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
<!-- Page specific script -->
<script type="text/javascript">
$(function () {
  $("#example1").DataTable();
  $('#example2').DataTable({
    "paging": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "info": true,
    "autoWidth": false,
  });
});
</script>
  </body>
  </html>
