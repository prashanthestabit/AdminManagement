@extends('adminmanagement::layouts.app')

@section('content')
    <x-adminmanagement::page-header pageTitle="Creare Role" :breadcrumbs="['Home', 'Creare Role']" />

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create Role<small></small></h3>
                        </div>
                        {!! Form::open(['route' => 'admin.roles.store', 'method' => 'POST', 'id' => 'roleForm']) !!}
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
                                <label for="permissions">Permissions</label>
                                <div style="padding-bottom: 4px">
                                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">
                                        {{ trans('adminmanagement::auth.select_all') }}</span>
                                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">
                                        {{ trans('adminmanagement::auth.deselect_all') }}</span>
                                </div>
                                <div class="select2-purple">
                                <select class="select2" id="permissions"
                                multiple="multiple" data-placeholder="Select a Permissions"
                                data-dropdown-css-class="select2-purple" style="width: 100%;"
                                name="permissions[]" required>
                                    @forelse ($permissions as $permission)
                                        <option value="{{ $permission->id }}"
                                        {{ (old("permissions") == $permission->name ? "selected":"") }}>
                                        {{ $permission->name }}
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                </div>
                                @if ($errors->has('permissions'))
                                    <div class="text-danger">
                                        {{ $errors->first('permissions') }}
                                    </div>
                                @endif
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
            $(document).on('click','.select-all',function(){
                $("#permissions > option").prop("selected", true);
                $("#permissions").trigger("change");
            });

            $(document).on('click','.deselect-all',function(){
                $("#permissions > option").prop("selected", false);
                $("#permissions").trigger("change");
            });

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
                    permissions: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a name",
                        minlength: "Your name must be at least 2 characters long"
                    },
                    permissions: {
                        required: "Please select permission",
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
