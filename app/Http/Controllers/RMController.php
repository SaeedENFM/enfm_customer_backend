<?php

namespace App\Http\Controllers;

use App\Models\CaniasSetup;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;


class RMController extends Controller
{
    protected $credentials_user_name;
    protected $credentials_password;
    protected $client;
    protected $caniasUrlwsdl;


    public function __construct()
    {
        $this->caniasUrlwsdl = config('constents.canias_url');
        $this->credentials_user_name = config('constents.credentials_user_name');
        $this->credentials_password = config('constents.credentials_password');
    }

    public function getPendingRM(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array( 
                'user_id' => 'required|numeric',  

            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {

                        $response = DB::select('exec Mob_GetRMWorkOrderListByUserId ?', [$parameters['user_id']]);
                        return $this->handleResponse($response, 'Work order list');

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getPendingRM function

    public function getSingleWOHistory(Request $request)
    {
        if ($request->ismethod('post')) { 
            $rules = array( 
                'wo_id' => 'required|numeric',   
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {

                        $response = DB::select('exec Mob_GetWOHistory ?', [$parameters['wo_id']]);
                        return $this->handleResponse($response, 'Work order history');

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getSingleWOHistory function

    public function getRMWOHistory(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array( 
                'user_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {

                        $response = DB::select('exec GetRMHistoryByUserId ?', [$parameters['user_id']]);
                        return $this->handleResponse($response, 'Work order list');

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getRMWOHistory function

    public function getWOCount(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array( 
                'user_id' => 'required|numeric',  

            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {

                        $response = DB::select('exec Mob_Count_Tech ?', [$parameters['user_id']]);
                        return $this->handleResponse($response, 'Work order count');

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getWOCount function




    public function getWOImage(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array( 
                'wo_id' => 'required|numeric',  


            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {

                        // $source = "Asset";  //'RM';
                        $response = DB::select('exec sc_App_GetAllImages ?,?', [$parameters['wo_id'], $parameters['source']]);
                        // return $response;
                        // $img_response = $this->getImageFromFolder("164742-1685352092.png");
                        // $decoded_response = json_decode($img_response);
                        // return $decoded_response->response;

                        foreach ($response as $key => $image) {
                            if ($image->DocumentFileName != null) {
                                $image_name = $image->DocumentFileName;
                                // return $image_name;
                                $img_response = $this->getImageFromFolder($image_name);
                                // return $img_response;
                                if ($img_response) {
                                    $decoded_response = json_decode($img_response);
                                    $image->Base64Image = $decoded_response->response;
                                }
                            }
                            if ($key > 7) {
                                break;
                            }
                        }


                        return $this->handleResponse($response, 'Work order images');

                        // $response = ['fser'] ;

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of history function
    public function getImageFromFolder($image_name)
    {
        $client = new Client();
        try {
            $res = $client->request('POST', 'https://cafm.emiratesnfm.ae/ReflexionTechAppService/getImage.php', [

                'form_params' => [
                    'image_name' => $image_name
                ],
                'verify' => false,
            ]);
            // return $res->getStatus();
            return $res->getBody();
        } catch (\Throwable $th) {
            return null;
        }

    }


    public function getWOStatus(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array( 
                'user_id' => 'required|numeric',  
                'wo_id' => 'required|numeric',
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {
                        $status = '';
                        $response = DB::select('exec Mob_GetRMWOStatusByCurrentStatusId ?,?,?', [$parameters['user_id'], $parameters['wo_id'], $status]);
                        return $this->handleResponse($response, "WO status");

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getRequestDetails function

    public function getRMHistory(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array( 
                'user_id' => 'required|numeric',  

            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {
                        $lat = '0';
                        $long = '0';
                        $response = DB::select('exec Mob_GetRMWorkOrderHistoryByUserId ?,?,?', [$parameters['user_id'], $lat, $long]);

                        return $this->handleResponse($response, "RM history ");

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getRequestDetails function


    public function updateWOStatus(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array(
                'WorkOrderId' => 'required|numeric',
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
 
                    $current_date = date('Y-m-d H:i:s');

                    // return $current_date;  
                    try {
                        $WorkOrderId = $parameters['WorkOrderId'];
                        $StaffId = $parameters['StaffId'];
                        $MobileNumber = $parameters['MobileNumber'];
                        $StatusId = $parameters['StatusId'];
                        $StatusTime = $current_date;
                        $StatusUpdateSourceId = 45;
                        $UserId = $parameters['UserId'];
                        $Notes = $parameters['Notes'];
                        $reOpen = 0;
                        $Latitude = 0;
                        $Longitude = 0;
                        $SignatureHold = $parameters['SignatureHold'];
                        $ReasonId = $parameters['ReasonId'];
                        $response = null;
                        $uniqueArray = [];
                        // $images =  json_decode($parameters['base64Images']);
                        // $uniqueArray = array_unique($images);

                        // return [count($uniqueArray)];
                        // return [count($images)];

                        $response = DB::select('exec WorkOrderStatusDetails_Insert ?,?,?,?,?,?,?,?,?,?,?,?,?', [$WorkOrderId, $StaffId, $MobileNumber, $StatusId, $StatusTime, $StatusUpdateSourceId, $UserId, $Notes, $reOpen, $Latitude, $Longitude, $SignatureHold, $ReasonId]);
                        
                        foreach ($uniqueArray as $key => $image) {
                            
                            $this->saveWOImageNew($parameters, $image);
                        }
                        // return $response;
                        return $this->handleResponse($response, "Status Updated");

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of updateWOStatus function

    public function saveWOImageNew($parameters,$image_base64)
    {
         
        try {

            $current_date = date('Y-m-d H:i:s');

            $current_timestamp = Carbon::now()->timestamp;
            $image_name = $parameters['WorkOrderId'] . "-" . rand() . "-" . $current_timestamp . ".png";

            $DocumentType = $parameters['DocumentType']; //AS
            $DocumentSource = $parameters['DocumentSource']; //Asset
            $DocumentDestination = "\\" . "\\10.0.0.7\Documents\TechAppPhotos\\" . $image_name;

            // return $DocumentDestination;
            $DocumentFileName = $image_name;
            $AttachedBy = $parameters['UserId'];
            $IssueDate = $current_date;
            $AttachDate = $current_date;
            $FileSize = 0;
            $FileType = "png";
            $WorkOrderId = $parameters['WorkOrderId']; //assetId
            $DocumentSourceID = $parameters['DocumentSourceID']; //13
            $StatusId = $parameters['StatusId'];
            $Base64Image = null;
            $parameters['image_base64'] = $image_base64;

            // return $image_base64;

            $img_response = $this->uploadImage($image_base64, $image_name);
            $decodedResponse = json_decode($img_response);
            if ($decodedResponse->response_code == 200) {
                $response = DB::update('exec sc_App_Documents_Save_NEW ?,?,?,?,?,?,?,?,?,?,?,?,?', [$DocumentType, $DocumentSource, $DocumentDestination, $DocumentFileName, $AttachedBy, $IssueDate, $AttachDate, $FileSize, $FileType, $WorkOrderId, $DocumentSourceID, $StatusId, $Base64Image]);
            } else {
                $response = $img_response;
            }


            return $this->handleResponse($response, "Status Updated");

        } catch (\Exception $e) {
            return $this->handleError($e->getMessage(), 400);
        }
             
    } // end of saveWOImageNew function


    public function saveWOImage(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array(
                'WorkOrderId' => 'required|numeric',
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {

                        $current_date = date('Y-m-d H:i:s'); 
                        $current_timestamp = Carbon::now()->timestamp;
                        $image_name = $parameters['WorkOrderId'] . "-" . rand() . "-" . $current_timestamp . ".png";

                        $DocumentType = $parameters['DocumentType']; //AS
                        $DocumentSource = $parameters['DocumentSource']; //Asset
                        $DocumentDestination = "\\" . "\\10.0.0.7\Documents\TechAppPhotos\\" . $image_name;

                        // return $DocumentDestination;
                        $DocumentFileName = $image_name;
                        $AttachedBy = $parameters['AttachedBy'];
                        $IssueDate = $current_date;
                        $AttachDate = $current_date;
                        $FileSize = 0;
                        $FileType = "png";
                        $WorkOrderId = $parameters['WorkOrderId']; //assetId
                        $DocumentSourceID = $parameters['DocumentSourceID']; //13
                        $StatusId = $parameters['StatusId'];
                        $Base64Image = null;

                        $img_response = $this->uploadImage($parameters['Base64Image'], $image_name);
                        $decodedResponse = json_decode($img_response);
                        if ($decodedResponse->response_code == 200) {
                            $response = DB::update('exec sc_App_Documents_Save_NEW ?,?,?,?,?,?,?,?,?,?,?,?,?', [$DocumentType, $DocumentSource, $DocumentDestination, $DocumentFileName, $AttachedBy, $IssueDate, $AttachDate, $FileSize, $FileType, $WorkOrderId, $DocumentSourceID, $StatusId, $Base64Image]);
                        } else {
                            $response = $img_response;
                        }


                        return $this->handleResponse($response, "Status Updated");

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of saveWOImage function

    public function uploadImage($image_base64, $image_name)
    {
        $client = new Client();
        // return "hare";
        $res = $client->request('POST', 'https://cafm.emiratesnfm.ae/ReflexionTechAppService/uploadImage.php', [

            'form_params' => [
                'image_base64' => $image_base64,
                'image_name' => $image_name
            ],
            'verify' => false,
        ]);
        return $res->getBody();
        // echo $res->getStatusCode(); 
    }

    public function getAllReasons(Request $request)
    {
        if ($request->ismethod('post')) {

            $token = request()->bearerToken();

            if ($this->validateCredentails($token)) {
                try {

                    $response = DB::select('exec sc_App_GetAllReasonDetails');

                    return $this->handleResponse($response, "reasons list");

                } catch (\Exception $e) {
                    return $this->handleError($e->getMessage(), 400);
                }
            } else {
                return $this->handleError('Authentication Failed', 404);
            } // end validate credentails


        } // end of check post method 

    } // end of updateWOStatus function



    public function getGeneralStores(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array( 
                'user_id' => 'required|numeric',  
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {
                        $response = DB::select('exec GetGenstore ?', [$parameters['user_id']]);
                        return $this->handleResponse($response, "General Stores list ");
                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getGeneralStores function

    public function getGeneralStoreCategories(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array(
                'store_id' => 'required|numeric',
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {
                        $response = DB::select('exec Getcategory ?', [$parameters['store_id']]);
                        return $this->handleResponse($response, "General Store categories list ");
                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getGeneralStoreCategories function

    
    public function getMaterialHistory(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array( 
                'user_id' => 'required|numeric',  

            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {
                        $workOrder = 0;
                        $response = DB::select('exec WorkOrderMaterialsHistory ?,?', [$parameters['user_id'], $workOrder]);
                        return $this->handleResponse($response, "Work order history ");
                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getStoreItems function

 
   



    //---------------------------------------------------------- 
   
      
    function testWSDlWeb()
    { 
        // return $this->caniasLogin();
        // phpinfo();
        // return null;
        try {
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                    'allow_self_signed' => true
                ),
            ); 
            // $pemPath = storage_path('dev/cacert.pem');
            $pemPath = "/Applications/XAMPP/xamppfiles/share/openssl/openssl.cnf";
 
            
            // return $pemPath;
            // $wsdl = file_get_contents('https://canias.emiratesnfm.ae/CaniasWS-v2/services/CaniasWebService?wsdl', false, stream_context_create($arrContextOptions));
            // $path = "dev/caniaswsd.xml";
            // \Storage::disk('local')->put($path, $wsdl);
            // return $wsdl;

            $wsdl = "https://canias.emiratesnfm.ae/CaniasWS-v2/services/CaniasWebService?wsdl";
            // $wsdl = 'http://10.0.0.4:8080/CaniasWS-v2/services/CaniasWebService?wsdl';
            // $wsdl = 'http://213.42.147.182:8080/CaniasWS-v2/services/CaniasWebService?wsdl';
           
            $opts = array(
                'https'=>array(
                    'user_agent' => 'PHPSoapClient'
                ),
                'ssl' => array(
                    'cafile' => $pemPath,
                    'verify_peer' => true,
                    'verify_peer_name' => true, 
                    'allow_self_signed' => true
                )
            );
            $context = stream_context_create($opts);
             

            $client = new \SoapClient($wsdl, array(
                    'trace' => 1,
                    'stream_context' => $context, 
                )
            ); 

            // $client = new \SoapClient($wsdl, array('trace' => 1));
            // $responce_param = $client->logout("23443");  
            // $responce_param = $client->login(dtCansis.Rows(0)("Client"), dtCansis.Rows(0)("Language"), dtCansis.Rows(0)("DBServer"), dtCansis.Rows(0)("DBName"), dtCansis.Rows(0)("ApplicationServer"), dtCansis.Rows(0)("UserName"), dtCansis.Rows(0)("Password"), dtCansis.Rows(0)("Encrypted"), dtCansis.Rows(0)("Compression"), dtCansis.Rows(0)("LCheck"), dtCansis.Rows(0)("VKey"));
 
            $responce_param = $client->login("00", "E", "CANIAS", "ENFM", '10.0.0.4:27499', "REFLEXION", "REF123", "0", "0", "", "");

            return $responce_param;
        } catch (\Exception $e) { 
            return $e->getMessage();
        }

    }

    public function handleError($message, $status)
    {
        return response()->json([
            'status' => $status,
            'data' => null,
            'message' => $message,
        ], 400);
    } //end of handle error

    public function handleResponse($data, $message)
    {
        return response()->json([
            'status' => 200,
            'data' => $data,
            'message' => $message,
        ], 200);
    } //end of handle response

    public function decodeJWT($token)
    {
        $tokenParts = explode(".", $token);
        $tokenHeader = isset($tokenParts[0]) ? base64_decode($tokenParts[0]) : null;
        $tokenPayload = isset($tokenParts[1]) ? base64_decode($tokenParts[1]) : null;
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);

        return $jwtPayload;
    } //end of decodeJWT

    public function validateCredentails($token)
    {
        return true;

        // $response = false;
        // $bearerToken = $this->decodeJWT($token);
        // if ($bearerToken) {
        //     if ($this->credentials_user_name == $bearerToken->user_name && $this->credentials_password == $bearerToken->password) {
        //         $response = true;
        //     }
        // }

        // return $response;
    } //end of validate




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
