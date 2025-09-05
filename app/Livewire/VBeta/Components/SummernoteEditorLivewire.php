<?php

namespace App\Livewire\VBeta\Components;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mews\Purifier\Facades\Purifier;

class SummernoteEditorLivewire extends Component
{
     public $fieldName;
    public $content = '';
    public $placeholder;
    public $height = 200;

    public function mount($fieldName, $content = '', $placeholder = 'Écrivez ici...', $height = 200)
    {
        $this->fieldName   = $fieldName;
        $this->content     = $content;
        $this->placeholder = $placeholder;
        $this->height      = $height;
    }
    
    // composer require mews/purifier


    public function render()
    {
        return view('livewire.v-beta.components.summernote-editor-livewire');
    }
}
