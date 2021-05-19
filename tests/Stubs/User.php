<?php

namespace Artificertech\RelationshipEvents\Tests\Stubs;

use Artificertech\RelationshipEvents\Concerns\HasRelationshipEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class User extends Model
{
    use HasRelationshipEvents;

    protected $fillable = ['name'];

    protected static function booting()
    {
        static::hasManyCreating('posts', function ($user, $post) {
            if ($post->name == 'badName') return false;
        });

        static::hasManySaving('posts', function ($user, $post) {
            if ($post->name == 'badName') return false;
        });

        static::hasOneCreating('profile', function ($user, $profile) {
            if ($profile->name == 'badName') return false;
        });

        static::hasOneSaving('profile', function ($user, $profile) {
            if ($profile->name == 'badName') return false;
        });

        static::morphOneCreating('address', function ($user, $address) {
            if ($address->name == 'badName') return false;
        });

        static::morphOneSaving('address', function ($user, $address) {
            if ($address->name == 'badName') return false;
        });
    }

    public static function setupTable()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function profile()
    {
        return $this->hasOne(Profile::class)->withEvents();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->withEvents();
    }

    public function address()
    {
        return $this->morphOne(Address::class, 'addressable')->withEvents();
    }
}
