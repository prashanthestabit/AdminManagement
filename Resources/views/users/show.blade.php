@extends('adminmanagement::layouts.app')

@section('content')
    <x-adminmanagement::page-header pageTitle="User Detail" :breadcrumbs="['Home', 'User Detail']" />

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">User<small></small></h3>
                        </div>
                      <div class="card-body">
                            <div class="form-group">
                                <label for="name">Name : {{ $user->name }} </label>

                            </div>

                            <div class="form-group">
                                <label for="email">Email : {{ $user->email }}</label>
                            </div>


                            <div class="form-group">
                                <label for="role">Role :  {{ implode(',',$userRole) }} </label>
                            </div>

                            @if($userPermission)
                            <div class="form-group">
                                <label for="role">Permission :  {{ implode(', ',$userPermission) }} </label>
                            </div>
                            @endif

                        </div>
                    </div>

                </div>
                <!-- /.card -->
            </div>

        </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
