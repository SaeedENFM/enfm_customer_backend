<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB; 
use Validator; 
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    //

    protected $credentials_user_name;
    protected $credentials_password;

    public function __construct()
    {
        $this->credentials_user_name = config('constents.credentials_user_name');
        $this->credentials_password = config('constents.credentials_password'); 
    }


    public function login(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'user_name' => 'required|regex:/^[a-zA-Z0-9]+$/',  
                'password' => 'required',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                        $UserName = $parameters['user_name'];
                        $Password = $parameters['password'];
                        $DeviceId = '0';
                        $TokenId = '0';

                        $response = DB::select('exec Mob_Check_User_Validation ?,?,?,?',[ $UserName, $Password,$DeviceId,$TokenId]);
                        // return $response;
                        if (count($response) > 0) {
                            if($response[0]->isLocked == 0 ){ 
                                if(Hash::check($Password, $response[0]->Password360)) {
                                    // $response[0]->Password360 = '';
                                    $user = new User();
                                    $user->id = $response[0]->ID;
                                    $user->name = $response[0]->FirstName;
                                    $user->email = $response[0]->EmailId;  
                                    $user->password = $response[0]->Password360;  
                                    $token = $user->createToken('login-token')->plainTextToken;
                                    // return $token;
                                    $response[0]->Token = $token; 
                                    unset($response[0]->Password360);
                                    return $this->handleResponse($response, "Login successfully"); 
                                }else{
                                    return $this->handleError('Your password is incorrect !',404); 
                                }
                            }else{ 
                                 return $this->handleError('Your account has been locked !',404); 
                            }
                        } else {
                            return $this->handleError('Invalid Credentials',404);
                        }
                        
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getUserProfile function

    public function uploadImage(Request $request)
    {
        // dd($request->all());
        $image = $request->image;
        // dd($image);
        $client = new Client();
        $res = $client->request('POST', 'https://cafm.emiratesnfm.ae/ReflexionTechAppService/Service.svc/PostImage', [
            'headers' => ['fileName' => 'test-file.png'],
            // 'headers' => ['fileName' => $image],
            'form_params' => [
                'fileName' => $image, 
            ],
            'verify' => false,
            
        ]);
        echo $res->getStatusCode();
        // 200 
        echo $res->getBody();
        // {"type":"User"...'
        // $response = Http::withHeaders([
        //     'fileName' => '$image'
        //     ])->post('https://cafm.emiratesnfm.ae/ReflexionTechAppService/Service.svc/PostImage', [
        //     'fileName' => $image, 
        //     ['verify' => false]
        // ]);

        // $jsonData = $response->json();

        // dd($jsonData);
    }

    public function getUserProfile(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'user_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec csr_GetUserProfile ?',[$parameters['user_id']]);
                        return $this->handleResponse($requests, "user profile"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getUserProfile function

    public function updateProfile(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'firstName' => 'required', 
                'emailId' => 'required', 
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $token= request()->bearerToken();   
                if ($this->validateCredentails($token)) { 
                    try{ 
                        $parameters = $request->all(); 
  
                        $emailId = $parameters['emailId'];
                        $password = "";
                        $firstName = $parameters['firstName'];
                        $lastName = $parameters['lastName'] ?? "";
                        $mobile = $parameters['mobile'];
                        $csrTypeId = $parameters['csrTypeId'];
                        $subZoneId = "";
                        $vila = "";
                        $verificationCode = "";
                        $id = $parameters['id'];
                        $verificationCodeBySMS = "";
                        $rowId = 12;
                        // return $parameters;
                        // $requestIDRE = DB::select('exec csr_NewRequest ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',[
                        //     $RequestNumber,$SubmittedUserId,$CSRTypeId,$BaseunitId,$Location,$Title,$Details,
                        //     $CSRStatusId,$Rating,$RatingComments,$CSRUpdateSourceId,$Comments,$CurrentOwner,$MasterCommunityId,
                        //     $SubCommunityId,$ZoneId,$SubZoneId,$FaultCodeId
                        // ]);
                        $response = DB::update("exec csr_Users_Insert '$emailId',$password,$firstName,'$lastName','$mobile','$csrTypeId','$subZoneId',$vila,$verificationCode,'$id',$verificationCodeBySMS,'$rowId'");
 
                        if ($response < 1) { 
                            return $this->handleResponse($response, 'Profile updated');  
                        }else{
                            return $this->handleError('Something went wrong',400); 
                        }

                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            }   //end of validator condition 

        }   // end of check post method 

    }

    public function forgetPassword(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'user_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec csr_GetUserProfile ?',[$parameters['user_id']]);
                        return $this->handleResponse($requests, "user profile"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getUserProfile function




    //---------------------------------------------------------- 


    public function handleError($message, $status)
    {
        return response()->json([
            'status'=>$status,
            'data' => null,
            'message' => $message,
        ],404);
        // ->send();
    }//end of handle error

    public function handleResponse($data,$message)
    {
        return response()->json([
            'status'=>200,
            'data' => $data,
            'message' => $message,
        ],200);
    }//end of handle response

    public function decodeJWT($token)
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
        return true;

        // $response = false;
        // $bearerToken = $this->decodeJWT($token);   
        // if ($bearerToken) { 
        //     if ($this->credentials_user_name == $bearerToken->user_name && $this->credentials_password == $bearerToken->password ) {
        //         $response = true;
        //     } 
        // }

        // return $response;
    }//end of validate



}
