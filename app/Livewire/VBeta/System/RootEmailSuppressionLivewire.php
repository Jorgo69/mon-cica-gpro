<?php

namespace App\Livewire\VBeta\System;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\EmailSuppression;
use Livewire\Component;
use Livewire\WithPagination;

class RootEmailSuppressionLivewire extends Component
{
    use WithPagination, WithToastNotifications;

    public $search = '';
    public $reasonFilter = '';

    public $newEmail = '';
    public $newReason = 'bounced';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedReasonFilter()
    {
        $this->resetPage();
    }

    public function unsuppress($id)
    {
        EmailSuppression::findOrFail($id)->delete();

        $this->notifyToast('success', 'L\'email a été retiré de la liste de suppression.', 'Email retiré');
    }

    public function addSuppression()
    {
        $this->validate([
            'newEmail' => 'required|email',
        ]);

        EmailSuppression::suppress($this->newEmail, $this->newReason);

        $this->reset(['newEmail', 'newReason']);
        $this->newReason = 'bounced';

        $this->notifyToast('success', 'L\'email a été ajouté à la liste de suppression.', 'Email ajouté');
    }

    public function render()
    {
        $totalBounced = EmailSuppression::where('reason', 'bounced')->count();
        $totalUnsubscribed = EmailSuppression::where('reason', 'unsubscribed')->count();
        $totalComplained = EmailSuppression::where('reason', 'complained')->count();

        $suppressions = EmailSuppression::query()
            ->when($this->search, fn ($q) => $q->where('email', 'like', '%' . $this->search . '%'))
            ->when($this->reasonFilter, fn ($q) => $q->where('reason', $this->reasonFilter))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.v-beta.system.root-email-suppression-livewire', [
            'suppressions' => $suppressions,
            'totalBounced' => $totalBounced,
            'totalUnsubscribed' => $totalUnsubscribed,
            'totalComplained' => $totalComplained,
        ]);
    }
}
