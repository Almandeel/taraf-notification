<div class="tab-pane fade {{ (session('active_tab') == $id) || (isset($active) && !session()->has('active_tab')) ? 'active show' : '' }}" id="tabs-{{ $id }}" role="tabpanel" aria-labelledby="tabs-{{ $id }}-tab">
    {!! $content !!}
</div>