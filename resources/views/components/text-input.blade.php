@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-dark-text-secondary focus:border-light-accent-secondary dark:focus:border-dark-accent-secondary dark:focus:border-indigo-600 focus:ring-light-accent-secondary dark:focus:ring-dark-accent-secondary dark:focus:ring-indigo-600 rounded-md shadow-sm']) }}>
