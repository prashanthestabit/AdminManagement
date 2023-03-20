@extends('adminmanagement::layouts.app')

@section('content')
    <x-adminmanagement::page-header pageTitle="Edit User" :breadcrumbs="['Home', 'Edit User']" />

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit User<small></small></h3>
                        </div>
                        {!! Form::model($user, ['method' => 'PATCH','route' => ['admin.users.update', $user->id]]) !!}
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input class="form-control" type="text" name="name"
                                 value="{{ old('name',($user->name)?$user->name:'') }}"
                                    required>
                                @if ($errors->has('name'))
                                    <div class="text-danger">
                                        {{ $errors->first('name') }}
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="form-control" type="text" name="email"
                                value="{{ old('email',($user->email)?$user->email:'') }}" required>
                                @if ($errors->has('email'))
                                    <div class="text-danger">
                                        {{ $errors->first('email') }}
                                    </div>
                                @endif
                            </div>


                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" name="roles[]" multiple required>
                                    @forelse ($roles as $role)
                                        <option value="{{ $role }}"
                                        {{ ((in_array($role,$userRole)) ? "selected":"") }}>{{ $role }}</option>
                                    @empty
                                    @endforelse
                                </select>
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
                    }
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
