<?php
include 'process.php';

?>
<?php
session_start();
if (!isset($_SESSION['group'])) {
  header('location: sections/utils/logout.php');
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
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Inventory</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inventory</a></li>
              <li class="breadcrumb-item active">Products</li>
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
          <div class="col-md-12">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Products</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <div class="card-body">
                  <div class="row">
                  <input type="hidden" name="id" value="<?php echo $id; ?>">
                  <div class="col-md-4 form-group">
                    <label for="exampleInputEmail1">Product Name <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter name" name="product_name" value="<?php echo $product_name; ?>">
                    <span style="color: red;"><?php echo $product_err; ?></span>
                  </div>
                  <div class="col-md-4 form-group">
                    <label>Category <span style="color: red;">*</span></label>
                    <select name="category" class="form-control" name="category">
                      <?php
                        $category = $conn->query("SELECT * FROM product_categories") or die($conn->error);
                      ?>
                        <?php while ($row = $category->fetch_assoc()):?>
                          <option class="dropdown-item" value="<?php echo $row['name']; ?>"><?php echo $row['name'];?></option>
                        <?php endwhile;?>
                    </select>
                  </div>

                  <div class="col-md-4 form-group">
                    <label for="shellcost">Cost of Liquid <span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Cost of liquid i.e. exclude empties price" name="cost" value="<?php echo $cost; ?>">
                    <span style="color: red;"><?php echo $liquid_cost_err; ?></span>
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">RRP Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="selling_cost" value="<?php echo $selling_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Stockist Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="stockist_cost" value="<?php echo $stockist_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Wholesale Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="wholesale_cost" value="<?php echo $wholesale_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Distributor Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="distributor_cost" value="<?php echo $distributor_cost; ?>">
                  </div>
                  <div class="col-md-4 form-group">
                    <label for="shellcost">Retail Price<span style="color: red;">*</span></label>
                    <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Price(Kshs) e.g. 650" name="retail_cost" value="<?php echo $retail_cost; ?>">
                  </div>
                </div>

                <!-- /.card-body -->

                <div class="card-footer">
                  <?php if ($product_update) :?>
                  <button type="submit" class="btn btn-primary" name="product_update">Update</button>
                  <?php else : ?>
                  <button type="submit" class="btn btn-primary" name="product_submit">Submit</button>
                <?php endif; ?>
                </div>
              </div>
            </form>
          </div>
        </div>
            <!-- /.card -->
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Registered Products</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Category</th>
                      <th>Cost</th>
                      <th>RRP Price</th>
                      <th>Wholesale Price</th>
                      <th>Stockist Price</th>
                      <th>Retail Price</th>
                      <th>Distributor Price</th>
                      <th colspan="2">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $number =1;
                    // $conn = mysqli_connect('localhost','root','','premierdb') or die($conn->error);
                    $res = $conn->query("SELECT * FROM products") or die($conn->error);
                    while ($row= $res->fetch_assoc()):
                    ?>
                    <tr>
                      <td><?php echo $number; ?></td>
                      <td><?php echo $row['name']; ?></td>
                      <td><?php echo $row['category']; ?></td>
                      <td><?php echo $row['price']; ?></td>
                      <td><?php echo $row['selling_cost']; ?></td>
                      <td><?php echo $row['wholesale']; ?></td>
                      <td><?php echo $row['stockist']; ?></td>
                      <td><?php echo $row['retail']; ?></td>
                      <td><?php echo $row['distributor']; ?></td>
                      <td style="width: 40px"><a href="products.php?product_edit=<?php echo $row['id'];?>"><button class="btn btn-success">Edit</button></a></td>
                      <td style="width: 40px"><a href="products.php?product_delete=<?php echo $row['id'];?>"><button class="btn btn-danger">Delete</button></a></td>

                    </tr>
                    <?php
                      $number++;
                    endwhile;
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
              <!-- /.card-body -->
            <!-- /.card -->

        <!-- /.row -->

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
<script src="../../plugins/moment/moment.min.js"></script>
<script src="../../plugins/fullcalendar/main.min.js"></script>
<script src="../../plugins/fullcalendar-daygrid/main.min.js"></script>
<script src="../../plugins/fullcalendar-timegrid/main.min.js"></script>
<script src="../../plugins/fullcalendar-interaction/main.min.js"></script>
<script src="../../plugins/fullcalendar-bootstrap/main.min.js"></script>
<!-- Page specific script -->
<script>
  $(function () {

    /* initialize the external events
     -----------------------------------------------------------------*/
    function ini_events(ele) {
      ele.each(function () {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
          title: $.trim($(this).text()) // use the element's text as the event title
        }

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject)

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex        : 1070,
          revert        : true, // will cause the event to go back to its
          revertDuration: 0  //  original position after the drag
        })

      })
    }

    ini_events($('#external-events div.external-event'))

    /* initialize the calendar
     -----------------------------------------------------------------*/
    //Date for the calendar events (dummy data)
    var date = new Date()
    var d    = date.getDate(),
        m    = date.getMonth(),
        y    = date.getFullYear()

    var Calendar = FullCalendar.Calendar;
    var Draggable = FullCalendarInteraction.Draggable;

    var containerEl = document.getElementById('external-events');
    var checkbox = document.getElementById('drop-remove');
    var calendarEl = document.getElementById('calendar');

    // initialize the external events
    // -----------------------------------------------------------------

    new Draggable(containerEl, {
      itemSelector: '.external-event',
      eventData: function(eventEl) {
        console.log(eventEl);
        return {
          title: eventEl.innerText,
          backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
          textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
        };
      }
    });

    var calendar = new Calendar(calendarEl, {
      plugins: [ 'bootstrap', 'interaction', 'dayGrid', 'timeGrid' ],
      header    : {
        left  : 'prev,next today',
        center: 'title',
        right : 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      //Random default events
      events    : [
        {
          title          : 'All Day Event',
          start          : new Date(y, m, 1),
          backgroundColor: '#f56954', //red
          borderColor    : '#f56954' //red
        },
        {
          title          : 'Long Event',
          start          : new Date(y, m, d - 5),
          end            : new Date(y, m, d - 2),
          backgroundColor: '#f39c12', //yellow
          borderColor    : '#f39c12' //yellow
        },
        {
          title          : 'Meeting',
          start          : new Date(y, m, d, 10, 30),
          allDay         : false,
          backgroundColor: '#0073b7', //Blue
          borderColor    : '#0073b7' //Blue
        },
        {
          title          : 'Lunch',
          start          : new Date(y, m, d, 12, 0),
          end            : new Date(y, m, d, 14, 0),
          allDay         : false,
          backgroundColor: '#00c0ef', //Info (aqua)
          borderColor    : '#00c0ef' //Info (aqua)
        },
        {
          title          : 'Birthday Party',
          start          : new Date(y, m, d + 1, 19, 0),
          end            : new Date(y, m, d + 1, 22, 30),
          allDay         : false,
          backgroundColor: '#00a65a', //Success (green)
          borderColor    : '#00a65a' //Success (green)
        },
        {
          title          : 'Click for Google',
          start          : new Date(y, m, 28),
          end            : new Date(y, m, 29),
          url            : 'http://google.com/',
          backgroundColor: '#3c8dbc', //Primary (light-blue)
          borderColor    : '#3c8dbc' //Primary (light-blue)
        }
      ],
      editable  : true,
      droppable : true, // this allows things to be dropped onto the calendar !!!
      drop      : function(info) {
        // is the "remove after drop" checkbox checked?
        if (checkbox.checked) {
          // if so, remove the element from the "Draggable Events" list
          info.draggedEl.parentNode.removeChild(info.draggedEl);
        }
      }
    });

    calendar.render();
    // $('#calendar').fullCalendar()

    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')
    $('#color-chooser > li > a').click(function (e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({
        'background-color': currColor,
        'border-color'    : currColor
      })
    })
    $('#add-new-event').click(function (e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color'    : currColor,
        'color'           : '#fff'
      }).addClass('external-event')
      event.html(val)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      ini_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })
  })
</script>
</body>
</html>
