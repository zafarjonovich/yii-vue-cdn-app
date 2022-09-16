<?php

namespace zafarjonovich\YiiVueCdnApp\yii2;

use yii\base\Widget;
use yii\helpers\Html;

class VueApp extends Widget
{
    public $syncJSFiles = true;

    public $files = [
        'Vue' => 'https://unpkg.com/vue@3/dist/vue.esm-browser.js'
    ];

    public $baseFilePath;

    protected function getJSImportMap()
    {
        $files = [];

        foreach ($this->files as $key => $jsFile) {
            if (substr($jsFile, 0, 1) === '@') {
                $path = \Yii::getAlias($jsFile);
            } else if (filter_var($jsFile, FILTER_VALIDATE_URL) !== false) {
                $path = $jsFile;
            } else {
                $path = "$this->baseFilePath/$jsFile";
            }
            if ($this->syncJSFiles) {
                $path .= "?v=" . time();
            }
            $files[$key] = $path;
        }

        return $files;
    }

    public function run()
    {
        return Html::tag(
            'script',
            json_encode(
                ['imports' => $this->getJSImportMap()],
                JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
            ),
            ['type' => 'importmap']
        );
    }
}