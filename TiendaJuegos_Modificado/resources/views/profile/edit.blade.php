@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="container" style="padding: 40px 20px;">
    <h1 class="titulo" style="text-align: center; font-size: 45px; margin-bottom: 40px; color: #ef4444;">👤 Mi Perfil</h1>

    <div class="form-card" style="max-width: 600px; margin: 0 auto; background: #2d333d; padding: 35px; border-radius: 20px;">
        


        @if($errors->any())
            <div style="background: #ef4444; color: white; padding: 12px; border-radius: 10px; margin-bottom: 20px;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" novalidate>
            @csrf
            @method('PATCH')

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #ef4444;">Nombre</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required 
                       style="width: 100%; padding: 12px; border-radius: 10px; border: none; background: #1f232b; color: white;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #ef4444;">Correo electrónico</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required 
                       style="width: 100%; padding: 12px; border-radius: 10px; border: none; background: #1f232b; color: white;">
            </div>

            <div style="margin-top: 30px;">
                <button type="submit" style="width: 100%; background: #dc2626; color: white; padding: 14px; border-radius: 10px; font-weight: bold; cursor: pointer;">
                    💾 Actualizar Perfil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection