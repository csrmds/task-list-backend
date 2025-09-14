<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {

    private $user;

    public function __construct() {
        $this->user= new User;
    }


    public function setUser($data) {
        if ($data['google_id']==null) {
            $this->user->name= $data['name'];
            $this->user->last_name= $data['last_name'];
            $this->user->email= $data['email'];
            $this->user->password= $data['password'];
            $this->user->avatar= $data['avatar'];
        } else {
            $this->user->name= $data['name'];
            $this->user->last_name= $data['last_name'];
            $this->user->email= $data['email'];
            $this->user->google_id= $data['google_id'];
            $this->user->avatar= $data['avatar'];
        }
    }

    public function store(Request $request) {
        
        try {
            $userData= $request->input('userData');
            $this->setUser($userData);
            $this->user->updateOrCreate();
            
            return response()->json([
                'success'=> true,
                'message'=> 'Usuário salvo com sucesso',
                'data'=> $this->user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao tentar salvar a conta de usuário',
                'error'=> $e->getMessage()
            ]);
        }

    }

    public function authGoogle(Request $request) {

        return Socialite::driver('google')
        ->scopes([
            'openid',
            'https://www.googleapis.com/auth/calendar',
        ])
        ->with(['prompt' => 'select_account'])
        ->redirect();
        
    }

    public function authCallback(Request $request) {
        $frontendUrl= env('APP_URL');
        
        try {
            $googleUser= Socialite::driver('google')->stateless()->user();

            $user= User::updateOrCreate(
                ['google_id'=> $googleUser->getId()],
                [
                    'name'=> $googleUser->getName(),
                    'email'=> $googleUser->getEmail(),
                    'avatar'=> $googleUser->getAvatar(),
                    'access_token'=> $googleUser->token,
                    'refresh_token'=> $googleUser->refreshToken,
                    'token_expires_in'=> $googleUser->expiresIn,
                ]
            );

            Auth::login($user);
            
            $token= $user->createToken('api-token')->plainTextToken;
            
            return redirect($frontendUrl."?google_auth=success&email=$user->email&name=$user->name&last_name=$user->last_name&avatar=$user->avatar&access_token=$token");
        } catch(\Exception $e) {
            //logger("login error: ", [$e->getMessage()]);
            return redirect($frontendUrl . '?google_auth=error&message=erro_ao_autenticar');
        }

    }

    public function logout(Request $request) {

        try {
            $request->user()->tokens()->delete();

            return response()->json([
                'success'=> true,
                'message'=> "logoff feito com sucesso",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao fazer logoff',
                'error'=> $e->getMessage()
            ]);
        }

    }

    public function login(Request $request) {

        //logger("login attempt: ", [$request->input()]);

        try {

            $credentials= $request->validate([
                'email'=> ['required', 'email'],
                'password'=> ['required']
            ]);


            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'success'=> false,
                    'message'=> "Usuário ou senha inválidos"
                ]);
            }

            $user= Auth::user();

            $token= $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'success'=> true,
                'message'=> 'Login feito com sucesso',
                'token'=> $token,
                'data'=> $user
            ]);

            
        } catch(\Exception $e) {
            $dbConnection= env('DBA_CONNECTION');
            $dbHost= env('DBA_HOST');
            $dbUrl= env('DBA_URL');
            $dbUser= env('DBA_USERNAME');
            $dbPort= env('DBA_PORT');
            $dbDatabase= env('DBA_DATABASE');
            //$dbPassword= env('DB_PASSWORD');

            return response()->json([
                'success'=> false,
                'message'=> 'Erro ao fazer login',
                'error'=> $e->getMessage(),
                'data'=> [
                    'connection'=> $dbConnection, 
                    'host'=> $dbHost, 
                    'database'=> $dbDatabase, 
                    'user'=> $dbUser, 
                    'port'=> $dbPort ]
            ]);
        }

    }

    public function authCheck(Request $request) {
        try {
            $user = Auth::user();

            if ($user) {
                return response()->json([
                    'success'=> true,
                    'data'=> $user
                ]);
            }
            
        } catch (\Exception $e) {
            //logger('Erro em authCheck: ', [$e->getMessage()]);
            return response()->json([
                'success'=> false,
                'error'=> $e->getMessage(),
                'message'=> 'Usuário não autenticado'
            ]);
        }
    }

    public function teste(Resquest $request) {
        try {
            //$user = Auth::user();
            $var= env('DB_CONNECTION');
            return response()->json([
                'teste'=> "qq informação de retorno",
                'var'=> $var
            ]);

        } catch(\Exception $e) {
            return response()->json([
                'success'=> false,
                'error'=> $e->getMessage(),
                'message'=> 'Erro'
            ]);
        }
    }

}