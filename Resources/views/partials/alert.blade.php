@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if (session('message'))
<div class="alert alert-info">
    {{ session('message') }}
</div>
@endif

@if (session('success'))
<div class="alert alert-info">
    {{ session('success') }}
</div>
@endif

@if (count($errors) > 0)
  <div class="alert alert-danger">
    <strong>Whoops!</strong>Something went wrong.<br><br>
    <ul>
       @foreach ($errors->all() as $error)
         <li>{{ $error }}</li>
       @endforeach
    </ul>
  </div>
@endif


