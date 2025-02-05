<?php

namespace App\Http\Controllers\inicio_sesion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Responses\inicio_sesion\LoginStore;
use Exception;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordRecovery\MailPasswordRecovery;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Http\Responses\inicio_sesion\RecoveryPasswordEmail;
use App\Http\Responses\inicio_sesion\RecoveryPasswordPost;
use App\Traits\MetodosTrait;

class LoginController extends Controller
{
    use MetodosTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vista = 'inicio_sesion.login_entrenador';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection") {
            return view('database_connection');
        } else {
            return view($vista);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new LoginStore();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function resetPassword()
    {
        $vista = 'inicio_sesion.resetear_password';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection") {
            return view('database_connection');
        } else {
            return view($vista);
        }
    }

    public function resetPasswordStudent()
    {
        $vista = 'inicio_sesion.resetear_password_estudiante';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection") {
            return view('database_connection');
        } else {
            return view($vista);
        }
    }

    public function loginEstudiante()
    {
        $vista = 'inicio_sesion.login_estudiante';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection") {
            return view('database_connection');
        } else {
            return view($vista);
        }
    }

    public function recoveryPassword(Request $request)
    {
        $vista = 'inicio_sesion.recovery_password';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection") {
            return view('database_connection');
        } else {
            return view($vista);
        }
    }

    public function logout(Request $request)
    {
        try {
            Session::forget('usuario_id');
            Session::forget('username');
            Session::forget('sesion_iniciada');
            Session::forget('rol');
            Session::flush();
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->to(route('home'));

        } catch (Exception $e)
        {
            alert()->error('Error','An error has occurred, try again, if the problem persists contact support.');
            return back();
        }
    }

    public function recoveryPasswordEmail(Request $request)
    {
        return new RecoveryPasswordEmail();
    }

    public function recoveryPasswordLink($id)
    {
        $vista = 'inicio_sesion.recovery_password_link';
        $checkConnection = $this->checkDatabaseConnection($vista);

        if($checkConnection->getName() == "database_connection") {
            return view('database_connection');
        } else {
            return view($vista, compact('id'));
        }
    }

    public function recoveryPasswordPost(Request $request)
    {
        return new RecoveryPasswordPost();
    }
}
