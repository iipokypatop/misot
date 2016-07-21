<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 21.07.2016
 * Time: 13:42
 */

namespace Aot\Sviaz\Processors\AotGraph\CollocationManager\FactoriesCollocationCandidate\API;


class RegistrySQL
{
    const INITIAL_FORM_OF_FIRST_WORD = 'initial_form_of_first_word';
    const COLLOCATION_IDS = 'collocation_ids';
    const COLLOCATION_ID = 'collocation_id';
    const COLLOCATION_INITIAL_FORM = 'collocation_initial_form';
    const INITIAL_FORMS = 'initial_forms';
    const POSITION_MAIN_WORD = 'position_main_word';


    /**
     * @return string
     */
    public static function getSqlMapCollocationIdsByFirstInitialForm()
    {
        return "
        select min(w.word) as " . static::INITIAL_FORM_OF_FIRST_WORD . ", array_agg(i.wcombi_id) as " . static::COLLOCATION_IDS . "
        from  wcombi_item i 
        join mwords w on i.winit_id = w.id and i.position = 1
        group by i.winit_id
        ";
    }

    /**
     * @return string
     */
    public static function getSqlMapCollocationInitialFormByCollocationId()
    {
        return "
        select id as " . static::COLLOCATION_ID . ", init_form as " . static::COLLOCATION_INITIAL_FORM . " 
        from wcombi
        ";
    }

    /**
     * @return string
     */
    public static function getSqlMapInitialFormsByCollocationId()
    {
        return "
        select i.wcombi_id as " . static::COLLOCATION_ID . ", array_agg(w.word order by i.position) as  " . static::INITIAL_FORMS . "
        from wcombi_item i 
        join mwords w on i.winit_id = w.id 
        group by i.wcombi_id
        ";
    }

    /**
     * @return string
     */
    public static function getSqlMapMainPositionByCollocationId()
    {
        return "
        select i.wcombi_id as " . static::COLLOCATION_ID . ", min(case when i.is_key then i.position end) as " . static::POSITION_MAIN_WORD . "
        from wcombi_item i 
        group by i.wcombi_id
        ";
    }

}