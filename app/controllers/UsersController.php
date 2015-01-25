<?php

class UsersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Input::merge(array_map('trim', Input::all()));
        $validator = Validator::make(
            Input::get(),
            array(
                'firstname' =>'required|alpha|max:50',
                'lastname' =>'required|alpha|max:50',
                'password' =>'required|alpha|min:5|max:50',
                'email' =>'required|email|unique:users',
                //'avatar' => 'image|mimes:jpeg,bmp,png',
            ));
        if($validator->fails())
        {
            return Response::json(array('data'=> $validator->messages(),'status'=> false));
        }

        $filename = "";
        /*if (Input::hasFile('avatar'))
        {
            $name = Input::file('avatar')->getClientOriginalName();
            $filename = $filename = uniqid() . "_" . trim($name," ");
            $destination_path = public_path().'/images/';
            Input::file('avatar')->move($destination_path, $filename);
        }*/


        /*if ($_POST['avatar'])
        {
            $imageData = base64_decode($_POST['avatar']);
            $filename = uniqid().".jpg" ;

            file_put_contents($destination_path."/".$filename, $imageData);
        }*/

        $user = new User;
        $user->firstname = Input::get('firstname');
        $user->lastname = Input::get('lastname');
        $user->password = Hash::make(Input::get('password'));
        $user->email = Input::get('email');
        $user->status = 1 ;
        $user->superuser = 0 ;
        $user->verify_email = 0 ;
        $user->avatar = Input::get('avatar') ;
        $user->activation_key = Hash::make(microtime() . Input::get('password'));
        $user->ip = $user->getUserIp();

        try {
            $user->save();
        }
        catch(\Exception $e)
        {
            return Response::json(array('data'=> 'Unable to save User.','status'=> false));
        }

        $insertedUser = User::find($user->id);

        return Response::json(array('data'=> $insertedUser,'status'=> false));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$validator = Validator::make(
            array('id' => $id),
            array('id' => 'required|numeric')
        );

        if($validator->fails())
        {
            return Response::json(array('data'=> 'Invalid User Id.','status'=> false));
        }
        $user = User::find($id);

        if (!$user) {
        	return Response::json(array('data'=> 'User does not exist.','status'=> false));
        }

        return Response::json(array('data'=> $user,'status'=> true));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		Input::merge(array_map('trim', Input::all()));
        $validator = Validator::make(
            array('id' => $id),
            array('id' => 'required|numeric')
        );

        if($validator->fails())
        {
            return Response::json(array('data'=> 'Invalid User Id.','status'=> false));
        }

        $user = User::find($id);

        if (!$user) {
            return Response::json(array('data'=> 'User does not exist.','status'=> false));
        }

        $validator = Validator::make(
            Input::get(),
            array(
                'firstname' =>'sometimes|alpha_spaces|max:50',
                'lastname' =>'sometimes|alpha_spaces|max:50',
                'current_password' =>'required_with:password|alpha_spaces|min:5|max:50',
                'password' =>'required_with:current_password|alpha_spaces|min:5|max:50',
                'email' =>'sometimes|email',
                'status' =>'sometimes'
            ));
        if($validator->fails())
        {
            return Response::json(array('data'=> $validator->messages(),'status'=> false));
        }
        $user->firstname = Input::get('firstname')?Input::get('firstname'):$user->firstname;
        $user->lastname = Input::get('lastname')?Input::get('lastname'):$user->lastname;
        if(Input::get('password') && Input::get('current_password'))
        {
            if(Hash::check(Input::get('current_password'),$user->password)) 
            {
                $user->password = Input::get('password')?Hash::make(Input::get('password')):$user->password;
            }
            else
            {
                return Response::json(array('data'=> 'Invalid User password.','status'=> false));
            }
        }
        $user->email = Input::get('email')?Input::get('email'):$user->email;
        
        $user->status = Input::get('status')?((Input::get('status')==='true') ? 1 : 0):$user->status;
        try {
            $user->save();
        }
        catch(\Exception $e)
        {
            return Response::json(array('data'=> 'Unable to save User.','status'=> false));
        }

		$updatedUser = User::find($id);        

        return Response::json(array('data'=> $updatedUser,'status'=> true));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$validator = Validator::make(
            array('id' => $id),
            array('id' => 'required|numeric')
        );

        if($validator->fails())
        {
            return Response::json(array('data'=> 'Invalid User Id.','status'=> false));
        }

        $user = User::withTrashed()->find($id);

        if (!$user) {
        	return Response::json(array('data'=> 'User does not exist.','status'=> false));
        }
        if ($user->trashed()) {
            return Response::json(array('data'=> 'User already deleted.','status'=> false));
        }

        try{

           $user->delete();
 //        Delete other things also
        }
        catch (Exception $e)
        {
            return Response::json(array('data'=> 'Unable to delete User.','status'=> false));
        }

        return Response::json(array('data'=> 'User deleted.','status'=> true));
	}

	public function login()
	{
		$validator = Validator::make(
				            Input::get(),
				            array(
				                'email' =>'required|email',
				                'password' =>'required|alpha_spaces|min:5|max:50',
				            ));

        /*if($validator->fails())
        {
            return Response::json(array('data'=> $validator->messages(),'status'=> false));
        }*/

		$user = array(
            'email' => Input::get('email'),
            'password' => Input::get('password')
        );
        
        if (Auth::attempt($user)) {
            $access_token = uniqid();
            $user_id = Auth::user()->id;
            $user = User::find($user_id);
            $user->access_token = $access_token;
            $user->save();
            
            return Response::json(array('data'=> 'You are successfully logged in.','access_token' => $access_token ,'status'=> true));
        }
        else
        {
        	return Response::json(array('data'=> 'Your email/password combination was incorrect.','access_token'=>"",'status'=> false));
        }
	}

	public function logout()
	{
        $access_token = uniqid();
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->access_token = $access_token;
        $user->save();
        
		Auth::logout();
        return Response::json(array('data'=> 'You are successfully logged out.','status'=> true));
	}

	

}
