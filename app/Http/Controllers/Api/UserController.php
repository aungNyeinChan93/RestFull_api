<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\ValidationException;
use Storage;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller implements HasMiddleware
{

    //middleware
    public static function middleware()
    {
        return [
            new Middleware(['admin'], only: ['destroy']),
        ];
    }
    //index
    public function index()
    {
        try {
            return response()->json([
                'users' => User::query()->get(['name', 'email']),
                'message' => 'success',
            ], 200);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // show
    public function show(User $user)
    {
        try {
            return response()->json([
                'message' => 'success',
                'user' => $user,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 433);
        }
    }

    // destroy

    public function destroy(Request $request)
    {
        try {
            $request->user()->delete();
            return response()->json([
                'message' => 'success',
                'userName' => $request->user()->name,
            ], 200);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    // profile-update
    public function profile_update(Request $request)
    {

        $fileds = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'avator' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5000',
        ]);

        if ($request->hasFile('avator')) {
            // old avator delete
            if ($request->user()->avator) {
                Storage::disk('public')->delete($request->user()->avator);
            }
            // new avator add
            $path = $request->file('avator')->store('/avators/', 'public');
            $fileds['avator'] = $path;
        }

        $request->user()->update($fileds);

        return response()->json([
            'message' => 'success',
            'user' => $request->user()
        ], 200);
    }

    // changePassword
    public function changePassword(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'email', 'unique:users,email,' . $request->user()->id],
            'old_password' => ['required', 'min:6',],
            'password' => ['required', 'confirmed', 'min:6',],
            'password_confirmation' => ['required', 'same:password']
        ]);

        $user = User::where('email', $request->email)->first();

        if (!Hash::check($fields['old_password'], $user->password)) {
            throw ValidationException::withMessages([
                'old_password' => ['The provided old_password is not correct!']
            ]);
        }

        $user->update(['password' => Hash::make($fields['password'])]);

        return response()->json([
            'message' => 'success',
            'user' => $user
        ]);

    }
}
