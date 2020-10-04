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
<script>
    $(function(){
        var current = location.pathname.replace(/\/[A-Z1-9-+]+\/[A-Z1-9-+]+\//i, '../');
        console.log(current)
        $('.nav li a').each(function(){
            var $this = $(this);
            // if the current path is like this link, make it active
            if($this.attr('href').indexOf(current) !== -1){
                $this.addClass('active');
                $this.parents('.nav-treeview').prev().addClass('active').parent().addClass('menu-open');
            }
        })
    })
    $(".alert:not(.dont-close)").fadeTo(8000, 500).slideUp(500, function(){
        $(".alert:not(.dont-close)").slideUp(500);
    });
</script>