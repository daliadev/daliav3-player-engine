<?php

namespace App\Http\Controllers;

use App\Scene;

class SceneController extends Controller
{
  private $sceneModel;

  public function __construct()
  {
    // Modele(s)
    $this->sceneModel = new Scene;
  }

  

}
