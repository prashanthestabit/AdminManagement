@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

@if (session('message'))
<div class="alert alert-info sort-message">
    {{ session('message') }}
</div>
@endif

@if (session('success'))
<div class="alert alert-info sort-message">
    {{ session('success') }}
</div>
@endif



