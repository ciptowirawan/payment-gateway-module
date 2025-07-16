<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Resources\TestResource;
use App\Http\Resources\InquiryResource;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // public function checkInquiry(Request $request) {
    //     $http = new Client;
    
    //     try {
    //         $response = $http->get('http://127.0.0.1:8000/api/payment/showInquiry', [
    //             'query' => [
    //                 'id' => $request->query('id'),
    //                 'user_id' => $request->query('user_id'),
    //             ],
    //         ]);
            
    //         $responseBody = json_decode((string) $response->getBody(), true);

    //         return new InquiryResource($responseBody['success'], $responseBody['message'], $responseBody['data']);
            
    //     } catch (BadResponseException $e) {
    //         return response()->json($e->getMessage(), $e->getCode());
    //     }
    // }

    public function checkInquiry(Request $request) {
        $http = new Client;
    
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'partnerServiceId' => 'required|string',
                'customerNo' => 'required|string',
                'virtualAccountNo' => 'required|string',
                'trxDateInit' => 'required|date',
                'channelCode' => 'required|integer',
                'additionalInfo' => 'sometimes|array',
                'inquiryRequestId' => 'required|string',
            ]);
    
            // Send request to the show route
            $response = $http->get('http://103.127.98.225:8081/api/payment/showInquiry', [
                'query' => [
                    'id' => $validatedData['virtualAccountNo'],
                ],
            ]);
            
            $responseBody = json_decode((string) $response->getBody(), true);
    
            // Return the response as is, since it's already in the correct format
            return response()->json($responseBody);
            
        } catch (ValidationException $e) {
            return response()->json([
                'responseCode' => '2002400',
                'responseMessage' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (BadResponseException $e) {
            return response()->json([
                'responseCode' => $e->getCode(),
                'responseMessage' => $e->getMessage(),
                'virtualAccountData' => null
            ], $e->getCode());
        }
    }

    // public function updateInquiry(Request $request) {
    //     $http = new Client;
    
    //     try {
    //         $response = $http->post('http://127.0.0.1:8000/api/payment/updateInquiry', [
    //             'query' => [
    //                 'id' => $request->query('inquiry'),
    //                 'amount' => $request->query('amount'),
    //                 'status' => 'SUCCESS'
    //             ],
    //         ]);
            
    //         $responseBody = json_decode((string) $response->getBody(), true);

    //         return new InquiryResource($responseBody['success'], $responseBody['message'], $responseBody['data']);
            
    //     } catch (BadResponseException $e) {
    //         return response()->json($e->getMessage(), $e->getCode());
    //     }
    // }

    public function updateInquiry(Request $request) {
        $http = new Client;
    
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'virtualAccountNo' => 'required|string',
                'amount' => 'required|array',
                'amount.value' => 'required|numeric',
                'amount.currency' => 'required|string',
            ]);
    
            // Send request to the update route
            $response = $http->post('http://103.127.98.225:8081/api/payment/updateInquiry', [
                'query' => [
                    'id' => $validatedData['virtualAccountNo'],
                    'amount' => $validatedData['amount']['value'],
                    'status' => 'SUCCESS', // Assuming the status is always Success when updating
                ],
            ]);
            
            $responseBody = json_decode((string) $response->getBody(), true);
    
            // Return the response as is, since it's already in the correct format
            return response()->json($responseBody);
            
        } catch (ValidationException $e) {
            return response()->json([
                'responseCode' => '2002400',
                'responseMessage' => 'Validation Error',
                'errors' => $e->errors(),
            ], 422);
        } catch (BadResponseException $e) {
            return response()->json([
                'responseCode' => $e->getCode(),
                'responseMessage' => $e->getMessage(),
                'virtualAccountData' => null
            ], $e->getCode());
        }
    }
}
