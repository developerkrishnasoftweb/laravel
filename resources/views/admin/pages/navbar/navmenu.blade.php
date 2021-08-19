@extends('admin.layouts.default')
@section('body')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Nav Menu</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Nav Menu</li>
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
            <div class="card card-primary" id="navbar-form-container" style="display:none">
              <div class="card-header">
                <h4 class="card-title" id="navbar-form-title">Add Nav Menu</h4>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="navbar-form" enctype="multipart/form-data">
                @csrf
                <input type="text" name="id" id="id" value="" hidden>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nav_title">Nav Title</label>
                        <input type="text" name="nav_title" class="form-control" id="nav_title" placeholder="Enter nav title">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nav_url">Nav URL</label>
                        <input type="text" name="nav_url" class="form-control" id="nav_url" placeholder="Enter nav url">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="nav_icon">Nav Icon</label>
                        <input type="text" name="nav_icon" class="form-control" id="nav_icon" placeholder="Enter nav icon class">
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
                        <label for="nav_type">Menu Type</label>
                        <select name="nav_type" class="form-control" id="nav_type">
                          <option value="menu">Menu</option>
                          <option value="group">Group</option>
                        </select>
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
                  <button type="button" class="btn btn-danger m-1" id="close-navbar-btn">Cancel</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

            <div class="card" id="navbar-data-container">
              <div class="card-header">
                <h3 class="card-title">Manage Nav Menu</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-md-6">
                    <button class="btn btn-primary" id="add-navbar-btn"><i class="fa fa-plus"></i> Add Nav Menu</button>
                    <button class="btn btn-danger" id="delete-selected-btn" style="display: none" data-toggle="modal" data-target="#modal-delete-navbar"><i class="fa fa-trash"></i> Delete</button>
                  </div>
                  <div class="col-md-4"></div>
                  <div class="col-md-2">
                    <form method="get" action="{{ route('admin.navbar.filter') }}">
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
                        <th>Nav Menu Title</th>
                        <th>Nav Menu URL</th>
                        <th>Nav Type</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php($cnt = $navbars->firstItem())
                    @forelse($navbars as $row)
                      <tr id="row-{{ $row->id }}">
                        <td><input type="checkbox" name="select" class="select-row" data-id="{{ $row->id }}"></td>
                        <td>{{ $cnt++ }}</td>
                        <td>{{ $row->title }}</td>
                        <td>{{ $row->url }}</td>
                        <td>{{ $row->nav_type }}</td>
                        <td>{{ $row->position }}</td>
                        <td id="status-{{ $row->id }}">
                          {!! ($row->status == 'y' ? '<button class="btn badge bg-success status" data-id="'.$row->id.'" data-status="'.$row->status.'">Active</button>' : '<button class="badge bg-danger status" data-id="'.$row->id.'" data-status="'.$row->status.'">Deactive</button>') !!}
                        </td>
                        <td>
                          @if($row->nav_type == 'group')
                          <a href="{{ route('admin.navbar.show', [$row->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-folder"></i> View</a>
                          @endif
                          <button class="btn btn-info btn-sm edit-navbar-btn" data-id="{{ $row->id }}"><i class="fas fa-pencil-alt"></i> Edit</button>
                          <button class="btn btn-danger btn-sm delete-navbar-btn" data-toggle="modal" data-target="#modal-delete-navbar" data-id="{{ $row->id }}"><i class="fas fa-trash"></i> Delete</button>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" style="text-align:center">No data available in table</td>
                      </tr>
                    @endforelse
                    </tbody>
                  </table>
                </div>
                <div style="padding: 10px 0">
                  {{ $navbars->onEachSide(2)->links() }}
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
      <div class="modal fade" id="modal-delete-navbar">
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
              <button type="button" class="btn btn-danger m-1" id="delete-navbar-cnf">Delete</button>
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
  $("#navbar-data-container").on("click", ".select-row", function() {
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
  $("#navbar-form").validate({
    rules: {
      nav_title: {
        required: true
      },
      nav_url: {
        required: true
      },
      nav_icon: {
        required: true
      },
      status: {
        required: "Please select status"
      }
    },
    messages: {
      nav_title: {
        required: "Please enter a nav title"
      },
      nav_url: {
        required: "Please enter a nav url"
      },
      nav_icon: {
        required: "Please enter a nav icon class"
      },
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

  $("#navbar-form").submit(function() {
    if($("#navbar-form").valid()) {
      // Show preloader
      $.LoadingOverlay("show");
    }
  });

  $("#add-navbar-btn").click(function() {
    $("#navbar-form-title").html("Add Nav Menu");
    $("#navbar-form").attr("action", "{{ route('admin.navbar.store') }}");
    $("#navbar-form").attr("method", "post");
    $("#navbar-form-container").fadeIn();
    $("#navbar-data-container").hide();
  });

  $("#close-navbar-btn").click(function() {
    $("#navbar-form-container").hide();
    $("#navbar-data-container").fadeIn();
    $("#navbar-form").trigger("reset");
    // Remove error class
    $(".form-group").children(".error").remove();
    $(".form-control").removeClass("is-invalid");
  });

  $(".delete-navbar-btn").click(function() {
    $("#delete-navbar-cnf").data("id", $(this).data("id"));
  });

  $("#navbar-data-container").on("click", ".status", function() {
    var id = $(this).data("id");
    var status = $(this).data("status") == "y" ? "n" : "y";
    // Show preloader
    $.LoadingOverlay("show");
    $.ajax({
      url: "{{ route('admin.navbar.update.status') }}",
      method: "post",
      data: {id: id, status: status, _token: "{{ csrf_token() }}"},
      success: function(res) {
        // Hide preloader
        $.LoadingOverlay("hide");
        if(res.status == true) {
          $("#status-"+id).html('<button class="badge bg-'+(status == 'y' ? 'success' : 'danger')+' status" data-id="'+id+'" data-status="'+status+'">'+(status == 'y' ? 'Active' : 'Deactive')+'</button>');
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

  $("#navbar-data-container").on("click", ".edit-navbar-btn", function() {
    var id = $(this).data("id");
    // Show preloader
    $.LoadingOverlay("show");
    $.ajax({
      url: "{{ route('admin.navbar.get') }}",
      method: "post",
      data: {id: id, _token: "{{ csrf_token() }}"},
      success: async function(res) {
        // Hide preloader
        $.LoadingOverlay("hide");
        $("#navbar-form-title").html("Update Nav Menu");
        $("#navbar-form").attr("action", "{{ route('admin.navbar.update') }}");
        $("#navbar-form").attr("method", "post");
        $("#id").val(res.data[0].id);
        $("#nav_title").val(res.data[0].title);
        $("#nav_url").val(res.data[0].url);
        $("#nav_icon").val(res.data[0].icon);
        $("#position").val(res.data[0].position);
        $("#nav_type").val(res.data[0].nav_type);
        $("#status").val(res.data[0].status);
        $("#navbar-form-container").fadeIn();
        $("#navbar-data-container").hide();
      },
      error: function(error) {
        // Hide preloader
        $.LoadingOverlay("hide");
        toastr.error('Something went wrong please reload page and try again');
      }
    });
  });

  $("#delete-navbar-cnf").click(function() {
    $("#modal-delete-navbar").modal("toggle");
    if($(this).data("id") && selectedID.includes($(this).data("id")) == false) {
      selectedID.push($(this).data("id"));
    }
    // Show preloader
    $.LoadingOverlay("show");
    let form = $("<form/>", { action: "{{ route('admin.navbar.delete') }}", method: "post", enctype: "multipart/form-data" });
    selectedID.forEach(function(id) {
      form.append($("<input>", { type: "text", name: "id[]", value: id }));
    });
    form.append($("<input>", { type: "text", name: "_token", value: "{{ csrf_token() }}" }));
    form.appendTo('body').submit();
  });
});
</script>
@endpush