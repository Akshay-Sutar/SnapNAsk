<?php
    class API{
        private $sUrl;
        private $aParams;
        private $aHeaders;
        private $oCurl;

        function __construct(){
            $this->oCurl = curl_init();
        }

        public function setUrl($sUrl){
            $this->sUrl = $sUrl;
            return $this;
        }

        public function setParam($aData){
            $this->aParams = $aData;
            return $this;
        }

        private function hasParams(){
            return ( !empty($this->aParams) )?true:false;
        }

        private function hasheaders(){
            return ( !empty($this->aHeaders) )?true:false;
        }

        public function GET(){
            
            if($this->hasParams()){
                $url = sprintf("%s?%s", $this->sUrl, http_build_query($this->aParams));                
            }
            return $this;
        }

        public function POST(){
            curl_setopt($this->oCurl, CURLOPT_POST, 1);            
            if($this->hasParams()){
                curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, $this->aParams);
            }            
            return $this;
        }

        public function PUT(){
            curl_setopt($this->oCurl, CURLOPT_CUSTOMREQUEST, "PUT");

            if($this->hasParams()){
                curl_setopt($this->oCurl, CURLOPT_POSTFIELDS, $this->aParams);
            }            
            return $this;
        }
        

        public function getData(){
            // OPTIONS:
             curl_setopt($this->oCurl, CURLOPT_URL, $this->sUrl);
             if($this->hasheaders()){
                curl_setopt($this->oCurl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    $this->aHeaders
                 ));
             }else{
                curl_setopt($this->oCurl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                ));
            }
            
            curl_setopt($this->oCurl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($this->oCurl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // EXECUTE:

            $result = curl_exec($this->oCurl);
            if(!$result){
                $aResponse = ['iCode'=>400,'sMessage'=>'Error'];
            }else{
                $aDataInJSON = json_decode($result,true);

                $aResponse = ['iCode'=>200,'sMessage'=>'Success','aResponse'=>$aDataInJSON];
            }

            curl_close($this->oCurl);            
            return $aResponse;
        }        
    }

