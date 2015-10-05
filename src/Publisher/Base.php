<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 07.08.2015
 * Time: 12:50
 */

namespace Aot\Publisher;


class Base
{
    protected static $i;

    protected $lib_dir;
    protected $web_lib_dir;
    protected $vendor_dir;

    protected $published_code = [];
    protected $published_files = [];

    /**
     * Base constructor.
     */
    protected function __construct()
    {
        $this->lib_dir = PROJECT_ROOT . '/public/lib';
        $this->web_lib_dir = '/lib';
        $this->vendor_dir = PROJECT_ROOT . '/' . 'vendor';
    }

    /**
     * @return static
     */
    public static function get()
    {
        if (null === static::$i) {
            static::$i = new static;
        }

        return static::$i;
    }

    const RENDERER_JS = 1;
    const RENDERER_CSS = 2;

    protected function getPublishData()
    {
        return [];
        return [
            'components/jquery' => [
                'jquery.js' => static::RENDERER_JS,
            ],
            'joint/joint/dist' => [
                'geometry.js' => static::RENDERER_JS,
                'joint.browserify-bundle.js' => static::RENDERER_JS,
                'joint.core.css' => static::RENDERER_CSS,
                'joint.core.js' => static::RENDERER_JS,
                'joint.css' => static::RENDERER_CSS,
                'joint.js' => static::RENDERER_JS,
                'joint.layout.DirectedGraph.js' => static::RENDERER_JS,
                'joint.shapes.chess.js' => static::RENDERER_JS,
                //'joint.shapes.devs.js' => static::RENDERER_JS,
                'joint.shapes.erd.js' => static::RENDERER_JS,
                'joint.shapes.fsa.js' => static::RENDERER_JS,
                'joint.shapes.logic.js' => static::RENDERER_JS,
                'joint.shapes.org.js' => static::RENDERER_JS,
                'joint.shapes.pn.js' => static::RENDERER_JS,
                'joint.shapes.uml.js' => static::RENDERER_JS,
                'joint.webpack-bundle.js' => static::RENDERER_JS,
                'vectorizer.js' => static::RENDERER_JS,
            ],
            'components/backbone' => [
                'backbone.js' => static::RENDERER_JS
            ],
            'components/underscore' => [
                'underscore.js' => static::RENDERER_JS
            ],
            'dagre/dagre/dist' => [
                'dagre.js' => static::RENDERER_JS,
                'dagre.core.js' => static::RENDERER_JS,
            ],
            'cpettitt/graphlib/dist' => [
                'graphlib.js' => static::RENDERER_JS,
                'graphlib.core.js' => static::RENDERER_JS,
            ],
            'lodash/lodash' => [
                'lodash.core.js' => static::RENDERER_JS,
                'lodash.js' => static::RENDERER_JS,
            ]
        ];
    }


    public function publish()
    {
        foreach ($this->getPublishData() as $package_path => $package) {

            $dir_hash = $this->hash($package_path);

            $dir_path = $this->lib_dir . '/' . $dir_hash;

            if (!file_exists($dir_path)) {
                mkdir($dir_path);
            }

            if (!file_exists($dir_path)) {
                $this->rip("cant create dir {$dir_path}");
            }

            foreach ($package as $file => $renderer_id) {

                $source_file_path = $this->vendor_dir . "/" . $package_path . '/' . $file;

                if (!file_exists($source_file_path)) {
                    $this->rip("no file {$source_file_path}");
                }

                $mtime = filemtime($source_file_path);

                $destignation_file_path = "$dir_path/$file?$mtime";

                if (!file_exists($destignation_file_path)) {
                    $result = copy($source_file_path, $destignation_file_path);
                    if (!$result) {
                        $this->rip("can't copy file");
                    }
                }

                $this->addToPublished("/$dir_hash/$file?$mtime", $destignation_file_path, $renderer_id);
            }
        }
    }

    protected function rip($str)
    {
        die($str);
    }

    protected function hash($str)
    {
        return md5($str);
    }


    protected function addToPublished($web_file_path, $file_system_path, $renderer_id)
    {
        if ($renderer_id === static::RENDERER_JS) {
            $this->published_code[($web_file_path)] = "<script type='text/javascript' src='{$web_file_path}'></script>";
        } elseif ($renderer_id === static::RENDERER_CSS) {
            $this->published_code[($web_file_path)] = "<link rel='stylesheet' type='text/css'  href='{$web_file_path}'  media='screen'>";
        }

        $this->published_files[$web_file_path] = $file_system_path;
    }

    public function isPublished($web_file_path)
    {
        return isset($this->published_files[$web_file_path]);
    }

    public function getCode($web_file_path)
    {
        return file_get_contents($this->published_files[$web_file_path]);
    }

    public function render()
    {
        return join("\n", $this->published_code);
    }
}