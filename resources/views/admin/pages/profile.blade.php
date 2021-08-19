@extends('admin.layouts.default')
@section('body')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">User Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  @if($profile->profile_image)
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset($profile->profile_image) }}"
                       alt="User profile picture">
                  @else
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('assets/img/user2-160x160.jpg') }}"
                       alt="User profile picture">
                  @endif
                </div>

                <h3 class="profile-username text-center">{{ $profile->name }}</h3>

                <p class="text-muted text-center">{{ $profile->email }}</p>

                <!-- <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Followers</b> <a class="float-right">1,322</a>
                  </li>
                  <li class="list-group-item">
                    <b>Following</b> <a class="float-right">543</a>
                  </li>
                  <li class="list-group-item">
                    <b>Friends</b> <a class="float-right">13,287</a>
                  </li>
                </ul> -->

                <a href="{{ route('admin.logout') }}" class="btn btn-primary btn-block"><b>Logout</b></a>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#profile-update" data-toggle="tab">Update Profile</a></li>
                  <li class="nav-item"><a class="nav-link" href="#chnage-password" data-toggle="tab">Chnage Password</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="profile-update">
                    <form class="form-horizontal" action="{{ route('admin.profile') }}" method="post" enctype="multipart/form-data" id="profile-form">
                      @csrf
                      <div class="form-group row">
                        <label for="profile_image" class="col-sm-2 col-form-label">Profile Image</label>
                        <div class="col-sm-10">
                          @if($profile->profile_image)
                            <img src="{{ asset($profile->profile_image) }}" id="profile-image-preview" style="display: block; height: 60px;"/>
                            <input type="file" accept="image/*" class="form-control" name="profile_image" id="profile_image" onchange="validateImageFile(this)" hidden>
                          @else
                            <img src="" id="profile-image-preview" style="display: none; height: 60px;"/>
                            <input type="file" accept="image/*" class="form-control" name="profile_image" id="profile_image" onchange="validateImageFile(this)">
                          @endif
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Name</label>
                        <div class="col-sm-10">
                          <input type="text" name="name" class="form-control" id="inputName" placeholder="Name" value="{{ $profile->name }}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" value="{{ $profile->email }}">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="chnage-password">
                    <form class="form-horizontal" role="form" id="change-password-form" method="post" action="{{ route('admin.profile.changePassword') }}" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group row">
                        <label for="old_password" class="col-sm-2 col-form-label">Old Password</label>
                        <div class="col-sm-10">
                          <input type="password" name="old_password" class="form-control" id="old_password" placeholder="Old Password">
                          @error('old_password')
                            <div class="text text-danger mt-3">
                                {{ $message }} <br>
                            </div>
                          @enderror
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="password" class="col-sm-2 col-form-label">New Password</label>
                        <div class="col-sm-10">
                          <input type="password" name="password" class="form-control" id="password" placeholder="New Password">
                          @error('password')
                            <div class="text text-danger mt-3">
                                {{ $message }} <br>
                            </div>
                          @enderror
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                          <input type="password" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm Password">
                          @error('confirm_password')
                            <div class="text text-danger mt-3">
                                {{ $message }} <br>
                            </div>
                          @enderror
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection
@push('scripts')
<script>
//Show preloader
$.LoadingOverlay("show");
$(document).ready(function() {
  //hide preloader
  $.LoadingOverlay("hide");

  @if(session()->has('success'))
    toastr.success('{{ session()->get('success') }}');
  @elseif(session()->has('error'))
    toastr.error('{{ session()->get('error') }}');
  @endif

  $("#profile-form").validate({
    rules: {
      name: {
        required: true
      },
      email: {
        required: true
      }
    },
    messages: {
      email: {
        required: "Please enter a valid email address"
      }
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid");
    }
  });

  $("#profile-form").submit(function() {
    if($("#profile-form").valid()) {
      //Show preloader
      $.LoadingOverlay("show");
    }
  });

  $("#change-password-form").validate({
    rules: {
      old_password: {
        required: true
      },
      password: {
        required: true
      },
      confirm_password: {
        required: true,
        equalTo: "#password"
      }
    },
    messages: {
      confirm_password: {
        required: "Please enter confirm password.",
        equalTo: "Password not matched."
      }
    },
    errorElement: "span",
    errorPlacement: function (error, element) {
      error.addClass("invalid-feedback");
      element.closest(".form-group").append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass("is-invalid");
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass("is-invalid");
    }
  });

  $("#change-password-form").submit(function() {
    if($("#change-password-form").valid()) {
      //Show preloader
      $.LoadingOverlay("show");
    }
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

//Check image file is valid or not
function validateImageFile(input) {
  var URL = window.URL || window.webkitURL;
  var file = input.files[0];
  if(file) {
    var image = new Image();
    image.src = URL.createObjectURL(file);
    image.onload = function() {
      if(this.width) {
        //alert('valid file');
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