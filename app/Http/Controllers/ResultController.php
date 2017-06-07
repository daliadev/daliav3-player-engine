<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Repositories\ResultRepository;

use App\User;
use App\Scene;
use App\Sequence;
use App\Activite;
use App\Session;
use App\Result;

use Response;

//JSON Web Token
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ResultController extends Controller
{
  protected $nbrPerPage = 10;

  private $resultModel;

  private $request;

  public function __construct(ResultRepository $resultRepository)
  {
    // Modele(s)
    $this->request = new Request;
    $this->resultModel = new Result;

    // Repositori(es)
    $this->resultRepository = $resultRepository;

    // Middleware(s)
    $this->middleware('auth')->only(['index', 'create', 'show']);
  }

  /**
   *
   */
  public function index()
  {
    return 'tous les resultats ICI !';


    // Prepare la pagination et les liens pour la vue
    $links = $this->resultRepository->getPaginate($this->nbrPerPage)->setPath('')->render();
    // Recupere la liste des activites
    $results = $this->resultRepository->getPaginate($this->nbrPerPage);

    return view('results', compact(
      'results',
      'links'
    ));
  }

  /**
  *
  */
  public function show($result_id)
  {
    // Variables test :
    $count_exercice = 2;
    $score_total = 100;

    return view('result', compact(
      'result_id',
      'count_exercice',
      'score_total'
    ));
  }

  public function create()
  {

  }

}
