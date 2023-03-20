@extends('adminmanagement::layouts.master')

@section('content')
<div class="register-box">
    <div class="register-logo">
      <a href="#">{{ config('app.name') }}</a>
    </div>
<div class="card">
    <div class="card-body register-card-body">
      <p class="login-box-msg">Register a new membership</p>

      <form action="{{ route('register.post') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="text" name="name" value="{{ old('name') }}"
          required class="form-control" placeholder="Full name">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
            @if ($errors->has('name'))
            <span class="text-danger">{{ $errors->first('name') }}</span>
          @endif
        </div>
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
        @if ($errors->has('password_confirmation'))
        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
      @endif
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
            <div class="icheck-primary">
                @if ($errors->has('terms'))
                <span class="text-danger">{{ $errors->first('terms') }}</span>
              @endif
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
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
