<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Upvote extends Eloquent{

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'upvote';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

    protected $primaryKey="id";

    protected $dates = ['deleted_at'];

    public static $rules = array(
    'user_id'=>'required|integer',
    'post_id'=>'required|integer',
    );

}
