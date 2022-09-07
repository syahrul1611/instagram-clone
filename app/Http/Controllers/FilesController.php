<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilesController extends Controller
{
    public function __construct()
	{
		$this->middleware('auth');
	}

	public function getFile($foldername,$filename)
	{
        if ($filename !== Auth::user()->username) {
            exit;
        }
		$fullpath="{$foldername}/{$filename}";
        return response()->download(storage_path($fullpath), null, [], null);
	}

}
