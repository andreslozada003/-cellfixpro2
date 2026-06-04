@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Editar Categoria</h1>
        <p class="text-gray-500">{{ $categoria->nombre }}</p>
    </div>
    <a href="{{ route('categorias.index') }}" class="text-gray-600 hover:text-gray-700">
        <i class="fas fa-arrow-left mr-1"></i> Volver
    </a>
</div>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form action="{{ route('categorias.update', $categoria) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                    Nombre <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $categoria->nombre) }}" required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div>
                <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Categoria Padre</label>
                <select name="parent_id" id="parent_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Ninguna (Categoria Principal)</option>
                    @foreach($categoriasPadre as $cat)
                        <option value="{{ $cat->id }}" {{ old('parent_id', $categoria->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="icono" class="block text-sm font-medium text-gray-700 mb-1">Icono (FontAwesome)</label>
                <input type="text" name="icono" id="icono" value="{{ old('icono', $categoria->icono) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Ej: mobile-alt, box, tools...">
            </div>

            <div>
                <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                <input type="color" name="color" id="color" value="{{ old('color', $categoria->color ?: '#3b82f6') }}"
                    class="w-full h-10 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div>
                <label for="orden" class="block text-sm font-medium text-gray-700 mb-1">Orden</label>
                <input type="number" name="orden" id="orden" value="{{ old('orden', $categoria->orden) }}" min="0"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div>
                <label class="flex items-center mt-6">
                    <input type="checkbox" name="activo" value="1" {{ old('activo', $categoria->activo) ? 'checked' : '' }}
                        class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                    <span class="ml-2 text-sm text-gray-700">Categoria activa</span>
                </label>
            </div>

            <div class="md:col-span-2">
                <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen de la categoria</label>
                @if($categoria->imagen_url)
                    <div class="mb-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                        <div class="flex items-center gap-4">
                            <img src="{{ $categoria->imagen_url }}" alt="" class="h-24 w-24 rounded-lg object-cover border border-gray-200 bg-white">
                            <div>
                                <p class="text-sm font-medium text-gray-700">Imagen actual</p>
                                <p class="text-xs text-gray-500">Selecciona otra imagen para reemplazarla.</p>
                            </div>
                        </div>
                    </div>
                @endif
                <input type="file" name="imagen" id="imagen" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                @error('imagen')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="md:col-span-2">
                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripcion</label>
                <textarea name="descripcion" id="descripcion" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('descripcion', $categoria->descripcion) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end space-x-4 mt-6 pt-6 border-t border-gray-200">
            <a href="{{ route('categorias.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
                <i class="fas fa-save mr-2"></i>Guardar Cambios
            </button>
        </div>
    </form>
</div>
@endsection
