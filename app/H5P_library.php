<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class H5P_library extends Model
{

  	protected $table = 'h5p_libraries';
    protected $primaryKey = 'id';
    protected $fillable = [];

    public function getPreloaded($id)
    {
      $preloaded = DB::table('h5p_libraries')
      ->select('preloaded_js', 'preloaded_css')
      ->where('id', '=', $id)
      ->get();

      return $preloaded;
    }

}
