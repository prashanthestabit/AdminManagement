@props(['data'])

@if ($data->hasPages())
    <ul class="pagination pagination-sm m-0 float-right">
        @if ($data->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $data->previousPageUrl() }}">
                    Previous</a>
            </li>
        @endif

        @foreach ($data as $element)
            @if (is_string($element))
                <li class="page-item disabled">{{ $element }}</li>
            @endif

            @if (is_array($element))
                @foreach ($data as $page => $url)
                    @if ($page == $data->currentPage())
                        <li class="page-item active">
                            <a class="page-link">{{ $page }}</a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($data->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $data->nextPageUrl() }}" rel="next">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#">Next</a>
            </li>
        @endif
    </ul>

    Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} Results

@endif
