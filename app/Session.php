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
    $first_scene = ($this->sceneModel->getScenes($activite_id))[0]->ID_SCENE;
    $date = new \DateTime();
    $now = $date->format('Y-m-d\ H:i:s');

     DB::table('sessions')->insert([
        'user_id' => $user_id,
        'activite_id' => $activite_id,
        'SESSION_DATE' => $now,
        'SESSION_STARTED' => true,
        'CURRENT_SCENE' => $first_scene,
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
    ->orderBy('SESSION_DATE', 'DESC')
    ->limit(1)
    ->get();

    return $session;
  }

  public function getStep($user_id, $activite_id)
  {
    $step = DB::table('sessions')
    ->select('CURRENT_SCENE')
    ->where('activite_id', '=', $activite_id)
    ->where('user_id', '=', $user_id)
    ->orderBy('SESSION_DATE', 'DESC')
    ->limit(1)
    ->get();

    return $step;
  }

  public function nextSceneToThisSession($infos_session, $next_scene_id, $penultimate)
  {
    $session = Session::find($infos_session[0]->id);
    $session->CURRENT_SCENE = $next_scene_id;
    if ($penultimate) {
      $session->SESSION_FINISHED = true;
    }
    $session->save();

  }

}
