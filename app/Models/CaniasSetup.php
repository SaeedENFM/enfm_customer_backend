<?php

namespace App\Models;
use SoapClient;
use SoapServer;
use Artisaninweb\SoapWrapper\SoapWrapper;

class CaniasSetup 
{ 

     // ------------ canias setup methods
     function caniasCallService($IASSeesionID, $IASSecurityKey, $ServiceId,  $Parameters, $Compressed, $Permanent, $ExtraVariables, $requestId)
     {
         try {
  
   
             $xml = "<?xml version='1.0' encoding='utf-8'?>
             <soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>
             <soap:Body>
                 <callService>
                     <SessionId>$IASSeesionID</SessionId>
                     <SecurityKey>$IASSecurityKey</SecurityKey>
                     <ServiceId>$ServiceId</ServiceId>
                     <Parameters>$Parameters</Parameters>
                     <Compressed>0</Compressed>
                     <Permanent>0</Permanent>
                     <ExtraVariables></ExtraVariables>
                     <RequestId>$requestId</RequestId> 
                 </callService>
             </soap:Body>
             </soap:Envelope>
             ";
            //  return $xml;
 
             $response = $this->wsdlCurl($xml);
             return $response;    
             $doc = new \DOMDocument('1.0', 'utf-8');
             $doc->validateOnParse = true; 
             $doc->preserveWhiteSpace = false;
             $doc->loadXML($response);  
             $Response = $this->getElementById($doc,'id1')->firstChild->nodeValue; 
             $SYSStatusXMLresults = $doc->getElementsByTagName("SYSStatus"); 
             $SYSStatus = $SYSStatusXMLresults->item(0)->nodeValue;
              
             $response = (object)[
                 "Response"=>$Response,
                 "SYSStatus"=>$SYSStatus, 
             ];
 
             return $response;
         } catch (\Exception $e) {  
             return $e->getMessage(); 
         }
 
  
     }

     function soapClientCaniasCallService($IASSeesionID, $IASSecurityKey, $ServiceId,  $Parameters, $Compressed, $Permanent, $ExtraVariables, $requestId)
     {
         try {
               


            $wsdl = "http://canias.emiratesnfm.ae:80/CaniasWS-v2/services/CaniasWebService?wsdl";
            // $wsdl = 'http://10.0.0.4:8080/CaniasWS-v2/services/CaniasWebService?wsdl';
            // $wsdl = 'http://20.46.150.165:8080/CaniasWS-v2/services/CaniasWebService?wsdl';
            
            // $wsdl = 'https://213.42.147.182:8080/CaniasWS-v2/services/CaniasWebService?wsdl';
            //    return $Parameters;
             
            $opts = array(
                'http' => array(
                        'user_agent' => 'PHPSoapClient'
                    )
            );
            
            $context = stream_context_create($opts);
            
            $client = new SoapClient($wsdl, array(
                    'trace' => 1,
                    'exceptions' => true,
                    'stream_context' => $context,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'style' => SOAP_RPC,
                    'use' => SOAP_ENCODED,
                )
            );  
            $client->__setLocation('http://canias.emiratesnfm.ae/CaniasWS-v2/services/CaniasWebService');
            // $response = $client->callService($IASSeesionID, $IASSecurityKey, $ServiceId,  $Parameters, $Compressed, $Permanent, $ExtraVariables, $requestId);
           
            // $client = new SoapClient($wsdl,array('soap_version' => SOAP_1_1, 'trace' => 1, "exceptions" => 0));
            // $response = $client->callService($IASSeesionID, $IASSecurityKey, $ServiceId,  $Parameters, $Compressed, $Permanent, $ExtraVariables, $requestId);
             
            $response = $client->login("00", "E", "CANIAS", "ENFM", '10.0.0.4:27499', "REFLEXION", "REF123", "0", "0", "", "");

              
            return $response;     
  
         } catch (\Exception $e) {  
             return $e->getMessage(); 
         }
 
  
     }
     function getElementById($doc,$id)
     {
         $xpath = new \DOMXPath($doc);
         return $xpath->query("//*[@id='$id']")->item(0);
     }
 
     function caniasLogin()
     {
         try {
 
             $caniasSetting = \DB::select('select * from CaniasSettings');
             // return $caniasSetting; 
             $Client = $caniasSetting[0]->Client;
             $Language = $caniasSetting[0]->Language;
             $DBServer = $caniasSetting[0]->DBServer;
             $DBName = $caniasSetting[0]->DBName;
             $ApplicationServer = $caniasSetting[0]->ApplicationServer;
             $UserName = $caniasSetting[0]->UserName;
             $Password = $caniasSetting[0]->Password;
             $Encrypted = $caniasSetting[0]->Encrypted;
             $Compression = $caniasSetting[0]->Compression;
             $LCheck = $caniasSetting[0]->LCheck;           
             $VKey = $caniasSetting[0]->VKey;  
   
             $xml = "<?xml version='1.0' encoding='utf-8'?>
             <soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>
             <soap:Body>
                 <login>
                     <Client>$Client</Client>
                     <Language>$Language</Language>
                     <DBServer>$DBServer</DBServer>
                     <DBName>$DBName</DBName>
                     <ApplicationServer>$ApplicationServer</ApplicationServer>
                     <Username>$UserName</Username>
                     <Password>$Password</Password>
                     <Encrypted>$Encrypted</Encrypted>
                     <Compression>$Compression</Compression>
                     <LCheck>$LCheck</LCheck>
                     <VKey>$VKey</VKey>
                 </login>
             </soap:Body>
             </soap:Envelope>
             ";
 
             $response = $this->wsdlCurl($xml);
             // return $response;  
 
             $doc = new \DOMDocument('1.0', 'utf-8');
             $doc->loadXML( $response ); 
             $sessionIdXMLresults = $doc->getElementsByTagName("SessionId"); 
             $SessionId = $sessionIdXMLresults->item(0)->nodeValue;
 
             $SuccessXMLresults = $doc->getElementsByTagName("Success"); 
             $Success = $SuccessXMLresults->item(0)->nodeValue;
             
 
             $SecurityKeyXMLresults = $doc->getElementsByTagName("SecurityKey"); 
             $SecurityKey = $SecurityKeyXMLresults->item(0)->nodeValue;
             
             $ErrorMessageXMLresults = $doc->getElementsByTagName("ErrorMessage"); 
             $ErrorMessage = $ErrorMessageXMLresults->item(0)->nodeValue;
             
             $response = (object)[
                 "SessionId"=>$SessionId,
                 "Success"=>$Success,
                 "SecurityKey"=>$SecurityKey,  
                 "ErrorMessage"=>$ErrorMessage
             ];
 
             return $response;
         } catch (\Exception $e) {  
             return $e->getMessage(); 
         }
 
  
     }
     //end of caniasLogin
 
     function caniasLogout($sessionId)
     {
         try {
 
             
   
             $xml = "<?xml version='1.0' encoding='utf-8'?>
             <soap:Envelope xmlns:soap='http://schemas.xmlsoap.org/soap/envelope/'>
             <soap:Body>
                 <logout>
                     <SessionId>$sessionId</SessionId>
                 </logout>
             </soap:Body>
             </soap:Envelope>
             ";
 
             $response = $this->wsdlCurl($xml);
             // return $response;  
 
             $doc = new \DOMDocument('1.0', 'utf-8');
             $doc->loadXML( $response ); 
             $logoutReturnXMLresults = $doc->getElementsByTagName("logoutReturn"); 
             $logoutReturn = $logoutReturnXMLresults->item(0)->nodeValue;
 
             $response = (object)[
                 "logoutReturn"=>$logoutReturn, 
             ];
 
             return $response;
         } catch (\Exception $e) {  
             return $e->getMessage(); 
         }
 
  
     }
 
     function wsdlCurl($xml) { 
             $url = "https://canias.emiratesnfm.ae/CaniasWS-v2/services/CaniasWebService?wsdl";
             $ch = curl_init();
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 
             $headers = array();
             array_push($headers, "Content-Type: text/xml; charset=utf-8");
             array_push($headers, "Accept: text/xml");
             array_push($headers, "Cache-Control: no-cache");
             array_push($headers, "Pragma: no-cache");
             array_push($headers, "SOAPAction: callService");
 
             if ($xml != null) {
                 curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml");
                 array_push($headers, "Content-Length: " . strlen($xml));
             }
 
             // curl_setopt($ch, CURLOPT_USERPWD, "user_name:password"); /* If required */
             curl_setopt($ch, CURLOPT_POST, true);
             curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 
             $response = curl_exec($ch);
             $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
             curl_close($ch);
             return $response;
     }

    function createStockXMl($list)
    { 
        $xml_stock_info = new \SimpleXMLElement("<?xml version=\"1.0\"?><STOCK></STOCK>");

        $this->array_to_xml($list, $xml_stock_info);

        return $xml_stock_info->asXML();
    }

    function array_to_xml($details, $xml_details)
    {
        foreach ($details as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml_details->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                } else {
                    $subnode = $xml_details->addChild("STOCKPARAM");
                    $this->array_to_xml($value, $subnode);
                }
            } else {
                $xml_details->addChild("$key", "$value");
            }
        }
    }

    function createIssueItemXMl($list)
    { 
        $xml_stock_info = new \SimpleXMLElement("<?xml version=\"1.0\"?><STOCK></STOCK>");

        $this->array_to_xml($list, $xml_stock_info);

        return $xml_stock_info->asXML();
    }

     // ------------ end canias setup methods
 
}
