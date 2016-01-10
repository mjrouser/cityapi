<?
/*
    TranslateController.php

    Copyright Stefan Fisk 2012.
*/

require_once('NuSOAP/lib/nusoap.php');

class TranslateController extends Controller {
    const WSDL = 'http://api.microsofttranslator.com/V2/Soap.svc';

    private $_soapClient;

    private $accessToken = null;

    public function filters() {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'allow',
                'actions' => array('user', 'location', 'project', 'post', 'comment'),
                'users' => array('*'),
            ),

            array(
                'deny',
            ),
        );
    }

    public function actionUser($id, $to = null) {
        $this->response(User::model(), $id, 'description', $to);
    }
    public function actionLocation($id, $to = null) {
        $this->response(Location::model(), $id, 'description', $to);
    }
    public function actionProject($id, $to = null) {
        $this->response(Project::model(), $id, 'description', $to);
    }
    public function actionPost($id, $to = null) {
        $this->response(Post::model(), $id, 'text', $to);
    }
    public function actionComment($id, $to = null) {
        $this->response(Comment::model(), $id, 'text', $to);
    }

    private function response($staticModel, $id, $attribute, $to) {
        $model = $this->loadModel($staticModel, $id);

        $text = $model->attributes[$attribute];

        $languages = $this->getLanguages(Yii::app()->getLanguage());
        $defaultLanguage = Yii::app()->user->isGuest ? Yii::app()->language : Yii::app()->user->getModel()->language;

        if (null === $to) {
            $this->render('selectLanguage', array(
                'languages' => $languages,
                'defaultLanguage' => $defaultLanguage,
                'text' => $text,
            ));

            Yii::app()->end();
        }

        $translatedText = Yii::app()->cache->get('translate/' . get_class($model) . '/' . $model->id . '/' . $to);
        if (false === $translatedText) {
            $translatedText = $this->translate($text, $to);
            Yii::app()->cache->set('translate/' . get_class($model) . '/' . $model->id . '/' . $to, $translatedText, Yii::app()->params['translate']['translationExpire']);
        }

        if (!Yii::app()->user->isGuest) {
            $user = Yii::app()->user->getModel();
            $user->language = $to;
            $user->save();
        }

        $this->render('showTranslation', array(
            'languageCode' => $to,
            'languageName' => $languages[$to],
            'translatedText' => $translatedText,
        ));
    }

    private function loadModel($staticModel, $id) {
        $model = $staticModel->findByPk($id);

        if(null === $model) {
            throw new CHttpException(404, Yii::t('app', 'The requested page does not exist.'));
        }

        return $model;
    }

    // Translator API

    public function getSoapClient() {
        if (!$this->_soapClient) {
            $this->_soapClient = new SoapClient(self::WSDL, array(
                'soap_version' => 'SOAP_1_2',
                'encoding' => 'UTF-8',
                'exceptions' => true,
                'trace' => true,
                'cache_wsdl' => 'WSDL_CACHE_NONE',
                'stream_context' => stream_context_create(array(
                    'http' => array(
                        'header' => 'Authorization: Bearer ' . $this->getAccessToken(),
                    )
                )),
            ));
        }

        return $this->_soapClient;
    }

    private function getAccessToken() {
        if (null !== $this->accessToken) {
            return $this->accessToken;
        }

        $clientID = Yii::app()->params['microsoft']['azure']['clientId'];
        $clientSecret = Yii::app()->params['microsoft']['azure']['clientSecret'];
        $authUrl = 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/';
        $scopeUrl = 'http://api.microsofttranslator.com';
        $grantType = 'client_credentials';
        $ch = curl_init();

        $params = http_build_query(array (
             'grant_type'    => $grantType,
             'scope'         => $scopeUrl,
             'client_id'     => $clientID,
             'client_secret' => $clientSecret
        ));

        curl_setopt($ch, CURLOPT_URL, $authUrl);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $strResponse = curl_exec($ch);

        $errno = curl_errno($ch);
        if($errno){
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception($error);
        }

        curl_close($ch);

        $objResponse = json_decode($strResponse);

        if (property_exists($objResponse, 'error')) {
            throw new Exception($objResponse->error_description);
        }

        $this->accessToken = $objResponse->access_token;

        return $this->accessToken;
    }

    private function getLanguages($locale) {
        $languages = unserialize(Yii::app()->cache->get('translate/languages/' . $locale));

        if (false === $languages) {
            $response = $this->soapClient->GetLanguagesForTranslate(array());

            $languageCodes = $response->GetLanguagesForTranslateResult;
            $codes = array();
            foreach ($languageCodes->string as $code) {
                $codes[] = $code;
            }

            if(0 >= sizeof($codes)) {
                Yii::log('Did not receive language codes. $strResponse = ' . $strResponse, 'error', 'app.translate');
                throw new CHttpException(500);
            }

            $response = $this->soapClient->GetLanguageNames(array(
                'locale' => $locale,
                'languageCodes' => $codes,
            ));

            $languageNames = $response->GetLanguageNamesResult;
            $names = array();
            foreach ($languageNames->string as $language) {
                $names[] = $language;
            }

            $languages = array_combine($codes, $names);

            Yii::app()->cache->set('translate/languages/' . $locale, serialize($languages), Yii::app()->params['translate']['languagesExpire']);
        }

        return $languages;
    }

    private function translate($text, $to, $from = null) {
        try {
            $response = $this->soapClient->Translate(array(
                'text' => $text,
                'from' => $from,
                'to' => $to,
            ));
        }
        catch(SoapFault $exception) {
            return $text;
        }

        return $response->TranslateResult;
    }
}
