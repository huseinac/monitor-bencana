@php($menus = $menus ?? [])
<nav class="sidebar-nav">
    @foreach($menus as $menu)
        @if($menu['route'] === null)
            <div class="nav-section">{{ $menu['caption'] }}</div>
        @endif
        @if($menu['route'] !== null)
            <div class="nav-item">
                <a href="{{ has_route($menu['route']) }}" class="nav-link-sb"><i class="bi {{ $menu['icon'] }}"></i> {{ $menu['caption'] }}</a>
            </div>
        @endif
    @endforeach
</nav>
