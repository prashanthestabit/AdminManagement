@extends('adminmanagement::layouts.app')

@section('content')

<x-adminmanagement::page-header pageTitle="Permission Management" :breadcrumbs="['Home', 'Permission Management']" />

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Permissions</h3>
                  @can('create permission')
                  <div class="text-right">
                        <a class="btn btn-success btn-sm"
                        href="{{ route('admin.permissions.create') }}"> Create New Permission </a>
                  </div>
                  @endcan
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Permission</th>
                        <th style="width: 280px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $permission)
                        <tr>
                          <td>{{ ++$i }}</td>
                          <td>{{ $permission->name }}</td>
                          <td>
                             @can('edit permission')
                             <a class="btn btn-info btn-sm"
                             href="{{ route('admin.permissions.edit',$permission->id) }}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                            </a>
                              @endcan
                              @can('delete permission')
                              {!! Form::open(['method' => 'DELETE',
                              'route' => ['admin.permissions.destroy', $permission->id],'style'=>'display:inline']) !!}
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
