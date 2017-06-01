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

  public function activeScene($user_id, $activite_id)
  {
    // A FAIRE : A REVOIR
    $active_scene = DB::table('sessions')
    ->select('curent_scene')
    ->where('user_id', '=', $user_id)
    ->where('activite_id', '=', $activite_id)
    ->orderBy('updated_at', 'DESC')
    ->limit(1)
    ->get();

    $scenes_list = json_decode($active_scene, true);

    $scene = DB::table('scenes')
    ->select('scenes.*')
    ->join('sequences_scenes', 'sequences_scenes.id_scene', '=', 'scenes.id')
    ->join('activites_sequences', 'activites_sequences.id_sequence', '=', 'sequences_scenes.id_sequence')
    ->where('activites_sequences.id_activite', '=', $activite_id)
    ->where('sequences_scenes.position', '=', $scenes_list)
    ->orderBy('sequences_scenes.position', 'ASC')
    ->get();

    return $scene;
  }

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

  public function activiteIsStarted($user_id, $activite_id)
  {
    $activite_user = DB::table('sessions')
    ->select('id')
    ->where('user_id', '=', $user_id)
    ->where('activite_id', '=', $activite_id)
    ->get();

    $exist = json_decode($activite_user, true);
    return $exist;
  }

  public function sceneCount($activite_id)
  {
    $scene_count = $this->getScenes($activite_id);
    $scene_count = count($scene_count);

    return $scene_count;
  }

  public function getNextSceneId($activite_id, $curent_scene_id)
  {
    // dans sequence_scene, select l'id_scene où position = position+1 ET
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
