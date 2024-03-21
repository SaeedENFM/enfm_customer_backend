<?php

namespace App\Http\Controllers;

use App\Models\CaniasSetup;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Validator; 
 

class OthersController extends Controller
{
    protected $credentials_user_name;
    protected $credentials_password;

    public function __construct()
    {
        $this->credentials_user_name = config('constents.credentials_user_name');
        $this->credentials_password = config('constents.credentials_password'); 
    }

    public function getCustomersList(Request $request )
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
                         
                        $response = DB::select('exec Customer_GetList ?',[$parameters['user_id']]);
                        return $this->handleResponse($response, 'Customers list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getCustomersList function

    public function getCustomersContractList(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'client_id' => 'required|numeric',   
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

                        if(isset($parameters['user_id'])){
                            $userId =  $parameters['user_id']??1; 
                            $response = DB::select('exec CustomerContract_GetList_NEW ?,?',[$userId, $parameters['client_id']]);
                        }else{
                            $response = DB::select('exec CustomerContract_GetList ?',[$parameters['client_id']]);
                        }

                        // $response = DB::select('exec CustomerContract_GetList ?',[$parameters['client_id']]);

                        return $this->handleResponse($response, 'Customer Contracts list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getCustomersContractList function

    public function getContractSitesList(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'user_id' => 'required|numeric',  
                'contract_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $response = DB::select('exec CustomerContract_Site_GetList_New ?,?',[$parameters['user_id'], $parameters['contract_id']]);
                        return $this->handleResponse($response, 'Contract sites list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getContractSitesList function


    public function getReqeustCustomersContractList(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'client_id' => 'required|numeric',  
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
                         
                        $response = DB::select('exec Customer_GetList_new ?,?',[$parameters['user_id'],$parameters['client_id']]);
                        return $this->handleResponse($response, 'Customer Contracts list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getReqeustCustomersContractList function


    public function getClientBaseUnits(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'contract_id' => 'required|numeric', 
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $response = DB::select('exec Mob_getBaseUnitByclient_NEW ?',[$parameters['contract_id']]);
                        // $response = DB::select('exec Mob_getBaseUnitByclient ?',[$parameters['contract_id']]);
                        
                        return $this->handleResponse($response, 'base units list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getClientBaseUnits function

    public function getRequestBaseUnits(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(  
                'contract_id' => 'required|numeric', 

            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $response = DB::select('exec sc_App_GetAllLocationDetails ?,?',[$parameters['sub_community_id'],$parameters['contract_id']]);
                        return $this->handleResponse($response, 'base units list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getRequestBaseUnits function


    public function getServiceGroups(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(  
                'contract_id' => 'required|numeric', 

            );

            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $requests = DB::select('exec sc_App_GetServiceGroup_ByClientContractID ?',[$parameters['contract_id']]);
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
                'contract_id' => 'required|numeric',  
                'service_group_id' => 'required|numeric', 

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
 
    public function getPriority(Request $request )
    {
        if($request->ismethod('post')){ 
 
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  

                        $response = DB::select('exec Priority_GetList');
                        
                        return $this->handleResponse($response, "priority list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails
 

        }// end of check post method 

    } // end of getPriority function
    public function saveServiceRequest(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'UserId' => 'required|numeric',    
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

                        $MRNumber =  $parameters['MRNumber'] ?? ''; //pass blank 
                        $SubZone = $parameters['SubZone']; 
                        $Location =  $parameters['Location'] ?? '';
                        $Details = $parameters['Details'];
                        $ServiceGroup = $parameters['ServiceGroup']; 
                        $Priority  = $parameters['Priority'];
                        $UserId = $parameters['UserId'];
                        $ServiceProviderId = $parameters['ServiceProviderId'];
                        $SubCommunityId = $parameters['SubCommunityId'] ?? '0';
                        $AssetId = $parameters['AssetId'] ?? '0';
                        $BaseunitId = $parameters['BaseunitId'] ?? '0';
                        $FaultCodeId = $parameters['FaultCodeId'] ?? '0';  
                        $SourceId = 30; //$parameters['SourceId']; 
                        $ClientContractId = $parameters['ClientContractId']; 
                        $ScheduledDate = $current_date;// $parameters['ScheduledDate'];  
                        $Parentid = $parameters['Parentid'] ?? '0'; 
                        $WorkDescription = $parameters['WorkDescription']; 
                        $MRequestNumber = $parameters['MRequestNumber'] ?? '0'; 

                        // return json_decode($parameters['base64Images']);

                        // if($parameters['isSelfAssign'] == 1){  
                            // return "self assign";
                            $response = DB::select('exec MaintenanceRequest_SavewithSelfassign_FORNEWAPP ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',
                            [$MRNumber,$SubZone,$Location,$Details,$ServiceGroup,$Priority,$UserId,$ServiceProviderId,$SubCommunityId,$AssetId,
                            $BaseunitId,$FaultCodeId,$SourceId,$ClientContractId,$ScheduledDate,$Parentid,$WorkDescription,$MRequestNumber]);
                            
                        // }{   
                        //     $response = DB::select('exec MaintenanceRequest_Save_FORNEWAPP ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',
                        //     [$MRNumber,$SubZone,$Location,$Details,$ServiceGroup,$Priority,$UserId,$ServiceProviderId,$SubCommunityId,$AssetId,
                        //     $BaseunitId,$FaultCodeId,$SourceId,$ClientContractId,$ScheduledDate,$Parentid,$WorkDescription,$MRequestNumber]);
                            
                        // }

                        $images =  json_decode($parameters['base64Images']);
                        $uniqueArray = array_unique($images);

                        foreach ($uniqueArray as $key => $image) { 
                            $this->saveRequestImageNew($response[0]->Id,$parameters, $image);
                        }
                        
                        if($parameters['isSelfAssign'] == 1){  
                        return $this->handleResponse($response, 'Save New Service Request with self assign'); 

                        }{
                        return $this->handleResponse($response, 'Save New Service Request'); 

                        }
                        
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of saveWOCostBooking function

    public function saveRequestImageNew($requestId,$parameters,$base64_image )
    {
         
        try{  
            $current_timestamp = Carbon::now()->timestamp; 
            $image_name = $requestId."-".rand()."-".$current_timestamp.".png";
            $current_date = date('Y-m-d H:i:s');
                
            $DocumentType = $parameters['DocumentType']; //RM
            $DocumentSource = "New Request" ;//$parameters['DocumentSource']; //New Reqeust
            $DocumentDestination = "\\10.0.0.7\Documents\TechAppPhotos\"".$image_name;  
            $DocumentFileName = $image_name; 
            $AttachedBy = $parameters['UserId'];
            $IssueDate = $current_date;
            $AttachDate = $current_date;
            $FileSize = 0;  
            $FileType = "png";  
            $WorkOrderId = $requestId; //reqeustId
            $DocumentSourceID = $parameters['DocumentSourceID'];  //9
            $StatusId = $parameters['StatusId'];
            $Base64Image = null;
                
            $img_response =  $this->uploadImage($base64_image, $image_name);
            $decodedResponse =  json_decode($img_response);
            if($decodedResponse->response_code == 200){
                $response = DB::update('exec sc_App_Documents_Save_ForNewRequest_NEW ?,?,?,?,?,?,?,?,?,?,?,?,?',[$DocumentType,$DocumentSource,$DocumentDestination,$DocumentFileName,$AttachedBy,$IssueDate,$AttachDate,$FileSize,$FileType,$WorkOrderId,$DocumentSourceID,$StatusId,$Base64Image]);
            
            }else{
                $response = $img_response;
            }
                    
            
            return $this->handleResponse($response, "Image uploaded"); 
            
        }catch (\Exception $e){
            return $this->handleError($e->getMessage(),400);
        } 
              
    } // end of saveRequestImage function
    
    public function saveRequestImage(Request $request )
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
                        $current_timestamp = Carbon::now()->timestamp; 
                        $image_name = $parameters['WorkOrderId']."-".rand()."-".$current_timestamp.".png";
                        $current_date = date('Y-m-d H:i:s');
                         
                        $DocumentType = $parameters['DocumentType']; //RM
                        $DocumentSource = "New Request" ;//$parameters['DocumentSource']; //New Reqeust
                        $DocumentDestination = "\\10.0.0.7\Documents\TechAppPhotos\"".$image_name;  
                        $DocumentFileName = $image_name; 
                        $AttachedBy = $parameters['AttachedBy'];
                        $IssueDate = $current_date;
                        $AttachDate = $current_date;
                        $FileSize = 0;  
                        $FileType = "png";  
                        $WorkOrderId = $parameters['WorkOrderId']; //reqeustId
                        $DocumentSourceID = $parameters['DocumentSourceID'];  //9
                        $StatusId = $parameters['StatusId'];
                        $Base64Image = null;
                           
                        $img_response =  $this->uploadImage($parameters['Base64Image'], $image_name);
                        $decodedResponse =  json_decode($img_response);
                        if($decodedResponse->response_code == 200){
                            $response = DB::update('exec sc_App_Documents_Save_ForNewRequest_NEW ?,?,?,?,?,?,?,?,?,?,?,?,?',[$DocumentType,$DocumentSource,$DocumentDestination,$DocumentFileName,$AttachedBy,$IssueDate,$AttachDate,$FileSize,$FileType,$WorkOrderId,$DocumentSourceID,$StatusId,$Base64Image]);
                        
                        }else{
                            $response = $img_response;
                        }
                                
                        
                        return $this->handleResponse($response, "Image uploaded"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of saveRequestImage function
    
    public function uploadImage($base64_image,$image_name)
    {
        $client = new Client(); 
        $res = $client->request('POST', 'https://cafm.emiratesnfm.ae/ReflexionTechAppService/uploadImage.php', [
            
            'form_params' => [
                'image_base64' => $base64_image, 
                'image_name' => $image_name
            ],
            'verify' => false, 
        ]);
        return $res->getBody();
        // echo $res->getStatusCode(); 
    }
    
    public function saveWOCostBooking(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'UserId' => 'required|numeric',  
                'CostTypeID' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{   
                        // return $parameters;
                        $current_date = date('Y-m-d H:i:s');

                        $UserId =  $parameters['UserId'];  
                        $CostTypeID = $parameters['CostTypeID']; 
                        $InvoiceNo =  $parameters['InvoiceNo'];
                        $InvoiceDate = $current_date;
                        $SupplierName = $parameters['SupplierName']; 
                        $CostDescription  = $parameters['CostDescription'];
                        $GrossAmount = $parameters['GrossAmount'];
                        $CostRemarks = $parameters['CostRemarks'];
                        $ItemDescription = $parameters['ItemDescription'];
                        $ItemQty = $parameters['ItemQty'] ?? '';
                        $ItemRemarks = $parameters['ItemRemarks'];
                        $WOId = $parameters['WOId']; 

                        $response = DB::select('exec UpdateCostBookingForWO ?,?,?,?,?,?,?,?,?,?,?,?',
                        [$UserId,$CostTypeID,$InvoiceNo,$InvoiceDate,$SupplierName,$CostDescription,$GrossAmount,$CostRemarks,$ItemDescription,$ItemQty,$ItemRemarks,$WOId]);
                        return $this->handleResponse($response, 'Cost Booking Added'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of saveWOCostBooking function

    public function saveMaterialBooking(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'UserId' => 'required|numeric',    
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                

                if ($this->validateCredentails($token)) { 
                    try{    
                        // return $parameters;
                        $this->caniasMaterialBookingNew($parameters);
                        // $decodedResponse =  json_decode($img_response);
                        $response =  DB::select("select dbo.fn_V_GetNextCode('CostIssue')");
                        $billNo = preg_replace('/\D/', '', explode(":",json_encode($response[0]))[1]); 
                        //  $billNo = "00020769";
                        $current_date = date('Y-m-d H:i:s'); 
                        $Id = $parameters['Id'] ?? '0'; 
                        $VoucherNo =  $billNo?? '0';
                        $VoucherDate = $current_date ?? '2020-07-22 10:02:32.000' ;
                        $VoucherTypeId = $parameters['VoucherTypeId']?? '30'; 
                        $WorkOrderId  = $parameters['WorkOrderId']?? '';//woID
                        $ReferenceId = $parameters['ReferenceId']?? '0';
                        $ReferenceNo = $parameters['ReferenceNo']?? '';
                        $ReferenceDate = $current_date?? '2020-07-22 10:02:32.000';
                        $VoucherLineNo = $parameters['VoucherLineNo'] ?? '';//list of items count
                        $TransactionCategoryId = $parameters['TransactionCategoryId']?? '3';
                        $LotNo = $parameters['LotNo']?? ''; 
                        $StoreId = $parameters['StoreId']?? ''; //store id
                        $ReferenceStoreId = $parameters['StoreId']?? ''; 
                        $SupplierId = $parameters['SupplierId']?? ''; 
                        $ItemId = $parameters['ItemId']?? ''; //itemId
                        $Qty = $parameters['Qty']?? ''; //qty
                        $QtyIssued = $parameters['QtyIssued']?? '0'; 
                        $UnitId = $parameters['UnitId']?? ''; //unitId
                        $ConversionFactor = $parameters['ConversionFactor']?? '1'; 
                        $UnitPrice = $parameters['UnitPrice']?? '1'; 
                        $LineDiscount = $parameters['LineDiscount']?? '0'; 
                        $LineTax = $parameters['LineTax']?? '0'; 
                        $TotalValue = $parameters['TotalValue']?? '0'; 
                        $UnitCost = $parameters['UnitCost']?? '0'; 
                        $TotalCost = $parameters['TotalCost']?? '0'; 
                        $VoucherDiscount = $parameters['VoucherDiscount']?? '0'; 
                        $VoucherTax = $parameters['VoucherTax']?? '0'; 
                        $ActualPrice = $parameters['ActualPrice']?? '0'; 
                        $ActualValue = $parameters['ActualValue']?? '0'; 
                        $CurrentAveragePrice = $parameters['CurrentAveragePrice']?? '0'; 
                        $CurrentBalance = $parameters['CurrentBalance']?? '0'; 
                        $Details = $parameters['Details']?? '';//add remarks field 
                        $UserId = $parameters['UserId']?? ''; //userId
                        $StatusId = $parameters['StatusId']?? '0'; //
                        $SupplierInvoiceNo = $parameters['SupplierInvoiceNo']?? ''; 
                        $SupplierInvoiceDate = $current_date ?? 'null'; 
                        $IsNonStock = $parameters['IsNonStock']?? '0'; 
                        $Narration = $parameters['Narration']?? '';  

                        $response = DB::select('exec InventoryTransactions_SaveUpdate ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?',
                        [$Id,$VoucherNo,$VoucherDate,$VoucherTypeId,$WorkOrderId,$ReferenceId,$ReferenceNo,$ReferenceDate,$VoucherLineNo,$TransactionCategoryId,
                        $LotNo,$StoreId,$ReferenceStoreId,$SupplierId,$ItemId,$Qty,$QtyIssued,$UnitId,$ConversionFactor,$UnitPrice,$LineDiscount,$LineTax,$TotalValue,$UnitCost,$TotalCost,
                        $VoucherDiscount,$VoucherTax,$ActualPrice,$ActualValue,$CurrentAveragePrice,$CurrentBalance,$Details,$UserId,$StatusId,$SupplierInvoiceNo,$SupplierInvoiceDate,$IsNonStock,$Narration    
                    ]);
                        return $this->handleResponse($response, 'Material Booking Added'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{  
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of saveMaterialBooking function

    public function caniasMaterialBookingNew($parameters)
    {
        $client = new Client(); 
        $res = $client->request('POST', 'https://cafm.emiratesnfm.ae/ReflexionTechAppService/caniasIssueItem.php', [
            
            'form_params' => [
                'WorkOrderId' => $parameters['WorkOrderId'],  
                'StoreId' => $parameters['StoreId'],  
                'UnitId' => $parameters['UnitId'],  
                'ItemId' => $parameters['ItemId'],  
                'Qty' => $parameters['Qty'],   
            ],
            'verify' => false, 
        ]);
        return $res->getBody();
        // echo $res->getStatusCode(); 
    }

    

    
    public function getAssetsByContractId(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(   
                'contract_id' => 'required|numeric',  
                'base_unit_id' => 'required|numeric',    
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  
                         
                        $response = DB::select('exec Mob_getAssetByBaseUnitcontract ?,?',[$parameters['base_unit_id'],$parameters['contract_id']]);
                         
                        return $this->handleResponse($response, 'Assets list'); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getAssetsByContractId function

    
    public function getOEMList(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(   
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
                        $response = DB::select('exec AssetOEMDetails_GetListForMaster');
                        return $this->handleResponse($response, "OEM list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getOEMList function

    public function getMakes(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'oem_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{    
                        $response = DB::select('exec GetBrands ?',[$parameters['oem_id']]);
                          
                        return $this->handleResponse($response, "makes list "); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getMakes function

    public function getModels(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'make_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{    
                        $response = DB::select('exec Model_GetListByBrand ?',[$parameters['make_id']]);
                          
                        return $this->handleResponse($response, "models list "); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getMakes function

 
    public function getAssetMasterCategories(Request $request )
    {
        if($request->ismethod('post')){ 
 
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  

                        $response = DB::select('exec AssetMasterCategory_GetList_ForMaster');
                        
                        return $this->handleResponse($response, "master categories list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails
 

        }// end of check post method 

    } // end of getAssetMasterCategories function
  
    public function getAssetCategories(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'master_cat_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{    
                        $response = DB::select('exec AssetCategoryList_AssetMasterCategoryId ?',[$parameters['master_cat_id']]); 
                        return $this->handleResponse($response, "categories list ");  
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getAssetCategories function

    public function getAssetSubCategories(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'asset_cat_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{    
                        $response = DB::select('exec AssetSubCategoryList_AssetCategoryId ?',[$parameters['asset_cat_id']]); 
                        return $this->handleResponse($response, "Asset sub categories list ");  
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getAssetSubCategories function
    
    public function getFunctionalStatusList(Request $request )
    {
        if($request->ismethod('post')){ 
 
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  

                        $response = DB::select('exec getAssetFunctionalStatus_List');
                        
                        return $this->handleResponse($response, "functional status list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails
 

        }// end of check post method 

    } // end of getFunctionalStatusList function
  
    public function getAssetConditionList(Request $request )
    {
        if($request->ismethod('post')){ 
 
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  

                        $response = DB::select('exec getAssetCondition_List');
                        
                        return $this->handleResponse($response, "functional status list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails
 

        }// end of check post method 

    } // end of getAssetConditionList function
    public function getENFMStaff(Request $request )
    {
        if($request->ismethod('post')){ 
 
                $token= request()->bearerToken();  
 
                if ($this->validateCredentails($token)) { 
                    try{  

                        $response = DB::select('exec AssetEnFMStaffs_GetListForMaster');
                        
                        return $this->handleResponse($response, "ENFM Staff list"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails
 

        }// end of check post method 

    } // end of getAssetConditionList function
    
    public function createAsset(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'AssetCode' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
                
                if ($this->validateCredentails($token)) { 
                    try{  
                        $AssetCode = $parameters['AssetCode'];
                        $AssetName = $parameters['AssetName'];
                        $AssetSubCategoryId = $parameters['AssetSubCategoryId'];
                        $BaseUnitId = $parameters['BaseUnitId'];
                        $Brand = $parameters['Brand'];
                        $Model = $parameters['Model']; 
                        $OEMDetailId = $parameters['OEMDetailId'];
                        $UserId = $parameters['UserId'];
                        $ModelId = $parameters['ModelId'];
                        $CustodianStaffID = $parameters['CustodianStaffID'];
                        $FunctionalStatusId = $parameters['FunctionalStatusId']; 
                        $AssetConditionid = $parameters['AssetConditionid']; 
                        $Notes = $parameters['Notes']; 
                        $ContractId = $parameters['ContractId']??0;

                        $statusCode = 0;

                        $response = DB::select("exec Tech_Asset_Create  '$AssetCode','$AssetName',$AssetSubCategoryId,$BaseUnitId,$Brand,'$Model',$OEMDetailId,$UserId,$ModelId,$CustodianStaffID,$FunctionalStatusId,$AssetConditionid,'$Notes',$ContractId");
                        
                        // $response = DB::select('exec Tech_Asset_Create ?,?,?,?,?,?,?,?,?,?,?,?,?',
                        // [$AssetCode,$AssetName,$AssetSubCategoryId,$BaseUnitId,$Brand,$Model,$OEMDetailId,$UserId,$ModelId,$CustodianStaffID,$FunctionalStatusId,$AssetConditionid,$Notes]);
                        

                        return $this->handleResponse($response, "Asset created"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of createAsset function

    public function updateAsset(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array(
                'AssetCode' => 'required|regex:/^[a-zA-Z0-9\-]+$/',  
            );
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) { 
                return $this->handleError($validator->errors(),404);
            } else {
                $parameters = $request->all();
                $token= request()->bearerToken();  
                
                if ($this->validateCredentails($token)) { 
                    try{  
                        $AssetId = $parameters['AssetId'];
                        $AssetCode = $parameters['AssetCode'];
                        $AssetName = $parameters['AssetName'];
                        $AssetSubCategoryId = $parameters['AssetSubCategoryId'];
                        $BaseUnitId = $parameters['BaseUnitId'];
                        $Brand = $parameters['Brand'];
                        $Model = $parameters['Model']; 
                        $OEMDetailId = $parameters['OEMDetailId'];
                        $UserId = $parameters['UserId'];
                        $ModelId = $parameters['ModelId'];
                        $CustodianStaffID = $parameters['CustodianStaffID'];
                        $FunctionalStatusId = $parameters['FunctionalStatusId']; 
                        $AssetConditionid = $parameters['AssetConditionid']; 
                        $Notes = $parameters['Notes']; 
                        $ContractId = $parameters['ContractId']??0;
                        
                        $statusCode = 0;

                        $response = DB::select("exec Tech_Asset_Update  $AssetId,'$AssetCode','$AssetName',$AssetSubCategoryId,$BaseUnitId,$Brand,'$Model',$OEMDetailId,$UserId,$ModelId,$CustodianStaffID,$FunctionalStatusId,$AssetConditionid,'$Notes',$ContractId");
                        
                         

                        return $this->handleResponse($response, "Asset Updated"); 
                        
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of updateWOStatus function

    public function getCostBookingHistory(Request $request )
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
                        $response = DB::select('exec GetCostBookingHistory ?',[$parameters['user_id']]); 
                        return $this->handleResponse($response, "cost booking list ");  
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getAssetCategories function

    public function getAssetHistory(Request $request )
    {
        if($request->ismethod('post')){ 

            $rules = array( 
                'asset_no' => 'required|regex:/^[a-zA-Z0-9\-]+$/',   
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
                        $response = DB::select('exec Tech_Asset_GetHistoryNew ?,?',[$parameters['asset_no'],$parameters['user_id']]); 
                        return $this->handleResponse($response, "asset history ");  
                    }catch (\Exception $e){
                        return $this->handleError($e->getMessage(),400);
                    } 
                }else{
                    return $this->handleError('Authentication Failed',404);
                } // end validate credentails

            } //end of validator condition

        }// end of check post method 

    } // end of getAssetHistory function
 
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

