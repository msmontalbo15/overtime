<?php 
  $this->load->view('components/header');
  $this->load->view('components/navbar');
  $this->load->view('components/sidebar');
 ?>

  <?php  $this->load->view('components/table_style'); ?>
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/toastr/toastr.min.css'); ?>">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <h1 class="m-0 text-dark float-left">User</h1> 
            <button class="btn btn-success btn-sm float-right" onclick="loadForm('<?php echo base_url("user/add"); ?>')"><i class="fas fa-plus"></i> User</button>
            <a href="<?php echo base_url('user/export'); ?>" class="btn btn-secondary btn-sm float-right mr-1">Export</a> 
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-hover" id="tbl_data">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th style="width: 25%">Position</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 5%">Action</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- /.col-md-9 -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<div class="modal" role="dialog" tabindex="2" id="form_modal">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="load_form"></div>  
      </div>
    </div>
  </div>
</div>  


<?php $this->load->view('components/footer'); ?>
<?php  $this->load->view('components/table_script'); ?>
<script src="<?php echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>

<script type="text/javascript">
$( document ).ready(function() {
  $(".loading-overlay").css("display", "none");
});  
toastr.options = {
  "newestOnTop": true,
  "showDuration": "300",
  "hideDuration": "1000",
  "showEasing": "swing",
  "hideEasing": "swing",
  "showMethod": "slideDown",
  "hideMethod": "slideUp"
}  
/*DATA TABLE*/
url     = "<?php echo base_url('user/data'); ?>"
config  = {
  "order": [[ 1, "asc" ]],
  "pageLength": 25,
  "ajax": {
          "url": url,
  },
  "columns": [
      { "data": "id" },
      { "data": "name" },
      { "data": "title" },
      { "data": "status" },
      { "data": "button" },
    ],
  "columnDefs": [
      {
          "targets": [ 0 ],
          "visible": false,
          "searchable": false
      },
  ],
  "responsive": true,
  "autoWidth": false,   
}

table =  $("#tbl_data").DataTable(config); 

function loadForm(url){
  $('#load_form').load(url);
  $('#form_modal').modal('show');
} 


</script>

</body>
</html>