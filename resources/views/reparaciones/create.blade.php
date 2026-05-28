@extends('layouts.app')

@section('title', 'Nueva Reparación')

@section('page-header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Nueva Orden de Reparación</h1>
        <p class="text-gray-500">Registra una nueva reparación</p>
    </div>
    <a href="{{ route('reparaciones.index') }}" class="text-gray-600 hover:text-gray-700">
        <i class="fas fa-arrow-left mr-1"></i> Volver
    </a>
</div>
@endsection

@section('content')
<form action="{{ route('reparaciones.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Cliente -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-user mr-2 text-primary-600"></i>Cliente
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Cliente <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="cliente_id" id="cliente_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('cliente_id') border-red-500 @enderror">
                            <option value="">Seleccionar cliente...</option>
                            @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre_completo }} - {{ $cliente->telefono }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('cliente_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">¿El cliente no está registrado?</span>
                    <a href="{{ route('clientes.create') }}" target="_blank" class="text-sm text-primary-600 hover:text-primary-700">
                        <i class="fas fa-plus mr-1"></i>Registrar nuevo cliente
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Dispositivo -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-mobile-alt mr-2 text-primary-600"></i>Dispositivo
            </h3>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="dispositivo_tipo" class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo <span class="text-red-500">*</span>
                    </label>
                    <select name="dispositivo_tipo" id="dispositivo_tipo" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="Celular">Celular</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Smartwatch">Smartwatch</option>
                        <option value="Laptop">Laptop</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div>
                    <label for="dispositivo_marca" class="block text-sm font-medium text-gray-700 mb-1">
                        Marca <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="dispositivo_marca" id="dispositivo_marca" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Ej: Samsung, iPhone, etc.">
                </div>
                <div>
                    <label for="dispositivo_modelo" class="block text-sm font-medium text-gray-700 mb-1">
                        Modelo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="dispositivo_modelo" id="dispositivo_modelo" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Ej: Galaxy S21, iPhone 13, etc.">
                </div>
                <div>
                    <label for="dispositivo_color" class="block text-sm font-medium text-gray-700 mb-1">
                        Color
                    </label>
                    <input type="text" name="dispositivo_color" id="dispositivo_color"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Ej: Negro, Azul, etc.">
                </div>
                <div>
                    <label for="dispositivo_imei" class="block text-sm font-medium text-gray-700 mb-1">
                        IMEI
                    </label>
                    <input type="text" name="dispositivo_imei" id="dispositivo_imei"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="15 dígitos">
                </div>
                <div>
                    <label for="dispositivo_serial" class="block text-sm font-medium text-gray-700 mb-1">
                        Número de Serie
                    </label>
                    <input type="text" name="dispositivo_serial" id="dispositivo_serial"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            
            <div class="mt-4">
                <label for="dispositivo_contrasena" class="block text-sm font-medium text-gray-700 mb-1">
                    Contraseña / Patrón
                </label>
                <button type="button" id="unlock_method_button"
                    class="mb-3 inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <i class="fas fa-lock mr-2 text-primary-600"></i>
                    Elegir bloqueo
                </button>
                <input type="text" name="dispositivo_contrasena" id="dispositivo_contrasena"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Contraseña o patrón de desbloqueo">
                <p class="text-xs text-gray-500 mt-1">Esta información es confidencial y solo será usada para pruebas.</p>
            </div>
        </div>
    </div>
    
    <!-- Problema y Costos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-exclamation-circle mr-2 text-primary-600"></i>Problema Reportado
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="problema_reportado" class="block text-sm font-medium text-gray-700 mb-1">
                        Descripción del problema <span class="text-red-500">*</span>
                    </label>
                    <textarea name="problema_reportado" id="problema_reportado" rows="4" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Describe el problema que reporta el cliente...">{{ old('problema_reportado') }}</textarea>
                </div>

                <div>
                    <label for="diagnostico" class="block text-sm font-medium text-gray-700 mb-1">
                        Diagnostico
                    </label>
                    <textarea name="diagnostico" id="diagnostico" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Diagnostico inicial del equipo...">{{ old('diagnostico') }}</textarea>
                </div>

                <div>
                    <label for="solucion" class="block text-sm font-medium text-gray-700 mb-1">
                        Solucion
                    </label>
                    <textarea name="solucion" id="solucion" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Solucion aplicada o recomendada...">{{ old('solucion') }}</textarea>
                </div>
                
                <div>
                    <label for="condiciones_previas" class="block text-sm font-medium text-gray-700 mb-1">
                        Condiciones previas del equipo
                    </label>
                    <textarea name="condiciones_previas" id="condiciones_previas" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Rayones, golpes, piezas faltantes, etc.">{{ old('condiciones_previas') }}</textarea>
                </div>
                
                <div>
                    <label for="accesorios_incluidos" class="block text-sm font-medium text-gray-700 mb-1">
                        Accesorios incluidos
                    </label>
                    <textarea name="accesorios_incluidos" id="accesorios_incluidos" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Cargador, funda, cable, etc.">{{ old('accesorios_incluidos') }}</textarea>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-dollar-sign mr-2 text-primary-600"></i>Costos y Asignación
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="tecnico_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Técnico asignado
                    </label>
                    <select name="tecnico_id" id="tecnico_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Sin asignar</option>
                        @foreach($tecnicos as $tecnico)
                        <option value="{{ $tecnico->id }}" {{ old('tecnico_id', request('tecnico_id')) == $tecnico->id ? 'selected' : '' }}>{{ $tecnico->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="costo_estimado" class="block text-sm font-medium text-gray-700 mb-1">
                            Costo Estimado
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" name="costo_estimado" id="costo_estimado" step="0.01" min="0"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="0.00">
                        </div>
                    </div>
                    <div>
                        <label for="adelanto" class="block text-sm font-medium text-gray-700 mb-1">
                            Adelanto
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" name="adelanto" id="adelanto" step="0.01" min="0"
                                class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                                placeholder="0.00">
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="fecha_estimada_entrega" class="block text-sm font-medium text-gray-700 mb-1">
                            Fecha estimada de entrega
                        </label>
                        <input type="date" name="fecha_estimada_entrega" id="fecha_estimada_entrega"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ now()->addDays($diasEstimados)->format('Y-m-d') }}">
                    </div>
                    <div>
                        <label for="garantia_dias" class="block text-sm font-medium text-gray-700 mb-1">
                            Garantía (días)
                        </label>
                        <input type="number" name="garantia_dias" id="garantia_dias" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            value="{{ $garantiaDefault }}">
                    </div>
                </div>
                
                <div>
                    <label for="notas_cliente" class="block text-sm font-medium text-gray-700 mb-1">
                        Notas adicionales
                    </label>
                    <textarea name="notas_cliente" id="notas_cliente" rows="2"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Notas adicionales para el cliente..."></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fotos -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-camera mr-2 text-primary-600"></i>Registro fotografico del equipo
        </h3>
        <p class="mt-1 text-sm text-gray-500">Primero registra las fotos del estado en que recibes el equipo. Las fotos despues se agregan cuando finalices el trabajo.</p>
        
        <div class="mt-5 grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                <p class="mb-3 font-medium text-gray-700">Fotos antes de la reparacion</p>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3 xl:grid-cols-1 2xl:grid-cols-3">
            @for($i = 1; $i <= 3; $i++)
            <div>
                <label for="foto_antes_{{ $i }}" class="block text-sm font-medium text-gray-700 mb-1">
                    Foto antes {{ $i }}
                </label>
                <input type="file" name="foto_antes_{{ $i }}" id="foto_antes_{{ $i }}" accept="image/*"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
            </div>
            @endfor
                </div>
            </div>

            <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-4">
                <p class="mb-2 font-medium text-gray-700">Fotos despues de la reparacion</p>
                <div class="flex min-h-32 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white px-4 py-6 text-center">
                    <div>
                        <i class="fas fa-camera-retro mb-2 text-2xl text-gray-300"></i>
                        <p class="text-sm font-medium text-gray-600">Se agregan desde la orden creada</p>
                        <p class="mt-1 text-xs text-gray-500">Cuando termines el trabajo, abre la orden y carga las fotos finales.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Botones -->
    <div class="flex justify-end space-x-4">
        <a href="{{ route('reparaciones.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
            Cancelar
        </a>
        <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg font-medium hover:bg-primary-700 transition-colors">
            <i class="fas fa-save mr-2"></i>Guardar Orden
        </button>
    </div>
</form>

<div id="unlock_method_modal" class="fixed inset-0 z-50 hidden items-start justify-center overflow-y-auto bg-gray-900/45 px-4 py-4 sm:py-6">
    <div class="w-full max-w-lg rounded-lg bg-white p-5 text-center shadow-xl sm:p-6">
        <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-primary-100 sm:h-16 sm:w-16">
            <i class="fas fa-lock text-2xl text-primary-600 sm:text-3xl"></i>
        </div>
        <h2 class="text-xl font-bold leading-tight text-gray-900 sm:text-2xl">Que metodo de desbloqueo utiliza el cliente?</h2>
        <p class="mx-auto mt-2 max-w-md text-sm text-gray-500 sm:text-base">
            Para continuar con el servicio, indicanos el tipo de bloqueo que tiene el dispositivo del cliente.
        </p>

        <div class="mt-5 grid grid-cols-2 gap-3 sm:gap-4">
            <button type="button" data-unlock-method="Patron"
                class="unlock-method-option rounded-lg border border-gray-300 px-4 py-5 text-center transition hover:border-primary-500 hover:bg-primary-50 focus:ring-2 focus:ring-primary-500 sm:px-6 sm:py-6">
                <i class="fas fa-project-diagram mb-3 text-4xl text-primary-600 sm:text-5xl"></i>
                <span class="block text-base font-semibold text-gray-900 sm:text-lg">Patron</span>
            </button>
            <button type="button" data-unlock-method="Contrasena"
                class="unlock-method-option rounded-lg border border-gray-300 px-4 py-5 text-center transition hover:border-primary-500 hover:bg-primary-50 focus:ring-2 focus:ring-primary-500 sm:px-6 sm:py-6">
                <i class="fas fa-lock mb-3 text-4xl text-primary-600 sm:text-5xl"></i>
                <span class="block text-base font-semibold text-gray-900 sm:text-lg">Contrasena</span>
            </button>
        </div>

        <div id="pattern_panel" class="mt-5 hidden">
            <p class="mb-3 text-sm font-medium text-gray-700">Toca los puntos en el mismo orden del patron.</p>
            <div class="mx-auto grid w-48 grid-cols-3 gap-3 sm:w-52">
                @for($point = 1; $point <= 9; $point++)
                <button type="button" data-pattern-point="{{ $point }}"
                    class="pattern-point flex h-12 w-12 items-center justify-center rounded-full border-2 border-primary-200 bg-white font-semibold text-primary-700 transition hover:border-primary-600 hover:bg-primary-50 sm:h-14 sm:w-14">
                    {{ $point }}
                </button>
                @endfor
            </div>
            <p id="pattern_preview" class="mt-3 min-h-5 text-sm font-semibold text-gray-700"></p>
            <div class="mt-3 flex justify-center gap-3">
                <button type="button" id="pattern_clear"
                    class="rounded-lg border border-gray-300 px-5 py-2 font-medium text-gray-600 hover:bg-gray-50">
                    Limpiar
                </button>
                <button type="button" id="pattern_done"
                    class="rounded-lg bg-primary-600 px-5 py-2 font-medium text-white hover:bg-primary-700">
                    Confirmar
                </button>
            </div>
        </div>

        <div id="password_panel" class="mt-5 hidden">
            <label for="unlock_password_value" class="mb-2 block text-left text-sm font-medium text-gray-700">
                PIN o contrasena del equipo
            </label>
            <input type="text" id="unlock_password_value"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-center text-lg tracking-wider focus:border-primary-500 focus:ring-2 focus:ring-primary-500"
                placeholder="Escribe el PIN o contrasena">
            <button type="button" id="password_done"
                class="mt-4 rounded-lg bg-primary-600 px-6 py-2 font-medium text-white hover:bg-primary-700">
                Confirmar
            </button>
        </div>

        <button type="button" id="unlock_method_cancel"
            class="mt-5 inline-flex min-w-32 justify-center rounded-lg border border-gray-300 px-6 py-3 font-medium text-gray-600 hover:bg-gray-50 focus:ring-2 focus:ring-primary-500">
            Cancelar
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('unlock_method_modal');
        const openButton = document.getElementById('unlock_method_button');
        const cancelButton = document.getElementById('unlock_method_cancel');
        const input = document.getElementById('dispositivo_contrasena');
        const help = input.nextElementSibling;
        const options = document.querySelectorAll('.unlock-method-option');
        const patternPanel = document.getElementById('pattern_panel');
        const patternPreview = document.getElementById('pattern_preview');
        const patternButtons = document.querySelectorAll('.pattern-point');
        const patternClear = document.getElementById('pattern_clear');
        const patternDone = document.getElementById('pattern_done');
        const passwordPanel = document.getElementById('password_panel');
        const passwordValue = document.getElementById('unlock_password_value');
        const passwordDone = document.getElementById('password_done');
        let pattern = [];

        function openModal() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function resetPattern() {
            pattern = [];
            patternPreview.textContent = '';
            patternButtons.forEach(function (button) {
                button.classList.remove('border-primary-600', 'bg-primary-600', 'text-white');
                button.classList.add('border-primary-200', 'bg-white', 'text-primary-700');
            });
        }

        function showPatternPanel() {
            resetPattern();
            hidePasswordPanel();
            patternPanel.classList.remove('hidden');
        }

        function hidePatternPanel() {
            patternPanel.classList.add('hidden');
        }

        function showPasswordPanel() {
            hidePatternPanel();
            passwordValue.value = '';
            passwordPanel.classList.remove('hidden');
            passwordValue.focus();
        }

        function hidePasswordPanel() {
            passwordPanel.classList.add('hidden');
        }

        openButton.addEventListener('click', openModal);

        input.addEventListener('focus', function () {
            if (!input.dataset.unlockMethod) {
                openModal();
            }
        });

        cancelButton.addEventListener('click', closeModal);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModal();
            }
        });

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                const method = option.dataset.unlockMethod;
                input.dataset.unlockMethod = method;
                input.placeholder = method === 'Patron'
                    ? 'Patron seleccionado'
                    : 'Ingresa el PIN o contrasena del equipo';
                help.textContent = 'Bloqueo seleccionado: ' + method + '. Esta informacion es confidencial.';
                openButton.innerHTML = '<i class="fas fa-lock mr-2 text-primary-600"></i>' + method;

                if (method === 'Patron') {
                    input.value = '';
                    showPatternPanel();
                    return;
                }

                input.value = '';
                showPasswordPanel();
            });
        });

        patternButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const point = button.dataset.patternPoint;

                if (pattern.includes(point)) {
                    return;
                }

                pattern.push(point);
                patternPreview.textContent = 'Patron: ' + pattern.join('-');
                button.classList.remove('border-primary-200', 'bg-white', 'text-primary-700');
                button.classList.add('border-primary-600', 'bg-primary-600', 'text-white');
            });
        });

        patternClear.addEventListener('click', resetPattern);

        patternDone.addEventListener('click', function () {
            if (pattern.length < 2) {
                patternPreview.textContent = 'Selecciona minimo 2 puntos.';
                return;
            }

            input.value = 'Patron: ' + pattern.join('-');
            closeModal();
        });

        passwordDone.addEventListener('click', function () {
            const value = passwordValue.value.trim();

            if (!value) {
                passwordValue.focus();
                return;
            }

            input.value = value;
            closeModal();
        });
    });
</script>
@endpush
