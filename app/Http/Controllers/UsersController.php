<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Aloha\Twilio\Twilio;


class UsersController extends Controller
{

	public function __construct()
	{
		$this->twilio = new Twilio($sid =env('TWILIO_SID'), $token=env('TWILIO_TOKEN'), $from=env('TWILIO_FROM'), $sslVerify = true);
	}
	public function usersData(){
		$getData = User::where('type' , 'user')->get();
		return view('backend.users_data', compact('getData'));
	}

	public function registerForm(){
		return view('auth.register');
	}

	public function deletUser($id){
		User::where('id',$id)->delete();
		return redirect()->route('users-data');
	}


	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => 'required|string|max:255',
			'email' => 'required|unique:users',
			'address' => 'required',
			'phone' => 'required|unique:users',
			'sms_count' => 'required',
			'password' => 'required|string|min:6|confirmed',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'address' => $data['address'],
			'phone' => $data['phone'],
			'remember_token' => $data['_token'],
			'type' => 'user',
			'sms_count' => $data['sms_count'],
			'password' => bcrypt($data['password']),
		]);
	}
	public function register(Request $request)
	{
		$this->validator($request->all())->validate();
		event( $this->create($request->all()));
		//$this->guard()->login($user);
		return redirect()->route('users-data');
	}

	/*
	 * Send Verification notification
	*/
	public function login(Request $request){

		$numbers =  mt_rand(1000, 9999);
		$user = User::where('email', $request->email)->first();
		if($user != null){
			$userEmail = $request->email;
			$password  = $request->password;
			User::where('email','=', $request->email)->update(['verification_code'=> $numbers]);
			$this->sendVerificationCode($user->phone, $numbers);
			return view('auth.passwords.verification', compact('userEmail', 'password'));
		}else{
			$request->session()->flash( 'login-error', 'Email or Password does not Match to Any Account!' );
			return redirect('/');
		}
		$request->session()->flash( 'login-error', 'Email or Password does not Match to Any Account!' );
		return redirect('/');
	}

	/*
	 * Send Verify
	*/
	public function verification_code(Request $request)
	{
		if(isset($request->resend))
		{
			$numbers =  mt_rand(1000, 9999);
			$user = User::where('email', $request->email)->first();
			User::where('email','=', $request->email)->update(['verification_code'=> $numbers]);
			$this->sendVerificationCode($user->phone, $numbers);
			$userEmail = $request->email;
			$password = $request->password;
			$request->session()->flash( 'code-sent', 'Confirmation code Sent ' );
			return view('auth.passwords.verification', compact('userEmail', 'password'));
		}else{
			$user = User::where('verification_code', $request->verification_code)->first();
			$userEmail = $request->email;
			$password  = $request->password;
			if($user != null){
				Auth::attempt(['email' => $request->email, 'password' => $request->password]);
				return redirect('/');
			}else{
				$request->session()->flash( 'verify-error', 'Confirmation code does not Match' );
				return view('auth.passwords.verification', compact('userEmail', 'password'));
			}
		}
		$request->session()->flash( 'verify-error', 'Confirmation code does not Match' );
		return view('auth.passwords.verification', compact('userEmail', 'password'));
	}

	/*
	 * Send Verification Code to Member
	 * */
	public function sendVerificationCode($toNumber, $verificationCode)
	{
		$response = $this->twilio->message($toNumber, 'Your Verification Code '.$verificationCode);
	}

}
