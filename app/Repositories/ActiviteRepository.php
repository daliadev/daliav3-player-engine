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
