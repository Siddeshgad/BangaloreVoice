<?php

class DownvotesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($post_id)
	{
		$user_id = Auth::user()->id;

		$result = DB::table('downvote')->where('user_id', $user_id)
									->where('post_id', $post_id)
									->first();

		if(!is_null($result))
		{
			return json_encode(array('data'=> 'User downvoted this post','status'=> true));
		}
		else
		{
			return json_encode(array('data'=> 'No downvote for this post','status'=> false));
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
		$downvote = new Downvote;
        $downvote->user_id = Auth::user()->id;
        $downvote->post_id = $post_id;
       	
        try {
            $downvote->save();
        }
        catch(\Exception $e)
        {
            return json_encode(array('data'=> 'Unable to save post downvote.','status'=> false));
        }

        $downvotedPost = Downvote::find($downvote->id);
        if(!is_null($downvotedPost))
        {
        	$post = Post::find($post_id);

			$post->downvote = $post->downvote + 1;

			$post->save();
        }

        return json_encode(array('data'=> $downvotedPost,'status'=> true));
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
	public function destroy($post_id,$downvote_id)
	{
		$user_id = Auth::user()->id;

		$result = DB::table('downvote')->where('user_id', $user_id)
									->where('post_id', $post_id)
									->pluck('id');

		if(!is_null($result))
		{
			try{
	           Downvote::destroy($result);
	        }
	        catch (Exception $e)
	        {
	            return json_encode(array('data'=> 'Unable to delete post downvote.','status'=> false));
	        }

	        return json_encode(array('data'=> 'Post downvote removed.','status'=> true));
		}

	}


}
