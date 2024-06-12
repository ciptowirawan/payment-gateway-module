<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Http\Resources\InquiryResource;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function checkInquiry(Request $request) {
        $http = new Client;
    
        try {
            $response = $http->get('http://127.0.0.1:8000/showInquiry', [
                'query' => [
                    'id' => $request->query('inquiry'),
                ],
            ]);
            
            $responseBody = json_decode((string) $response->getBody(), true);

            return new InquiryResource($responseBody);
            
        } catch (BadResponseException $e) {
            return response()->json('Unable to retrieve access token', $e->getCode());
        }
    }

    public function updateInquiry(Request $request) {
        $http = new Client;
    
        try {
            $response = $http->post('http://127.0.0.1:8000/updateInquiry', [
                'query' => [
                    'id' => $request->query('inquiry'),
                    'user_id' => $request->query('user_id'),
                    'status' => 'SUCCESS'
                ],
            ]);
            
            $responseBody = json_decode((string) $response->getBody(), true);

            return new InquiryResource($responseBody);
            
        } catch (BadResponseException $e) {
            return response()->json('Unable to retrieve access token', $e->getCode());
        }
    }
}
