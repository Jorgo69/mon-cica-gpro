<?php

namespace App\Livewire\V1\Attachments;

use App\Livewire\Traits\WithToastNotifications;
use App\Models\Attachment;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AttachmentSectionLivewire extends Component
{
    use WithFileUploads, WithToastNotifications;

    public string $attachableType;
    public string $attachableId;

    public array $newFiles = [];
    public string $description = '';

    protected function rules(): array
    {
        return [
            'newFiles' => 'required|array|min:1',
            'newFiles.*' => 'file|max:51200', // 50MB
            'description' => 'nullable|string|max:255',
        ];
    }

    public function mount(string $attachableType, string $attachableId): void
    {
        $this->attachableType = $attachableType;
        $this->attachableId = $attachableId;
    }

    public function upload(): void
    {
        $this->validate();

        foreach ($this->newFiles as $file) {
            $path = $file->store('attachments/' . date('Y/m'), 'local');

            Attachment::create([
                'attachable_type' => $this->attachableType,
                'attachable_id' => $this->attachableId,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $this->description ?: null,
            ]);
        }

        $count = count($this->newFiles);
        $this->newFiles = [];
        $this->description = '';
        $this->notifyToast('success', "{$count} fichier(s) ajoute(s).");
    }

    public function delete(string $id): void
    {
        $attachment = Attachment::find($id);
        if (!$attachment) return;

        if ($attachment->user_id !== auth()->id() && !auth()->user()->can('manage-users')) {
            $this->notifyToast('error', 'Non autorise.');
            return;
        }

        $attachment->delete();
        $this->notifyToast('success', 'Fichier supprime.');
    }

    public function download(string $id)
    {
        $attachment = Attachment::findOrFail($id);

        if (!Storage::exists($attachment->file_path)) {
            $this->notifyToast('error', 'Fichier introuvable.');
            return;
        }

        return Storage::download($attachment->file_path, $attachment->file_name);
    }

    public function render()
    {
        $attachments = Attachment::where('attachable_type', $this->attachableType)
            ->where('attachable_id', $this->attachableId)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        return view('livewire.v1.attachments.attachment-section-livewire', [
            'attachments' => $attachments,
        ]);
    }
}
