@if (isset($action))
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
            @if (isset($description))
                <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
            @endif
        </div>
        <a href="{{ $action['url'] }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <i class="{{ $action['icon'] ?? 'fas fa-plus' }} mr-2"></i>
            {{ $action['text'] }}
        </a>
    </div>
@else
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if (isset($description))
            <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
        @endif
    </div>
@endif
