@props(['href' => '#'])

<a {{ $attributes->merge([
    'href' => $href,
    'class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-150 ease-in-out'
]) }}>
    {{ $slot }}
</a>
