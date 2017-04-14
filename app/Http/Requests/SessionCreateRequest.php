<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionCreateRequest extends FormRequest
{

    public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'user_id' => 'required',
      'activite_id' => 'required',
      'scene_count' => 'required',
      'curent_scene' => 'required'
		];
	}

}
