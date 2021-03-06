<?php namespace Waka\Segator\Classes;


use ApplicationException;
use Event;
use Waka\Utils\Classes\DataSource;
use Waka\Segator\Models\Tag;

class TagCreator
{

    private $dataSourceModel;
    private $dataSourceId;
    private $model;
    public $classCalculs;
    private static $tag;

    public static function find($tag_id, $slug = false)
    {
        $tag = null;
        if ($slug) {
            $tag = Tag::where('slug', $tag_id)->first();
            if (!$tag) {
                throw new ApplicationException("TagCreator::find => Le code tag ne fonctionne pas : " . $tag_id);
            }
        } else {
            $tag = Tag::find($tag_id);
        }
        self::$tag = $tag;
        return new self;
    }
    public static function getProductor()
    {
        return self::$tag;
    }

    public function calculate($onlyId = false) {

        //Pour l'instant $onlyId ne fonctionne pas il permetra de travailler sur une selection de modèle.
        
        $tag = $this->getProductor();
        $calculClass = new $tag->calculModel;
        $calculs = $tag->calculs;

        $ds = new DataSource($tag->data_source);
        $modelClass = new $ds->class;
        $morphName = $modelClass->getMorphClass();

        //suppresion de ce tag pour tt les model de ce type
        \DB::table('waka_segator_taggables')->where('taggable_type', $morphName)->where('tag_id', $tag->id)->delete();

        $ids = []; // list des ids du scope. il va diminuer à chaque calcul + l'only tag initial si existe.

        if ($tag->parent_incs) {
            //trace_log("il y a de l'only tag on recherche le ou les tags prescedents");
            $tagIds = [];
            foreach ($tag->parent_incs as $previousTag) {
                $tempIds = $modelClass::TagFilter([$previousTag])->get()->pluck('id')->toArray();
                //trace_log($tempIds);
                if ($tempIds) {
                    $tagIds = array_merge($tagIds, $tempIds);
                }
            }
            $ids = array_unique($tagIds);
        }
        foreach ($calculs as $calcul) {
            $calculName = $calcul['calculCode'];
            $ids = $calculClass->{$calculName}($calcul, $ids);
        }

        $models = $modelClass::whereIn('id', $ids)->get();
        foreach ($models as $model) {
            $model->wakatags()->add($tag);
        }
    }

}
