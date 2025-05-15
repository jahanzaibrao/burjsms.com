<?php

/**
 * DooHlrLookupHelper class file
 * @package doo.helper
 * @author Saurav <saurabh.pandey@cubelabs.in>
 */

/**
 * DooHlrLookupHelper
 *
 * This file has hard coded API specification for different providers to simplify HLR API calls
 * HLR API request and response vary too much from provider to provider hence this file sorts this
 */

class DooHlrLookupHelper
{

    var $provider = array();
    /**
     *
     * Typical API caller data
     * id : Just enter the serial number
     * name: Company name of the API provider
     * website: Web address of the provider
     * auth: Array of parameters for authentication. In some cases, it will be API KEY, just enter the paramter names in array. For HTTP Authorizations, which is more secure provide the name of the type e.g. Basic
     * method: Enter "async" if this supports asynchronous calls. Callback URL will be <domain>/hlrApiCallback/<id>/index
     *
     */
    var $apiCallers = array(
        array(
            'id' => 1,
            'name' => 'HLR-Lookups',
            'website' => 'www.hlr-lookups.com',
            'auth' => array(
                'method' => 'post',
                'params' => array('api_key')
            ),
            'method' => 'async'
        ),
        array(
            'id' => 2,
            'name' => 'SMS 77',
            'website' => 'www.sms77.io',
            'auth' => array(
                'method' => 'httpauth',
                'type' => 'basic'
            )
        ),
        array(
            'id' => 3,
            'name' => 'Infobip HLR',
            'website' => 'www.infobip.com',
            'auth' => array(
                'method' => 'httpauth',
                'type' => 'basic'
            )
        )
    );

    var $msisdnHlrResponse = array(
        'msisdn' => '',
        'hlr_status' => 0,
        'mccmnc' => 0,
        'connected_flag' => 0,
        'roaming_flag' => 0,
        'ported_flag' => 0,
        'original_location' => '',
        'roaming_location' => '',
        'response_data' => ''
    );

    public function __construct($providerid = 0)
    {
        if ($providerid != 0) {
            $response = array_filter($this->apiCallers, function ($i) use ($providerid) {
                return $i['id'] == $providerid;
            });
            $this->provider = array_values($response)[0];
        }
    }

    public function getProviderById($id)
    {
        $response = array_filter($this->apiCallers, function ($i) use ($id) {
            return $i['id'] == $id;
        });
        return array_values($response)[0];
    }

    public function getCallers()
    {
        return $this->apiCallers;
    }

    public function sendHlrRequest($data)
    {
        if ($this->provider['id'] == 1) {
            //hlr-lookups.com
            return $this->submitToHlrLookups($data);
        }
        if ($this->provider['id'] == 2) {
            //sms77.io
            return $this->submitToSmsSevenSeven($data);
        }
        if ($this->provider['id'] == 3) {
            //Infobip.com
            return $this->submitToInfobipHlr($data);
        }
    }

    public function submitToHlrLookups($data)
    {
        //split numbers into batches as this API allows 1000 mobile numbers per request
        $response = array();
        $batches = array();
        if (sizeof($data['numbers']) < 1000) {
            array_push($batches, $data['numbers']);
        } else {
            $batches = array_chunk($data['numbers'], 999);
        }
        //foreach batch prepare request data
        // $ch = curl_init();
        // foreach ($batches as $nums) {
        //     $numstr = implode(",", $nums);
        //     $httpquery = http_build_query(array(
        //         'username' => $data['authdata']['username'],
        //         'password' => $data['authdata']['password'],
        //         'action' => 'submitAsyncLookupRequest',
        //         'msisdns' => $numstr,
        //         'route' => null,
        //         'storage' => null
        //     ), '', '&');
        //     curl_setopt($ch, CURLOPT_URL, 'https://www.hlr-lookups.com/api');
        //     curl_setopt($ch, CURLOPT_POST, true);
        //     curl_setopt($ch, CURLOPT_POSTFIELDS, $httpquery);
        //     curl_setopt($ch, CURLOPT_PORT, 443);
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //     curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        //     curl_setopt($ch, CURLOPT_USERAGENT, 'VmgLtd/HlrLookupClient PHP SDK 1.0.0');
        //     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        //     curl_setopt($ch, CURLOPT_TIMEOUT, 30000);
        //     curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        //     curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //     $res = curl_exec($ch);
        //     array_push($response, $res);
        // }
        // curl_close($ch);

        $ch = curl_init();
        foreach ($batches as $nums) {
            $numstr = implode(",", $nums);
            $httpquery = http_build_query(array(
                'api_key' => $data['authdata']['key'],
                'numbers' => $numstr,
                'details' => '1'
            ), '', '&');
            curl_setopt($ch, CURLOPT_URL, 'https://api.hlr-lookups.com/v1/hlr');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $httpquery);
            curl_setopt($ch, CURLOPT_PORT, 443);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'VmgLtd/HlrLookupClient PHP SDK 1.0.0');
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30000);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $res = curl_exec($ch);
            array_push($response, json_decode($res, true));
        }
        curl_close($ch);
        return $response;
    }

    public function submitToSmsSevenSeven($data)
    {
        $response = array();
        $numstr = implode(",", $data['numbers']);
        //call api
        $api_url = 'https://gateway.sms77.io/api/lookup?p=' . $data['authdata']['key'] . '&type=hlr&number=' . $numstr;
        $response = file_get_contents($api_url);
        $httpquery = http_build_query(array(
            'json' => $response
        ), '', '&');
        //since this is synchronous call, get response and parse it
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Doo::conf()->APP_URL . 'hlrApiCallback/2/index');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $httpquery);
        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30000);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        return $res;
    }

    public function submitToInfobipHlr($data)
    {
        $response = array();
        $numstr = json_encode($data['numbers']); // implode(",",$data['numbers']);
        //call api
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://28mem.api.infobip.com/number/1/notify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{"to": ' . $numstr . ',"notifyUrl":"' . Doo::conf()->APP_URL . 'hlrApiCallback/3/index' . '","notifyContentType":"application/json"}',
            CURLOPT_HTTPHEADER => array(
                'Authorization: App ' . $data['authdata']['key'],
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    public function parseResponse($data)
    {
        if ($this->provider['id'] == 1) {
            //hlr-lookups.com
            return $this->parseFromHlrLookups($data);
        }
        if ($this->provider['id'] == 2) {
            //sms77.io
            return $this->parseFromSmsSevenSeven($data);
        }
        if ($this->provider['id'] == 3) {
            //infobip.com
            return $this->parseFromInfobipHlr($data);
        }
    }

    public function parseFromHlrLookups($data)
    {
        $respdata = json_decode($data['json']);
        $response = array();
        foreach ($respdata->results as $hlr) {
            $this->msisdnHlrResponse['msisdn'] = $hlr->msisdn;
            $this->msisdnHlrResponse['hlr_status'] = $hlr->statuscode == 'HLRSTATUS_DELIVERED' ? 1 : 2;
            $this->msisdnHlrResponse['mccmnc'] = $hlr->mccmnc;
            $this->msisdnHlrResponse['connected_flag'] = $hlr->subscriberstatus == 'SUBSCRIBERSTATUS_CONNECTED' ? 1 : 2;
            $this->msisdnHlrResponse['roaming_flag'] = $hlr->isroaming == 'Yes' ? 2 : 1;
            $this->msisdnHlrResponse['ported_flag'] = $hlr->isported == 'Yes' ? 2 : 1;
            $this->msisdnHlrResponse['original_location'] = $hlr->originalcountrycode;
            $this->msisdnHlrResponse['roaming_location'] = $hlr->roamingcountrycode;
            $this->msisdnHlrResponse['response_data'] = base64_encode(json_encode($hlr));
            array_push($response, $this->msisdnHlrResponse);
            $this->resetMsisdnResponse();
        }
        return $response;
    }

    public function parseFromSmsSevenSeven($data)
    {
        $respdata = json_decode($data['json']);
        if (!is_array($respdata)) {
            $respdata = array();
            array_push($respdata, json_decode($data['json']));
        }

        $response = array();
        foreach ($respdata as $hlr) {
            $this->msisdnHlrResponse['msisdn'] = $hlr->international_format_number;
            $this->msisdnHlrResponse['hlr_status'] = $hlr->lookup_outcome_message == 'success' ? 1 : 2;
            $this->msisdnHlrResponse['mccmnc'] = $hlr->current_carrier->network_code;
            $this->msisdnHlrResponse['connected_flag'] = $hlr->reachable == 'reachable' ? 1 : ($hlr->reachable == 'unknown' ? '0' : 2);
            $this->msisdnHlrResponse['roaming_flag'] = $hlr->roaming == 'not_roaming' ? 1 : ($hlr->roaming == 'unknown' ? 0 : 2);
            $this->msisdnHlrResponse['ported_flag'] = $hlr->ported == 'not_ported' || $hlr->ported == 'assumed_not_ported' ? 1 : ($hlr->ported == 'unknown' ? 0 : 2);
            $this->msisdnHlrResponse['original_location'] = $hlr->original_carrier->country;
            $this->msisdnHlrResponse['roaming_location'] = $hlr->current_carrier->country;
            $this->msisdnHlrResponse['response_data'] = base64_encode(json_encode($hlr));
            array_push($response, $this->msisdnHlrResponse);
            $this->resetMsisdnResponse();
        }
        return $response;
    }

    public function parseFromInfobipHlr($data)
    {
        $respdata = json_decode($data);
        $response = array();
        foreach ($respdata->results as $hlr) {
            $this->msisdnHlrResponse['msisdn'] = $hlr->to;
            $this->msisdnHlrResponse['hlr_status'] = $hlr->status->name == 'DELIVERED_TO_HANDSET' ? 1 : 2;
            $this->msisdnHlrResponse['mccmnc'] = $hlr->mccMnc;
            $this->msisdnHlrResponse['connected_flag'] = $hlr->status->name == 'DELIVERED_TO_HANDSET' ? 1 : 2;
            $this->msisdnHlrResponse['roaming_flag'] = $hlr->roaming == 'true' ? 2 : 1;
            $this->msisdnHlrResponse['ported_flag'] = $hlr->ported == 'true' ? 2 : 1;
            $this->msisdnHlrResponse['original_location'] = $hlr->originalNetwork->countryName;
            $this->msisdnHlrResponse['roaming_location'] = $hlr->roamingNetwork->countryName;
            $this->msisdnHlrResponse['response_data'] = base64_encode(json_encode($hlr));
            array_push($response, $this->msisdnHlrResponse);
            $this->resetMsisdnResponse();
        }
        return $response;
    }

    public function resetMsisdnResponse()
    {
        $this->msisdnHlrResponse['msisdn'] = '';
        $this->msisdnHlrResponse['hlr_status'] = 0;
        $this->msisdnHlrResponse['mccmnc'] = 0;
        $this->msisdnHlrResponse['connected_flag'] = 0;
        $this->msisdnHlrResponse['roaming_flag'] = 0;
        $this->msisdnHlrResponse['ported_flag'] = 0;
        $this->msisdnHlrResponse['original_location'] = '';
        $this->msisdnHlrResponse['roaming_location'] = '';
        $this->msisdnHlrResponse['response_data'] = '';
    }
}
