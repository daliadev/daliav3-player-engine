<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

use App\Scene;

class Session extends Model
{
  protected $primaryKey = 'SESSION_ID';

  private $sceneModel;

  public $timestamps = false;

  public function __construct()
  {
    $this->sceneModel = new Scene;
  }

  /**
  * Insert un nouvelle session pour ce User pour cette Activite
  * @param  int  $user_id : ID du User
  * @param  int  $activite_id : ID de l'activité
  */
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

  /**
  * Retourne les infos relatives à la derniere session de ce User pour
  * cette Activite
  * @param  int  $user_id : ID du User
  * @param  int  $activite_id : ID de l'activité
  * @return
  */
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

  /**
  * Retourne l'ID de la scene active pour la derniere session de cette Activite
  * pour ce User
  * @param  int  $user_id : ID du User
  * @param  int  $activite_id : ID de l'activité
  * @return
  */
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

  /**
  * Update la CURRENT_SCENE (et le SESSION_FINISHED si besoin) vers la prochaine
  * scene de cette activité
  * @param       $infos_session : session actuelle
  * @param  int  $next_scene_id : ID de la prochaine scene de cette activité
  * @param bool $penultimate : 1 si CURRENT_SCENE = avant derniere ; 0 si non
  * @return
  */
  public function nextSceneToThisSession($infos_session, $next_scene_id, $penultimate)
  {
    $session = Session::find($infos_session[0]->SESSION_ID);
    $session->CURRENT_SCENE = $next_scene_id;
    if ($penultimate) {
      $session->SESSION_FINISHED = true;
    }
    $session->save();

  }

}
