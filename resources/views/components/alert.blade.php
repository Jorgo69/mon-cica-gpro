{{-- resources/views/components/alert.blade.php --}}

@if(session($sessionKey))
    <div class="alert-container" data-duration="{{ $duration ?? 5000 }}">
        <div class="bg-{{ $bgColor }}-100 border-t-4 border-{{ $borderColor }}-500 rounded-b text-{{ $textColor }}-900 px-4 py-3 my-2 shadow-md relative" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 {{ $iconColor }} mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        {!! $iconSvg !!}
                    </svg>
                </div>
                <div>
                    <p class="font-bold">{{ $title }}</p>
                    <p class="text-sm">{{ session($sessionKey) }}</p>
                </div>
            </div>
        </div>
    </div>
@endif