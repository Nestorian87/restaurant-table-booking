<div class="btn-group rounded-pill border overflow-hidden shadow-sm" role="group" style="background-color: #f8f9fa;">
    <a href="{{ route('lang.switch', 'en') }}"
       class="btn btn-sm px-3 border-0 {{ app()->getLocale() === 'en' ? 'btn-success text-white' : 'btn-light text-success' }}"
       title="English">
        EN
    </a>
    <a href="{{ route('lang.switch', 'uk') }}"
       class="btn btn-sm px-3 border-0 {{ app()->getLocale() === 'uk' ? 'btn-warning text-dark' : 'btn-light text-warning' }}"
       title="Українська">
        UK
    </a>
</div>
