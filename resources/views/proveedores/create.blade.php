@extends('layouts.app')

@section('title', 'Lista de Productos')
@section('header', 'Lista de Productos')

@section('content')
    <div>
        Lista de proveedores
    </div>
    <div class="bg-white">
        <form action="{{ route('proveedores.store') }}" method="POST">
            @csrf

            <input type="text" name="name" placeholder="Name">
            <br><br>

            <button type="submit" style="background: red;">
                Guardar
            </button>
        </form>
    </div>
@endsection
