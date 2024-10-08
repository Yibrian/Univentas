<nav class="text-sm text-gray-600">
    <ol class="list-reset flex">
        @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb['url'])
                <li>
                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-gray-800">
                        {{ $breadcrumb['title'] }}
                    </a>
                    @if (!$loop->last)
                        <span class="mx-2">/</span>
                    @endif
                </li>
            @else
                <li class="text-gray-500">
                    {{ $breadcrumb['title'] }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>
