<x-app-layout>
    <x-slot name="title">
        Add Book
    </x-slot>

    <x-slot name="header">
        <h1 class="text-xl font-bold">Add a New Book</h1>
    </x-slot>

    <div class="p-8 space-y-4">

        {{-- Book Form --}}
        <form method="POST" action="{{ route('books.store') }}" class="p-6 bg-white shadow-sm sm:rounded-lg space-y-6"
            enctype="multipart/form-data" autocomplete="off">
            @csrf

            {{-- Section Header --}}
            <div class="flex justify-between items-center mb-2">
                <h1 class="font-bold text-base">Book Information</h1>
                <a href="{{ route('books.index') }}">
                    <x-secondary-button>
                        Go Back
                    </x-secondary-button>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-6">

                {{-- Book Cover --}}
                <div x-data="imageUploader()" class="col-span-1 relative">
                    <x-input-label for="cover" :value="__('Book Cover')" />

                    {{-- Upload Box (relative wrapper) --}}
                    <div class="relative mt-1 flex flex-col items-center justify-center border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition"
                        @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                        @drop.prevent="handleDrop($event)"
                        :class="dragging ? 'border-primary bg-primary/5' : 'border-gray-300'"
                        style="aspect-ratio: 1 / 1.414; width: 200px; margin: auto; overflow: hidden;">
                        {{-- Preview --}}
                        <template x-if="preview">
                            <img :src="preview" alt="Cover Preview"
                                class="object-contain h-full w-full rounded-md" />
                        </template>

                        {{-- Placeholder --}}
                        <template x-if="!preview">
                            <div class="text-center py-6">
                                <span class="material-symbols-outlined text-gray-400 text-4xl mb-2">upload</span>
                                <p class="text-gray-600 text-sm">Drag & drop or click to upload</p>
                                <p class="text-xs text-gray-400">(JPG, PNG, WEBP — Max 2MB)</p>
                            </div>
                        </template>

                        {{-- ✅ File Input — stays INSIDE the upload box --}}
                        <input type="file" id="cover" name="cover" accept="image/*"
                            class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer z-10"
                            @change="previewImage" />
                    </div>

                    <x-input-error class="mt-2" :messages="$errors->get('cover')" />
                </div>


                <div class="">

                </div>

                {{-- Book Title --}}
                <div>
                    <x-input-label for="title" :value="__('Book Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                        :value="old('title')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                {{-- Language --}}
                <div>
                    <x-input-label for="language" :value="__('Book Language')" />
                    <select id="language" name="language"
                        class="mt-1 block w-full px-2 py-2 bg-background-light border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 rounded-md"
                        required>
                        <option value="" disabled {{ old('language') ? '' : 'selected' }}>Select Language</option>
                        <option value="khmer" {{ old('language') === 'khmer' ? 'selected' : '' }}>Khmer</option>
                        <option value="english" {{ old('language') === 'english' ? 'selected' : '' }}>English</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('language')" />
                </div>

                {{-- Author --}}
                <div>
                    <x-input-label for="author" :value="__('Book Author')" />
                    <x-text-input id="author" name="author" type="text" class="mt-1 block w-full"
                        :value="old('author')" />
                    <x-input-error class="mt-2" :messages="$errors->get('author')" />
                </div>

                {{-- Published Year --}}
                <div>
                    <x-input-label for="published_year" :value="__('Published Year')" />
                    <x-text-input id="published_year" name="published_year" type="number" class="mt-1 block w-full"
                        :value="old('published_year')" />
                    <x-input-error class="mt-2" :messages="$errors->get('published_year')" />
                </div>


                {{-- Book Category --}}
                <style>
                    [x-cloak] {
                        display: none !important
                    }
                </style>

                <div x-data="categoryDropdown()" x-init="init()" class="relative">
                    <x-input-label for="category_id" :value="__('Book Category')" />

                    <div class="relative">
                        {{-- Search box --}}
                        <input type="text" x-model="search" @focus="open = true"
                            placeholder="Search or add a new category"
                            class="mt-1 block w-full pl-2 pr-8 py-2 bg-background-light border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 rounded-md" />

                        {{-- Search toggle button --}}
                        <button type="button" @click="toggleDropdown"
                            class="absolute inset-y-0 right-2 flex items-center text-gray-500 hover:text-primary">
                            <span class="material-symbols-outlined text-base">search</span>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open" x-cloak x-transition.opacity.duration.150ms @mousedown.away="closeDropdown"
                            class="absolute z-10 w-full bg-white border border-gray-200 rounded-md mt-1 shadow-lg max-h-56 overflow-auto">

                            {{-- Loading --}}
                            <div class="px-3 py-2 text-gray-400 text-sm italic" x-show="loading">
                                Loading categories...
                            </div>

                            {{-- Category List --}}
                            <template x-if="!loading && filtered.length">
                                <div>
                                    <template x-for="(category, idx) in filtered" :key="category.id">
                                        <div
                                            class="group flex items-center justify-between px-3 py-2 hover:bg-tertiary/10 transition">
                                            {{-- Editable Name or Input --}}
                                            <div class="flex-1 truncate">
                                                {{-- Normal view --}}
                                                <div x-show="!category.editing" @click="selectCategory(category)"
                                                    class="cursor-pointer">
                                                    <span x-text="category.name"></span>
                                                </div>

                                                {{-- Editing view --}}
                                                <div x-show="category.editing" class="flex items-center gap-2">
                                                    <input type="text" x-model="category.tempName"
                                                        class="border border-gray-300 rounded px-2 py-1 text-sm w-full focus:outline-none focus:ring focus:ring-primary/20" />

                                                    {{-- Confirm (save) --}}
                                                    <button type="button"
                                                        @click.stop="updateCategory(category, category.tempName)"
                                                        class="text-green-500 hover:text-green-700 transition">
                                                        <span class="material-symbols-outlined text-sm">check</span>
                                                    </button>

                                                    {{-- Cancel --}}
                                                    <button type="button"
                                                        @click.stop="category.editing = false; category.tempName = category.name"
                                                        class="text-gray-400 hover:text-gray-600 transition">
                                                        <span class="material-symbols-outlined text-sm">close</span>
                                                    </button>
                                                </div>
                                            </div>

                                            {{-- Action Buttons (Edit/Delete) --}}
                                            <div x-show="!category.editing"
                                                class="flex gap-1 ml-2 opacity-0 group-hover:opacity-100 transition">
                                                <button type="button" class="text-blue-500 hover:text-blue-700"
                                                    @click.stop="category.editing = true; category.tempName = category.name">
                                                    <span class="material-symbols-outlined text-sm">edit</span>
                                                </button>
                                                <button type="button" class="text-red-500 hover:text-red-700"
                                                    @click.stop="deleteCategory(category)">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            {{-- No Categories --}}
                            <div x-show="!loading && !filtered.length && search.trim() === ''"
                                class="px-3 py-2 text-gray-500 text-sm italic">
                                No categories found.
                            </div>

                            {{-- Add New --}}
                            <div x-show="!loading && !filtered.length && search.trim() !== ''" @click="addCategory"
                                class="px-3 py-2 cursor-pointer bg-green-50 text-green-700 font-semibold hover:bg-green-100 transition">
                                + Add "<span x-text="search"></span>"
                            </div>
                        </div>
                    </div>

                    {{-- Hidden field --}}
                    <input type="hidden" name="category_id" :value="selected?.id || ''" />

                    {{-- Selected display --}}
                    <template x-if="selected">
                        <p class="text-sm text-gray-600 mt-1">
                            Selected:
                            <span class="font-medium text-primary" x-text="selected.name"></span>
                        </p>
                    </template>
                </div>

                {{-- Source --}}
                <div>
                    <x-input-label for="source" :value="__('Book Source')" />
                    <select id="source" name="source"
                        class="mt-1 block w-full px-2 py-2 bg-background-light border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 rounded-md"
                        required>
                        <option value="" disabled {{ old('source') ? '' : 'selected' }}>Select Source</option>
                        <option value="donated" {{ old('language') === 'donated' ? 'selected' : '' }}>Donated</option>
                        <option value="purchased" {{ old('language') === 'purchased' ? 'selected' : '' }}>Purchased
                        </option>
                        <option value="sponsored" {{ old('language') === 'sponsored' ? 'selected' : '' }}>Sponsored
                        </option>
                        <option value="other" {{ old('language') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('language')" />
                </div>

                {{--  Total Copies --}}
                <div>
                    <x-input-label for="total_copies" :value="__('Book Amount (Total Copies)')" />
                    <x-text-input id="total_copies" name="total_copies" type="number" class="mt-1 block w-full"
                        :value=0 />
                    <x-input-error class="mt-2" :messages="$errors->get('total_copies')" />
                </div>

                {{-- Book Description --}}
                <div class="col-span-2">
                    <x-input-label for="description" :value="__('Book Description')" />

                    <textarea id="description" name="description" rows="5"
                        class="mt-1 block w-full px-3 py-2 bg-background-light border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary/20 inset-shadow-sm inset-shadow-gray-300"
                        placeholder="Write a short summary or description of the book...">{{ old('description') }}</textarea>

                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-4">
                <x-primary-button>
                    Save Book
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
