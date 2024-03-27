@extends('layouts.main')

@section('contenido')
    <main role="main" class="container">
        <form method="post" class='mt-5'>
            @csrf
            <div class='row'>
                <div class='col-12 mb-5'>
                    <h1 class='mb-3'>Identificación</h1>
                </div>
                <div class='col-12'>
                    Usuario de CAS: <input type="text" name="username" id="username"> prueba
                </div>
                <div class='col-12 text-center mt-3'>
                    <button type="submit">Entrar</button>
                </div>
            </div>
        </form>
    </main>
@endsection
