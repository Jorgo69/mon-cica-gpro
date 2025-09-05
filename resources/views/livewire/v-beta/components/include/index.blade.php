@push('styles')
<link rel="stylesheet" href="{{ asset('assets/summernote/summernote-lite.min.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/summernote/summernote-lite.min.js') }}"></script>
<script>
    function initSummernotes() {
        console.log("initSummernotes appelé !");
    
        document.querySelectorAll('textarea.summernote:not([data-initialized="1"])').forEach(function (el) {
            el.setAttribute('data-initialized', '1');

            $(el).summernote({
                placeholder: el.getAttribute('placeholder') || 'Écrivez ici...',
                tabsize: 2,
                height: 200,
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

            // Synchroniser avec Livewire
            $(el).on('summernote.change', function (we, contents) {
                @this.set(el.dataset.field, contents);
            });
        });
    }

    // Compatibilité Livewire v2/v3
    document.addEventListener('livewire:load', initSummernotes);
    document.addEventListener('livewire:navigated', initSummernotes);
</script>
@endpush