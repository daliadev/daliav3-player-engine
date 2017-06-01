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
    $this->middleware('auth')->only(['store', 'create', 'show', 'update', 'destroy',
    'edit', 'goNextScene', 'goPreviousScene']);

    $this->user_id = Auth::id();
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
  }

  /**
  * Affiche l'activité dont l'ID est passé en parametre
  * Ex d'url : api/activite/$id
  * @param  int  $id : Clé primaire dans la BDD de l'activité a afficher
  * @return une vue
  */
  public function show($activite_id)
  {
    if ($this->activiteModel->findOrFail($activite_id)) {

        $user_id = Auth::id();
        $last_session = $this->sessionModel->getLastSession($user_id, $activite_id);

        if (empty($last_session[0])) {
          // Creer une nouvelle session
          $this->sessionModel->startNewSession($user_id, $activite_id);
          // Puis rediriger vers la vue
          return redirect()->route('activite.showScene', ['activite_id' => $activite_id]);
        } else {
          if ($last_session[0]->SESSION_FINISHED == true) {
            // echo 'voir results avec bouton recommencer cette activité (= startNewSession())';
            // die();
            return redirect()->route('result.show', ['activite_id' => $activite_id]);
          } else {
            // choix : recommencer (startnew puis methode qui show) ou continuer
            // (methode qui show)
            return view('newOrContinu', compact(
              'activite_id'
            ));
          }
        }
      }
    }

    public function showScene($activite_id)
    {

      if ($this->activiteModel->findOrFail($activite_id)) {
        $user_id = Auth::id();
        $last_session = $this->sessionModel->getLastSession($user_id, $activite_id);
        if (empty($last_session[0])) {
          // Creer une nouvelle session
          $this->sessionModel->startNewSession($user_id, $activite_id);
          // Puis rediriger vers la vue
          return redirect()->route('activite.showScene', ['activite_id' => $activite_id]);
        }
        // Recuperation des scenes composant l'activité n°$id :
        $scenes_list = $this->sceneModel->getScenes($activite_id);
        // Afficher la scene active
        $active_scene = $this->sceneModel->getActiveScene($user_id, $activite_id);
        // Checker le "type" de sequence. Si "exercice" => charger le js
        $scene_count = $this->sceneModel->sceneCount($activite_id);
        $step = $this->sessionModel->getStep($user_id, $activite_id);
        $position = $this->sceneModel->getPosition($activite_id, $step[0]->CURRENT_SCENE);

        $last_scene = ($position == $scene_count) ? 1 : 0;

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
      $last_session = $this->sessionModel->getLastSession($user_id, $activite_id);

      if (!empty($last_session[0])) {
        $step = $this->sessionModel->getStep($user_id, $activite_id);
        $position = $this->sceneModel->getPosition($activite_id, $step[0]->CURRENT_SCENE);
        $scene_count = $this->sceneModel->sceneCount($user_id, $activite_id);

        $last_scene = ($position == $scene_count) ? 1 : 0;
        $penultimate = ($position == $scene_count-1) ? 1 : 0;

        if ($last_scene) {
          // Si on est deja a la derniere scene, on redirige sur elle meme.
          return redirect()->route('activite.showScene', ['activite_id' => $activite_id]);
        } else {

          $next_scene_id = $this->sceneModel->getNextSceneId($activite_id, $step[0]->CURRENT_SCENE);
          $update_session = $this->sessionModel->nextSceneToThisSession($last_session, $next_scene_id, $penultimate);

          return redirect()->route('activite.showScene', ['activite_id' => $activite_id]);
        }
      } else {
        // Si l'utilisateur essai d'acceder a cette route, alors que la session n'existe pas :
        // On créé la session et on le redirige vers la 1e scene.
        $this->sessionModel->startNewSession($user_id, $activite_id);
        return redirect()->route('activite.showScene', ['activite_id' => $activite_id]);
      }
    }
  }

}
