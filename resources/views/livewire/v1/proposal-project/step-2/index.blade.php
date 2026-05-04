<div class="space-y-6">
    <div class="space-y-6">
        {{-- RICH TEXT EDITORS avec AI --}}
        <div class="space-y-4">
            <div>
                <x-ui.rich-editor
                    name="contextDescription"
                    :value="$contextDescription"
                    label="Contexte du Projet"
                    :required="true"
                    placeholder="Decrivez le contexte du projet..."
                >
                    <x-slot:afterLabel><x-ui.ai-field-button field="contextDescription" /></x-slot:afterLabel>
                </x-ui.rich-editor>
                <x-ui.ai-suggestion field="contextDescription" :activeField="$aiActiveField" :suggestion="$aiSuggestion" :loading="$aiLoading" />
            </div>

            <div>
                <x-ui.rich-editor
                    name="problemAnalysis"
                    :value="$problemAnalysis"
                    label="Analyse du probleme"
                    placeholder="Decrivez la problematique..."
                >
                    <x-slot:afterLabel><x-ui.ai-field-button field="problemAnalysis" /></x-slot:afterLabel>
                </x-ui.rich-editor>
                <x-ui.ai-suggestion field="problemAnalysis" :activeField="$aiActiveField" :suggestion="$aiSuggestion" :loading="$aiLoading" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-ui.rich-editor
                        name="strategy"
                        :value="$strategy"
                        label="Strategie"
                        placeholder="Decrivez la strategie..."
                        :height="150"
                    >
                        <x-slot:afterLabel><x-ui.ai-field-button field="strategy" /></x-slot:afterLabel>
                    </x-ui.rich-editor>
                    <x-ui.ai-suggestion field="strategy" :activeField="$aiActiveField" :suggestion="$aiSuggestion" :loading="$aiLoading" />
                </div>

                <div>
                    <x-ui.rich-editor
                        name="justification"
                        :value="$justification"
                        label="Justification"
                        placeholder="Justifiez le projet..."
                        :height="150"
                    >
                        <x-slot:afterLabel><x-ui.ai-field-button field="justification" /></x-slot:afterLabel>
                    </x-ui.rich-editor>
                    <x-ui.ai-suggestion field="justification" :activeField="$aiActiveField" :suggestion="$aiSuggestion" :loading="$aiLoading" />
                </div>
            </div>
        </div>

        {{-- DOCUMENT UPLOAD --}}
        <div class="pt-8 border-t border-border-light space-y-4">
            <label class="block text-[11px] font-black text-subtle uppercase tracking-[0.15em] ml-1">Documents Pertinents</label>
            <div class="p-8 border-2 border-dashed border-border rounded-3xl bg-surface/50 flex flex-col items-center justify-center text-center group hover:border-accent transition-all cursor-pointer relative">
                <input type="file" id="uploadedDocuments" wire:model="uploadedDocuments" multiple class="absolute inset-0 opacity-0 cursor-pointer" />
                <div class="w-12 h-12 rounded-full bg-card shadow-sm flex items-center justify-center text-muted group-hover:text-accent transition-colors mb-4">
                    <x-lucide-cloud-upload class="w-5 h-5" />
                </div>
                <p class="text-[11px] font-bold text-subtle tracking-tight">Deposez vos fichiers ici ou <span class="text-accent underline">cliquez pour parcourir</span></p>
                <p class="text-[9px] text-muted mt-1.5 uppercase tracking-[0.15em] font-black">Supporte PDF, Word, Excel, Images (Max 50MB)</p>
            </div>
            @error('uploadedDocuments.*') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror

            {{-- FILE LISTS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if (count($existingDocuments) > 0)
                    @foreach ($existingDocuments as $document)
                        <div class="flex items-center justify-between p-4 bg-card border border-border rounded-2xl shadow-sm hover:border-accent transition-all group">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-surface flex items-center justify-center text-subtle border border-border-light">
                                    <x-lucide-file-text class="w-3 h-3" />
                                </div>
                                <span class="text-[10px] font-bold text-body truncate group-hover:text-accent transition-colors">{{ $document['file_name'] ?? $document->file_name }}</span>
                            </div>
                            <button type="button" wire:click="removeExistingDocument('{{ $document['id'] ?? $document->id }}')" class="w-8 h-8 rounded-full flex items-center justify-center text-muted hover:text-rose-500 hover:bg-rose-50 transition-all">
                                <x-lucide-trash-2 class="w-2.5 h-2.5" />
                            </button>
                        </div>
                    @endforeach
                @endif

                @if (count($uploadedDocuments) > 0)
                    @foreach ($uploadedDocuments as $index => $file)
                        <div class="flex items-center justify-between p-4 bg-surface/50 border border-border rounded-2xl shadow-sm group">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-card flex items-center justify-center text-accent shadow-sm border border-border-light">
                                    <x-lucide-cloud-upload class="w-3 h-3" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-heading truncate group-hover:text-accent transition-colors">{{ $file->getClientOriginalName() }}</p>
                                    <p class="text-[9px] text-subtle uppercase font-black tracking-widest mt-0.5">{{ round($file->getSize() / 1024 / 1024, 2) }} MB</p>
                                </div>
                            </div>
                            <button type="button" wire:click="removeUploadedFile({{ $index }})" class="w-8 h-8 rounded-full flex items-center justify-center text-muted hover:text-rose-500 hover:bg-rose-50 transition-all">
                                <x-lucide-x class="w-2.5 h-2.5" />
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
