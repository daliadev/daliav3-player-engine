<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activite extends Model
{
  
  	protected $table = 'activites';
    protected $primaryKey = 'id_activite';
    protected $fillable = ['nom_activite', 'theme_activite', 'descript_activite'];

}
