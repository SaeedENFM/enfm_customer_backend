<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Validator; 
use App\Models\CaniasSetup;
 

class InventoryController extends Controller
{
    protected $credentials_user_name;
    protected $credentials_password;

    public function __construct()
    {
        $this->credentials_user_name = config('constents.credentials_user_name');
        $this->credentials_password = config('constents.credentials_password'); 
    }
 
    public function getAllStores(Request $request)
    {
        if ($request->ismethod('post')) {

             
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try {
                        $response = DB::select("SELECT Id, StoreCode, StoreCode +' - ' +   StoreName as StoreName from Store");

                        return $this->handleResponse($response, "All Stores list ");
                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

             
        } // end of check post method 

    } // end of getAllStores function

    public function getAllStaffList(Request $request)
    {
        if ($request->ismethod('post')) {
   
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try { 
                        $response = DB::select(" select Id, convert(nvarchar(200),[StaffCode]) As StaffCode, StaffCode +' - ' +  StaffName as StaffName from Staff Where IsNull(Active,0) = 1");

                        return $this->handleResponse($response, "all staffs list");

                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails
 
        } // end of check post method 

    }// end of getAllStaffList
    

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

    public function getStoreItems(Request $request)
    {
        if ($request->ismethod('post')) {

            $rules = array(
                'store_id' => 'required|numeric',
                'cat_id' => 'required|numeric',
            );
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return $this->handleError($validator->errors(), 404);
            } else {
                $parameters = $request->all();
                $token = request()->bearerToken();

                if ($this->validateCredentails($token)) {
                    try { 
                        $this->getCaniasItemsStocksNew($parameters); 
                        $results = DB::select('exec GetitemlistWithPagination ?,?,?,?', [$parameters['cat_id'], $parameters['store_id'], $parameters['page'], $parameters['page_size']]);
                        return $this->handleResponse($results, "General Store items list ");
                    } catch (\Exception $e) {
                        return $this->handleError($e->getMessage(), 400);
                    }
                } else {
                    return $this->handleError('Authentication Failed', 404);
                } // end validate credentails

            } //end of validator condition

        } // end of check post method 

    } // end of getStoreItems function
    
    
    public function getCaniasItemsStocksNew($parameters)
    {
        $client = new Client(); 
        $res = $client->request('POST', 'https://cafm.emiratesnfm.ae/ReflexionTechAppService/getCaniasStoreItems.php', [
            
            'form_params' => [
                'store_id' => $parameters['store_id'],   
            ],
            'verify' => false, 
        ]);
        return $res->getBody();
        // echo $res->getStatusCode(); 
    }

    function getCaniasItemsStocks($parameters)
    {
        $setting = DB::select('exec Settings_GetList');
        
        // return $setting;
        if ($setting[0]->IsCanias == "1" && $setting[0]->IsCaniasBalanceUpdateNeeded == "1") {
            $caniasSetup = new CaniasSetup();
           
            $caniasLogin = $caniasSetup->caniasLogin();
            // return $caniasLogin;

            if ($caniasLogin->Success) {
                $IASSeesionID = $caniasLogin->SessionId;
                $IASSecurityKey = $caniasLogin->SecurityKey;
            } else {
                $IASSeesionID = $caniasLogin->SessionId;
                $IASSecurityKey = $caniasLogin->SecurityKey;
            }

            $storeDetails = DB::select('exec caniasStore_GetByID ?', [$parameters['store_id']]);
            // return $storeDetails;
            $stock_info = array(
                array(
                    'SITECODE' => $storeDetails[0]->CaniasWareCode,
                ),
            );

            $stockXml = $caniasSetup->createStockXMl($stock_info);
            // return $stockXml;
            $ID = 0;
            $Response = $stockXml;
            $Status = "";
            $CreatedOn = date('Y-m-d H:i:s');
            $ServiceID = "GetStock";
            $RequestString = $stockXml;

            $caniasRequest = DB::select(
                'exec CaniasRequest_SaveUpdate ?,?,?,?,?,?',
                [$ID, $Response, $Status, $CreatedOn, $ServiceID, $RequestString]
            );

            $requestId = $caniasRequest[0]->InsertedID;
            // $requestId = 239769;


            // $Parms[0] = "<PARAMETERS><PARAM><![CDATA[" . $stockXml . "]]></PARAM></PARAMETERS>";
            // $Parms[0] = "<PARAMETERS><PARAM>" . $stockXml . "</PARAM></PARAMETERS>";
            $Parms[0] = "<![CDATA[" . $stockXml . "]]>";
            
            // $Parms[0] = "
            // <PARAMETERS>
            //     <PARAM> 
            //     <STOCK>
            //         <STOCKPARAM>
            //             <SITECODE>P0002</SITECODE>
            //         </STOCKPARAM>
            //     </STOCK>
            //     </PARAM>
            // </PARAMETERS> ";
             
            $Parms = $Parms[0];
            
            $serviceResponce = $caniasSetup->caniasCallService($IASSeesionID, $IASSecurityKey, "GetStock",(String)$Parms, false, false, "", $requestId);
            // return $serviceResponce->Response;
            $caniasSetup->caniasLogout($IASSeesionID);

            $ID = $requestId;
            $Response = $serviceResponce->Response;
            $Status = $serviceResponce->SYSStatus;
            $CreatedOn = date('Y-m-d H:i:s');
            $ServiceID = "GetStock";
            $RequestString = $stockXml; 
            $caniasRequest = DB::select(
                'exec CaniasRequest_SaveUpdate ?,?,?,?,?,?',
                [$ID, $Response, $Status, $CreatedOn, $ServiceID, $RequestString]
            );

            // return $caniasRequest;
        }
    } //end of getCaniasItemsStocks



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

