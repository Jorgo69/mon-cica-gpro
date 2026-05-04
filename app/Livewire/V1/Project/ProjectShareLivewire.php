<?php

namespace App\Livewire\V1\Project;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\Project;
use App\Models\ShareToken;
use Livewire\Component;

class ProjectShareLivewire extends Component
{
    use WithToastNotifications;

    public string $projectId;
    public string $label = '';
    public string $expiresIn = '30';
    public bool $showModal = false;

    public function createLink()
    {
        $this->validate([
            'label' => 'nullable|string|max:100',
            'expiresIn' => 'required|in:7,30,90,365,0',
        ]);

        $expiresAt = $this->expiresIn !== '0'
            ? now()->addDays((int) $this->expiresIn)
            : null;

        $token = ShareToken::create([
            'project_id' => $this->projectId,
            'created_by_user_id' => auth()->id(),
            'label' => $this->label ?: null,
            'expires_at' => $expiresAt,
        ]);

        $this->reset('label', 'expiresIn');
        $this->dispatch('copy-to-clipboard', url: $token->getUrl());
        $this->notifyToast('success', __('shared.link_created_copied'));
    }

    public function toggleLink(string $tokenId)
    {
        $token = ShareToken::where('project_id', $this->projectId)->findOrFail($tokenId);
        $token->update(['is_active' => ! $token->is_active]);

        $message = $token->is_active ? __('shared.link_activated') : __('shared.link_deactivated');
        $this->notifyToast('success', $message);
    }

    public function deleteLink(string $tokenId)
    {
        ShareToken::where('project_id', $this->projectId)->findOrFail($tokenId)->delete();
        $this->notifyToast('success', __('shared.link_deleted'));
    }

    public function render()
    {
        $tokens = ShareToken::where('project_id', $this->projectId)
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.v1.project.project-share-livewire', [
            'tokens' => $tokens,
        ]);
    }
}
