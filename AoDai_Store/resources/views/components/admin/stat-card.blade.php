@props(['label', 'value', 'icon', 'color' => 'red', 'href' => null, 'unit' => null])

@php
    $colors = [
        'red' => 'bg-red-50 text-red-800',
        'amber' => 'bg-amber-50 text-amber-700',
        'blue' => 'bg-blue-50 text-blue-700',
        'green' => 'bg-emerald-50 text-emerald-700',
        'indigo' => 'bg-indigo-50 text-indigo-700',
        'slate' => 'bg-slate-100 text-slate-600',
    ];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif
    @class([
        'flex min-w-0 items-center gap-2 rounded-2xl border border-red-100 bg-white p-3 shadow-sm sm:gap-3 sm:p-4',
        'transition hover:border-red-200 hover:shadow-md' => $href,
    ])>
    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl sm:h-11 sm:w-11 {{ $colors[$color] }}">
        <i class="{{ $icon }} text-sm sm:text-lg" aria-hidden="true"></i>
    </span>
    <div class="min-w-0">
        <p class="text-[11px] font-semibold leading-tight text-stone-500">{{ $label }}</p>
        <p class="mt-1 break-words text-lg font-bold text-red-900 sm:text-xl">
            {{ number_format($value, 0, ',', '.') }}{{ $unit ? ' '.$unit : '' }}
        </p>
    </div>
</{{ $tag }}>
