@extends('admin.layouts.default')
@section('body')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
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
            <div class="card card-primary" id="user-form-container" style="display:none">
              <div class="card-header">
                <h4 class="card-title" id="user-form-title">Add User</h4>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="user-form" enctype="multipart/form-data">
                @csrf
                <input type="text" name="id" id="id" value="" hidden>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" class="form-control" id="full_name" placeholder="Enter user full name">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Enter user email">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="roles">User Role</label>
                        <select name="roles[]" class="form-control" id="roles" multiple>
                          @foreach($roles as $row)
                            <option value="{{ $row->id }}">{{ $row->role }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter user password">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="profile_image">User Image</label>
                        <img src="" id="profile-image-preview" style="display:none; height: 50px;"/>
                        <input type="file" class="form-control" name="profile_image" id="profile_image" onchange="validateImageFile(this)" accept="image/*">
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
                  <button type="button" class="btn btn-danger m-1" id="close-user-btn">Cancel</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

            <div class="card" id="user-data-container">
              <div class="card-header">
                <h3 class="card-title">Manage Users</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-md-6">
                    <button class="btn btn-primary" id="add-user-btn"><i class="fa fa-plus"></i> Add User</button>
                    <button class="btn btn-danger" id="delete-selected-btn" style="display: none" data-toggle="modal" data-target="#modal-delete-user"><i class="fa fa-trash"></i> Delete</button>
                  </div>
                  <div class="col-md-4"></div>
                  <div class="col-md-2">
                    <form method="get" action="{{ route('admin.user.filter') }}">
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
                        <th>Profile</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php($cnt = $users->firstItem())
                    @forelse($users as $row)
                      <tr id="row-{{ $row->id }}">
                        <td><input type="checkbox" name="select" class="select-row" data-id="{{ $row->id }}"></td>
                        <td>{{ $cnt++ }}</td>
                        <td>{!! !empty($row->profile_image) ? '<a href="'.asset($row->profile_image).'" target="_blank">View</a>' : 'No Image' !!}</td>
                        <td>{{ $row->name }}</td>
                        <td>{{ $row->email }}</td>
                        <td>
                          @foreach($row->roles as $role)
                            {!! '<button class="btn badge bg-primary">'.Str::ucfirst($role->role).'</button>' !!}
                          @endforeach
                        </td>
                        <td id="status-{{ $row->id }}">
                          {!! ($row->status == 'y' ? '<button class="btn badge bg-success status" data-id="'.$row->id.'" data-status="'.$row->status.'">Active</button>' : '<button class="btn badge bg-danger status" data-id="'.$row->id.'" data-status="'.$row->status.'">Deactive</button>') !!}
                        </td>
                        <td>
                          <a href="{{ route('admin.user.show', [$row->id]) }}" class="btn btn-primary btn-sm"><i class="fas fa-folder"></i> View</a>
                          <button class="btn btn-info btn-sm edit-user-btn" data-id="{{ $row->id }}"><i class="fas fa-pencil-alt"></i> Edit</button>
                          <button class="btn btn-danger btn-sm delete-user-btn" data-toggle="modal" data-target="#modal-delete-user" data-id="{{ $row->id }}"><i class="fas fa-trash"></i> Delete</button>
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
                  {{ $users->onEachSide(2)->links() }}
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
      <div class="modal fade" id="modal-delete-user">
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
              <button type="button" class="btn btn-danger m-1" id="delete-user-cnf">Delete</button>
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

  $("#roles").select2({
    placeholder: "Select user roles",
    allowClear: true
  });

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
  $("#user-data-container").on("click", ".select-row", function() {
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
  $("#user-form").validate({
    ignore: ":hidden",
    rules: {
      full_name: {
        required: true
      },
      email: {
        required: true,
        unique: true
      },
      password: {
        required: true,
        minlength: 6
      },
      "roles[]": {
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

  $.validator.addMethod("unique",
    function(value, element) {
      var result = false;
      $.ajax({
        url: "{{ route('admin.user.get') }}",
        method: "POST",
        async: false,
        data: {email: value, _token: "{{ csrf_token() }}"},
        success: function(res) {
          if(res.status == true) {
            result = res.data[0].id == $("#id").val() ? true : false;
          } else {
            result = true;
          }
        }
      });
      return result;
    },
    "Already exists."
  );

  $("#user-form").submit(function() {
    if($("#user-form").valid()) {
      //Show preloader
      $.LoadingOverlay("show");
    } else {
      console.log('invalid');
    }
  });

  $("#add-user-btn").click(function() {
    // Show form
    $("#user-form-title").html("Add Users");
    $("#user-form").attr("action", "{{ route('admin.user.store') }}");
    $("#user-form").attr("method", "post");
    $("#user-form-container").fadeIn();
    $("#user-data-container").hide();
  });

  $("#close-user-btn").click(function() {
    // Hide form
    $("#user-form-container").hide();
    // Show table container
    $("#user-data-container").fadeIn();
    // Reset update form
    $("#user-form").trigger("reset");
    $('#roles').val([]).trigger('change');
    $("#password").parent().parent().show();
    $('#profile-image-preview').hide();
    $('#profile-image-preview').attr("src", "");
    $('#profile_image').removeAttr("hidden");
    // Remove error class
    $(".form-group").children(".error").remove();
    $(".form-control").removeClass("is-invalid");
  });

  $(".delete-user-btn").click(function() {
    $("#delete-user-cnf").data("id", $(this).data("id"));
  });

  $("#user-data-container").on("click", ".status", function() {
    var id = $(this).data("id");
    var status = $(this).data("status") == "y" ? "n" : "y";
    // Show preloader
    $.LoadingOverlay("show");
    $.ajax({
      url: "{{ route('admin.user.update.status') }}",
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

  $("#user-data-container").on("click", ".edit-user-btn", function() {
    var id = $(this).data("id");
    // Show preloader
    $.LoadingOverlay("show");
    $.ajax({
      url: "{{ route('admin.user.get') }}",
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
        $("#user-form-title").html("Update Users");
        $("#user-form").attr("action", "{{ route('admin.user.update') }}");
        $("#user-form").attr("method", "post");
        $("#id").val(res.data[0].id);
        $("#full_name").val(res.data[0].name);
        $("#email").val(res.data[0].email);
        if(res.data[0].profile_image) {
          $('#profile-image-preview').css("display", "block");
          $('#profile-image-preview').attr("src", "{{ url('') }}/"+res.data[0].profile_image);
          $("#profile_image").attr("hidden", "true");
        }
        if(res.data[0].roles.length > 0) {
          $("#roles").val(res.data[0].roles.map(function(role) {
            return role.id;
          }));
          $("#roles").trigger('change');
        }
        $("#password").parent().parent().hide();
        $("#status").val(res.data[0].status);
        $("#user-form-container").fadeIn();
        $("#user-data-container").hide();
      },
      error: function(error) {
        // Hide preloader
        $.LoadingOverlay("hide");
        toastr.error('Something went wrong please reload page and try again');
      }
    });
  });

  $("#delete-user-cnf").click(function() {
    $("#modal-delete-user").modal("toggle");
    if($(this).data("id") && selectedID.includes($(this).data("id")) == false) {
      selectedID.push($(this).data("id"));
    }
    // Show preloader
    $.LoadingOverlay("show");
    let form = $("<form/>", { action: "{{ route('admin.user.delete') }}", method: "post", enctype: "multipart/form-data" });
    selectedID.forEach(function(id) {
      form.append($("<input>", { type: "text", name: "id[]", value: id }));
    });
    form.append($("<input>", { type: "text", name: "_token", value: "{{ csrf_token() }}" }));
    form.appendTo('body').submit();
  });

  $("#profile_image").change(function() {
    $('#profile-image-preview').css("display", "block");
    $('#profile-image-preview').attr("src", window.URL.createObjectURL(this.files[0]));
    $("#profile_image").attr("hidden", "true");
  });

  $("#profile-image-preview").click(function() {
    $("#profile_image").click();
  });
});

// Check image file is valid or not
function validateImageFile(input) {
  var URL = window.URL || window.webkitURL;
  var file = input.files[0];
  if(file) {
    var image = new Image();
    image.src = URL.createObjectURL(file);
    image.onload = function() {
      if(this.width) {
        // alert('valid file');
      } else {
        alert('Image file is not valid');
        input.value = "";
      }
    };
    image.onerror = function(ev) {
      alert('Image file is not valid');
      input.value = "";
    }
  }
}
</script>
@endpush