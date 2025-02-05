<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
// use Illuminate\Support\Facades\Auth;

// ============================================================

Route::get('/', function ()
 {
    return view('inicio_sesion.login');
})->name('home');

// RUTA COMPROBAR CONEXIÓN BASE DE DATOS
Route::get('check_database_connection', 'inicio_sesion\LoginController@checkDatabaseConnection')->name('check_database_connection');

// SERVIDOR REMOTO (CPANEL) DEL STORAGE:LINK
Route::get('storage-link', function(){
    Artisan::call('storage:link');

    if (file_exists(public_path('storage'))) {
        return 'El directorio "public/storage" ya existe';
    }

    app('files')->link(
        storage_path(('app/public')), public_path('storage')
    );

    return 'El directorio "public/storage" ha sido vinculado (linked)';
});

Route::group(['middleware' => ['verify_current_user']], function () {
    // Rutas Login
    Route::resource('login', 'inicio_sesion\LoginController');
    Route::get('login_estudiante', 'inicio_sesion\LoginController@loginEstudiante')->name('login_estudiante');
    Route::get('reset_password', 'inicio_sesion\LoginController@resetPassword')->name('reset_password');
    Route::get('reset_password_student', 'inicio_sesion\LoginController@resetPasswordStudent')->name('reset_password_student');
    Route::get('recovery_password', 'inicio_sesion\LoginController@recoveryPassword')->name('recovery_password');
    Route::post('recovery_password_email', 'inicio_sesion\LoginController@recoveryPasswordEmail')->name('recovery_password_email');
    Route::get('recovery_password_link/{id}', 'inicio_sesion\LoginController@recoveryPasswordLink')->name('recovery_password_link');
    Route::post('recovery_password_post', 'inicio_sesion\LoginController@recoveryPasswordPost')->name('recovery_password_post');
    Route::get('logout', 'inicio_sesion\LoginController@logout')->name('logout');

    // Rutas ADMINISTRADOR
    Route::resource('administrador', 'admin\AdministradorController');
    Route::post('cambiar_estado', 'admin\AdministradorController@cambiarEstadoUsuario')->name('cambiar_estado');
    Route::post('validar_cedula', 'admin\AdministradorController@validarCedula')->name('validar_cedula');
    Route::post('validar_cedula_edicion', 'admin\AdministradorController@validarCedulaEdicion')->name('validar_cedula_edicion');
    Route::post('validar_correo', 'admin\AdministradorController@validarCorreo')->name('validar_correo');
    Route::post('validar_correo_edicion', 'admin\AdministradorController@validarCorreoEdicion')->name('validar_correo_edicion');
    Route::post('actualizar_clave', 'admin\AdministradorController@actualizarClave')->name('actualizar_clave');
    
    // Rutas módulo disponibilidades
    Route::get('disponibilidad_entrenadores', 'admin\AdministradorController@disponibilidades')->name('administrador.disponibilidad_entrenadores');
    Route::post('actualizar_disponibilidad_entrenador', 'admin\AdministradorController@actualizarDisponibilidad')->name('actualizar_evento');
    Route::get('disponibilidad_admin', 'admin\AdministradorController@vistaAdminDisponibilidad')->name('administrador.disponibilidad_admin');
    Route::post('disponibilidad_admin_store', 'admin\AdministradorController@storeAdminDisponibilidad')->name('administrador.disponibilidad_admin_store');
    Route::post('disponibilidad_admin_state', 'admin\AdministradorController@changeStateAdminDisponibilidad')->name('disponibilidad_admin_state');
    Route::get('disponibilidades_libres', 'admin\AdministradorController@disponibilidadesLibres')->name('administrador.disponibilidades_libres');
    
    // Rutas módulo niveles
    Route::get('niveles_index', 'admin\AdministradorController@nivelesIndex')->name('administrador.niveles_index');
    Route::post('crear_nivel', 'admin\AdministradorController@crearNivel')->name('crear_nivel');
    Route::post('editar_nivel', 'admin\AdministradorController@editarNivel')->name('editar_nivel');
    Route::post('inactivar_nivel', 'admin\AdministradorController@inactivarNivel')->name('inactivar_nivel');
    Route::post('activar_nivel', 'admin\AdministradorController@activarNivel')->name('activar_nivel');
    Route::post('consultar_nivel', 'admin\AdministradorController@consultarNivel')->name('consultar_nivel');

    // Rutas ENTRENADOR
    Route::resource('trainer', 'entrenador\EntrenadorController');
    Route::post('cargar_eventos_entrenador', 'entrenador\EntrenadorController@cargarEventos')->name('cargar_eventos_entrenador');
    Route::delete('eliminar_evento', 'entrenador\EntrenadorController@deleteEvent')->name('eliminar_evento');
    Route::post('cargar_info_evento', 'entrenador\EntrenadorController@cargarInfoEventoPorId')->name('cargar_info_evento');
    Route::post('detalle_sesion_entrenador', 'entrenador\EntrenadorController@cargaDetalleSesion')->name('detalle_sesion_entrenador');
    Route::post('evaluacion_interna_entrenador', 'entrenador\EntrenadorController@evaluacionInternaEntrenador')->name('evaluacion_interna_entrenador');
    Route::post('consulta_evaluacion_interna', 'entrenador\EntrenadorController@consultaEvaluacionInterna')->name('consulta_evaluacion_interna');
    Route::post('actualizacion_masiva_diponibilidades', 'entrenador\EntrenadorController@actualizacionMasivaDiponibilidades')->name('actualizacion_masiva_diponibilidades');
    Route::get('student_resume', 'entrenador\EntrenadorController@studentResume')->name('student_resume');
    Route::post('estudiante_hoja_vida', 'entrenador\EntrenadorController@estudianteHojaVida')->name('estudiante_hoja_vida');

    // Rutas ESTUDIANTE
    Route::resource('estudiante', 'estudiante\EstudianteController');
    Route::get('disponibilidad', 'estudiante\EstudianteController@disponibilidadEntrenadores')->name('estudiante.disponibilidad');
    Route::get('mis_creditos', 'estudiante\EstudianteController@misCreditos')->name('estudiante.mis_creditos');
    Route::get('creditos_disponibles', 'estudiante\EstudianteController@creditosDisponibles')->name('estudiante.creditos_disponibles');
    Route::post('comprar_creditos', 'estudiante\EstudianteController@comprarCreditos')->name('estudiante.comprar_creditos');
    Route::post('reservar_clase', 'estudiante\EstudianteController@reservarClase')->name('estudiante.reservar_clase');
    Route::post('cancelar_clase', 'estudiante\EstudianteController@cancelarClase')->name('estudiante.cancelar_clase');

    // Rutas de Autenticación de Google
    Route::get('/auth/google', 'estudiante\EstudianteController@redirectToGoogle')->name('auth.google');
    Route::get('/auth/google/callback/reservar', 'estudiante\EstudianteController@handleGoogleCallbackReservar');
    Route::get('/auth/google/callback/cancelar', 'estudiante\EstudianteController@handleGoogleCallbackCancelar');
    Route::get('/create-meet', 'estudiante\EstudianteController@createMeet')->name('createMeet');

    // Rutas FOOTER
    Route::get('about_us', 'comunes\ComunController@aboutUs')->name('about_us');
    Route::get('services', 'comunes\ComunController@services')->name('services');
});




