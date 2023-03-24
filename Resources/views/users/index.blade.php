@extends('adminmanagement::layouts.app')

@section('content')

<x-adminmanagement::page-header pageTitle="User Management" :breadcrumbs="['Home', 'User Management']" />

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Users</h3>
                  @can('create user')
                  <div class="text-right">
                        <a class="btn btn-success btn-sm" href="{{ route('admin.users.create') }}"> Create New User </a>
                  </div>
                  @endcan
                </div>

                <x-adminmanagement::global-search :href="route('admin.users.index')"
                 :value="request()->input('table_search')"/>

                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th style="width: 280px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $user)
                        <tr>
                          <td>{{ ++$i }}</td>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->email }}</td>
                          <td>
                            @if(!empty($user->getRoleNames()))
                              @foreach($user->getRoleNames() as $v)
                                 <label class="badge badge-success">{{ $v }}</label>
                              @endforeach
                            @endif
                          </td>
                          <td>
                            @can('access user')
                             <a class="btn btn-primary btn-sm" href="{{ route('admin.users.show',$user->id) }}">
                                <i class="fas fa-folder">
                                </i>
                                Show
                            </a>
                            @endcan
                             @can('edit user')
                             <a class="btn btn-info btn-sm" href="{{ route('admin.users.edit',$user->id) }}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                            </a>
                              @endcan
                              @can('delete user')
                              {!! Form::open(['method' => 'DELETE',
                              'route' => ['admin.users.destroy', $user->id],'style'=>'display:inline']) !!}
                              <input type="hidden" name="page" value="{{ $data->currentPage() }}">

                              {{ Form::button('<i class="fas fa-trash""></i> Delete',
                                ['class' => 'btn btn-danger btn-sm',
                                'type' => 'submit']) }}

                              {!! Form::close() !!}
                              @endcan
                          </td>
                        </tr>
                       @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                  <x-adminmanagement::pagination-link  :data="$data" />

                </div>

              </div>
              <!-- /.card -->
            </div>

          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->

@endsection
