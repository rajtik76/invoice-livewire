@props(['options' => [], 'name'])

<select {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>
    @foreach($options as $key => $value)
        @if(is_null($this->{$name})))
            @if($loop->first)
                <option value="null" selected disabled>Please select option ...</option>
            @endif
                <option value="{{ $key }}">{{ $value }}</option>
        @else
            <option value="{{ $key }}" @selected(old($name) == $key)>{{ $value }}</option>
        @endif
    @endforeach
</select>
