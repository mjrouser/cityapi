<?
/*
    ResourcesUrlRule.php

    Copyright Stefan Fisk 2012.
*/

class ResourcesUrlRule extends CBaseUrlRule {
    // resource/list?region=[:region->id] => [:region->slug]/resources
    public function createUrl($manager, $route, $params, $ampersand) {
        if ('resource/list' !== $route) {
            return FALSE;
        }

        $regionId = isset($params['region']) ? $params['region'] : Yii::app()->session['region'];

        $region = Region::model()->findByPk($regionId);

        if (null == $region) {
            return FALSE;
        }

        $type = isset($params['type']) ? $params['type'] : null;

        return $region->slug . ((null === $type) ? '/resources' : '/resources/' . $type . 's');
    }

    // [:region->slug]/resources => resource/list?region=[:region->id]
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        if (!preg_match('%^(\w+)/resources(/(.+)s)?$%', $pathInfo, $matches)) {
            return FALSE;
        }

        $regionSlug = $matches[1];
        $region = Region::model()->findByAttributes(array('slug' => $regionSlug));

        $type = isset($matches[3]) ? $matches[3] : null;

        if (null === $region) {
            return FALSE;
        }

        $_GET['region'] = $region->id;

        if (null !== $type) {
            $_GET['type'] = $type;
        }

        return 'resource/list';
    }
}