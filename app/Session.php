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

  public function startNewSession($user_id, $activite_id)
  {
    $scene_count = count($this->sceneModel->getScenes($activite_id));
    $first_scene = ($this->sceneModel->getScenes($activite_id))[0]->id;

     DB::table('sessions')->insert([
        'user_id' => $user_id,
        'activite_id' => $activite_id,
        'scene_count' => $scene_count,
        'curent_scene' => $first_scene,
        'finish' => false
     ]);
  }

  public function getSessions($user_id, $activite_id)
  {
    $sessions = DB::table('sessions')
    ->select('*')
    ->where('activite_id', '=', $activite_id)
    ->where('user_id', '=', $user_id)
    ->get();

    return $sessions;
  }

  public function getLastSession($user_id, $activite_id)
  {
    $session = DB::table('sessions')
    ->select('*')
    ->where('activite_id', '=', $activite_id)
    ->where('user_id', '=', $user_id)
    ->orderBy('updated_at', 'DESC')
    ->limit(1)
    ->get();

    return $session;
  }

  public function getStep($user_id, $activite_id)
  {
    $step = DB::table('sessions')
    ->select('curent_scene')
    ->where('activite_id', '=', $activite_id)
    ->where('user_id', '=', $user_id)
    ->orderBy('updated_at', 'DESC')
    ->limit(1)
    ->get();

    return $step;
  }

  public function nextSceneToThisSession($session_id, $next_scene_id)
  {
    $session = Session::find($session_id);
    $session->curent_scene = $next_scene_id;
    $session->save();

    echo '<pre>';
    var_dump($session->curent_scene);
    echo '</pre>';
    die();
    if (1 == 0) {
      $session->finish = true;
      $session->save();
    }
  }

}
