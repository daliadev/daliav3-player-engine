<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

use App\Activite;
use App\User;
use App\H5P_content;
use App\H5P_library;

use Response;

//JSON Web Token
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class H5PController extends Controller
{
  private $H5P_library_model;

  public function __construct(){

    $this->H5P_library_model = new H5P_library;

  }
  /**
   *
   *
   *
   */
  public function index()
  {
    return 'Liste de toutes les activités';
  }

  /**
  *
  *
  *
  *
  */
  public function show($id)
  {
    $h5p_contents = H5P_content::find($id);

    if (!$h5p_contents) {
      return Response::json([
        'error' => [
          'message' => 'Cet exercice n\'existe pas.'
        ]
      ], 404);
    } else {
      // Aller chercher dans un modele adapté, les librairies
      // a precharger pour faire fonctionner le content
      $results = json_decode($h5p_contents, true);
      $library_id = $results['library_id'];
      $preloaded = json_decode($this->H5P_library_model->getPreloaded($library_id), true);

      $preloadeds_css = $preloaded[0]['preloaded_css'];
      $preloadeds_js = explode(', ', $preloaded[0]['preloaded_js']);

      echo $preloadeds_css;
      echo '<br>';

      foreach ($preloadeds_js as $preloaded_js) {
        echo $preloaded_js. '<br>';
      }
      die();

      // Return une viex, avec des tableaux contenant les adresses des
      // *.js et *.css en arguments.


      // return Response::json([
      //   'activites' => $h5p_contents
      // ], 200);
    }
  }

  public function exercice()
  {
    return view('exercice');
  }

}
