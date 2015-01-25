<?php

class PostController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(isset($_GET['access_token']))
		{
			$user_id = DB::table('users')->where('access_token', $_GET['access_token'])->pluck('id');

			if(!is_null($user_id))
			{
				$validator = Validator::make(
		            array(
		                'limit' => Input::get('limit'),
		                'page' => Input::get('page')
		            ),
		            array(
		                'limit' => 'sometimes|numeric',
		                'page' => 'sometimes|numeric'
		            )
		        );

		        if($validator->fails())
		        {
		            return Response::json(array('data'=> 'Invalid pagination request.','status'=> false));
		        }

		        if((Input::has('limit')) && (Input::get('limit') < 20))
		        	$limit = Input::get('limit');
		        else
		        	$limit = 20;

				$posts = Post::orderBy('id', 'desc')->paginate($limit);
				
				return Response::json($posts);
			}
			else
			{
				return Response::json(array('data'=> 'Invalid access token.','status'=> false));
			}
		}
		else
		{
			return Response::json(array('data'=> 'No access token passed.','status'=> false));
		}
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
		if(isset($_GET['access_token']))
		{
			$user_id = DB::table('users')->where('access_token', $_GET['access_token'])->pluck('id');

			if(!is_null($user_id))
			{
				Input::merge(array_map('trim', Input::all()));
		        $validator = Validator::make(
		            Input::get(),
		            array(
		                'title' =>'required|min:5',
		                'description' =>'required|min:10',
		            ));

		        if($validator->fails())
		        {
		            return Response::json(array('data'=> $validator->messages(),'status'=> false));
		        }

		        $post = new Post;
		        $post->title = Input::get('title');
		        $post->description = Input::get('description');
		        $post->image = Input::get('image')?Input::get('image') : "";
		        $post->location = Input::get('location')?Input::get('location') : "";
		        $post->upvotes = 0 ;
		        $post->downvotes = 0 ;
		        $post->parent_id = 0 ;
		        $post->status = 0 ;

		        try {
		            $post->save();
		        }
		        catch(\Exception $e)
		        {
		            return Response::json(array('data'=> 'Unable to save Post.','status'=> false));
		        }

		        $insertedPost = Post::find($post->id);

		        return Response::json(array('data'=> $insertedPost,'status'=> false));
		    }
		    else
			{
				return Response::json(array('data'=> 'Invalid access token.','status'=> false));
			}
		}
		else
		{
			return Response::json(array('data'=> 'No access token passed.','status'=> false));
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(isset($_GET['access_token']))
		{
			$user_id = DB::table('users')->where('access_token', $_GET['access_token'])->pluck('id');

			if(!is_null($user_id))
			{
				$validator = Validator::make(
		            array('id' => $id),
		            array('id' => 'required|numeric')
		        );

		        if($validator->fails())
		        {
		            return Response::json(array('data'=> 'Invalid Post Id.','status'=> false));
		        }
		        $post = Post::find($id);

		        if (!$post) {
		        	return Response::json(array('data'=> 'Post does not exist.','status'=> false));
		        }

		        return Response::json(array('data'=> $post,'status'=> true));
		    }
		    else
			{
				return Response::json(array('data'=> 'Invalid access token.','status'=> false));
			}
		}
		else
		{
			return Response::json(array('data'=> 'No access token passed.','status'=> false));
		}
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
		if(isset($_GET['access_token']))
		{
			$user_id = DB::table('users')->where('access_token', $_GET['access_token'])->pluck('id');

			if(!is_null($user_id))
			{
				Input::merge(array_map('trim', Input::all()));
		        $validator = Validator::make(
		            array('id' => $id),
		            array('id' => 'required|numeric')
		        );

		        if($validator->fails())
		        {
		            return Response::json(array('data'=> 'Invalid Post Id.','status'=> false));
		        }

		        $post = Post::find($id);

		        if (!$post) {
		            return Response::json(array('data'=> 'Post does not exist.','status'=> false));
		        }

		        $post->title = Input::get('title')?Input::get('title'):$post->title;
		        $post->description = Input::get('description')?Input::get('description'):$post->description;
		        $post->image = Input::get('image')?Input::get('image'):$post->image;
		        $post->location = Input::get('location')?Input::get('location'):$post->location;
		        $post->status = Input::get('status')?((Input::get('status')==='true') ? 1 : 0):$post->status;
		        $post->response = Input::get('response')?Input::get('response'):$post->response;

		        try {
		            $post->save();
		        }
		        catch(\Exception $e)
		        {
		            return Response::json(array('data'=> 'Unable to save post.','status'=> false));
		        }

				$updatedPost = Post::find($id);        

		        return Response::json(array('data'=> $updatedPost,'status'=> true));
		    }
		    else
			{
				return Response::json(array('data'=> 'Invalid access token.','status'=> false));
			}
		}
		else
		{
			return Response::json(array('data'=> 'No access token passed.','status'=> false));
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(isset($_GET['access_token']))
		{
			$user_id = DB::table('users')->where('access_token', $_GET['access_token'])->pluck('id');

			if(!is_null($user_id))
			{
				$validator = Validator::make(
		            array('id' => $id),
		            array('id' => 'required|numeric')
		        );

		        if($validator->fails())
		        {
		            return Response::json(array('data'=> 'Invalid Post Id.','status'=> false));
		        }

		        $post = Post::withTrashed()->find($id);

		        if (!$post) {
		        	return Response::json(array('data'=> 'Post does not exist.','status'=> false));
		        }
		        if ($post->trashed()) {
		            return Response::json(array('data'=> 'Post already deleted.','status'=> false));
		        }

		        try{

		           $post->delete();
		 //        Delete other things also
		        }
		        catch (Exception $e)
		        {
		            return Response::json(array('data'=> 'Unable to delete Post.','status'=> false));
		        }

		        return Response::json(array('data'=> 'Post deleted.','status'=> true));
		    }
		    else
			{
				return Response::json(array('data'=> 'Invalid access token.','status'=> false));
			}
		}
		else
		{
			return Response::json(array('data'=> 'No access token passed.','status'=> false));
		}
	}


}
