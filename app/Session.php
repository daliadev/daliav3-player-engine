<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

use App\Scene;

class Session extends Model
{
  private $sceneModel;

  public function __construct()
  {
    $this->sceneModel = new Scene;
  }

  public function startNewActivite($user_id, $activite_id)
  {
    $scene_count = count($this->sceneModel->getScenes($activite_id));

     DB::table('sessions')->insert([
        'user_id' => $user_id,
        'activite_id' => $activite_id,
        'scene_count' => $scene_count,
        'curent_scene' => 1
     ]);
  }
}
