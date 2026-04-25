<div class="space-y-6">
    <div class="space-y-6">
        {{-- RICH TEXT EDITORS --}}
        <div class="space-y-4">
            <div wire:ignore class="space-y-2">
                <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Contexte du Projet <span class="text-rose-500">*</span></label>
                <div class="rounded-xl overflow-hidden border shadow-sm @error('contextDescription') border-rose-500 ring-2 ring-rose-500/20 @else border-slate-200 dark:border-slate-700 @enderror">
                    <textarea id="contextDescription" class="summernote" data-field="contextDescription">{!! $contextDescription !!}</textarea>
                </div>
                @error('contextDescription') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror
            </div>

            <div wire:ignore class="space-y-2">
                <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Analyse du problème</label>
                <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
                    <textarea id="problemAnalysis" class="summernote" data-field="problemAnalysis">{!! $problemAnalysis !!}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div wire:ignore class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Stratégie</label>
                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
                        <textarea id="strategy" class="summernote" data-field="strategy">{!! $strategy !!}</textarea>
                    </div>
                </div>

                <div wire:ignore class="space-y-2">
                    <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Justification</label>
                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-sm">
                        <textarea id="justification" class="summernote" data-field="justification">{!! $justification !!}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- DOCUMENT UPLOAD --}}
        <div class="pt-8 border-t border-slate-100 dark:border-slate-800 space-y-4">
            <label class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.15em] ml-1">Documents Pertinents</label>
            <div class="p-8 border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-3xl bg-slate-50 dark:bg-slate-800/50 flex flex-col items-center justify-center text-center group hover:border-accent transition-all cursor-pointer relative">
                <input type="file" id="uploadedDocuments" wire:model="uploadedDocuments" multiple class="absolute inset-0 opacity-0 cursor-pointer" />
                <div class="w-12 h-12 rounded-full bg-white dark:bg-slate-900 shadow-sm flex items-center justify-center text-slate-400 group-hover:text-accent transition-colors mb-4">
                    <x-dynamic-component component="lucide-cloud-upload" class="w-5 h-5" />
                </div>
                <p class="text-[11px] font-bold text-slate-600 dark:text-slate-400 tracking-tight">Déposez vos fichiers ici ou <span class="text-accent underline">cliquez pour parcourir</span></p>
                <p class="text-[9px] text-slate-400 mt-1.5 uppercase tracking-[0.15em] font-black">Supporte PDF, Word, Excel, Images (Max 50MB)</p>
            </div>
            @error('uploadedDocuments.*') <p class="text-[10px] text-rose-500 font-bold italic mt-1.5 ml-1 uppercase tracking-tight">{{ $message }}</p> @enderror

            {{-- FILE LISTS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if (count($existingDocuments) > 0)
                    @foreach ($existingDocuments as $document)
                        <div class="flex items-center justify-between p-4 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm hover:border-accent transition-all group">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-slate-500 border border-slate-100 dark:border-slate-700">
                                    <x-dynamic-component component="lucide-file-text" class="w-3 h-3" />
                                </div>
                                <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 truncate group-hover:text-accent transition-colors">{{ $document['file_name'] ?? $document->file_name }}</span>
                            </div>
                            <button type="button" wire:click="removeExistingDocument('{{ $document['id'] ?? $document->id }}')" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                                <x-dynamic-component component="lucide-trash-2" class="w-2.5 h-2.5" />
                            </button>
                        </div>
                    @endforeach
                @endif

                @if (count($uploadedDocuments) > 0)
                    @foreach ($uploadedDocuments as $index => $file)
                        <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm group">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-white dark:bg-slate-900 flex items-center justify-center text-accent shadow-sm border border-slate-100 dark:border-slate-800">
                                    <x-dynamic-component component="lucide-cloud-upload" class="w-3 h-3" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200 truncate group-hover:text-accent transition-colors">{{ $file->getClientOriginalName() }}</p>
                                    <p class="text-[9px] text-slate-500 uppercase font-black tracking-widest mt-0.5">{{ round($file->getSize() / 1024 / 1024, 2) }} MB</p>
                                </div>
                            </div>
                            <button type="button" wire:click="removeUploadedFile({{ $index }})" class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                                <x-dynamic-component component="lucide-x" class="w-2.5 h-2.5" />
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@include('livewire.v-beta.components.include.index')
