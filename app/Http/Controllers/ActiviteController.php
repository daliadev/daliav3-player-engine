<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Repositories\ActiviteRepository;

use App\User;
use App\Scene;
use App\Sequence;
use App\Activite;
use App\Session;

use Response;

//JSON Web Token
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ActiviteController extends Controller
{
  protected $gameRepository;
  protected $nbrPerPage = 10;

  private $sceneModel;
  private $sequenceModel;
  private $activiteModel;
  private $sessionModel;

  private $request;

  public function __construct(ActiviteRepository $activiteRepository)
  {
    // Modele(s)
    $this->request = new Request;
    $this->sceneModel = new Scene;
    $this->sequenceModel = new Sequence;
    $this->activiteModel = new Activite;
    $this->sessionModel = new Session;

    // Repositori(es)
    $this->activiteRepository = $activiteRepository;

    // Middleware(s)
    $this->middleware('auth')->only(['store', 'create', 'show', 'update', 'destroy', 'edit', 'goNextScene',
      'goPreviousScene']);
  }

  /**
   * Affiche la liste de TOUTES les activite
   * Ex d'url : api/activite
   * @return Response format JSON
   */
  public function index()
  {
    // Prepare la pagination et les liens pour la vue
    $links = $this->activiteRepository->getPaginate($this->nbrPerPage)->setPath('')->render();

    // Recupere la liste des activites
    $activites = $this->activiteRepository->getPaginate($this->nbrPerPage);

    return view('activite', compact('activites', 'links'));

    // $activites = Activite::all();
    // return Response::json([
		// 	'activites' => $activites
		// ], 200);
  }

  /**
  * Affiche l'activité dont l'ID est passé en parametre
  * Ex d'url : api/activite/$id
  * @param  int  $id : Clé primaire dans la BDD de l'activité a afficher
  * @return
  */
  public function show($activite_id)
  {
    // A FAIRE : remplacer ci-dessous par middleware (?)
    $user_id = Auth::id();

    $activite_is_started = $this->sceneModel->activiteIsStarted($user_id, $activite_id);

    // Recuperation des scenes composant l'activité n°$id :
    $scenes_list = $this->sceneModel->getScenes($activite_id);

    // On verifie si le user a deja commencé cette activité
    if(empty($activite_is_started)) {
      //Si cette activité existe
      if ($this->activiteModel->findOrFail($activite_id)) {
        $this->sessionModel->startNewActivite($user_id, $activite_id);
        // Puis recharger la page
        return redirect()->route('activite.show', ['activite_id' => $activite_id]);
        // Sinon => 404
      }
    } else {
      // Afficher la scene active
      $active_scene = $this->sceneModel->activeScene($user_id, $activite_id);
      // Checker le "type" de sequence. Si "exercice" => charger le js
      $scene_count = $this->sceneModel->sceneCount($user_id, $activite_id);
      $position = 1;
      // var_dump($active_scene);
      // die();
      $last_scene = ($position == $scene_count) ? 1 : 0;
      $first_scene = ($position == 1) ? 1 : 0;
      return view('scene', compact(
        'scenes_list',
        'activite_id',
        'active_scene',
        'last_scene',
        'first_scene',
        'position'
      ));
    }
  }

  public function create()
  {
  return view('createActivite', compact(
    ''
  ));
  }

  public function goNextScene($activite_id)
  {
    // On verifie que l'activité existe
    if ($this->activiteModel->findOrFail($activite_id)) {
      $user_id = Auth::id();

      $active_scene = $this->sceneModel->activeScene($user_id, $activite_id);
      $scene_count = $this->sceneModel->sceneCount($user_id, $activite_id);

      $next_scene = ($active_scene[0]->position)+1;

      // Si on est deja a la derniere scene :
      if ($active_scene[0]->position == $scene_count) {
        return redirect()->route('activite.show', ['activite_id' => $activite_id]);
      }

      $session_id = $this->sceneModel->activiteIsStarted($user_id, $activite_id);
      $session = Session::find($session_id[0]['id']);
      $session->curent_scene = $next_scene;
      $session->save();

      return redirect()->route('activite.show', ['activite_id' => $activite_id]);
    }
  }

  public function goPreviousScene($activite_id)
  {
    // On verifie que l'activité existe
    if ($this->activiteModel->findOrFail($activite_id)) {
      $user_id = Auth::id();

      $active_scene = $this->sceneModel->activeScene($user_id, $activite_id);

      $previous_scene = ($active_scene[0]->position)-1;

      // Si on est deja a la premiere scene
      if ($active_scene[0]->position == 1) {
        return redirect()->route('activite.show', ['activite_id' => $activite_id]);
      }

      $session_id = $this->sceneModel->activiteIsStarted($user_id, $activite_id);
      $session = Session::find($session_id[0]['id']);
      $session->curent_scene = $previous_scene;
      $session->save();

      return redirect()->route('activite.show', ['activite_id' => $activite_id]);
    }
  }

  public function viewResults($activite_id){
      // A FAIRE : on ne peut acceder a result QUE si on a suivi l'activité jusqu'à la fin
    $user_id = Auth::id();
    $active_scene = $this->sceneModel->activeScene($user_id, $activite_id);
    $scene_count = $this->sceneModel->sceneCount($user_id, $activite_id);
    if ($active_scene[0]->position == $scene_count) {
      return view('results', compact(
        'activite_id'
        )) ;
    } else {
      return redirect()->route('activite.show', ['activite_id' => $activite_id]);
    }
  }


}
