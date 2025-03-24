<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::leftJoin('restaurants', function($join) {
            $join->on('users.id', '=', 'restaurants.owner_id');
        })
        ->leftJoin('restaurant_employees', function($join) {
            $join->on('restaurant_employees.restaurant_id', '=', 'restaurants.id')
                 ->on('restaurant_employees.user_id', '=', 'users.id');
        })
        ->select(
            'users.*',
            'restaurants.name as restaurant_name',
            'restaurants.address as restaurant_address',
            'restaurants.phone_number as restaurant_phone',
            'restaurants.email as restaurant_email',
            'restaurants.website',
            'restaurant_employees.position as employee_role',
            \DB::raw('CASE WHEN users.id = restaurants.owner_id THEN "Owner" ELSE "" END as is_owner')
        )
        ->where(function ($query) {
            $userRole = auth()->user()->role;
            if ($userRole == 'restaurant_owner') {
                $query->where('restaurants.owner_id', auth()->user()->id) OR
                $query->where('restaurant_employees.user_id', auth()->user()->id);
            }else{
                $query->where('restaurant_employees.user_id', auth()->user()->id);
            }
        })
        ->get();

    return view('manage-users.index', ['users' => $users]);
        //
    }

    /**
     * This function is used to return form via on your email account
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */
    public function Reset(Request $request)
    {

        $data['email'] = $request->email;
        $data['token'] = $request->token;
        $data['users'] = User::where('id', $request->id)->get();
        return view('auth.reset',$data);
    }

/**
     * This function is used to store password
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */

    public function storePassword(Request $request)
    {
        try{
        $validator = \Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed'],
        ]);
        if ($validator->fails()) {
                    return back()->withErrors($validator);
        }
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    // 'account_verified'=>1,
                    'password' => \Hash::make($request->password),
                    'remember_token' => \Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );


       if($status == Password::PASSWORD_RESET)
       {
           $request->session()->flash('success', 'Please login again with updated password');
           return redirect()->route('login');
       }
       return back()->withInput($request->only('email'))
       ->withErrors(['email' => __($status)]);
    }
    catch(Exception $e)
    {
        return back()->withErrors(['errors' => 'Something went wrong '.$e->getMessage()]);
    }
    }
    /**
     * This function is used to store password
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */

    public function storePasswordReset(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validator->fails()) {
                        return redirect(route('forgot-password'))
                        ->withErrors($validator)
                        ->withInput();
            }
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if($user == null)
            {
                $request->session()->flash('error', "Email is not exist in database");
                return redirect(route('forgot-password'));
            }
            event(new ResetPasswordEvent($email));
            $request->session()->flash('link_sent', "Reset link is successfully sent");
            return response()->json(['status' => 200,'message' => "Other cert Item add",'data'=>$user]);
         }
        catch(Exception $e)
        {

        }
    }
   /**
     * This function is used to send link on email
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Routing\Redirector
     * @author Caritasi:Kwizera
     */

    public function store(Request $request)
    {
        try{
            $validator = \Validator::make($request->all(), [
                'email' => 'required|email'
            ]);
            if ($validator->fails()) {
                        return redirect(route('forgot-password'))
                        ->withErrors($validator)
                        ->withInput();
            }
            $email = $request->email;
            $user = User::where('email', $email)->first();
            if($user == null)
            {
                $request->session()->flash('error', "Email is not exist in database");
                return redirect(route('forgot-password'));
            }
            event(new ResetPasswordEvent($email));
            $request->session()->flash('link_sent', "Reset link is successfully sent");
                return redirect(route('forgot-password'));
         }
        catch(Exception $e)
        {

        }
        //
    }
}
