<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use App\Models\Publisher;
use App\Models\User;
use App\Models\UserInvite;
use App\Notifications\NewUserInvited;
use App\Notifications\UserConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request) {
        $users = User::with('roles')->orderBy('id', 'desc')->get();

        return inertia()->render('Users')->with([
            'users' => $users,
        ]);
    }

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

    public function resendInvite(User $user) {
        UserInvite::where('user_id', $user->id)->delete();

        $userInvite = UserInvite::create([
            'user_id' => $user->id,
            'expire_dt' => now()->addDay(),
            'token' => Str::random(12),
        ]);

        $user->notify(new NewUserInvited($user, $userInvite));
    }

    public function userSetup(UserInvite $invite, Request $request) {
        if (! $request->hasValidSignature()) {
            abort(403);
        }

        return inertia()->render('Signed/AccountSetup')->with([
            'email' => $invite->user->email,
            'token' => $invite->id,
        ]);
    }

    public function completeSetup(UserInvite $invite, Request $request) {
        $request->validate([
            'password' => 'required|confirmed',
        ]);

        $user = User::findOrFail($invite->user_id);
        $user->email_verified_at = now();
        $user->password = Hash::make($request->password);
        $user->save();

        $invite->delete();

        $user->notify(new UserConfirmed($user));

        return redirect(route('dashboard'));
    }
}
