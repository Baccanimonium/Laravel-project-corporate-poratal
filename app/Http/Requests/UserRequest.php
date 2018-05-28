<?php

namespace Corp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       return Auth::user()->hasRole('admin');
    }
	
	protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
    
	    $validator->sometimes('password', 'required|min:6|confirmed', function($input)
	    {
			
			if(!empty($input->password) || ((empty($input->password) && $this->route()->getName() !== 'adminUsers.update'))) {
				return TRUE;
			}
			
			return FALSE;
	    });

	    return $validator;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = (isset($this->route()->parameter('adminUser')->id)) ? $this->route()->parameter('adminUser')->id : '';
		
		return [
             'name' => 'required|max:255',
			 'login' => 'required|max:255',
			 'role_id' => 'required|integer',
             'email' => 'required|email|max:255|unique:users,email,'.$id
        ];
    }
}
