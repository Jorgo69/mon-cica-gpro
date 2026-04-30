<div wire:ignore>
    <textarea
        id="summernote-{{ $fieldName }}"
        class="summernote"
        data-field="{{ $fieldName }}"
        placeholder="{{ $placeholder }}"
    >{!! $content !!}</textarea>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/summernote/summernote-lite.min.js') }}"></script>
<script>
    function initSummernotes() {
        document.querySelectorAll('textarea.summernote:not([data-initialized="1"])').forEach(function (el) {
            el.setAttribute('data-initialized', '1');

            $(el).summernote({
                placeholder: el.getAttribute('placeholder') || 'Écrivez ici...',
                tabsize: 2,
                height: parseInt(el.dataset.height) || 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // ⚡ Émettre vers le parent
            $(el).on('summernote.change', function (we, contents) {
                window.Livewire.dispatch('summernoteUpdated', {
                    field: el.dataset.field,
                    content: contents
                });
            });
        });
    }

    document.addEventListener('livewire:load', initSummernotes);
    document.addEventListener('livewire:navigated', initSummernotes);
</script>
@endpush
