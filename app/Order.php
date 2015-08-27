<?php
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 05/07/15
 * Time: 21:59
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class Order extends Model{
    /**
     * The database table used by the model
     * @var string
     */
    protected $table = "orders";

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name','volume','status'];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [];
    protected $dates = ['created_at'];

    public function deleteOrder(){
        $this->status=4;
        $this->save();
        $this->Drink->restoreDrinkIngredients($this->volume);
    }
    /**
     * The drink ordered
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Drink(){
        return $this->belongsTo('App\Drink','drink_id');
    }

}