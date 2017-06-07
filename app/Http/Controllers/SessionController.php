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

use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SessionController extends Controller
{
  private $sessionModel;

  public function __construct(ActiviteRepository $activiteRepository)
  {
    // Modele(s)
    $this->sessionModel = new Session;
  }

  /**
  * Route de type /api/activite/$id/new qui fait appel au Model qui fait l'Insert
  * en BDD de la nouvelle session puis redirige vers showScene
  * @param  int  $activite_id : ID dans la BDD de l'activité a afficher
  * @return une redirection.
  */
  public function newSession($activite_id)
  {
    $user_id = Auth::id();

    $this->sessionModel->startNewSession($user_id, $activite_id);
    // Puis recharger la page
    return redirect()->route('activite.showScene', ['activite_id' => $activite_id]);
  }

}
