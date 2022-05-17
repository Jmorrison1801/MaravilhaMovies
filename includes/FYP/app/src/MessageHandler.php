<?php


namespace MaravilhaMovies;


class MessageHandler
{
    private $soap_client_handle;
    private $soapclient_parameters;
    private $database_connection_settings;
    private $sql_queries;
    private $DatabaseWrapper;
    private $srcMSISDN;
    private $destMSISDN;
    private $recTime;
    private $bearer;
    private $messageRef;
    private $username;
    private $password;

    private function createSoapClient(){
        $soap_client_handle = false;

        $soapclient_attributes = ['trace' => true, 'exceptions' => true];
        $wsdl = WSDL;

        try
        {
            $soap_client_handle = new \SoapClient($wsdl, $soapclient_attributes);
        }
        catch (\SoapFault $exception)
        {
            trigger_error($exception);
        }

        return $soap_client_handle;
    }

    public function setSrcMSISDN($sMSISDN){
        $this->srcMSISDN = $sMSISDN;
    }
    public function setDestMSISDN($dMSISDN){
        $this->destMSISDN = $dMSISDN;
    }
    public function setRecTime($rTime){
        $this->recTime = $rTime;
    }
    public function setBearer($Bearer){
        $this->bearer = $Bearer;
    }
    public function setMessageRef($MessageRef){
        $this->messageRef = $MessageRef;
    }
    public function setUsername($username){
        $this->username = $username;
    }
    public function setPassword($password){
        $this->password = $password;
    }
}