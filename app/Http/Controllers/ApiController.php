<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * @var int
     */
    protected $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    /**
     * Запрос был выполнен, и, в результате, создан новый ресурс.
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondCreated($message = "")
    {
        return $this->setStatusCode('201')->respond($message);
    }

    /**
     * Запрос принят в обработку, но еще не завершен
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondAccepted($message = "")
    {
        return $this->setStatusCode('202')->respond($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = "Not found!")
    {
        return $this->setStatusCode('404')->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondMethodNotAllowed($message = "405 method not allowed")
    {
        return $this->setStatusCode('405')->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondFail500x($message = "Internal Error")
    {
        return $this->setStatusCode('500')->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondFail403x($message = "Unauthorized action!")
    {
        return $this->setStatusCode('403')->respondWithError($message);
    }

    /**
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondFail422x($message = "Unprocessable Entity!")
    {
        return $this->setStatusCode('422')->respondWithError($message);
    }

    /**
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return json_encode($data);
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message)
    {
        return $this->respond([
            'error' => [
                'message' => $message,
                'staus_code' => $this->getStatusCode(),
            ]
        ]);

    }
}