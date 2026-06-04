@props(['count' => 4])
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @for($i = 0; $i < $count; $i++)
        <div class="animate-pulse">
            <div class="aspect-[4/5] bg-gray-200 dark:bg-gray-800 rounded-xl"></div>
            <div class="mt-4 space-y-2">
                <div class="h-3 bg-gray-200 dark:bg-gray-800 rounded w-1/3"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-800 rounded w-2/3"></div>
                <div class="h-4 bg-gray-200 dark:bg-gray-800 rounded w-1/2"></div>
            </div>
        </div>
    @endfor
</div>
