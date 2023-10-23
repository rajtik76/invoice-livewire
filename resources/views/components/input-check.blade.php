@props(['disabled' => false, 'name'])

<input
    type="checkbox"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'border-gray-300 dark:border-blue-700 dark:bg-gray-900 dark:text-blue-500 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}
    @checked($this->{$name})
>
