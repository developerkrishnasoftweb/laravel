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
                  @if($user->profile_image)
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset($user->profile_image) }}"
                       alt="User profile picture">
                  @else
                  <img class="profile-user-img img-fluid img-circle"
                       src="{{ asset('assets/img/user2-160x160.jpg') }}"
                       alt="User profile picture">
                  @endif
                </div>

                <h3 class="profile-username text-center">{{ $user->name }}</h3>

                <p class="text-muted text-center">{{ $user->email }}</p>

                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b>Full Name</b> <a class="float-right">{{ $user->name }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                  </li>
                  <li class="list-group-item">
                    <b>Role</b>
                    <a class="float-right">
                      @foreach($user->roles as $role)
                        {!! '<button class="btn badge bg-primary">'.Str::ucfirst($role->role).'</button>' !!}
                      @endforeach
                    </a>
                  </li>
                </ul>
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
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">User Activity</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="activity">
                    <div class="row">
                      <div class="col-12">
                        <h4>Recent Activity</h4>
                        <div class="post">
                          <div class="user-block">
                            @if($user->profile_image)
                              <img class="img-circle img-bordered-sm" src="{{ asset($user->profile_image) }}" alt="user image">
                            @else
                              <img class="img-circle img-bordered-sm" src="{{ asset('assets/img/user2-160x160.jpg') }}" alt="user image">
                            @endif
                            <span class="username">
                              <a href="#">{{ $user->name }}</a>
                            </span>
                            <span class="description">Shared publicly - 7:45 PM today</span>
                          </div>
                          <!-- /.user-block -->
                          <p>
                            Lorem ipsum represents a long-held tradition for designers,
                            typographers and the like. Some people hate it and argue for
                            its demise, but others ignore.
                          </p>
                          <!-- <p>
                            <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                          </p> -->
                        </div>

                        <div class="post">
                          <div class="user-block">
                            @if($user->profile_image)
                              <img class="img-circle img-bordered-sm" src="{{ asset($user->profile_image) }}" alt="user image">
                            @else
                              <img class="img-circle img-bordered-sm" src="{{ asset('assets/img/user2-160x160.jpg') }}" alt="user image">
                            @endif
                            <span class="username">
                              <a href="#">{{ $user->name }}</a>
                            </span>
                            <span class="description">Shared publicly - 7:45 PM today</span>
                          </div>
                          <!-- /.user-block -->
                          <p>
                            Lorem ipsum represents a long-held tradition for designers,
                            typographers and the like. Some people hate it and argue for
                            its demise, but others ignore.
                          </p>
                          <!-- <p>
                            <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 1 v2</a>
                          </p> -->
                        </div>
                      </div>
                    </div>
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
</script>
@endpush