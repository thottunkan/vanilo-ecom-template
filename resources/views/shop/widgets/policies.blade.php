@foreach ($policies as $policy)
    <a href="{{ $policy->url ?? '#' }}" class="text-white-50 text-decoration-none">
        {{ $policy->title ?? '' }}
    </a>
@endforeach