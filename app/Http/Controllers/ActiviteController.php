<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Repositories\ActiviteRepository;

use App\User;
use App\Scene;
use App\Sequence;
use App\Activite;
use App\Session;

use Response;

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ActiviteController extends Controller
{
  // Permet de modifier le nombre d'activités affichées par page sur l'index
  // (et d'adapter la pagination)
  protected $nbrPerPage = 2;

  private $sceneModel;
  private $sequenceModel;
  private $activiteModel;
  private $sessionModel;


  public function __construct(ActiviteRepository $activiteRepository)
  {
    // Modele(s)
    $this->sceneModel = new Scene;
    $this->activiteModel = new Activite;
    $this->sessionModel = new Session;

    // Repositori(es)
    $this->activiteRepository = $activiteRepository;

    // Middleware(s)
    $this->middleware('auth')->only(['store', 'create', 'show', 'update', 'destroy',
    'edit', 'goNextScene']);
  }

  /**
   * Affiche la liste de TOUTES les activite
   * Ex d'url : / ou api/activite
   * @return View
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
  * @param  int  $id : ID dans la BDD de l'activité a afficher
  * @return une view ou une redirection vers ActiviteController@showscene ou  ResultController@show.
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
            // Si, dans la derniere session, l'activité était terminée : redirection
            // vers la view de Resultats
            return redirect()->route('result.show', ['activite_id' => $activite_id]);
          } else {
            // Sinon, redirection vers la vue de choix "continuer ou recommencer"
            return view('newOrContinu', compact(
              'activite_id'
            ));
          }
        }
      }
    }

    /**
    * Affiche la vue d'une scene
    * Ex d'url : api/activite/$id/show
    * @param  int  $activite_id : Id de l'activité
    * @return une view ou une redirection vers ActiviteController@showscene ou  ResultController@show.
    */
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
        // Recuperation de la scene active et de diverses infos la concernant.
        $active_scene = $this->sceneModel->getActiveScene($user_id, $activite_id);
        $scene_count = $this->sceneModel->sceneCount($activite_id);
        $step = $this->sessionModel->getStep($user_id, $activite_id);
        $position = $this->sceneModel->getPosition($activite_id, $step[0]->CURRENT_SCENE);

        $last_scene = ($position == $scene_count) ? 1 : 0;

        if ($last_session[0]->SESSION_FINISHED != 1 && $last_scene == 1) {
          $finished = $this->sessionModel->sessionIsFinished($last_session);
        }

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
    // Function à utiliser pour l'Insert de nouvelles activités en BDD (outil de
    // scenarisation ?)
    return view('createActivite', compact(
      ''
    ));
  }

  /**
  * Permet de mettre a jour le current_scene dans la session puis redirige vers
  * showScene (qui renverra la vue correspondante.)
  * Ex d'url : api/activite/$id/next
  * @param  int  $id : ID dans la BDD de l'activité a afficher
  * @return une redirection.
  */
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

        if ($last_scene) {
          // Si on est deja a la derniere scene, on redirige sur elle meme.
          return redirect()->route('activite.showScene', ['activite_id' => $activite_id]);
        } else {

          $next_scene_id = $this->sceneModel->getNextSceneId($activite_id, $step[0]->CURRENT_SCENE);
          $update_session = $this->sessionModel->nextSceneToThisSession($last_session, $next_scene_id);

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
