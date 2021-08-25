@extends('admin.layouts.default')
@section('body')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Banners</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Banners</li>
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
            <div class="card card-primary" id="banner-form-container" style="display:none">
              <div class="card-header">
                <h4 class="card-title" id="banner-form-title">Add Banner</h4>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form" id="banner-form" enctype="multipart/form-data">
                @csrf
                <input type="text" name="id" id="id" value="" hidden>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="banner_title">Banner Title</label>
                        <input type="text" name="banner_title" class="form-control" id="banner_title" placeholder="Enter banner title">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="banner_image">Banner Image</label>
                        <img src="" id="banner-image-preview" style="display:none; height: 50px;"/>
                        <input type="file" class="form-control" name="banner_image" id="banner_image" onchange="validateImageFile(this)" accept="image/*">
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
                  <button type="button" class="btn btn-danger m-1" id="close-banner-btn">Cancel</button>
                </div>
              </form>
            </div>
            <!-- /.card -->

            <div class="card" id="banner-data-container">
              <div class="card-header">
                <h3 class="card-title">Manage Banners</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6 col-md-6">
                    <button class="btn btn-primary" id="add-banner-btn"><i class="fa fa-plus"></i> Add Banner</button>
                    <button class="btn btn-danger" id="delete-selected-btn" style="display: none" data-toggle="modal" data-target="#modal-delete-banner"><i class="fa fa-trash"></i> Delete</button>
                  </div>
                  <div class="col-md-4"></div>
                  <div class="col-md-2">
                    <form method="get" action="{{ route('admin.banner.filter') }}">
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
                        <th>Banner Title</th>
                        <th>Banner Image</th>
                        <th>Position</th>
                        <th>Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    @php($cnt = $banners->firstItem())
                    @forelse($banners as $row)
                      <tr id="row-{{ $row->id }}">
                        <td><input type="checkbox" name="select" class="select-row" data-id="{{ $row->id }}"></td>
                        <td>{{ $cnt++ }}</td>
                        <td>{{ $row->title }}</td>
                        <td>{!! !empty($row->image_path) ? '<a href="'.asset($row->image_path).'" target="_blank">View</a>' : 'No Image' !!}</td>
                        <td>{{ $row->position }}</td>
                        <td id="status-{{ $row->id }}">
                          {!! ($row->status == 'y' ? '<button class="btn badge bg-success status" data-id="'.$row->id.'" data-status="'.$row->status.'">Active</button>' : '<button class="btn badge bg-danger status" data-id="'.$row->id.'" data-status="'.$row->status.'">Deactive</button>') !!}
                        </td>
                        <td>
                          <!-- <button class="btn btn-primary btn-sm"><i class="fas fa-folder"></i> View</button> -->
                          <button class="btn btn-info btn-sm edit-banner-btn" data-id="{{ $row->id }}"><i class="fas fa-pencil-alt"></i> Edit</button>
                          <button class="btn btn-danger btn-sm delete-banner-btn" data-toggle="modal" data-target="#modal-delete-banner" data-id="{{ $row->id }}"><i class="fas fa-trash"></i> Delete</button>
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
                  {{ $banners->onEachSide(2)->links() }}
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
      <div class="modal fade" id="modal-delete-banner">
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
              <button type="button" class="btn btn-danger m-1" id="delete-banner-cnf">Delete</button>
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
  $("#banner-data-container").on("click", ".select-row", function() {
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
  $("#banner-form").validate({
    rules: {
      banner_title: {
        required: true
      },
      banner_image: {
        required: true
      },
      status: {
        required: true
      }
    },
    messages: {
      banner_title: {
        required: "Please enter a banner title"
      },
      banner_image: {
        required: "Please upload a banner image"
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

  $("#banner-form").submit(function() {
    if($("#banner-form").valid()) {
      //Show preloader
      $.LoadingOverlay("show");
    }
  });

  $("#add-banner-btn").click(function() {
    // Show form
    $("#banner-form-title").html("Add Banner");
    $("#banner-form").attr("action", "{{ route('admin.banner.store') }}");
    $("#banner-form").attr("method", "post");
    $("#banner-form-container").fadeIn();
    $("#banner-data-container").hide();
  });

  $("#close-banner-btn").click(function() {
    // Hide form
    $("#banner-form-container").hide();
    // Show table container
    $("#banner-data-container").fadeIn();
    // Reset form
    $("#banner-form").trigger("reset");
    $('#banner-image-preview').hide();
    $('#banner-image-preview').attr("src", "");
    $('#banner_image').removeAttr("hidden");
    // Remove error class
    $(".form-group").children(".error").remove();
    $(".form-control").removeClass("is-invalid");
  });

  $(".delete-banner-btn").click(function() {
    $("#delete-banner-cnf").data("id", $(this).data("id"));
  });

  $("#banner-data-container").on("click", ".status", function() {
    var id = $(this).data("id");
    var status = $(this).data("status") == "y" ? "n" : "y";
    // Show preloader
    $.LoadingOverlay("show");
    $.ajax({
      url: "{{ route('admin.banner.update.status') }}",
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

  $("#banner-data-container").on("click", ".edit-banner-btn", function() {
    var id = $(this).data("id");
    // Show preloader
    $.LoadingOverlay("show");
    $.ajax({
      url: "{{ route('admin.banner.get') }}",
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
        $("#banner-form-title").html("Update Banner");
        $("#banner-form").attr("action", "{{ route('admin.banner.update') }}");
        $("#banner-form").attr("method", "post");
        $("#id").val(res.data[0].id);
        $("#banner_title").val(res.data[0].title);
        if(res.data[0].image_path) {
          $('#banner-image-preview').css("display", "block");
          $('#banner-image-preview').attr("src", "{{ url('') }}/"+res.data[0].image_path);
          $("#banner_image").attr("hidden", "true");
        }
        $("#position").val(res.data[0].position);
        $("#status").val(res.data[0].status);
        $("#banner-form-container").fadeIn();
        $("#banner-data-container").hide();
      },
      error: function(error) {
        // Hide preloader
        $.LoadingOverlay("hide");
        toastr.error('Something went wrong please reload page and try again');
      }
    });
  });

  $("#delete-banner-cnf").click(function() {
    $("#modal-delete-banner").modal("toggle");
    if($(this).data("id") && selectedID.includes($(this).data("id")) == false) {
      selectedID.push($(this).data("id"));
    }
    // Show preloader
    $.LoadingOverlay("show");
    let form = $("<form/>", { action: "{{ route('admin.banner.delete') }}", method: "post", enctype: "multipart/form-data" });
    selectedID.forEach(function(id) {
      form.append($("<input>", { type: "text", name: "id[]", value: id }));
    });
    form.append($("<input>", { type: "text", name: "_token", value: "{{ csrf_token() }}" }));
    form.appendTo('body').submit();
  });

  $("#banner_image").change(function() {
    $('#banner-image-preview').css("display", "block");
    $('#banner-image-preview').attr("src", window.URL.createObjectURL(this.files[0]));
    $("#banner_image").attr("hidden", "true");
  });

  $("#banner-image-preview").click(function() {
    $("#banner_image").click();
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