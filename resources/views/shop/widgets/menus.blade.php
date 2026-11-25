@foreach ($menus as $menu)
    <a href="{{ $menu->url ?? '#' }}" class="text-white-50 text-decoration-none">
        {{ $menu->title ?? '' }}
    </a>
@endforeach