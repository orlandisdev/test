@if(Session::has('flash-info'))
    <div class="alert alert-info m-2 alert-dismissible fade show">
        <i class="fas fa-info-circle"></i>
        {!! Session::get('flash-info') !!}
        <div class='float-end'><i class="far fa-window-close cerrar"></i></div>
    </div>
@endif
@if(Session::has('flash-success'))
    <div class="alert alert-success m-2 alert-dismissible fade show">
        <i class="fas fa-check"></i>
        {!! Session::get('flash-success') !!}
        <div class='float-end'><i class="far fa-window-close cerrar"></i></div>
    </div>
@endif
@if(Session::has('flash-warning'))
    <div class="alert alert-warning m-2 alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle"></i>
        {!! Session::get('flash-warning') !!}
        <div class='float-end'><i class="far fa-window-close cerrar"></i></div>
    </div>
@endif
@if(Session::has('flash-danger'))
    <div class="alert alert-danger m-2 alert-dismissible fade show">
        <i class="fas fa-hand-paper"></i>
        {!! Session::get('flash-danger') !!}
        <div class='float-end'><i class="far fa-window-close cerrar"></i></div>
    </div>
@endif
@php
    Session::forget('flash-info');
    Session::forget('flash-success');
    Session::forget('flash-warning');
    Session::forget('flash-danger');
@endphp