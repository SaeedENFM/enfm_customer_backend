<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Validator; 
 

class PPMController extends Controller
{
    protected $credentials_user_name;
    protected $credentials_password;

    public function __construct()
    {
        $this->credentials_user_name = config('constents.credentials_user_name');
        $this->credentials_password = config('constents.credentials_password'); 
    }

    public function getPendingPPM(Request $request )
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
                         
                        $response = DB::select('exec PPM_WorkOrderPending ?',[$parameters['user_id']]);
                        return $this->handleResponse($response, 'PPM Work order list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getPendingPPM function

    public function getPPMWOTasks(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(   
                'wo_id' => 'required|numeric',  

            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         $status = 0;
                        $response = DB::select('exec Contract_App_PPMPendingWorkOrderOpenByStatus ?,?',[$parameters['wo_id'],$status]);
                        return $this->handleResponse($response, 'Work order tasks list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getPPMWOTasks function

    public function getMeteringParametesLOV(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'metering_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{   
                        $response = DB::select('exec sc_App_GetMeteringParameterLOV ?',[$parameters['metering_id']]);
                        return $this->handleResponse($response, 'Metering parameters'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getPPMWOTasks function

    public function updateWOTask(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'UserId' => 'required|numeric',  
                'TaskId' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{   
                        $UserId =  $parameters['UserId'];  
                        $TaskId = $parameters['TaskId']; 
                        $IsCompleted =  $parameters['IsCompleted'];
                        $Reading = $parameters['Reading'] ?? '.00';
                        $LOVResultId = $parameters['LOVResultId']; 
                        $SubZone  = $parameters['SubZone'];
                        $SubCommunityId = $parameters['SubCommunityId'];
                        $AssetId = 0;
                        $BaseunitId = $parameters['BaseunitId'];
                        $ClientContractId = $parameters['ClientContractId'];
                        $PPMWOId = $parameters['PPMWOId'];
                        $Remarks = $parameters['Remarks'];

                        $response = DB::select('exec WorkOrderTask_Update ?,?,?,?,?,?,?,?,?,?,?,?',
                        [$UserId,$TaskId,$IsCompleted,$Reading,$LOVResultId,$SubZone,$SubCommunityId,$AssetId,$BaseunitId,$ClientContractId,$PPMWOId,$Remarks]);
                        return $this->handleResponse($response, 'Work order task updated'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getPPMWOTasks function


    public function getWOCount(Request $request )
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
                         
                        $response = DB::select('exec Mob_Count_Tech ?',[$parameters['user_id']]);
                        return $this->handleResponse($response, 'Work order count'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getRMWOHistory function

    

    public function getWOImage(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(   
                'wo_id' => 'required|numeric',  


            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                        
                        $arrContextOptions=array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        ); 

                        $source = "Asset";  //'RM';
                        $response = DB::select('exec sc_App_GetAllImages ?,?',[$parameters['wo_id'],$parameters['source']]);
                        // foreach ($response as $key => $image) {
                        //     $image_name = $image->DocumentFileName;
                        //     $image_url = 'https://cafm.emiratesnfm.ae/ReflexionTechAppService/TechAppPhotos/'.$image_name;
                        //     $base64 = base64_encode(file_get_contents($image_url, false, stream_context_create($arrContextOptions)));
                        //     return $base64;
                        // }
                        // $image_url = 'https://cafm.emiratesnfm.ae/ReflexionTechAppService/TechAppPhotos/168915-17-0-20230201_014225.png';
                        // $base64 = base64_encode(file_get_contents($image_url, false, stream_context_create($arrContextOptions)));
                        // return $base64;
                        
                        // return $response;
                        return $this->handleResponse($response, 'Work order images'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of history function

    public function getWOStatus(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(  
                'user_id' => 'required|numeric',  
                'wo_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                        $status = '';
                        $response = DB::select('exec Mob_GetRMWOStatusByCurrentStatusId ?,?,?',[$parameters['user_id'], $parameters['wo_id'],$status]);
                        return $this->handleResponse($response, "WO status"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getRequestDetails function

    public function getPPMHistory(Request $request )
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
                        $lat = '0';
                        $long = '0';
                        $response = DB::select('exec GetPPMHistoryByUserId ?,?,?',[$parameters['user_id'],$lat,$long]);
                          
                        return $this->handleResponse($response, "PPM history "); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getPPMHistory function

    

    public function updateWOStatus(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'WorkOrderId' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                        $WorkOrderId = $parameters['WorkOrderId'];
                        $StaffId = $parameters['StaffId'];
                        $MobileNumber = $parameters['MobileNumber'];
                        $StatusId = $parameters['StatusId'];
                        $StatusTime = $parameters['StatusTime'];
                        $StatusUpdateSourceId = 45;
                        $UserId = $parameters['UserId'];
                        $Notes = $parameters['Notes'];
                        $reOpen = 0;
                        $Latitude = 0;
                        $Longitude = 0;
                        $SignatureHold = $parameters['SignatureHold']; 
                        $ReasonId = $parameters['ReasonId']; 

                        $response = DB::update('exec WorkOrderStatusDetails_Insert ?,?,?,?,?,?,?,?,?,?,?,?,?',[$WorkOrderId,$StaffId,$MobileNumber,$StatusId,$StatusTime,$StatusUpdateSourceId,$UserId,$Notes,$reOpen,$Latitude,$Longitude,$SignatureHold,$ReasonId]);
                        
                        return $this->handleResponse($response, "Status Updated"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of updateWOStatus function

    public function saveWOImage(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'WorkOrderId' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                        $current_date = date('Y-m-d H:i:s');

                        $DocumentType = $parameters['DocumentType']; //AS
                        $DocumentSource = $parameters['DocumentSource']; //Asset
                        $DocumentDestination = null;  
                        $DocumentFileName = null; 
                        $AttachedBy =  $parameters['AttachedBy'];
                        $IssueDate = $current_date; // $parameters['IssueDate'];
                        $AttachDate = $current_date; // $parameters['AttachDate'];
                        $FileSize = 0;  
                        $FileType = "png";  
                        $WorkOrderId = $parameters['WorkOrderId']; //assetId
                        $DocumentSourceID = $parameters['DocumentSourceID'];  //13
                        $StatusId = $parameters['StatusId'];
                        $Base64Image = $parameters['Base64Image'];
                          
                        

                        $response = DB::update('exec sc_App_Documents_Save_NEW ?,?,?,?,?,?,?,?,?,?,?,?,?',[$DocumentType,$DocumentSource,$DocumentDestination,$DocumentFileName,$AttachedBy,$IssueDate,$AttachDate,$FileSize,$FileType,$WorkOrderId,$DocumentSourceID,$StatusId,$Base64Image]);
                        
                        return $this->handleResponse($response, "Status Updated"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of updateWOStatus function

    public function getAllReasons(Request $request )
    {
        if($request->ismethod('post')){ 
 
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  

                        $response = DB::select('exec sc_App_GetAllReasonDetails');
                        
                        return $this->handleResponse($response, "reasons list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails
 

        }// end of check post method 

    } // end of updateWOStatus function
  
    public function getGeneralStores(Request $request )
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
                        $response = DB::select('exec GetGenstore ?',[$parameters['user_id']]); 
                        return $this->handleResponse($response, "General Stores list ");  
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getGeneralStores function

    public function getGeneralStoreCategories(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'store_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{    
                        $response = DB::select('exec Getcategory ?',[$parameters['store_id']]); 
                        return $this->handleResponse($response, "General Store categories list ");  
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getGeneralStoreCategories function

    public function getStoreItems(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'store_id' => 'required|numeric',  
                'cat_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{    

 
                        $results = DB::select('exec GetitemlistWithPagination ?,?,?,?',[$parameters['cat_id'],$parameters['store_id'],$parameters['page'],$parameters['page_size']]); 
 
                        return $this->handleResponse($results, "General Store items list ");  
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getStoreItems function
    public function getMaterialHistory(Request $request )
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
                        $workOrder = 0;
                        $response = DB::select('exec WorkOrderMaterialsHistory ?,?',[ $parameters['user_id'],$workOrder ]); 
                        return $this->handleResponse($response, "Work order history ");  
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getStoreItems function



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



// SET ANSI_NULLS ON
// GO
// SET QUOTED_IDENTIFIER ON
// GO
  
  
// ALTER procedure [dbo].[Getitemlist]  
// @catgId int,  
// @StoreId int , 
// @PageIndex int,
// @PageSize int
// as  
  
// select   ItemMaster.Id,ItemCode,ItemCode + '-'+ Replace(ItemName,'&','') as ItemName,
// ItemLocations.StoreId,itemmaster.ItemUnitId,Unit.UnitName unit,convert(decimal(18,2),
// sum(ItemLocations.balance) )as Quantity,MainGroup.MainGroupName AS ItemCategory,
// MainGroup.MainGroupCode AS ItemCategoryCOde,STORE.StoreName AS Location 
 
// from ItemMaster inner join 
// ItemLocations on ItemLocations.ItemId=ItemMaster.id and ItemLocations.StoreId=@StoreId  
// inner join Unit on Unit.id=ItemMaster.ItemUnitId  
// inner join SubGroup on subgroup.Id=ItemMaster.ItemSubGroupId  
// inner join MainGroup on MainGroup.id=SubGroup.MainGroupId and MainGroup.id=@catgId  
// INNER JOIN STORE ON STORE.ID=ItemLocations.StoreId
// where ItemLocations.Balance > 0 and ItemMaster.Id Between ((@PageIndex - 1)*@PageSize+1) And (@PageIndex * @PageSize)
// Group by ItemMaster.Id,ItemCode, ItemName,
// ItemLocations.StoreId,itemmaster.ItemUnitId,Unit.UnitName,MainGroupName,MainGroupCode,STORE.StoreName 

// GO
