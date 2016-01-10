<?
/*
    MapUrlRule.php

    Copyright Stefan Fisk 2012.
*/

class MapUrlRule extends CBaseUrlRule {
    // map/view => [:region->slug]
    public function createUrl($manager, $route, $params, $ampersand) {
        if ('location/map' !== $route) {
            return FALSE;
        }

        $regionId = isset($params['id']) ? $params['id'] : Yii::app()->session['region'];

        $region = Region::model()->findByPk($regionId);

        if (null == $region) {
            return FALSE;
        }

        return $region->slug;
    }

    // [:region->slug] => map/view?id=[:region->id]
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        $region = Region::model()->findByAttributes(array('slug' => $pathInfo));

        if (null === $region) {
            return FALSE;
        }

        $_GET['id'] = $region->id;

        return 'location/map';
    }
}