<div class="mt-4">
    <div class="flex items-center w-24 ml-2 rounded-md bg-white/5 pl-2 outline-1 -outline-offset-1 outline-gray-600 has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-indigo-500">
        <input type="number" min="0" max="100"
            {{ $attributes->merge([
                 'class' => 'w-10 bg-gray-800 py-1 text-right text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6'
            ]) }} />
        <span class="shrink-0 text-gray-400 pl-1">%</span>
    </div>
</div>


