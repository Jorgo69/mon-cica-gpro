<?php

namespace App\Livewire\VBeta;

use Livewire\Component;

class FaqLivewire extends Component
{
    public string $search = '';
    public string $activeCategory = 'all';

    public function render()
    {
        $categories = __('faq.categories');
        $allItems = __('faq.items');

        $items = collect($allItems)
            ->when($this->activeCategory !== 'all', fn ($c) => $c->where('category', $this->activeCategory))
            ->when($this->search, function ($c) {
                $term = mb_strtolower($this->search);
                return $c->filter(fn ($item) =>
                    str_contains(mb_strtolower($item['q']), $term) ||
                    str_contains(mb_strtolower(strip_tags($item['a'])), $term)
                );
            })
            ->values();

        return view('livewire.v-beta.faq-livewire', [
            'categories' => $categories,
            'items' => $items,
        ]);
    }
}
