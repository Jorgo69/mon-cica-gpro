<?php

namespace App\Livewire\VBeta\Admin;

use App\Enums\Currency;
use App\Livewire\Traits\WithToastNotifications;
use App\Models\ExchangeRate;
use Livewire\Component;
use Livewire\WithPagination;

class ExchangeRateManagementLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public string $search = '';
    public bool $showModal = false;
    public ?string $editingId = null;

    // Form
    public string $base_currency = 'XOF';
    public string $target_currency = 'EUR';
    public string $rate = '';
    public string $effective_date = '';

    protected function rules(): array
    {
        return [
            'base_currency' => 'required|in:' . implode(',', array_column(Currency::cases(), 'value')),
            'target_currency' => 'required|in:' . implode(',', array_column(Currency::cases(), 'value')) . '|different:base_currency',
            'rate' => 'required|numeric|gt:0',
            'effective_date' => 'required|date',
        ];
    }

    public function openModal(?string $id = null): void
    {
        $this->resetValidation();

        if ($id) {
            $rate = ExchangeRate::findOrFail($id);
            $this->editingId = $id;
            $this->base_currency = $rate->base_currency->value;
            $this->target_currency = $rate->target_currency->value;
            $this->rate = (string) $rate->rate;
            $this->effective_date = $rate->effective_date->format('Y-m-d');
        } else {
            $this->editingId = null;
            $this->base_currency = 'XOF';
            $this->target_currency = 'EUR';
            $this->rate = '';
            $this->effective_date = now()->format('Y-m-d');
        }

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'base_currency' => $this->base_currency,
            'target_currency' => $this->target_currency,
            'rate' => (float) $this->rate,
            'effective_date' => $this->effective_date,
            'organization_id' => auth()->user()->organization_id,
            'creator_user_id' => auth()->id(),
        ];

        if ($this->editingId) {
            ExchangeRate::findOrFail($this->editingId)->update($data);
            $this->notifyToast('success', 'Taux de change mis a jour.');
        } else {
            ExchangeRate::create($data);
            $this->notifyToast('success', 'Taux de change ajoute.');
        }

        $this->showModal = false;
    }

    public function delete(string $id): void
    {
        ExchangeRate::findOrFail($id)->delete();
        $this->notifyToast('success', 'Taux de change supprime.');
    }

    public function render()
    {
        $rates = ExchangeRate::query()
            ->when($this->search, fn($q) => $q->where('base_currency', 'like', "%{$this->search}%")
                ->orWhere('target_currency', 'like', "%{$this->search}%"))
            ->orderByDesc('effective_date')
            ->paginate(15);

        return view('livewire.v-beta.admin.exchange-rate-management-livewire', [
            'rates' => $rates,
            'currencies' => Currency::cases(),
        ]);
    }
}
