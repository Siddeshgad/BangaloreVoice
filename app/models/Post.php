<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Post extends Eloquent{

	use SoftDeletingTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

    protected $primaryKey="id";

    protected $dates = ['deleted_at'];

    public static $rules = array(
    'title'=>'required|min:5',
    'description'=>'required|min:10',
    'status'=>'required',
    //'avatar'=>'image',
    );

}
