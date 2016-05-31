<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'url', 'name', 'description'
    ];

    public function tag()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

}
