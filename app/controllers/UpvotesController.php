<?php

class UpvotesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($post_id)
	{
		$user_id = Auth::user()->id;

		$result = DB::table('upvote')->where('user_id', $user_id)
									->where('post_id', $post_id)
									->first();

		if(!is_null($result))
		{
			return json_encode(array('data'=> 'User upvoted this post','status'=> true));
		}
		else
		{
			return json_encode(array('data'=> 'No upvote for this post','status'=> false));
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
        $upvote = new Upvote;
        $upvote->user_id = Auth::user()->id;
        $upvote->post_id = $post_id;
       	
        try {
            $upvote->save();
        }
        catch(\Exception $e)
        {
            return json_encode(array('data'=> 'Unable to save post upvote.','status'=> false));
        }

        $upvotedPost = Upvote::find($upvote->id);
        if(!is_null($upvotedPost))
        {
        	$post = Post::find($post_id);

			$post->upvote = $post->upvote + 1;

			$post->save();
        }

        return json_encode(array('data'=> $upvotedPost,'status'=> true));
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
		$user_id = Auth::user()->id;

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
	            return json_encode(array('data'=> 'Unable to delete post upvote.','status'=> false));
	        }

	        return json_encode(array('data'=> 'Post upvote removed.','status'=> true));
		}

	}


}
