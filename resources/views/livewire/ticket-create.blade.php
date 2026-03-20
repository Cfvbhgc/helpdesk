<x-layouts.app title="Create Ticket">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create New Ticket</h1>
            <p class="mt-1 text-sm text-gray-500">Describe your issue and we'll get back to you as soon as possible.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-50 p-4">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
            <form wire:submit="createTicket" class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                    <div class="mt-2">
                        <input type="text" wire:model="title" id="title"
                               placeholder="Brief summary of the issue"
                               class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium leading-6 text-gray-900">Priority</label>
                    <div class="mt-2">
                        <select wire:model="priority" id="priority"
                                class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="low">Low (SLA: 72 hours)</option>
                            <option value="medium" selected>Medium (SLA: 24 hours)</option>
                            <option value="high">High (SLA: 8 hours)</option>
                            <option value="critical">Critical (SLA: 4 hours)</option>
                        </select>
                    </div>
                    @error('priority')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- SLA Info -->
                    <div class="mt-2 rounded-md bg-blue-50 p-3">
                        <p class="text-xs text-blue-700">
                            <strong>SLA Policy:</strong>
                            Critical = 4h | High = 8h | Medium = 24h | Low = 72h
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                    <div class="mt-2">
                        <textarea wire:model="description" id="description" rows="6"
                                  placeholder="Provide detailed information about your issue..."
                                  class="block w-full rounded-md border-0 py-2 px-3 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                    </div>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-x-4">
                    <a href="{{ route('tickets.index') }}" class="text-sm font-semibold leading-6 text-gray-900">Cancel</a>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50">
                        <span wire:loading.remove>Create Ticket</span>
                        <span wire:loading>Creating...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
