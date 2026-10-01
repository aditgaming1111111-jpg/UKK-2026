<div {{ $attributes->class(['overflow-hidden rounded-xl border border-gray-200 bg-white']) }}>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-left text-sm">{{ $slot }}</table>
    </div>
</div>
