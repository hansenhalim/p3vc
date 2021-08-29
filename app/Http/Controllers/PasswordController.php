<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
  public function edit()
  {
    return view('auth.passwords.change');
  }

  public function update(Request $request)
  {
    $request->validate([
      'current_password' => 'current_password',
      'password' => ['required', 'confirmed', Password::min(8)]
    ]);

    User::find(Auth::id())->update(['password'=> Hash::make($request->password)]);

    $request->session()->flash('status', 'Successfully changed password. Why not give it a try?');

    return back();
  }
}
