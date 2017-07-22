<?php

/**
 * Class centralstation_crm
 */
class centralstation_crm {

    private $apiUrl;
    private $accessKey;

    /**
     * centralstation_crm constructor.
     */
    public function __construct($apiUrl, $accessKey)
    {
        $this->apiUrl       = $apiUrl;
        $this->accessKey    = $accessKey;
    }

    /**
     * @param $szEntityType
     * e.g. people, companies, deals, projects, tasks
     * @return mixed
     */
    public function getEntityMethods($szEntityType, $methods = 'all')
    {
        $szMethod           = 'GET';
        $szUrl              = $szEntityType.'.json';
        $aData              = array();
        $aData['methods']   = $methods;
        $aResponse          = self::sendRequest($szMethod,$szUrl,$aData);

        return $aResponse['response'];
    }

    /**
     * @param $szEntityType
     * e.g. people,companies,deals,projects,tasks
     * @param $aEntity
     * @return bool
     */
    public function createEntity($szEntityType,$aEntity)
    {
        $szMethod   = 'POST';
        $szUrl      = $szEntityType.'.json';
        $aResponse  = self::sendRequest($szMethod,$szUrl,$aEntity);

        if ($aResponse['code'] == '201')
        {
            return $aResponse['response'];
        }

        return false;
    }

    /**
     * @param $szEntityType
     * e.g. people,companies,deals,projects,tasks
     * @param $szEntityId
     * @param $szEntitySubType
     * e.g. addr, contact_details, historic_events, custom_fields, positions, protocols, attachments, tags
     * @param $aEntitySub
     * @return bool
     */
    public function createEntitySubDetail($szEntityType,$szEntityId,$szEntitySubType,$aEntitySub)
    {
        $szMethod   = 'POST';
        $szUrl      = $szEntityType.'/'.$szEntityId.'/'.$szEntitySubType.'.json';
        $aResponse  = self::sendRequest($szMethod,$szUrl,$aEntitySub);

        if ($aResponse['code'] == '201')
        {
            return $aResponse['response'];
        }

        return false;
    }

    /**
     * @param $szEntityType
     * e.g. people, companies, deals, projects, tasks
     * @return mixed
     */
    public function getEntityList($szEntityType)
    {
        $szMethod   = 'GET';
        $szUrl      = $szEntityType.'.json';
        $aResponse  = self::sendRequest($szMethod,$szUrl);

        if ($aResponse['code'] == '200')
        {
            return $aResponse['response'];
        }

        return false;
    }

    /**
     * @param $szEntityType
     * e.g. people, companies, deals, projects, tasks
     * @param $szEntityId
     * @return bool
     */
    public function getEntityDetail($szEntityType,$szEntityId, $includes = 'all')
    {
        $szMethod               = 'GET';
        $szUrl                  = $szEntityType.'/'.$szEntityId.'.json';
        $aData['includes']      = $includes;
        $aResponse              = self::sendRequest($szMethod,$szUrl,$aData);

        if ($aResponse['code'] == '200')
        {
            return $aResponse['response'];
        }

        return false;
    }

    /**
     * @param $szEntityType
     * e.g. people,companies,deals,projects,tasks
     * @param $szEntityId
     * @param $szEntitySubType
     * e.g. addr, contact_details, historic_events, custom_fields, positions, protocols, attachments, tags
     * @param $szEntitySubId
     * @return bool
     */
    public function getEntitySubDetail($szEntityType,$szEntityId,$szEntitySubType,$szEntitySubId,$includes = 'all')
    {
        $szMethod               = 'GET';
        $szUrl                  = $szEntityType.'/'.$szEntityId.'/'.$szEntitySubType.'/'.$szEntitySubId.'.json';
        $aData['includes']      = $includes;
        $aResponse              = self::sendRequest($szMethod,$szUrl,$aData);

        if ($aResponse['code'] == '200')
        {
            return $aResponse['response'];
        }

        return false;
    }

    /**
     * @param $szEntityType
     * e.g. people,companies,deals,projects,tasks
     * @param $szEntityId
     * @param $aEntity
     * @return bool
     */
    public function updateEntityDetail($szEntityType,$szEntityId,$aEntity)
    {
        $szMethod   = 'PUT';
        $szUrl      = $szEntityType.'/'.$szEntityId.'.json';
        $aResponse  = self::sendRequest($szMethod,$szUrl,$aEntity);

        if ($aResponse['code'] == '200')
        {
            return true;
        }

        return false;
    }

    /**
     * @param $szEntityType
     * e.g. people,companies,deals,projects,tasks
     * @param $szEntityId
     * @param $szEntitySubType
     * e.g. addr, contact_details, historic_events, custom_fields, positions, protocols, attachments, tags
     * @param $aEntitySub
     * @param $szEntitySubId
     * @return bool
     */
    public function updateEntitySubDetail($szEntityType,$szEntityId,$szEntitySubType,$szEntitySubId,$aEntitySub)
    {
        $szMethod   = 'PUT';
        $szUrl      = $szEntityType.'/'.$szEntityId.'/'.$szEntitySubType.'/'.$szEntitySubId.'.json';
        $aResponse  = self::sendRequest($szMethod,$szUrl,$aEntitySub);

        if ($aResponse['code'] == '200')
        {
            return true;
        }

        return false;
    }

    /**
     * @param $szMethod
     * @param $szUrl
     * @param $aData
     * @return array
     */
    private function sendRequest ($szMethod,$szUrl,$aData = array())
    {
        $aResponse          = array();
        $aData['apikey']    = $this->accessKey;
        $szUrl              = $this->apiUrl.$szUrl;
        $aHeaders           = array(
            'Accept: application/json',
            'Content-Type: application/json',
        );

        $aCurl = curl_init();

        switch($szMethod) {
            case 'GET':
                $szUrl .= '?' . http_build_query($aData);
                break;
            case 'POST':
                curl_setopt($aCurl, CURLOPT_POST, true);
                curl_setopt($aCurl, CURLOPT_POSTFIELDS, json_encode($aData));
                break;
            case 'PUT':
                curl_setopt($aCurl, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($aCurl, CURLOPT_POSTFIELDS, json_encode($aData));
                break;
            case 'DELETE':
                curl_setopt($aCurl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                $szUrl .= '?' . http_build_query($aData);
                break;
        }

        curl_setopt($aCurl, CURLOPT_URL, $szUrl);
        curl_setopt($aCurl, CURLOPT_HTTPHEADER, $aHeaders);
        curl_setopt($aCurl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($aCurl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($aCurl, CURLOPT_SSL_VERIFYPEER, false);

        $aResponse['response']     = json_decode(curl_exec($aCurl),true);
        $aResponse['code']         = curl_getinfo($aCurl, CURLINFO_HTTP_CODE);
        curl_close($aCurl);

        return $aResponse;
    }
}
