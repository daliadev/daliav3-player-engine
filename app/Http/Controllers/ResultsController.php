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

class ResultsController extends Controller
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
    return 'tous les resultats ICI !';


    // Prepare la pagination et les liens pour la vue
    $links = $this->activiteRepository->getPaginate($this->nbrPerPage)->setPath('')->render();
    // Recupere la liste des activites
    $activites = $this->activiteRepository->getPaginate($this->nbrPerPage);

    return view('activite', compact(
      'activites',
      'links'
    ));
  }

  /**
  * Affiche l'activité dont l'ID est passé en parametre
  * Ex d'url : api/activite/$id
  * @param  int  $id : Clé primaire dans la BDD de l'activité a afficher
  * @return
  */
  public function show($activite_id)
  {
    echo 'resultats';
    die();
      // A FAIRE : on ne peut acceder a result QUE si on a suivi l'activité jusqu'à la fin
    $user_id = Auth::id();
    $scene_count = $this->sceneModel->sceneCount($user_id, $activite_id);
    $step = $this->sessionModel->getStep($user_id, $activite_id);

    if ($step[0]->curent_scene == $scene_count || $step[0]->curent_scene == $scene_count+1) {

      // A FAIRE : mettre a jour le status de cette session à "2"
      $session_id = ($this->sessionModel->getLastSession($user_id, $activite_id))[0]->id;

      $session = Session::find($session_id);
      $session->curent_scene = $session->scene_count+1;
      $session->finish = 1;
      $session->save();

      return view('results', compact(
        'activite_id'
        )) ;
    } else {

      return redirect()->route('activite.show', ['activite_id' => $activite_id]);
    }
  }

  public function create()
  {

  }

}
