<?php

namespace LotousOrganization\LaravelLogin\app\Http\Controllers\Auth;


use Illuminate\Routing\Controller;
use LotousOrganization\LaravelLogin\app\Http\Services\Response;
use LotousOrganization\LaravelLogin\app\Http\Constants\Constants;

class InfoController extends Controller
{
    public function info()
    {
        if (auth()->user())
            return Response::success(Constants::SUCCESS,auth()->user());

        return Response::error(Constants::ERROR,400);
    }
}
