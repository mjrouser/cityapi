<?php

/*
    LanguageSelector.php

    Copyright Stefan Fisk 2012
*/

class LanguageSelector extends CWidget {
    public function init() {
        $translations = self::getLanguagesList();

        ?>
        <div class="language-selector">
            <ul class="languages">
                <?php
                foreach(array_reverse(self::getLanguagesList()) as $language) {
                    ?>
                    <li class="language <?php if ($language === Yii::app()->getLanguage()) echo 'current'; ?>">
                        <?php
                        echo CHtml::link(
                            $language,
                            Yii::app()->request->requestUri,
                            array(
                                'class' => $language === Yii::app()->getLanguage(),
                                'submit' => Yii::app()->request->requestUri,
                                'params' => array(
                                    'languageSelector' => $language
                                )
                            )
                        );
                        ?>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
        <?php
    }

    public static function setLanguage($cookieDays = 180){
        if (Yii::app()->request->getPost('languageSelector') !== null && in_array($_POST['languageSelector'], self::getLanguagesList(), true)) {
            Yii::app()->setLanguage($_POST['languageSelector']);

            $cookie = new CHttpCookie('language', $_POST['languageSelector']);
            $cookie->expire = time() + 60 * 60 * 24 * $cookieDays;
            Yii::app()->request->cookies['language'] = $cookie;
        } else if (isset(Yii::app()->request->cookies['language']) && in_array(Yii::app()->request->cookies['language']->value, self::getLanguagesList(), true) ) {
            Yii::app()->setLanguage(Yii::app()->request->cookies['language']->value);
        } else if (isset(Yii::app()->request->cookies['language'])) {
            // Invalid language
            unset(Yii::app()->request->cookies['language']);
        } else {
            Yii::import('ext.EGeoIP');

            try {
                $geoIp = new EGeoIP();
                $geoIp->locate();
                $countryCode = strtolower($geoIp->getCountryCode());

                if (!in_array($countryCode, self::getLanguagesList(), true)) {
                    return;
                }

                Yii::app()->setLanguage($countryCode);

                $cookie = new CHttpCookie('language', $countryCode);
                $cookie->expire = time() + 60 * 60 * 24 * $cookieDays;
                Yii::app()->request->cookies['language'] = $cookie;
            } catch (Exception $exception) {
                Yii::log($exception->__toString(), 'error', 'app.widgets.languageSelector');
            }
        }
    }

    private static function getLanguagesList(){
        $translations = array();
        $directoryIterator = new DirectoryIterator(Yii::app()->messages->basePath);

        foreach ($directoryIterator as $item) {
            if ($item->isDir() && !$item->isDot()) {
                $translations[$item->getFilename()] = $item->getFilename();
            }
        }

        return $translations;
    }
}