<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Venue') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg sm:rounded-2xl p-8 border border-gray-100">

                <h3 class="text-lg font-bold text-gray-900 mb-6 pb-4 border-b border-gray-100">Edit Venue Information</h3>

                <form method="POST" action="{{ route('admin.venues.update', $venue) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Venue Name</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition" value="{{ old('name', $venue->name) }}" required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Address</label>
                        <textarea name="address" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition" rows="3">{{ old('address', $venue->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-2">Venue Image</label>
                        
                        @if ($venue->image)
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-1">Current Image:</p>
                                <img src="{{ asset('storage/' . $venue->image) }}" class="h-32 w-auto object-cover rounded-lg border border-gray-200">
                            </div>
                        @endif

                        <div class="relative">
                            <input type="file" name="image" id="image" accept="image/*" class="w-full block text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                        </div>
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Leave empty if you don't want to change. Max 2MB. Format: JPEG, PNG, JPG, GIF</p>

                        <!-- Image Preview -->
                        <div id="imagePreview" class="mt-3 hidden">
                            <p class="text-xs text-gray-500 mb-1">New Image Preview:</p>
                            <img id="previewImg" src="" alt="Preview" class="h-32 w-auto object-cover rounded-lg border border-gray-200">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.venues.index') }}" class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-black text-white rounded-lg hover:bg-gray-800 shadow-lg transition font-bold">Update Venue</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');

        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    imagePreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>
