<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Scene extends Model
{
  protected $primaryKey = 'ID_SCENE';

  public function sequence()
  {
      return $this->belongsTo(\App\Sequence::class);
  }

  /**
  * Retourne les infos relatives aux scenes de cette Activite
  * @param  int  $id : ID de l'activité
  * @return
  */
  public function getScenes($id)
  {
    $scenes = DB::table('scenes')
    ->select('scenes.ID_SCENE')
    ->join('cut', 'cut.ID_SCENE', '=', 'scenes.ID_SCENE')
    ->where('cut.ID_ACTIVITE', '=', $id)
    ->orderBy('cut.POSITION_INDEX', 'ASC')
    ->get();

    return $scenes;
  }

  /**
  * Retourne la derniere session de ce User dans cette Activité
  * @param int $user_id : Id du User
  * @param int $activite_id : Id de l'activité
  * @return
  */
  public function getActiveScene($user_id, $activite_id)
  {
    $active_scene = DB::table('sessions')
    ->select('*')
    ->where('user_id', '=', $user_id)
    ->where('activite_id', '=', $activite_id)
    ->orderBy('SESSION_DATE', 'DESC')
    ->limit(1)
    ->get();

    $id_scene = $active_scene[0]->CURRENT_SCENE;

    $scene = DB::table('scenes')
    ->select('*')
    ->where('ID_SCENE', '=', $id_scene)
    ->get();

    return $scene;
  }

  /**
  * Retourne la position de la scene ayant cet ID au sein de l'activité ayant
  * cet ID
  * @param int $activite_id : Id de l'activité
  * @param int $curent_scene_id : Id de la scene qu'on souhaite "localiser"
  * @return int : Position de la scene dans l'activite
  */
  public function getPosition($activite_id, $curent_scene_id)
  {
    // Recuperer la position de la scene ayant cet ID au sein de l'activité ayant cet ID
    $position = DB::table('cut')
    ->select('POSITION_INDEX')
    ->where('ID_ACTIVITE', '=', $activite_id)
    ->where('ID_SCENE', '=', $curent_scene_id)
    ->limit(1)
    ->get();

    return $position[0]->POSITION_INDEX;
  }

  /**
  * Retourne le compte du nombre de scenes composant cette activité.
  * @param int $activite_id : Id de l'activité
  * @return int : Count
  */
  public function sceneCount($activite_id)
  {
    $scene_count = $this->getScenes($activite_id);
    $scene_count = count($scene_count);

    return $scene_count;
  }

  /**
  * Recupere et retourne l'Id la scene qui suivra immediatement la Current_Scene.
  * @param int $activite_id : Id de l'activité
  * @param int $curent_scene_id : Id la scene courante
  * @return int : Id de la scene suivante
  */

  public function getNextSceneId($activite_id, $curent_scene_id)
  {
    $position = DB::table('cut')
    ->select('POSITION_INDEX')
    ->where('ID_ACTIVITE', '=', $activite_id)
    ->where('ID_SCENE', '=', $curent_scene_id)
    ->limit(1)
    ->get();

    $next_position = ($position[0]->POSITION_INDEX)+1;

    $next_id = DB::table('cut')
    ->select('ID_SCENE')
    ->where('ID_ACTIVITE', '=', $activite_id)
    ->where('POSITION_INDEX', '=', $next_position)
    ->limit(1)
    ->get();
    return $next_id[0]->ID_SCENE;
  }
}
