@extends('adminmanagement::layouts.master')

@section('content')
<div class="login-box">
    <div class="login-logo">
      <a href="#">{{ config('app.name') }}</a>
    </div>
<div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>

      <form action="{{ route('reset.password.post') }}" method="POST">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="input-group mb-3">
          <input type="text" name="email" value="{{ old('email') }}"
           required autofocus class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
            @if ($errors->has('email'))
            <span class="text-danger">{{ $errors->first('email') }}</span>
          @endif
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" required class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
            @if ($errors->has('password'))
            <span class="text-danger">{{ $errors->first('password') }}</span>
          @endif
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password_confirmation" required class="form-control"
            placeholder="Retype password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        @if ($errors->has('password'))
        <span class="text-danger">{{ $errors->first('password') }}</span>
      @endif
        <div class="row">
          <div class="col-4">

          </div>
          <!-- /.col -->
          <div class="col-8">
            <button type="submit" class="btn btn-primary btn-block">Reset Password
            </button>
          </div>
          <!-- /.col -->
        </div>
      </form>


      <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
@endsection
