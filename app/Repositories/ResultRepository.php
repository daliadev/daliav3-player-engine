<?php

namespace App\Repositories;

use App\Result;

use Illuminate\Support\Facades\Auth;

class ResultRepository
{

    protected $result;

    public function __construct(Result $result)
  	{
  		$this->result = $result;
  	}

	private function save(Result $result, Array $inputs)
	{

	}

	public function getPaginate($n)
	{
		return $this->result
        ->orderBy('id', 'asc')
        ->paginate($n);
	}

	public function store(Array $inputs)
	{
		$result = new $this->result;

		$this->save($result, $inputs);

		return $result;
	}

	public function getById($id)
	{
		return $this->result->findOrFail($id);
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
