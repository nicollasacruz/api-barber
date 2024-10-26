<?php

namespace App\Http\Controllers;

use App\Models\Client; // Importe o modelo Client
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Store a newly created user resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                    'contact' => 'required|string',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|confirmed|min:6',
                    'password_confirmation' => 'required',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            // Crie o usuário na tabela 'users'
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'password' => Hash::make($request->password),
            ]);

            // Crie o registro associado na tabela 'clients'
            $client = new Client();
            $client->user_id = $user->id;
            // Adicione quaisquer outros campos específicos do cliente aqui
            $client->save();

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'token' => $user->createToken(
                    $user->name,
                    [''],
                    Carbon::now()->addHours(6)
                )->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            // Tente autenticar o usuário
            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password do not match.',
                ], 401);
            }

            // Se autenticado com sucesso, crie um token para o usuário
            $user = $request->user();
            auth()->user()->tokens()->delete();

            $token = $user->createToken(
                $user->name,
                // [''],
                // Carbon::now()->addHours(6)
            )->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'User Logged in successfully',
                'token' => $token
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
