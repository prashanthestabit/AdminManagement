@extends('adminmanagement::layouts.app')

@section('content')

<x-adminmanagement::page-header pageTitle="Role Management" :breadcrumbs="['Home', 'Role Management']" />

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Roles</h3>
                  @can('create role')
                  <div class="text-right">
                        <a class="btn btn-success btn-sm" href="{{ route('admin.roles.create') }}"> Create New Role </a>
                  </div>
                  @endcan
                </div>
                <x-adminmanagement::global-search :href="route('admin.roles.index')"
                 :value="request()->input('table_search')"/>

                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th style="width: 280px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $key => $role)
                        <tr>
                          <td>{{ ++$i }}</td>
                          <td>{{ $role->name }}</td>
                          <td>
                            @if(!empty($role->permissions))
                            @foreach($role->permissions as $key => $item)
                                <span class="badge badge-info">{{ $item->name }}</span>
                            @endforeach
                            @endif
                          </td>
                          <td>
                             @can('edit role')
                             <a class="btn btn-info btn-sm" href="{{ route('admin.roles.edit',$role->id) }}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                Edit
                            </a>
                              @endcan
                              @can('delete role')
                              {!! Form::open(['method' => 'DELETE',
                              'route' => ['admin.roles.destroy', $role->id],'style'=>'display:inline']) !!}
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
