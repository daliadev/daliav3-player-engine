<?php

namespace App\Repositories;

use App\Activite;

use Illuminate\Support\Facades\Auth;

class ActiviteRepository
{

    protected $activite;

    public function __construct(Activite $activite)
  	{
  		$this->activite = $activite;
  	}

	private function save(Activite $activite, Array $inputs)
	{
    // Ci dessous : exemple d'un ancien projet gardé juste pour avoir un exemple de la syntaxe.
    // A FAIRE : a effacer/supprimer
		// $game->name = $inputs['name'];
		// $game->console = $inputs['console'];
		// $game->boite = isset($inputs['boite']);
    // $game->notice = isset($inputs['notice']);
    // $game->jaquette = isset($inputs['jaquette']);
    // $game->cale = isset($inputs['cale']);
    // $game->fourreau = isset($inputs['fourreau']);
    // $game->note = $inputs['note'];
    // $game->user_id = Auth::id();
    //
		// $game->save();
	}

	public function getPaginate($n)
	{
		return $this->activite
        ->orderBy('id', 'asc')
        ->paginate($n);
	}

	public function store(Array $inputs)
	{
		$activite = new $this->activite;

		$this->save($activite, $inputs);

		return $activite;
	}

	public function getById($id)
	{
		return $this->activite->findOrFail($id);
	}

	public function update($id, Array $inputs)
	{
		$this->save($this->getById($id), $inputs);
	}

	public function destroy($id)
	{
		$this->getById($id)->delete();
	}

}
