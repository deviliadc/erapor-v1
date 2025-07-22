@props(['title', 'value', 'icon', 'color' => 'blue'])

<div class="rounded-xl bg-white p-4 shadow dark:bg-gray-800 transition duration-300 hover:shadow-lg">
    <div class="flex items-center space-x-4">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-{{ $color }}-100 text-{{ $color }}-600">
            <i class="fas fa-{{ $icon }} text-lg"></i>
        </div>
        <div>
            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</h4>
            <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $value }}</p>
        </div>
    </div>
</div>
