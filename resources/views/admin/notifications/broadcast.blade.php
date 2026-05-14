<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Broadcast Notification') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white shadow-lg sm:rounded-2xl p-8 border border-gray-100">
                <div class="mb-6 pb-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Send to All Users</h3>
                    <p class="text-sm text-gray-500 mt-1">This will send an in-app notification to every registered user. You can also optionally send it via WhatsApp.</p>
                </div>

                <form method="POST" action="{{ route('admin.notifications.broadcast.store') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Notification Title</label>
                        <input type="text" name="title" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition" placeholder="Ex: Maintenance Notice / Holiday Promo" value="{{ old('title') }}" required>
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 mb-1">Message Content</label>
                        <textarea name="message" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition" rows="5" placeholder="Type your message here..." required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-100">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="send_whatsapp" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5" value="1" {{ old('send_whatsapp') ? 'checked' : '' }}>
                            <div class="ml-3">
                                <span class="block text-sm font-bold text-indigo-900">Also send via WhatsApp</span>
                                <span class="block text-xs text-indigo-700 mt-0.5">Warning: This will use Fonnte API quotas and may take a moment to process for many users.</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('dashboard') }}" class="px-6 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition">Cancel</a>
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-lg transition font-bold" onclick="return confirm('Are you sure you want to broadcast this to ALL users?');">
                            Send Broadcast
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
