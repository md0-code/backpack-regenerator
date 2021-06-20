{{-- enumerate the values in an array  --}}
@php
$value = data_get($entry, $column['name']);
$column['escaped'] = $column['escaped'] ?? false;
$column['prefix'] = $column['prefix'] ?? '';
$column['suffix'] = $column['suffix'] ?? '';

// the value should be an array wether or not attribute casting is used
if (!is_array($value)) {
$value = json_decode($value, true);
}
@endphp

<span>
    @if($value && count($value))
    {{ $column['prefix'] }}
    @foreach($value as $key => $text)
    @php
    $column['text'] = $text;
    $column['key'] = $key;
    @endphp

    <span class="d-inline-flex">
        @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
        @if($column['escaped'])
        {{ $column['text'] }}
        @else
        {!! $column['key'] !!}:
        @if ($column['text'])
        {!! $column['text'] !!}
        @else
        [x]
        @endif
        @endif
        @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
        @if(!$loop->last) &nbsp;| @endif
    </span>
    @endforeach
    {{ $column['suffix'] }}
    @else
    -
    @endif
</span>