<?
/*
    SpacesUrlRule.php

    Copyright Stefan Fisk 2012.
*/

class SpacesUrlRule extends CBaseUrlRule {
    // location/list?id=[:region->id] => [:region->slug]/spaces
    // location/view?id=[:location->id] => [:location->region-slug]/[:location->slug]
    public function createUrl($manager, $route, $params, $ampersand) {
        if (!preg_match('%^location/(list|view)$%', $route, $matches)) {
            return FALSE;
        }

        $action = $matches[1];
        $id = isset($params['id']) ? $params['id'] : null;

        if ('list' === $action) {
            if (null === $id) {
                $id = Yii::app()->session['region'];
            }

            $region = Region::model()->findByPk($id);

            if (null === $region) {
                return FALSE;
            }

            return $region->slug . '/spaces';
        }

        if ('view' === $action) {
            $location = Location::model()->findByPk($id);

            if (null === $location) {
                return FALSE;
            }

            return $location->region->slug . '/' . $location->slug;
        }
    }

    // [:region->slug]/spaces => location/list?id=[:region->id]
    // [:region->slug]/[:location->slug] => location/view?id=[:location->id]
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) {
        if (!preg_match('%^([\w-]+)/([\w-]+)?$%', $pathInfo, $matches)) {
            return FALSE;
        }

        $regionSlug = $matches[1];
        $actionOrLocationSlug = $matches[2];

        $region = Region::model()->findByAttributes(array('slug' => $regionSlug));

        if (null === $region) {
            return FALSE;
        }

        if ('spaces' === $actionOrLocationSlug) {
            $_GET['id'] = $region->id;
            return 'location/list';
        }

        $location = Location::model()->findByAttributes(array(
            'slug' => $actionOrLocationSlug,
            'region_id' => $region->id
        ));

        if (null === $location) {
            return FALSE;
        }

        $_GET['id'] = $location->id;
        return 'location/view';
    }
}