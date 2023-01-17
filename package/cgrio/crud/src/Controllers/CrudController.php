<?php
/*
Author: Alessandro Silva <alessrio@gmail.com>
*/

namespace Cgrio\Crud\Controllers;

use Sdx\Socialhub\Models\Credencial;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CrudController extends Controller
{

    public function __construct()
    {
      //  $this->middleware(['web', 'auth']);
    }


    public function index()
    {
        return view('cgrio.crud.index');
    }


}
