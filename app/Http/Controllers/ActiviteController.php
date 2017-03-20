<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Activite;
use App\User;

use Response;

//JSON Web Token
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ActiviteController extends Controller
{
  private $request;

  public function __construct(){

    $this->request = new Request;
    // $this->middleware('jwt.auth');
  }

  /**
   * Affiche la liste de TOUTES les activite
   * Ex d'url : api/activite
   * @return Response format JSON
   */
  public function index()
  {
    $activites = Activite::all();
    return Response::json([
			'activites' => $activites
		], 200);
  }

  /**
  * Affiche l'activité dont l'ID est passé en parametre
  * Ex d'url : api/activite/$id
  * @param  int  $id : Clé primaire dans la BDD de l'activité a afficher
  * @return Response format JSON
  */
  public function show($id)
  {
    $activite = Activite::find($id);

		if (!$activite) {
			return Response::json([
				'error' => [
					'message' => 'Cette activite n\'existe pas.'
				]
			], 404);
		} else {
		  return Response::json([
        'activites' => $activite
		  ], 200);
    }
  }

  /**
  * Enregistre une nouvelle activité
  * ex d'url : api/activite
  * @return Response format JSON
  */
  public function store()
  {

  }

  /**
   * Affiche l'interface de creation d'une nouvelle activité
   * Ex d'url : api/activite/create
   * @return Response format JSON
   */
  public function create()
  {

  }


  /**
  * Met à jour l'activité' dont l'ID est passé en parametre
  * Ex d'url : api/activite/$id
  * @param  int  $id : Clé primaire dans la BDD de l'activité a mettre a jour
  * @return Response format JSON
  */
  public function update($id)
  {

  }

  /**
  * Efface l'activité dont l'ID est passé en parametre
  * Ex d'url : api/activité/$id
  * @param  int  $id : Clé primaire dans la BDD de l'activité a effacer
  * @return Response format JSON
  */
  public function destroy($id)
  {

  }

  /**
   * Affiche l'interface de modification de l'activité dont l'ID est passé en parametre
   * Ex d'url : api/activité/$id/edit
   * @param  int  $id : Clé primaire dans la BDD de l'activité a mettre a jour
   * @return Response format JSON
   */
  public function edit($id)
  {

  }

}
