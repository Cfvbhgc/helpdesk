<?php

namespace App\Http\Livewire;

use App\Models\KnowledgeBase;
use Livewire\Component;

class KnowledgeBaseList extends Component
{
    public string $search = '';
    public string $categoryFilter = '';
    public ?KnowledgeBase $selectedArticle = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    /**
     * Select an article to display.
     */
    public function selectArticle(int $id): void
    {
        $this->selectedArticle = KnowledgeBase::find($id);
    }

    /**
     * Go back to the article list.
     */
    public function backToList(): void
    {
        $this->selectedArticle = null;
    }

    public function render()
    {
        $articles = KnowledgeBase::query()
            ->published()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                      ->orWhere('content', 'like', "%{$this->search}%");
                });
            })
            ->when($this->categoryFilter, fn ($query) => $query->category($this->categoryFilter))
            ->orderBy('title')
            ->get();

        $categories = KnowledgeBase::published()
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values();

        return view('livewire.knowledge-base-list', [
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }
}
