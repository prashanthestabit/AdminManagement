<div class="card-header">
    <h3 class="card-title"></h3>

    <div class="card-tools">
    <form action="{{ $href }}" method="get" role="search" >
      <div class="input-group input-group-sm" style="width: 150px;">
        <input type="text" name="table_search" value="{{ $value }}"
        class="form-control float-right" placeholder="Search">

        <div class="input-group-append">
          <button type="submit" class="btn btn-default">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    </div>
</div>
