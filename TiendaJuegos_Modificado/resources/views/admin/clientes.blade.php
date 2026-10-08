@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="container" style="padding: 40px 20px;">
    <h1 class="titulo" style="text-align: center; font-size: 45px; margin-bottom: 40px;">👥 Listado de Clientes</h1>

    <form method="GET" action="{{ route('admin.clientes') }}" class="buscador" style="margin-bottom: 30px; display: flex; justify-content: center; gap: 15px;">
        <input type="text" name="buscar" placeholder="Buscar cliente..." value="{{ request('buscar') }}" style="background: #2d333d; padding: 12px 20px; border-radius: 30px; border: none; color: white; width: 300px;">
        <button type="submit" style="background: #ef4444; padding: 12px 25px; border-radius: 30px; border: none; color: white; cursor: pointer;">🔍 Buscar</button>
        @if(request('buscar'))
            <a href="{{ route('admin.clientes') }}" style="background: #ef4444; padding: 12px 25px; border-radius: 30px; color: white; text-decoration: none;">Limpiar</a>
        @endif
    </form>

    @if($clientes->count() > 0)
    <table class="tabla" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #991b1b;">
                <th style="padding: 15px;">ID</th>
                <th style="padding: 15px;">Nombre</th>
                <th style="padding: 15px;">Correo</th>
                <th style="padding: 15px;">Edad</th>
                <th style="padding: 15px;">Compras</th>
                <th style="padding: 15px;">Registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clientes as $cliente)
            <tr style="background: #2d333d;">
                <td style="padding: 12px; text-align: center;">#{{ $cliente->id }}</td>
                <td style="padding: 12px;">{{ $cliente->name }}</td>
                <td style="padding: 12px;">{{ $cliente->email }}</td>
                <td style="padding: 12px; text-align: center;">{{ $cliente->edad ?? 'No especificada' }}</td>
                <td style="padding: 12px; text-align: center;">{{ $cliente->compras->count() }}</td>
                <td style="padding: 12px; text-align: center;">{{ \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination" style="display: flex; justify-content: center; margin-top: 40px;">
        {{ $clientes->links() }}
    </div>
    @else
    <div style="text-align: center; padding: 60px; background: #2d333d; border-radius: 15px;">
        <p style="font-size: 24px; color: #d0d5dd;">No hay clientes registrados.</p>
    </div>
    @endif
</div>
@endsection