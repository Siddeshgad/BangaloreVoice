<?php

class UpvotesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($post_id)
	{
		if(isset($_GET['access_token']))
		{
			$user_id = DB::table('users')->where('access_token', $_GET['access_token'])->pluck('id');

			if(!is_null($user_id))
			{
				$result = DB::table('upvote')->where('user_id', $user_id)
											->where('post_id', $post_id)
											->first();

				if(!is_null($result))
				{
					return Response::json(array('data'=> 'User upvoted this post','status'=> true));
				}
				else
				{
					return Response::json(array('data'=> 'No upvote for this post','status'=> false));
				}
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
	public function store($post_id)
	{
		if(isset($_GET['access_token']))
		{
			$user_id = DB::table('users')->where('access_token', $_GET['access_token'])->pluck('id');

			if(!is_null($user_id))
			{
		        $upvote = new Upvote;
		        $upvote->user_id = $user_id;
		        $upvote->post_id = $post_id;
		       	
		        try {
		            $upvote->save();
		        }
		        catch(\Exception $e)
		        {
		            return Response::json(array('data'=> 'Unable to save post upvote.','status'=> false));
		        }

		        $upvotedPost = Upvote::find($upvote->id);
		        if(!is_null($upvotedPost))
		        {
		        	$post = Post::find($post_id);

					$post->upvote = $post->upvote + 1;

					$post->save();
		        }

		        return Response::json(array('data'=> $upvotedPost,'status'=> true));
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
		//
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
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($post_id,$upvote_id)
	{
		if(isset($_GET['access_token']))
		{
			$user_id = DB::table('users')->where('access_token', $_GET['access_token'])->pluck('id');

			if(!is_null($user_id))
			{
				$result = DB::table('upvote')->where('user_id', $user_id)
											->where('post_id', $post_id)
											->pluck('id');

				if(!is_null($result))
				{
					try{
			           Upvote::destroy($result);
			        }
			        catch (Exception $e)
			        {
			            return Response::json(array('data'=> 'Unable to delete post upvote.','status'=> false));
			        }

			        return Response::json(array('data'=> 'Post upvote removed.','status'=> true));
				}
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
