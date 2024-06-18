<?php

namespace App\Http\Controllers\WebAPI;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
      $users = User::with('customer')->paginate(100);

      return new GeneralResource(true, 'List Data Users', $users);
    }
}
