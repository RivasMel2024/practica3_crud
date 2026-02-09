<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatchUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        // Buscando usuario por nombre o email
        $users = User::query()
            ->when(
                value: $request->input('is_trashed') === 'true',
                callback: fn($query) => $query->onlyTrashed()
            )
            ->when(
                $request->has('username'),
                fn($query) => $query->where('username', 'like','%' . $request->input('username') . '%')
            )
            ->when(
                $request->has('email'),
                fn($query) => $query->where('email', 'like','%' . $request->input('email') . '%')
            )
            ->get();

        // O devolvemos todos los usuarios
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Str::random(8); // Le colocamos una contraseña por defecto

        $user = User::create($data);
        
        return response()->json(UserResource::make($user), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Obtener usuario por id explícitamente
        $user = User::findOrFail($id);
        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();
        
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        
        $user->save();
        $user = User::find($id);

        return response()->json(UserResource::make($user), 200);
    }

    /**
     * Update the specified resource in storage (PATCH).
     */
    public function patch(PatchUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validated();
        
        // Actualizar usando fill y save explícitamente
        foreach ($data as $key => $value) {
            $user->$key = $value;
        }
        
        $user->save();
        
        // Obtener datos frescos de la BD
        $user = User::find($id);

        return response()->json(UserResource::make($user), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json([
            'message' => 'El usuario ha sido eliminado correctamente.'
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id){
        $user = User::onlyTrashed()->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        $user->restore();

        return response()->json([
            'message' => 'El usuario ha sido restaurado correctamente.',
            'user' => UserResource::make($user)
        ], 200);
    }
}
