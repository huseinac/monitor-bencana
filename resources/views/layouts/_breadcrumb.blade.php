@php($breadcrumbs = $breadcrumbs ?? [])
<div class="breadcrumb-bar">
    @foreach($breadcrumbs as $breadcrumb)
        @if($breadcrumb != last($breadcrumbs))
            <span>{{ $breadcrumb['caption'] }}</span>
        @else
            <i class="bi bi-chevron-right"></i>
            <a href="{{ has_route($breadcrumb['route']) }}">{{ $breadcrumb['caption'] }}</a>
        @endif
    @endforeach
</div>
