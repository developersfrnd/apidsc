<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModelCoinHistory extends Model
{
    protected $table = 'model_coin_history';

    protected $fillable = ['model_id','coin','earn_from'];
    

    // public update_history($history_id, $coin, $model_id, $earn_from){

    //     $this->where('id', $history_id)
    // }

}
