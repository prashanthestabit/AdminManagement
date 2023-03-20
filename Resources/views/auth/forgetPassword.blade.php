@extends('adminmanagement::layouts.master')

@section('content')
<div class="login-box">
    <div class="login-logo">
      <a href="#">{{ config('app.name') }}</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

        <form action="{{ route('forget.password.post') }}" method="POST">
            @csrf
          <div class="input-group mb-3">
            <input type="test" name="email" required autofocus class="form-control" placeholder="Email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope "></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            @if ($errors->has('email'))
            <span class="text-danger">{{ $errors->first('email') }}</span>
            @endif
          </div>

          <div class="row">
            <div class="col-4">

            </div>
            <!-- /.col -->
            <div class="col-8">
              <button type="submit" class="btn btn-primary btn-block">Request new password</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mb-1">
          <a href="{{ route('login') }}">Login</a>
        </p>
        <p class="mb-0">
          <a href="{{ route('register') }}" class="text-center">Register a new membership</a>
        </p>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->
@endsection
