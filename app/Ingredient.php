<?php
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 05/07/15
 * Time: 21:59
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class Ingredient extends Model{
    /**
     * The database table used by the model
     * @var string
     */
    protected $table = "ingredients";

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name','position','stock'];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [];

    /**
     * All dirnks using that ingredient
     * @return $this
     */
    public function drinksWhoUseIt(){
        return $this->belongsToMany(Drink::class,'ingredient_id','drink_id')->withPivot('needed');
    }

}