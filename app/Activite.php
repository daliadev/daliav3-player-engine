<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Activite extends Model
{
  public function sequences()
    {
        return $this->hasMany(\App\Sequence::class);
    }

  public function getActivite($id)
  {
    $activite = DB::table('activites')
    ->select('activites.*')
    ->where('id', '=', $id)
    ->get();

  return $activite;
  }
}
