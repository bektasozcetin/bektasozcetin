<span class="flex items-center gap-1.5">
    <button type="button" class="card inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full text-c0" @click="copyLink" aria-label="{{ __('site.notes.copy_link') }}" title="{{ __('site.notes.copy_link') }}"><x-ico name="link" /></button>
    <a class="card inline-flex h-11 min-w-11 items-center justify-center rounded-full px-3.5 font-mono text-xs font-semibold text-c0 no-underline" target="_blank" rel="noopener" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($url) }}" aria-label="LinkedIn">in</a>
    <a class="card inline-flex h-11 min-w-11 items-center justify-center rounded-full px-3.5 font-mono text-xs font-semibold text-c0 no-underline" target="_blank" rel="noopener" href="https://x.com/intent/post?url={{ urlencode($url) }}&text={{ urlencode($title) }}" aria-label="X">X</a>
    <a class="card inline-flex h-11 min-w-11 items-center justify-center rounded-full px-3.5 font-mono text-xs font-semibold text-c0 no-underline" target="_blank" rel="noopener" href="https://wa.me/?text={{ urlencode($title.' '.$url) }}" aria-label="WhatsApp">WA</a>
</span>
