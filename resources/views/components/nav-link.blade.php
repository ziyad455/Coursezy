@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-light-text-primary dark:text-dark-text-primary focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-light-text-muted dark:text-dark-text-muted hover:text-gray-700 dark:hover:text-dark-text-secondary hover:border-gray-300 dark:hover:border-dark-border-default focus:outline-none focus:text-gray-700 dark:focus:text-dark-text-secondary focus:border-gray-300 dark:focus:border-dark-border-default transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
