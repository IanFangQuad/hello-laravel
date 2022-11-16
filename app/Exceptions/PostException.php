<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;


class PostException extends Exception
{

    public function render(Request $request)
    {
        return redirect()->back()->with('msg', 'action fail, please try again');
    }
}
