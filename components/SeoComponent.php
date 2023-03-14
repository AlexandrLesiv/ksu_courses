<?php

namespace app\components;

use Yii;

class SeoComponent
{
	public static function putOpenGraphTags($properties)
	{
		foreach ($properties as $item => $value) {
			Yii::$app->view->registerMetaTag([
				'property' => $item,
				'content' => $value,
			]);
		}
	}

	public static function putGooglePlusMetaTags($properties)
	{
		foreach ($properties as $itemprop => $content) {
			Yii::$app->view->registerMetaTag([
				'itemprop' => $itemprop,
				'content' => $content,
			]);
		}
	}

	public static function putTwitterMetaTags($properties)
	{
		foreach ($properties as $name => $content) {
			Yii::$app->view->registerMetaTag([
				'name' => $name,
				'content' => $content,
			]);
		}
	}
}
