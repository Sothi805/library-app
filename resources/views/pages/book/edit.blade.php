<x-app-layout>
    <x-slot name="title">
        Edit Book
    </x-slot>

    <x-slot name="header">
        <h1 class="text-xl font-bold">Edit Book: {{ $book->title }}</h1>
    </x-slot>

    <div class="p-8 space-y-4">
        {{-- Book Form --}}
        <form method="POST" action="{{ route('books.update', $book) }}"
            class="p-6 bg-white/30 backdrop-blur-sm shadow-sm sm:rounded-lg space-y-6" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            {{-- Section Header --}}
            <div class="flex justify-between items-center mb-2">
                <div>
                    <h1 class="font-bold text-base">Book Information</h1>
                    <p class="text-sm text-gray-500">Book ID: {{ $book->book_id }}</p>
                </div>
                <a href="{{ url()->previous() }}">
                    <x-secondary-button>
                        Go Back
                    </x-secondary-button>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-6">
                {{-- Book Cover --}}
                <div x-data="imageUploader()" class="col-span-1 relative">
                    <x-input-label for="cover" :value="__('Book Cover')" />

                    {{-- Upload Box --}}
                    <div class="relative mt-1 flex flex-col items-center justify-center border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition"
                        @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                        @drop.prevent="handleDrop($event)"
                        :class="dragging ? 'border-primary bg-primary/5' : 'border-gray-300'"
                        style="aspect-ratio: 1 / 1.414; width: 200px; margin: auto; overflow: hidden;">

                        {{-- Preview of new upload --}}
                        <template x-if="preview">
                            <img :src="preview" alt="Cover Preview"
                                class="object-contain h-full w-full rounded-md" />
                        </template>

                        {{-- Current cover or placeholder --}}
                        <template x-if="!preview">
                            <div class="h-full w-full">
                                @if ($book->cover_path)
                                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="Current Book Cover"
                                        class="object-contain h-full w-full rounded-md" />
                                @else
                                    <div class="text-center py-6">
                                        <span
                                            class="material-symbols-outlined text-gray-400 text-4xl mb-2">upload</span>
                                        <p class="text-gray-600 text-sm">Drag & drop or click to upload</p>
                                        <p class="text-xs text-gray-400">(JPG, PNG, WEBP — Max 2MB)</p>
                                    </div>
                                @endif
                            </div>
                        </template>

                        {{-- File Input --}}
                        <input type="file" id="cover" name="cover" accept="image/*"
                            class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer z-10"
                            @change="previewImage" />
                    </div>

                    {{-- Cover Actions --}}
                    @if ($book->cover_path)
                        <div class="mt-2 flex justify-center gap-2">
                            {{-- Delete Cover Toggle --}}
                            <label class="flex items-center text-sm text-gray-600 gap-1">
                                <input type="checkbox" name="delete_cover" value="1"
                                    class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary/20"
                                    @change="if($event.target.checked) preview = null" />
                                Delete current cover
                            </label>
                        </div>
                    @endif

                    <x-input-error class="mt-2" :messages="$errors->get('cover')" />
                </div>

                <div></div>

                {{-- Book Title --}}
                <div>
                    <x-input-label for="title" :value="__('Book Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                        :value="old('title', $book->title)" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>

                {{-- Language --}}
                <div>
                    <x-input-label for="language" :value="__('Book Language')" />
                    <select id="language" name="language"
                        class="mt-1 block w-full px-2 py-2 bg-background-light border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 rounded-md"
                        required>
                        <option value="" disabled>Select Language</option>
                        <option value="khmer" {{ old('language', $book->language) === 'khmer' ? 'selected' : '' }}>
                            Khmer</option>
                        <option value="english" {{ old('language', $book->language) === 'english' ? 'selected' : '' }}>
                            English</option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('language')" />
                </div>

                {{-- Author --}}
                <div>
                    <x-input-label for="author" :value="__('Book Author')" />
                    <x-text-input id="author" name="author" type="text" class="mt-1 block w-full"
                        :value="old('author', $book->author)" />
                    <x-input-error class="mt-2" :messages="$errors->get('author')" />
                </div>

                {{-- Published Year --}}
                <div>
                    <x-input-label for="published_year" :value="__('Published Year')" />
                    <x-text-input id="published_year" name="published_year" type="number" class="mt-1 block w-full"
                        :value="old('published_year', $book->published_year)" />
                    <x-input-error class="mt-2" :messages="$errors->get('published_year')" />
                </div>

                {{-- Book Category --}}
                <style>
                    [x-cloak] {
                        display: none !important
                    }
                </style>

                <div x-data="categoryDropdown()" x-init="init();
                selected = {{ json_encode($book->category) }}" class="relative">
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
                            <div x-show="!loading && filtered.length === 0 && search.trim() === ''"
                                class="px-3 py-2 text-gray-500 text-sm italic">
                                No categories found.
                            </div>

                            {{-- Always show "Add New" option if input doesn't exactly match any existing category --}}
                            <div x-show="!loading && shouldShowAddNew" @click="addCategory"
                                class="px-3 py-2 cursor-pointer bg-green-50 text-green-700 font-semibold hover:bg-green-100 transition border-t border-gray-200">
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
                        <option value="" disabled>Select Source</option>
                        <option value="donated" {{ old('source', $book->source) === 'donated' ? 'selected' : '' }}>
                            Donated</option>
                        <option value="purchased" {{ old('source', $book->source) === 'purchased' ? 'selected' : '' }}>
                            Purchased
                        </option>
                        <option value="sponsored"
                            {{ old('source', $book->source) === 'sponsored' ? 'selected' : '' }}>Sponsored
                        </option>
                        <option value="other" {{ old('source', $book->source) === 'other' ? 'selected' : '' }}>Other
                        </option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('source')" />
                </div>

                {{-- Total Copies --}}
                <div>
                    <x-input-label for="total_copies" :value="__('Book Amount (Total Copies)')" />
                    <x-text-input id="total_copies" name="total_copies" type="number" class="mt-1 block w-full"
                        :value="old('total_copies', $book->total_copies)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('total_copies')" />
                </div>

                {{-- Available Copies --}}
                <div>
                    <x-input-label for="available_copies" :value="__('Available Copies')" />
                    <x-text-input id="available_copies" name="available_copies" type="number"
                        class="mt-1 block w-full" :value="old('available_copies', $book->available_copies)" required />
                    <div class="mt-1 text-sm text-gray-500">Must not exceed total copies</div>
                    <x-input-error class="mt-2" :messages="$errors->get('available_copies')" />
                </div>

                {{-- Book Description --}}
                <div class="col-span-2">
                    <x-input-label for="description" :value="__('Book Description')" />

                    <textarea id="description" name="description" rows="5"
                        class="mt-1 block w-full px-3 py-2 bg-background-light border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary/20 inset-shadow-sm inset-shadow-gray-300"
                        placeholder="Write a short summary or description of the book...">{{ old('description', $book->description) }}</textarea>

                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-4">
                <x-primary-button>
                    Update Book
                </x-primary-button>
            </div>
        </form>

        {{-- DELETE BOOK SECTION --}}
        <div class="mt-8 p-6 bg-white/10 backdrop-blur-sm shadow rounded-md" x-data="{ showDeleteBookModal: false }">

            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-red-600">Danger Zone</h2>
                    <p class="text-sm text-gray-500">This action is permanent.</p>
                </div>

                <x-danger-button @click="showDeleteBookModal = true">
                    Delete Book
                </x-danger-button>
            </div>

            {{-- Include Modal --}}
            @include('pages.book.partials.delete-modal', ['book' => $book])
        </div>
    </div>



</x-app-layout>
