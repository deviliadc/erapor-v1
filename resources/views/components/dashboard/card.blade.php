{{-- filepath: d:\DEVI\DRAFT\erapor-v1\resources\views\components\dashboard\card.blade.php --}}
@props(['title', 'value', 'icon' => null, 'iconSvg' => null, 'color' => 'blue', 'href' => null])

@php
    $colorMap = [
        'blue' => ['from' => 'from-blue-500', 'to' => 'to-blue-400', 'ring' => 'ring-blue-200', 'icon' => 'text-blue-600 dark:text-blue-300', 'bg' => 'bg-blue-100 dark:bg-blue-900'],
        'green' => ['from' => 'from-green-500', 'to' => 'to-green-400', 'ring' => 'ring-green-200', 'icon' => 'text-green-600 dark:text-green-300', 'bg' => 'bg-green-100 dark:bg-green-900'],
        'purple' => ['from' => 'from-purple-500', 'to' => 'to-purple-400', 'ring' => 'ring-purple-200', 'icon' => 'text-purple-600 dark:text-purple-300', 'bg' => 'bg-purple-100 dark:bg-purple-900'],
        'pink' => ['from' => 'from-pink-500', 'to' => 'to-pink-400', 'ring' => 'ring-pink-200', 'icon' => 'text-pink-600 dark:text-pink-300', 'bg' => 'bg-pink-100 dark:bg-pink-900'],
        'yellow' => ['from' => 'from-yellow-400', 'to' => 'to-yellow-300', 'ring' => 'ring-yellow-200', 'icon' => 'text-yellow-600 dark:text-yellow-400', 'bg' => 'bg-yellow-100 dark:bg-yellow-900'],
        'teal' => ['from' => 'from-teal-500', 'to' => 'to-teal-400', 'ring' => 'ring-teal-200', 'icon' => 'text-teal-600 dark:text-teal-300', 'bg' => 'bg-teal-100 dark:bg-teal-900'],
        'red' => ['from' => 'from-red-500', 'to' => 'to-red-400', 'ring' => 'ring-red-200', 'icon' => 'text-red-600 dark:text-red-300', 'bg' => 'bg-red-100 dark:bg-red-900'],
        'gray' => ['from' => 'from-gray-500', 'to' => 'to-gray-400', 'ring' => 'ring-gray-200', 'icon' => 'text-gray-600 dark:text-gray-300', 'bg' => 'bg-gray-100 dark:bg-gray-900'],
    ];
    $c = $colorMap[$color] ?? $colorMap['blue'];
    $cardClass = "rounded-xl bg-gradient-to-r {$c['from']} {$c['to']} p-1 shadow-lg hover:scale-[1.03] transition min-w-[220px] max-w-[320px] w-full";
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $cardClass }} cursor-pointer block focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-{{ $color }}-400">
@else
    <div class="{{ $cardClass }}">
@endif
    <div class="flex items-center space-x-4 bg-white dark:bg-gray-800 rounded-xl p-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full {{ $c['bg'] }} {{ $c['icon'] }} ring-4 {{ $c['ring'] }}">
            @if($iconSvg)
                {{-- SVG icon with Tailwind color for dark mode --}}
                <span class="w-7 h-7 [&_svg]:w-7 [&_svg]:h-7 [&_svg]:stroke-current [&_svg]:text-inherit">
                    {!! $iconSvg !!}
                </span>
            @elseif($icon)
                <i class="fas fa-{{ $icon }} text-xl"></i>
            @endif
        </div>
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h4>
            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
        </div>
    </div>
@if($href)
    </a>
@else
    </div>
@endif
