@extends('layouts.app')

@section('title', 'Ingresar Tecnico')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Ingresar Tecnico</h1>
        <p class="text-gray-500">Registra un tecnico para asignarlo en una nueva orden</p>
    </div>
    <a href="{{ route('reparaciones.create') }}" class="text-gray-600 hover:text-gray-700">
        <i class="fas fa-arrow-left mr-1"></i> Volver a nueva orden
    </a>
</div>
@endsection

@section('content')
<div class="max-w-3xl bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form action="{{ route('reparaciones.tecnicos.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre del tecnico <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                    placeholder="Ej: Carlos Ramirez">
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Telefono</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-500 @enderror"
                    placeholder="Ej: 3001234567">
                @error('phone')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo opcional</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror"
                    placeholder="Solo si quieres guardarle un correo">
                <p class="text-xs text-gray-500 mt-1">Si lo dejas vacio, el sistema crea un correo interno para poder listarlo como tecnico.</p>
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="rounded-lg bg-primary-50 border border-primary-100 p-4 text-sm text-primary-800">
            <i class="fas fa-info-circle mr-1"></i>
            Al guardar, este tecnico aparecera automaticamente en el selector de tecnico asignado de la nueva orden.
        </div>

        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
            <a href="{{ route('reparaciones.create') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Guardar y crear orden
            </button>
        </div>
    </form>
</div>
@endsection
