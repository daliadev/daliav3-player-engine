<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;

class Scene extends Model
{

  public function sequence()
  {
      return $this->belongsTo(\App\Sequence::class);
  }

  public function getScenes($id)
  {
    $scenes = DB::table('scenes')
    ->select('scenes.id')
    ->join('sequences_scenes', 'sequences_scenes.id_scene', '=', 'scenes.id')
    ->join('activites_sequences', 'activites_sequences.id_sequence', '=', 'sequences_scenes.id_sequence')
    ->where('activites_sequences.id_activite', '=', $id)
    ->orderBy('sequences_scenes.position', 'ASC')
    ->get();

    return $scenes;
  }

  public function getActiveScene($user_id, $activite_id)
  {
    $active_scene = DB::table('sessions')
    ->select('*')
    ->where('user_id', '=', $user_id)
    ->where('activite_id', '=', $activite_id)
    ->orderBy('updated_at', 'DESC')
    ->limit(1)
    ->get();

    $id_scene = $active_scene[0]->curent_scene;

    $scene = DB::table('scenes')
    ->select('*')
    ->where('id', '=', $id_scene)
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
    $position = DB::table('sequences_scenes')
    ->select('sequences_scenes.*')
    ->join('activites_sequences', 'activites_sequences.id_sequence', '=', 'sequences_scenes.id_sequence')
    ->where('activites_sequences.id_activite', '=', $activite_id)
    ->where('sequences_scenes.id_scene', '=', $curent_scene_id)
    ->orderBy('sequences_scenes.position', 'ASC')
    ->get();

    return $position[0]->position;
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

  public function sceneCount($user_id, $activite_id)
  {
    $scene_count = DB::table('sessions')
    ->select('scene_count')
    ->where('user_id', '=', $user_id)
    ->where('activite_id', '=', $activite_id)
    ->limit(1)
    ->get();

    return $scene_count[0]->scene_count;
  }

  public function getNextSceneId($activite_id, $curent_scene_id)
  {
    // dans sequence_scene, select l'id_scene où position = position+1 ET
    //
    $position = DB::table('sequences_scenes')
    ->select('sequences_scenes.*')
    ->join('activites_sequences', 'activites_sequences.id_sequence', '=', 'sequences_scenes.id_sequence')
    ->where('activites_sequences.id_activite', '=', $activite_id)
    ->where('sequences_scenes.id_scene', '=', $curent_scene_id)
    ->orderBy('sequences_scenes.position', 'ASC')
    ->get();
    $next_position = ($position[0]->position)+1;

    $next_id = DB::table('sequences_scenes')
    ->select('sequences_scenes.*')
    ->join('activites_sequences', 'activites_sequences.id_sequence', '=', 'sequences_scenes.id_sequence')
    ->where('activites_sequences.id_activite', '=', $activite_id)
    ->where('sequences_scenes.position', '=', $next_position)
    ->limit(1)
    ->get();
    return $next_id[0]->id_scene;
  }
}
