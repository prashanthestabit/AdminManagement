@extends('adminmanagement::layouts.app')

@section('content')

<x-adminmanagement::page-header pageTitle="Change Password" :breadcrumbs="['Home', 'Change Password']" />

   <!-- Main content -->
   <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Change Password</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>

          <div class="card-body">
            <form method="POST" action="{{ route("profile.password.update") }}">
                @csrf
                <div class="form-group">
                    <label class="required" for="title">Old Password</label>
                    <input class="form-control" type="password" name="old_password"
                        value="{{ old('old_password') }}" required>
                    @if($errors->has('old_password'))
                        <div class="text-danger">
                            {{ $errors->first('old_password') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="required" for="title">New Password</label>
                    <input class="form-control" type="password" name="password" id="password" required>
                    @if($errors->has('password'))
                        <div class="text-danger">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="required" for="title">Repeat New Password</label>
                    <input class="form-control" type="password" name="password_confirmation" required>
                    @if($errors->has('password_confirmation'))
                    <div class="text-danger">
                        {{ $errors->first('password_confirmation') }}
                    </div>
                @endif
                </div>
                <div class="form-group">
                    <button class="btn btn-success float-right" type="submit">
                        Update Password
                    </button>
                </div>
            </form>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
  </section>

@endsection
