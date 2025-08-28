<div>
    <div class="mt-4">
        <div class="flex items-center rounded-md bg-white/5 pl-3 outline-1 -outline-offset-1 outline-gray-600 has-[input:focus-within]:outline-2 has-[input:focus-within]:-outline-offset-2 has-[input:focus-within]:outline-indigo-500">
            <div class="shrink-0 text-base text-gray-400 select-none sm:text-sm/6">$</div>
            <input
                type="text"
                placeholder="0.00"
                {{ $attributes->merge(['class' => 'block min-w-0 w-5 grow bg-gray-800 py-1 pr-3 pl-1 text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6']) }}
            />
            <div class="grid shrink-0 grid-cols-1 focus-within:relative">
                <option>COP</option>
            </div>
        </div>
    </div>
</div>

