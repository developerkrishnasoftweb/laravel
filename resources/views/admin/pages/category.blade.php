@extends('admin.layouts.default')
@section('body')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-12">
            <!-- jquery validation -->
            <div class="card card-primary" id="category-form-container" style="display:none">
              <div class="card-header">
                <h4 class="card-title" id="category-form-title">Add Category</h4>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="category-form" enctype="multipart/form-data">
                @csrf
                <input type="text" name="id" id="id" value="" hidden>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" name="category_name" class="form-control" id="category_name" placeholder="Enter category name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="position">Position</label>
                        <input type="number" name="position" class="form-control" id="position" placeholder="Enter position">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                          <option value="y">Active</option>
                          <option value="n">Deactive</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-primary m-1">Submit</button>
                  <button type="button" class="btn btn-danger m-1" id="close-category-btn">Cancel</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

            <div class="card" id="category-data-container">
              <div class="card-header">
                <h3 class="card-title">Manage Categories</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-md-6">
                    <button class="btn btn-primary" id="add-category-btn"><i class="fa fa-plus"></i> Add Category</button>
                    <button class="btn btn-danger" id="delete-selected-btn" style="display: none" data-toggle="modal" data-target="#modal-delete-category"><i class="fa fa-trash"></i> Delete</button>
                  </div>
                  <div class="col-md-4"></div>
                  <div class="col-md-2">
                    <form method="get" action="{{ route('admin.category.filter') }}">
                      <input type="text" name="q" class="form-control" id="product_name" placeholder="Search..." value="{{ request()->q }}">
                    </form>
                  </div>
                </div>
                <br>
                <div class="table-responsive">
                  <table id="data-table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th><input type="checkbox" name="select_all" class="select-all"></th>
                        <th>Sr No</th>
                        <th>Category Name</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php($cnt = $categories->firstItem())
                    @forelse($categories as $row)
                      <tr id="row-{{ $row->id }}">
                        <td><input type="checkbox" name="select" class="select-row" data-id="{{ $row->id }}"></td>
                        <td>{{ $cnt++ }}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->position }}</td>
                        <td id="status-{{ $row->id }}">
                          {!! ($row->status == 'y' ? '<button class="btn badge bg-success status" data-id="'.$row->id.'" data-status="'.$row->status.'">Active</button>' : '<button class="btn badge bg-danger status" data-id="'.$row->id.'" data-status="'.$row->status.'">Deactive</button>') !!}
                        </td>
                        <td>
                          <!-- <button class="btn btn-primary btn-sm"><i class="fas fa-folder"></i> View</button> -->
                          <button class="btn btn-info btn-sm edit-category-btn" data-id="{{ $row->id }}"><i class="fas fa-pencil-alt"></i> Edit</button>
                          <button class="btn btn-danger btn-sm delete-category-btn" data-toggle="modal" data-target="#modal-delete-category" data-id="{{ $row->id }}"><i class="fas fa-trash"></i> Delete</button>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="8" style="text-align:center">No data available in table</td>
                      </tr>
                    @endforelse
                    </tbody>
                  </table>
                </div>
                <div style="padding: 10px 0">
                  {{ $categories->onEachSide(2)->links() }}
                </div>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>
        <!-- /.row -->

      </div>
      <!-- /.container-fluid -->

      <!-- modal -->
      <div class="modal fade" id="modal-delete-category">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-name">Delete</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <p>Do you want to delete?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger m-1" id="delete-category-cnf">Delete</button>
              <button type="button" class="btn btn-primary m-1" data-dismiss="modal">Cancel</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <!-- /.modal -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection
@push('scripts')
<script type="text/javascript">
// Show preloader
$.LoadingOverlay("show");
$(document).ready(function() {
  var selectedID = [];
  // Hide preloader
  $.LoadingOverlay("hide");

  @if(Session::has('success'))
    toastr.success('{{ Session::get('success') }}');
  @elseif(Session::has('error'))
    toastr.error('{{ Session::get('error') }}');
  @endif

  // Select all data
  $(".select-all").click(function() {
    if($(this).prop("checked")) {
      $(".select-row").each(function() {
        if($(this).data("id") && selectedID.includes($(this).data("id")) == false) {
          selectedID.push($(this).data("id"));
          $(this).prop("checked", true);
        }
      });
      // Show multiselecet options
      if(selectedID.length > 0) {
        $("#delete-selected-btn").show();
        $(".select-all").prop("checked", true);
      } else {
        $("#delete-selected-btn").hide();
        $(".select-all").prop("checked", false);
      }
    } else {
      selectedID = [];
      $(".select-row").prop("checked", false);
      // Hide multiselecet options
      $("#delete-selected-btn").hide();
    }
  });

  // Select data
  $("#category-data-container").on("click", ".select-row", function() {
    if($(this).prop("checked")) {
      if($(this).data("id") && selectedID.includes($(this).data("id")) == false) {
        selectedID.push($(this).data("id"));
      }
      // Show multiselecet options
      $("#delete-selected-btn").show();
    } else {
      selectedID.pop($(this).data("id"));
      // Hide multiselecet options if any data is not selecetd
      if(selectedID.length == 0) {
        $("#delete-selected-btn").hide();
        $(".select-all").prop("checked", false);
      }
    }
  });

  // Validate form
  $("#category-form").validate({
    rules: {
      category_name: {
        required: true
      },
      status: {
        required: true
      }
    },
    messages: {
      status: {
        required: "Please select status"
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });

  $("#category-form").submit(function() {
    if($("#category-form").valid()) {
      //Show preloader
      $.LoadingOverlay("show");
    }
  });

  $("#add-category-btn").click(function() {
    // Show form
    $("#category-form-title").html("Add Category");
    $("#category-form").attr("action", "{{ route('admin.category.store') }}");
    $("#category-form").attr("method", "post");
    $("#category-form-container").fadeIn();
    $("#category-data-container").hide();
  });

  $("#close-category-btn").click(function() {
    // Hide form
    $("#category-form-container").hide();
    // Show table container
    $("#category-data-container").fadeIn();
    // Reset form
    $("#category-form").trigger("reset");
    // Remove error class
    $(".form-group").children(".error").remove();
    $(".form-control").removeClass("is-invalid");
  });

  $(".delete-category-btn").click(function() {
    $("#delete-category-cnf").data("id", $(this).data("id"));
  });

  $("#category-data-container").on("click", ".status", function() {
    var id = $(this).data("id");
    var status = $(this).data("status") == "y" ? "n" : "y";
    // Show preloader
    $.LoadingOverlay("show");
    $.ajax({
      url: "{{ route('admin.category.update.status') }}",
      method: "post",
      data: {id: id, status: status, _token: "{{ csrf_token() }}"},
      success: function(res) {
        // Hide preloader
        $.LoadingOverlay("hide");
        if(res.status == true) {
          $("#status-"+id).html('<button class="btn badge bg-'+(status == 'y' ? 'success' : 'danger')+' status" data-id="'+id+'" data-status="'+status+'">'+(status == 'y' ? 'Active' : 'Deactive')+'</button>');
          toastr.success('Status updated successfully.');
        } else {
          toastr.error('Status not updated.');
        }
      },
      error: function(error) {
        // Hide preloader
        $.LoadingOverlay("hide");
        toastr.error('Something went wrong please reload page and try again');
      }
    });
  });

  $("#category-data-container").on("click", ".edit-category-btn", function() {
    var id = $(this).data("id");
    // Show preloader
    $.LoadingOverlay("show");
    $.ajax({
      url: "{{ route('admin.category.get') }}",
      method: "post",
      data: {id: id, _token: "{{ csrf_token() }}"},
      success: async function(res) {
        // Hide preloader
        $.LoadingOverlay("hide");
        // Check data found or not at server
        if(res.status == false) {
          toastr.error('Something went wrong please reload page and try again');
          return;
        }
        // Show update form
        $("#category-form-title").html("Update Category");
        $("#category-form").attr("action", "{{ route('admin.category.update') }}");
        $("#category-form").attr("method", "post");
        $("#id").val(res.data[0].id);
        $("#category_name").val(res.data[0].name);
        $("#position").val(res.data[0].position);
        $("#status").val(res.data[0].status);
        $("#category-form-container").fadeIn();
        $("#category-data-container").hide();
      },
      error: function(error) {
        // Hide preloader
        $.LoadingOverlay("hide");
        toastr.error('Something went wrong please reload page and try again');
      }
    });
  });

  $("#delete-category-cnf").click(function() {
    $("#modal-delete-category").modal("toggle");
    if($(this).data("id") && selectedID.includes($(this).data("id")) == false) {
      selectedID.push($(this).data("id"));
    }
    // Show preloader
    $.LoadingOverlay("show");
    let form = $("<form/>", { action: "{{ route('admin.category.delete') }}", method: "post", enctype: "multipart/form-data" });
    selectedID.forEach(function(id) {
      form.append($("<input>", { type: "text", name: "id[]", value: id }));
    });
    form.append($("<input>", { type: "text", name: "_token", value: "{{ csrf_token() }}" }));
    form.appendTo('body').submit();
  });
});
</script>
@endpush