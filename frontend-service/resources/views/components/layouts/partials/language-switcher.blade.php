<div class="btn-group" role="group" aria-label="Language switcher">
    <a href="{{ route('lang.switch', 'en') }}"
       class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-success' : 'btn-outline-success' }}">
        🇬🇧 EN
    </a>
    <a href="{{ route('lang.switch', 'uk') }}"
       class="btn btn-sm {{ app()->getLocale() === 'uk' ? 'btn-warning' : 'btn-outline-warning' }}">
        🇺🇦 UA
    </a>
</div>
