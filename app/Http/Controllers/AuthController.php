<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use MongoDB\Driver\Exception\BulkWriteException;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);
        } catch (BulkWriteException $e) {
            if ($e->getCode() === 11000) {
                return response()->json(['error' => 'Esse email já foi registrado.'], 409);
            }
            return response()->json(['error' => 'MongoDB error occurred.'], 500);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json(['error' => 'Esse email ja foi registrado.'], 409);
            }
            return response()->json(['error' => 'Database error occurred.'], 500);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'Usuário registrado!',
            'Token' => $token
        ];

        return response()->json($response, 200);
    }

    public function login(Request $request)
    {

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email inválido!'], 404);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Senha inválida!'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'Usuário logado!',
            'Token' => $token
        ];

        return response()->json($response, 200);
    }

    public function logout()
    {
        $user = request()->user();

        $user->tokens()->delete();

        $response = [
            'Usuário deslogado!',
        ];

        return response()->json($response, 200);
    }
}
