<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Illuminate\Pagination\LengthAwarePaginator ;

class Controller extends BaseController
{
    use  ValidatesRequests;

    public function __construct(){} 
    /**
     * @param $result
     * @param $message
     * @return JsonResponse
     */
    public function sendResponse($result, $message): JsonResponse
    {
        return Response::json($this->makeResponse($message, $result));
    }

     /**
     * @param $error
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, int $code = 400 , string $message= "" ): JsonResponse
    {
        return Response::json($this->makeError($message , $error), $code);
    }

     /**
     * @param string $message
     * @param mixed  $data
     *
     * @return array
     */
    private static function makeResponse($message, $data)
    {
        
        if( $data instanceof AnonymousResourceCollection  ){
            $resource = $data->resource ;
            if( $resource instanceof LengthAwarePaginator ){
            
                return array_merge([
                        'success' => true,
                        'message' => $message,
                    ] ,$resource->toArray()) ;
            }
        }
        return [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param array  $data
     *
     * @return array
     */
    protected static function makeError($message, array $data = [])
    {
        $res = [
            'success' => false,
            'message' => $message,
            'errors' => $data,
        ];

       

        return $res;
    }
}
