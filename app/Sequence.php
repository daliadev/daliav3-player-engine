<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{

  public function scenes()
    {
        return $this->hasMany(\App\Scene::class);
    }

  public function activite()
  {
      return $this->belongsTo(\App\Activite::class);
  }
}
