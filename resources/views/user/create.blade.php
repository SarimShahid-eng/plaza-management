@extends('partials.app', ['title' => $title])

@section('content')
        <x-toast-error field="updateId" />

    <div class="col-span-12 space-y-6 xl:col-span-12">
        <div class="p-4 mx-auto max-w-screen-2xl md:p-6">

            {{-- PAGE HEADER --}}
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-title-sm font-semibold text-gray-800 dark:text-white/90">Add Material</h2>
                    <p class="mt-0.5 text-theme-sm text-gray-500 dark:text-gray-400">Fill in the details to create a new
                        material</p>
                </div>
                <a href="{{ route('materials.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Back to Materials
                </a>
            </div>

            <form method="POST" action="{{ route('materials.store') }}">
                @csrf
                <input type="hidden" value="{{ @$material->id }}" name="updateId">
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Card Header --}}
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">Material Information</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Basic details and stock information for the
                            material.</p>
                    </div>

                    {{-- Fields --}}
                    <div class="border-t border-gray-100 p-5 sm:p-6 dark:border-gray-800">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div class="xl:col-span-2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Material Name <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ $material->name ?? old('name') }}"
                                        placeholder="e.g. Cement, Steel Rod" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'name'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'name'),
                                        ])>
                                    @if ($errors->has('name'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('name')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Unit --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Unit <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="unit" value="{{ $material->unit ?? old('unit') }}"
                                        placeholder="e.g. kg, bag, piece" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'unit'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'unit'),
                                        ])>
                                    @if ($errors->has('unit'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('unit')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Stock Quantity --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Stock Quantity <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="stock_quantity"
                                        value="{{ $material->stock_quantity ?? old('stock_quantity') }}"
                                        placeholder="e.g. 100" min="0" step="any" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'stock_quantity'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'stock_quantity'),
                                        ])>
                                    @if ($errors->has('stock_quantity'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('stock_quantity')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Average Rate --}}
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                                    Average Rate <span class="text-error-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="number" name="avg_rate"
                                        value="{{ $material->avg_rate ?? old('avg_rate') }}" placeholder="e.g. 250.00"
                                        min="0" step="any" @class([
                                            'dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:text-white/90 dark:placeholder:text-white/30',
                                            'border-error-300 focus:border-error-300 focus:ring-error-500/10 dark:border-error-700 dark:focus:border-error-800 pr-10' => $errors->has(
                                                'avg_rate'),
                                            'border-gray-300 focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:focus:border-brand-800 dark:bg-gray-900' => !$errors->has(
                                                'avg_rate'),
                                        ])>
                                    @if ($errors->has('avg_rate'))
                                        <span class="absolute top-1/2 right-3.5 -translate-y-1/2">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M2.58325 7.99967C2.58325 5.00813 5.00838 2.58301 7.99992 2.58301C10.9915 2.58301 13.4166 5.00813 13.4166 7.99967C13.4166 10.9912 10.9915 13.4163 7.99992 13.4163C5.00838 13.4163 2.58325 10.9912 2.58325 7.99967ZM7.99992 1.08301C4.17995 1.08301 1.08325 4.17971 1.08325 7.99967C1.08325 11.8196 4.17995 14.9163 7.99992 14.9163C11.8199 14.9163 14.9166 11.8196 14.9166 7.99967C14.9166 4.17971 11.8199 1.08301 7.99992 1.08301ZM7.09932 5.01639C7.09932 5.51345 7.50227 5.91639 7.99932 5.91639H7.99999C8.49705 5.91639 8.89999 5.51345 8.89999 5.01639C8.89999 4.51933 8.49705 4.11639 7.99999 4.11639H7.99932C7.50227 4.11639 7.09932 4.51933 7.09932 5.01639ZM7.99998 11.8306C7.58576 11.8306 7.24998 11.4948 7.24998 11.0806V7.29627C7.24998 6.88206 7.58576 6.54627 7.99998 6.54627C8.41419 6.54627 8.74998 6.88206 8.74998 7.29627V11.0806C8.74998 11.4948 8.41419 11.8306 7.99998 11.8306Z"
                                                    fill="#F04438" />
                                            </svg>
                                        </span>
                                    @endif
                                </div>
                                @error('avg_rate')
                                    <p class="text-theme-xs text-error-500 mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>
                    </div>

                    {{-- Footer Actions --}}
                    <div
                        class="flex items-center justify-end gap-3 border-t border-gray-100 px-5 py-4 sm:px-6 dark:border-gray-800">
                        <a href="{{ route('materials.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.05]">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                            Save Material
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>
@endsection
