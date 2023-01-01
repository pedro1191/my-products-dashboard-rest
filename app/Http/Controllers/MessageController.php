<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\Send as SendRequest;
use App\Services\Email\Send as SendService;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Message\Send  $request
     * @param  \App\Services\Email\Send  $service
     * @return \Illuminate\Http\Response
     */
    public function store(SendRequest $request, SendService $service)
    {
        $data = $request->validated();
        $service->execute($data);

        return response()->json(
            [
                'message' => 'Your message has been sent successfully.',
                'status_code' => Response::HTTP_OK
            ],
            Response::HTTP_OK
        );
    }
}
