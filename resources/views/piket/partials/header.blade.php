@if (isset($action))
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                @if (isset($icon))
                    <i class="{{ $icon }} text-primary-600 mr-3"></i>
                @endif
                {{ $title }}
            </h1>
            @if (isset($description))
                <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
            @endif
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ $action['url'] }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-primary-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                @if (isset($action['icon']))
                    <i class="{{ $action['icon'] }} mr-2"></i>
                @endif
                {{ $action['text'] }}
            </a>
        </div>
    </div>
@else
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 flex items-center">
            @if (isset($icon))
                <i class="{{ $icon }} text-primary-600 mr-3"></i>
            @endif
            {{ $title }}
        </h1>
        @if (isset($description))
            <p class="mt-1 text-sm text-gray-600">{{ $description }}</p>
        @endif
    </div>
@endif
