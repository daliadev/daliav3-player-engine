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
    // A FAIRE : on ne peut acceder a result QUE si on a suivi l'activité jusqu'à la fin
    if ($this->activiteModel->findOrFail($activite_id)) {
      $user_id = Auth::id();
      $last_session = $this->sessionModel->getLastSession($user_id, $activite_id);

      if (empty($last_session[0]) || $last_session[0]->SESSION_FINISHED == 0 ) {
        return 'Vous n\'avez pas accès aux resultats pour cette activité';
      }

      return view('results', compact(
        'activite_id'
        ));
    }
  }

  public function create()
  {

  }

}
