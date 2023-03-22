@extends('adminmanagement::layouts.app')

@section('content')
    <x-adminmanagement::page-header pageTitle="Creare User" :breadcrumbs="['Home', 'Creare User']" />

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create User<small></small></h3>
                        </div>
                        {!! Form::open(['route' => 'admin.users.store', 'method' => 'POST', 'id' => 'quickForm']) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                    required>
                                @if ($errors->has('name'))
                                    <div class="text-danger">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="text" name="email" value="{{ old('email') }}"
                                    required>
                                @if ($errors->has('email'))
                                    <div class="text-danger">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input class="form-control" type="password" name="password"
                                id="password" required>
                                @if ($errors->has('password'))
                                    <div class="text-danger">
                                        {{ $errors->first('password') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Retype password</label>
                                <input class="form-control" type="password" name="password_confirmation"
                                     required>
                                @if ($errors->has('password_confirmation'))
                                    <div class="text-danger">
                                        {{ $errors->first('password_confirmation') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control select2" name="roles[]" multiple required>
                                    @forelse ($roles as $role)
                                        <option value="{{ $role }}"
                                        {{ (old("roles") == $role ? "selected":"") }}>{{ $role }}</option>
                                    @empty
                                    @endforelse
                                </select>
                            </div>
                            <div class="card card-secondary">
                                <div class="card-header">
                                  <h3 class="card-title">Permission</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="row ml-2">
                                        @forelse ($permissions as $permission)
                                            <div class="col-sm-3 col-md-3 form-check">
                                                <input class="form-check-input" type="checkbox"
                                                        name="permissions[]"  value="{{ $permission->id }}">
                                            <label class="form-check-label">{{ Str::title($permission->name) }}</label>
                                            </div>
                                        @empty

                                        @endforelse
                                    </div>
                                </div>
                                <!-- /.card-body -->
                              </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <!-- /.card -->
            </div>

        </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('script')
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            $.validator.setDefaults({
                submitHandler: function() {
                    form.submit();
                }
            });
            $('#quickForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        minlength: 5
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 5,
                        equalTo: "#password"
                    },
                    roles: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a name",
                        minlength: "Your name must be at least 2 characters long"
                    },
                    email: {
                        required: "Please enter a email address",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 5 characters long"
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
