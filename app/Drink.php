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
    protected $fillable = ['name','photo'];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = [];
    /**
     * Max drink volume available
     * @var float
     */
    public $maxAvailable;

    public $timestamps=false;
    /**
     * Calculates total parts and max available
     */
    function getAvailable()
    {
        $ingredients=$this->Ingredients;
        $parts=$this->totalParts();
        for($i=0;$i<count($ingredients);$i++){
            if($ingredients[$i]->position>=0){
                $max=$this->roundToMultiple($ingredients[$i]->stock/$ingredients[$i]->pivot->needed)*$parts;
            }else{
                $max=0;
            }
            if($max<$this->maxAvailable||$i==0)$this->maxAvailable=$max;
            if($max<2)return 0;
        }
        return $this->maxAvailable;
    }

    /**
     * Rounds a number so that it is multiple of another one
     * @param $number
     * @param int $multiple
     * @return float
     */
    private function roundToMultiple($number,$multiple=2){
        return (floor(($number)%$multiple)==0)? floor($number) : floor(($number+$multiple)/$multiple)*$multiple;
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
    public function orderDrink($name,$volume){
        $parts=$this->totalParts();
        foreach($this->Ingredients as $i){
            $i->stock-=$this->roundToMultiple($volume*($i->pivot->needed/$parts));
            $i->save();
        }
        $order = new Order();
        $order->name=$name;
        $order->volume=$volume;
        $order->status=env('default_status',1);
        $order->Drink()->associate($this);
        $order->save();
    }

    /**
     * Restores ingredients after a deletion
     * @param $volume
     */
    public function restoreDrinkIngredients($volume){
        $parts=$this->totalParts();
        foreach($this->Ingredients as $i){
            $i->stock+=$this->roundToMultiple($volume*($i->pivot->needed/$parts));
            $i->save();
        }
    }
    /**
     * All the orders featuring that drink
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Orders(){
        return $this->hasMany(Order::class,'drink_id');
    }
    /**
     * All ingredients of the drink
     * @return $this
     */
    public function Ingredients(){
        return $this->belongsToMany(Ingredient::class,'drinks_ingredients','drink_id','ingredient_id')->withPivot('needed');
    }

}