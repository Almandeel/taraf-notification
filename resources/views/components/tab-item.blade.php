<li class="nav-item">
    <a class="nav-link {{ (session('active_tab') == $id) || (isset($active) && !session()->has('active_tab')) ? 'active' : '' }}" id="tabs-{{ $id }}-tab" data-toggle="pill" href="#tabs-{{ $id }}" role="tab"
        aria-controls="tabs-{{ $id }}" aria-selected="true">{{ $title }}</a>
</li>