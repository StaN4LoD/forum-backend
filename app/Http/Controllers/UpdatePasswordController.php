<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UpdatePasswordController extends Controller
{
	public function changePassword()
	{
$this->validate(request(), [
'current_password' => 'required|current_password',
'new_password' => 'required|string|min:6|confirmed',
]);

request()->user()->fill([
'password' => Hash::make(request()->input('new_password'))
])->save();
request()->session()->flash('success', 'Password changed!');

return redirect()->route('password.change');
	}
}