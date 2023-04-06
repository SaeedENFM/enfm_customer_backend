<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Validator; 
 

class RequestController extends Controller
{
    protected $credentials_user_name;
    protected $credentials_password;

    public function __construct()
    {
        $this->credentials_user_name = config('constents.credentials_user_name');
        $this->credentials_password = config('constents.credentials_password'); 
    }

    public function history(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'user_id' => 'required',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                        if (isset($parameters['page_index']) && isset($parameters['page_size'])) {  
                            $requests = DB::select('exec csr_GetHistory_Paging ?,?,?',[ $parameters['user_id'],$parameters['page_index'],$parameters['page_size'] ]);
                            return $this->handleResponse($requests, 'Requests history with pagination'); 
                            
                        }else{
                            $requests = DB::select('exec csr_GetHistory ?',array($parameters['user_id']));
                            return $this->handleResponse($requests, 'Requests History'); 
                        }
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of history function

    public function getRequestDetails(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'user_id' => 'required',  
                'request_id' => 'required',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec csr_GetHistoryStatusDetails ?',[$parameters['user_id'], $parameters['request_id']]);
                        return $this->handleResponse($requests, "Request Details"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getRequestDetails function

    public function getRequestImages(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'request_id' => 'required',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec csr_Pictures_GetImages ?',[$parameters['request_id']]);
                        return $this->handleResponse($requests, "Request images"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getRequestDetails function


    public function getClientContracts(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'user_id' => 'required',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec CSR_ClientContract_Active_ByUserId ?',[$parameters['user_id']]);
                        return $this->handleResponse($requests, "Client's contracts List"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getClientContracts function

    public function getZones(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'contract_property_id' => 'required',  
            );

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec CSR_Zone_GetListByPropertyID ?',[$parameters['contract_property_id']]);
                        return $this->handleResponse($requests, "Zones List"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getZones function

    public function getSubZones(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'zone_id' => 'required',  
            );

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec CSR_getSubZoneByZoneId ?',[$parameters['zone_id']]);
                        return $this->handleResponse($requests, "Sub zones list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getSubZones function

    public function getBaseUnits(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'sub_zone_id' => 'required',  
            );

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec CSR_getBaseUnitBySubZone ?',[$parameters['sub_zone_id']]);
                        return $this->handleResponse($requests, "Base units list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getBaseUnits function

    public function getServiceGroups(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'contract_id' => 'required',  
            );

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec sc_App_GetServiceGroup_ByClientContractID_CustApp ?',[$parameters['contract_id']]);
                        return $this->handleResponse($requests, "Service Group list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getServiceGroups function

    public function getServiceGroupDetails(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'contract_id' => 'required', 
                'service_group_id' => 'required', 

            );

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec sc_App_GetServiceGroup_ByClientContract_CustApp ?,?',[$parameters['contract_id'], $parameters['service_group_id']]);
                        return $this->handleResponse($requests, "Service Group Details"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getServiceGroups function


    public function createNewRequest(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'SubmittedUserId' => 'required', 
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $token= request()->bearerToken();   
                if ($this->validateCredentails($token)) { 
                    try{ 
                        $parameters = $request->all(); 

                        $getRequestNumber = DB::select('exec csr_Get_RequestReferenceNo ?',[$parameters['CSRTypeId']]);

                        // dd($getRequestNumber[0]);
                        // return $getRequestNumber[0]->CSRNewRequestNo;
                        $RequestNumber = $getRequestNumber[0]->CSRNewRequestNo; 
                        $SubmittedUserId = $parameters['SubmittedUserId'];
                        $CSRTypeId = $parameters['CSRTypeId']; $BaseunitId = $parameters['BaseunitId']; 
                        $Location = $parameters['Location']; $Title = $parameters['Title']; $Details = $parameters['Details']; 
                        $CSRStatusId = $parameters['CSRStatusId']; $Rating = 0; $RatingComments = "";
                        $CSRUpdateSourceId = $parameters['CSRUpdateSourceId'];
                        $Comments = $parameters['Comments']; $CurrentOwner = $parameters['CurrentOwner']; 
                        $MasterCommunityId = $parameters['MasterCommunityId']; 
                        $SubCommunityId = $parameters['SubCommunityId']; $ZoneId = $parameters['ZoneId']; 
                        $SubZoneId = $parameters['SubZoneId']; $FaultCodeId = $parameters['FaultCodeId'];

                        $requestID = DB::select('exec csr_NewRequest ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',[
                            $RequestNumber,$SubmittedUserId,$CSRTypeId,$BaseunitId,$Location,$Title,$Details,
                            $CSRStatusId,$Rating,$RatingComments,$CSRUpdateSourceId,$Comments,$CurrentOwner,$MasterCommunityId,
                            $SubCommunityId,$ZoneId,$SubZoneId,$FaultCodeId
                        ]);
 

                        return $this->handleResponse($requestID, 'New request added'); 
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            }   //end of validator condition 

        }   // end of check post method 

    }



    //---------------------------------------------------------- 


    public function handleError($message, $status)
    {
        return response()->json([
            'status'=>$status,
            'data' => null,
            'message' => $message,
        ]);
    }//end of handle error

    public function handleResponse($data,$message)
    {
        return response()->json([
            'status'=>200,
            'data' => $data,
            'message' => $message,
        ]);
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
