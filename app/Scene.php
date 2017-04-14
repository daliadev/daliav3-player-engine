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
    ->join('sequences', 'scenes.sequence_id', '=', 'sequences.id')
    ->join('activites', 'sequences.activite_id', '=', 'activites.id')
    ->where('activites.id', '=', $id)
    ->orderBy('position', 'ASC')
    ->get();

    return $scenes;
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

  public function activeScene($user_id, $activite_id)
  {
    $active_scene = DB::table('sessions')
    ->select('curent_scene')
    ->where('user_id', '=', $user_id)
    ->where('activite_id', '=', $activite_id)
    ->get();

    $scenes_list = json_decode($active_scene, true);;

    $scene = DB::table('scenes')
    ->select('scenes.*')
    ->join('sequences', 'scenes.sequence_id', '=', 'sequences.id')
    ->join('activites', 'sequences.activite_id', '=', 'activites.id')
    ->where('activites.id', '=', $activite_id)
    ->where('scenes.position', '=', $scenes_list)
    ->orderBy('position', 'ASC')
    ->get();

    return $scene;
  }

  public function sceneCount($user_id, $activite_id)
  {
    $scene_count = DB::table('sessions')
    ->select('scene_count')
    ->where('user_id', '=', $user_id)
    ->where('activite_id', '=', $activite_id)
    ->limit(1)
    ->get();

    $count = json_decode($scene_count, true);
    return $count[0]['scene_count'];
  }
}
