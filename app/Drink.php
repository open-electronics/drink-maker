<?php
/**
 * Created by PhpStorm.
 * User: marcobellan
 * Date: 05/07/15
 * Time: 21:59
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
class Drink extends Model{
    /**
     * The database table used by the model
     * @var string
     */
    protected $table = "drinks";

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name','photo','volume'];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [];


    public $timestamps=false;
    /**
     * Calculates total parts and max available
     */
    function getAvailable()
    {
        $ingredients=$this->Ingredients;
        $parts=$this->totalParts();
        for($i=0;$i<count($ingredients);$i++){
            if($ingredients[$i]->position<0 ||
                (($ingredients[$i]->pivot->needed/$parts)*$this->volume)>$ingredients[$i]->stock){
                return false;
            }
        }
        return true;
    }

    /**
     * Rounds a number so that it is multiple of another one
     * @param $number
     * @param int $multiple
     * @return float
     */
    private function roundToMultiple($number,$multiple=2){
        return (floor(($number)%$multiple)==0)? floor($number) : floor($number/$multiple)*$multiple;
    }
    private  function  totalParts(){
        $ingredients=$this->Ingredients;
        $part=0;
        foreach($ingredients as $i){
            $part+=$i->pivot->needed;
        }
        return $part;
    }
    /**
     * Orders a drink
     * @param $name
     * @param $volume
     */
    public function orderDrink($name){
        $parts=$this->totalParts();
        foreach($this->Ingredients as $i){
            $i->stock-=$this->volume*($i->pivot->needed/$parts);
            $i->save();
        }
        $order = new Order();
        $order->name=$name;
        $order->status=Settings::initial_status();
        $order->Drink()->associate($this);
        $order->save();
        return $order->id;
    }

    /**
     * Restores ingredients after a deletion
     * @param $volume
     */
    public function restoreDrinkIngredients(){
        $parts=$this->totalParts();
        foreach($this->Ingredients as $i){
            $i->stock+=$this->roundToMultiple($this->volume*($i->pivot->needed/$parts));
            $i->save();
        }
    }
    /**
     * All the orders featuring that drink
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Orders(){
        return $this->hasMany('App\Order','drink_id');
    }
    /**
     * All ingredients of the drink
     * @return $this
     */
    public function Ingredients(){
        return $this->belongsToMany('App\Ingredient','drinks_ingredients','drink_id','ingredient_id')->withPivot('needed');
    }

}