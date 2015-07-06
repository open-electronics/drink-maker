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
    /**
     * Total parts composing the drink
     * @var
     */
    public $totalParts;

    /**
     * Calculates total parts and max available
     */
    function __construct()
    {
        $ingredients=$this->Ingredients();
        foreach($ingredients as $i){
            $this->totalParts+=$i->needed;
        }
        for($i=0;$i<count($ingredients);$i++){
            $max=$this->roundToMultiple($ingredients[$i]->stock/$ingredients[$i]->needed)*$this->totalParts;
            if($max<$this->maxAvailable)$this->maxAvailable=$max;
        }

    }

    /**
     * Rounds a number so that it is multiple of another one
     * @param $number
     * @param int $multiple
     * @return float
     */
    private function roundToMultiple($number,$multiple=2){
        return (floor(($number)%$multiple==0)? floor($number) :
            floor(($number+$multiple)/$multiple)*$multiple;
    }

    /**
     * Orders a drink
     * @param $name
     * @param $volume
     */
    public function orderDrink($name,$volume){
        foreach($this->Ingredients() as $i){
            $i->stock-=$this->roundToMultiple($volume*($i->needed/$this->totalParts));
            $i->save();
        }
        $order = new Order();
        $order->name=$name;
        $order->volume=$volume;
        $order->status=env('default_status',1);
        $order->save();
    }

    /**
     * Restores ingredients after a deletion
     * @param $volume
     */
    public function restoreDrinkIngredients($volume){
        foreach($this->Ingredients() as $i){
            $i->stock+=$this->roundToMultiple($volume*($i->needed/$this->totalParts));
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
        return $this->belongsToMany(Ingredient::class,'drink_id','ingredient_id')->withPivot('needed');
    }

}