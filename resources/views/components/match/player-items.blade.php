@props(['items', 'GameIconHelper'])

@php
    $items = array_pad(
        array_filter($items, fn($item) => !empty($item)),
        6,
        null
    );
@endphp

<div class="items-grid">
    @foreach($items as $item)
        <div class="item-slot">
            @if($item)
                <img src="{{ $GameIconHelper->getItemIcon($item) }}" 
                     alt="{{ $item }}"
                     title="{{ $item }}">
            @endif
        </div>
    @endforeach
</div> 