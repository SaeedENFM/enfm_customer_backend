<?php

namespace App\Models;



class CommanFunctions {

    protected $credentials_user_name;
    protected $credentials_password;

    public function __construct()
    {
        $this->credentials_user_name = config('constents.credentials_user_name');
        $this->credentials_password = config('constents.credentials_password'); 
    }

    static public function handleError($message, $status)
    {
        return response()->json([
            'status'=>$status,
            'data' => null,
            'message' => $message,
        ]);
    }//end of handle error

    static  public function handleResponse($data,$message)
    {
        return response()->json([
            'status'=>200,
            'data' => $data,
            'message' => $message,
        ]);
    }//end of handle response

    static  public function decodeJWT($token)
    {
        $tokenParts = explode(".", $token);   
        $tokenHeader = isset($tokenParts[0]) ? base64_decode( $tokenParts[0]) : null;
        $tokenPayload = isset($tokenParts[1]) ? base64_decode($tokenParts[1]) : null;
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        return $jwtPayload;
    }//end of decodeJWT

    public function validateCredentails($token)
    { 
        $response = false;
        $bearerToken = $this->decodeJWT($token);   
        if ($bearerToken) { 
            if ($this->credentials_user_name == $bearerToken->user_name && $this->credentials_password == $bearerToken->password ) {
                $response = true;
            } 
        } 
        return $response;
    }//end of validate


}