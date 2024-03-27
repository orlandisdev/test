<div class='ml-5'><b>Menú Eureka</b></div>
<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fas fa-bars"></i>
</button>
@if (Auth::check())
    <div class="collapse navbar-collapse" id="navbarCollapse">
        Usuario conectado: {{ Auth::user() ->ds }}
        <br>Ruta: {{Route::current() ->getName() }}
        <br>Roles: <div id="roles-react">{{ route('api.permisos') }}</div>
        <ul class="navbar-nav ml-auto mr-5">
            <li class="nav-item active mr-3">
                <a class="nav-link" href="{{ route('home') }}" title='Inicio'>
                    <i class="fas fa-home"></i> Inicio
                </a>
            </li>
            @for($i = 1; $i <= 5; $i++)
                <li class="nav-item active mr-3">
                    <a href="{{ route('opcion'.$i) }}">Opcion {{ $i }}</a>
                </li>
            @endfor
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" title='Salir'> 
                    Salir
                </a>
            </li>
        </ul>
    </div>
    
    <div><b>Permisos por opciones en blade</b></div>
    @can('opc1')
        <div>botón permitido para opcion 1 <button tupe="button">boton 1</button></div>
    @endcan
    @can('opc2')
        <div>botón permitido para opcion 2 <button tupe="button">boton 2</button></div>
    @endcan
    @can('opc3')
        <div>botón permitido para opcion 3 <button tupe="button">boton 3</button></div>
    @endcan
    @can('opc4')
        <div>botón permitido para opcion 4 <button tupe="button">boton 4</button></div>
    @endcan
    @can('opc5')
        <div>botón permitido para opcion 5 <button tupe="button">boton 5</button></div>
    @endcan

@else
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav ml-auto mr-5">
            <li class="nav-item active mr-3">
                <a class="nav-link" href="{{ route('login') }}" title='Inicio'>
                    <i class="fas fa-home"></i> Entrar
                </a>
            </li>
        </ul>
    </div>
@endif
