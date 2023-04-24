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
                        $base64_images = [];
                        $images = DB::select('exec csr_Pictures_GetImages ?',[$parameters['request_id']]);
                        foreach ($images as $key => $image) {
                            $base64_images[$key]['requestId'] = $parameters['request_id'];
                            if ( $image->Picture != null ) { 
                                $base64_images[$key]['base64Image'] = base64_encode($image->Picture);
                            }else{
                                $base64_images[$key]['base64Image'] = $image->NewPicture;
                            }
                        }
                        // dd($images);

                        return $this->handleResponse($base64_images, "Request images"); 
                        
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
                        // return $this->handleResponse($getRequestNumber[0]->CSRNewRequestNo, 'New request added'); 
                         
                        $RequestNumber = $getRequestNumber[0]->CSRNewRequestNo; 
                        $SubmittedUserId = $parameters['SubmittedUserId'];
                        $CSRTypeId = $parameters['CSRTypeId']; $BaseunitId = $parameters['BaseunitId']; 
                        $Location = $parameters['Location']; $Title = $parameters['Title']; $Details = $parameters['Details']; 
                        $CSRStatusId = 10; //$parameters['CSRStatusId']; 
                        $Rating = 0; $RatingComments = "test";
                        $CSRUpdateSourceId = $parameters['CSRUpdateSourceId'];
                        $Comments = $parameters['Comments']; $CurrentOwner = $parameters['CurrentOwner']; 
                        $MasterCommunityId = $parameters['MasterCommunityId']; 
                        $SubCommunityId = $parameters['SubCommunityId']; $ZoneId = $parameters['ZoneId']; 
                        $SubZoneId = $parameters['SubZoneId']; $FaultCodeId = $parameters['FaultCodeId'];
                        $id = 5544;
                        
                        // $requestIDRE = DB::select('exec csr_NewRequest ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',[
                        //     $RequestNumber,$SubmittedUserId,$CSRTypeId,$BaseunitId,$Location,$Title,$Details,
                        //     $CSRStatusId,$Rating,$RatingComments,$CSRUpdateSourceId,$Comments,$CurrentOwner,$MasterCommunityId,
                        //     $SubCommunityId,$ZoneId,$SubZoneId,$FaultCodeId
                        // ]);
                        $requestID = DB::update("exec csr_NewRequest '$RequestNumber',$SubmittedUserId,$CSRTypeId,$BaseunitId,'$Location','$Title','$Details',$CSRStatusId,$Rating,'$RatingComments',$CSRUpdateSourceId,'$Comments',$CurrentOwner,$MasterCommunityId,$SubCommunityId,$ZoneId,$SubZoneId,$FaultCodeId,$id");
 
                        if ($requestID < 1) {
                            $Id = DB::table('CSR_Main')->select('Id')->where(['RequestNumber' => $RequestNumber])->first(); 
                            return $this->handleResponse($Id, 'New request added');  
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

    public function saveRequestImage(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'request_id' => 'required',  
                'base64_image' => 'required',  
            );

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    $parameters = $request->all();

                    try{  
                        $request_id = $parameters['request_id'];
                        $base64_image = null;
                        $pic_status = 0;
                        $location = null; 
                        $lat = null;
                        $lon = null; 
                        $new_pic = $parameters['base64_image'];
                        $response = DB::select('exec csr_Pictures_Insert ?,?,?,?,?,?,?',[$request_id,$base64_image,$location,$pic_status,$lat,$lon,$new_pic]);
                        return $this->handleResponse($response, "request image saved"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of saveRequestImage function



    //---------------------------------------------------------- 


    public function handleError($message, $status)
    {
        return response()->json([
            'status'=>$status,
            'data' => null,
            'message' => $message,
        ],400);
    }//end of handle error

    public function handleResponse($data,$message)
    {
        return response()->json([
            'status'=>200,
            'data' => $data ,
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
