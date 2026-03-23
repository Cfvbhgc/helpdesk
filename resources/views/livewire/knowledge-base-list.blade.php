<x-layouts.app title="Knowledge Base">
    <div>
        @if($selectedArticle)
            <!-- Article Detail View -->
            <div class="max-w-3xl mx-auto">
                <button wire:click="backToList" class="mb-4 text-sm text-indigo-600 hover:text-indigo-800">
                    &larr; Back to Knowledge Base
                </button>

                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-8">
                    <div class="mb-4">
                        <span class="inline-flex items-center rounded-full bg-indigo-100 px-3 py-1 text-xs font-medium text-indigo-700">
                            {{ $selectedArticle->category }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $selectedArticle->title }}</h1>

                    <div class="text-sm text-gray-500 mb-6">
                        Last updated: {{ $selectedArticle->updated_at->format('M d, Y') }}
                    </div>

                    <div class="prose prose-indigo max-w-none">
                        {!! nl2br(e($selectedArticle->content)) !!}
                    </div>
                </div>
            </div>
        @else
            <!-- Article List View -->
            <div class="max-w-4xl mx-auto">
                <div class="mb-8 text-center">
                    <h1 class="text-3xl font-bold text-gray-900">Knowledge Base</h1>
                    <p class="mt-2 text-gray-600">Find answers to common questions and learn how to use HelpDesk.</p>
                </div>

                <!-- Search and Filters -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-8">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="sm:col-span-2">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                   placeholder="Search articles..."
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                        </div>
                        <div>
                            <select wire:model.live="categoryFilter"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-2 px-3 border">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Articles Grid -->
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    @forelse($articles as $article)
                        <div wire:click="selectArticle({{ $article->id }})"
                             class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:border-indigo-300 hover:shadow-md transition-all cursor-pointer">
                            <div class="mb-2">
                                <span class="inline-flex items-center rounded-full bg-indigo-100 px-2.5 py-0.5 text-xs font-medium text-indigo-700">
                                    {{ $article->category }}
                                </span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $article->title }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-3">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                            <div class="mt-4">
                                <span class="text-sm text-indigo-600 font-medium">Read more &rarr;</span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-2 text-center py-12">
                            <p class="text-gray-500">No articles found. Try adjusting your search.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
