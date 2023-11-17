<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Publisher;
use App\Models\User;
use App\Models\UserInvite;
use App\Notifications\NewUserInvited;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function createUser(Request $request) {
        $request->validate([
            'first' => 'required',
            'last' => 'required',
            'email' => 'required|unique:users,email|email:rfc,dns',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'first' => $request->first,
            'last' => $request->last,
            'email' => $request->email,
            'password' => Hash::make(Str::random(8)),
        ]);

        $user->assignRole($request->role);

        if($request->role === "buyer") {
            Buyer::create([
                'name' => $user->first . ' ' . $user->last,
                'user_id' => $user->id,
            ]);
        }
        else if($request->role === "publisher") {
            Publisher::create([
                'name' => $user->first . ' ' . $user->last,
                'user_id' => $user->id,
            ]);
        }

        $userInvite = UserInvite::create([
            'user_id' => $user->id,
            'expire_dt' => now()->addDay(),
            'token' => Str::random(12),
        ]);

        $user->notify(new NewUserInvited($user, $userInvite));
    }

    public function userSetup() {
        return inertia()->render('Signed/AccountSetup');
    }
}
