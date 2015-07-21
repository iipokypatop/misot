<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 16.07.2015
 * Time: 11:46
 */

namespace Aot\Sviaz\Rule\AssertedMember\Builder;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry;
use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use Aot\View\Chargeable;

abstract class View extends \Aot\View\Base implements Chargeable
{
    /** @var  \Aot\Sviaz\Rule\AssertedMember\Builder\Base */
    protected $builderBase;

    /** @var \Aot\View\Field\Base[] */
    protected $fields = [];

    /** @var  int */
    protected $id;

    const FIELD_TEXT_TYPE = '100';
    const FIELD_TEXT_TYPE_NO = '101';
    const FIELD_TEXT_TYPE_TEXT = '102';
    const FIELD_TEXT_TYPE_TEXT_GROUP = '103';

    const FIELD_TEXT = '200';
    const FIELD_TEXT_GROUP_ID = '201';

    const FIELD_CHAST_RECHI = '300';
    const FIELD_ROLE = '400';

    const FIELD_MORPH = '500';


    public function __construct()
    {
        $this->id = Chargeable::NEW_ID_PREFIX . spl_object_hash($this);

        $this->defineFields();
    }

    public function write()
    {

    }

    public function getFieldName($name, array $extra = [])
    {
        $path = array_merge(
            [
                static::class,
                $this->id,
                $name,
            ],
            $extra
        );

        //foreach ($path as $k => $v) $path[$k] = md5($v);

        return Chargeable::OBJECT_CONTAINER . "[" . join("][", $path) . "]";
    }

    /**
     * @param int $chast_rechi_id
     * @param int $role_id
     * @return \Aot\Sviaz\Rule\AssertedMember\Builder\Base
     */
    abstract protected function createBuilder($chast_rechi_id, $role_id);

    /**
     * @param int $id
     */
    public function chargeId($id)
    {
        assert(is_int($id));
        $this->id = $id;
    }

    public function chargeData(array $data)
    {
        if (empty($data[static::FIELD_CHAST_RECHI]) || empty($data[static::FIELD_ROLE])) {
            throw new \RuntimeException('wtf?!');
        }

        $this->builderBase = $this->createBuilder(
            intval($data[static::FIELD_CHAST_RECHI]),
            intval($data[static::FIELD_ROLE])
        );


        $this(static::FIELD_TEXT_TYPE_NO)->charge('checked', false);
        $this(static::FIELD_TEXT_TYPE_TEXT)->charge('checked', false);
        $this(static::FIELD_TEXT_TYPE_TEXT_GROUP)->charge('checked', false);

        if (static::FIELD_TEXT_TYPE_NO === $data[static::FIELD_TEXT_TYPE]) {

            $this->fields[static::FIELD_TEXT_TYPE_NO]->charge('checked', true);


        } elseif (static::FIELD_TEXT_TYPE_TEXT === $data[static::FIELD_TEXT_TYPE]) {

            $this->fields[static::FIELD_TEXT_TYPE_TEXT]->charge('checked', true);

            $this->builderBase->text($data[static::FIELD_TEXT]);

        } elseif (static::FIELD_TEXT_TYPE_TEXT_GROUP === $data[static::FIELD_TEXT_TYPE]) {

            $this->fields[static::FIELD_TEXT_TYPE_TEXT_GROUP]->charge('checked', true);

            $this->builderBase->textGroupId($data[static::FIELD_TEXT_GROUP_ID]);

        } else {

            $this->fields[static::FIELD_TEXT_TYPE_NO]->charge('checked', true);
        }

        if (!empty($data[static::FIELD_MORPH])) {

            //var_export($data[static::FIELD_MORPH]);
        }
    }

    protected function defineFields()
    {
        $this->fields[static::FIELD_TEXT_TYPE_NO] = \Aot\View\Field\Base::create();
        $this->fields[static::FIELD_TEXT_TYPE_TEXT] = \Aot\View\Field\Base::create();
        $this->fields[static::FIELD_TEXT_TYPE_TEXT_GROUP] = \Aot\View\Field\Base::create();
        $this->fields[static::FIELD_CHAST_RECHI] = \Aot\View\Field\Base::create();
        $this->fields[static::FIELD_ROLE] = \Aot\View\Field\Base::create();
        $this->fields[static::FIELD_MORPH] = \Aot\View\Field\Base::create();


        $this(static::FIELD_TEXT_TYPE_NO)->charge('checked', true);
        $this->fields[static::FIELD_TEXT_TYPE_NO]->setView(
            function (\Aot\View\Field\Base $field) {

                $name = $this->getFieldName(static::FIELD_TEXT_TYPE);
                $value = static::FIELD_TEXT_TYPE_NO;
                $id = "{$name}={$value}";
                $checked = $field->getValue('checked') ? 'checked' : '';


                return <<<HTML
<input type='radio' name='{$name}' value='{$value}' id='{$id}' {$checked}>
    <label for='main[text_type] = no'>нет</label>
HTML;
            }
        );



        $this->fields[static::FIELD_TEXT_TYPE_TEXT]->setView(
            function (\Aot\View\Field\Base $field) {

                $name = $this->getFieldName(static::FIELD_TEXT_TYPE);
                $value = static::FIELD_TEXT_TYPE_TEXT;
                $id = "{$name}={$value}";
                $checked = $field->getValue('checked') ? 'checked' : '';
                $text_name = $this->getFieldName(static::FIELD_TEXT);


                return <<<HTML
<input type='radio' name='{$name}' value='{$value}' id='{$id}' {$checked}>
    <label for='{$id}'>текст</label>
    <input type="text" name="{$text_name}">
HTML;
            }
        );


        $this->fields[static::FIELD_TEXT_TYPE_TEXT_GROUP]->setView(
            function (\Aot\View\Field\Base $field) {

                $name = $this->getFieldName(static::FIELD_TEXT_TYPE);
                $value = static::FIELD_TEXT_TYPE_TEXT_GROUP;
                $id = "{$name}={$value}";
                $checked = $field->getValue('checked') ? 'checked' : '';
                $text_name = $this->getFieldName(static::FIELD_TEXT_GROUP_ID);

                $selected_id = $field->getValue('selected_id') ?: '';

                $options_data = \Aot\Text\GroupIdRegistry::getNames();

                $options = [];
                foreach ($options_data as $k => $v) {
                    $selected = '';
                    if ($k === $selected_id) {
                        $selected = 'selected';
                    }
                    $options[] = "<option {$selected} value='{$k}'>$v";
                }
                $options_html = join("\n", $options);

                return <<<HTML
<input type='radio' name='{$name}' value='{$value}' id='{$id}' {$checked}>
    <label for='{$id}'>группа</label>
    <select name="{$text_name}">
        <option value=""> - </option>
        {$options_html}
    </select>
HTML;
            }
        );


        $this->fields[static::FIELD_CHAST_RECHI]->setView(
            function (\Aot\View\Field\Base $field) {

                $name = $this->getFieldName(static::FIELD_CHAST_RECHI);
                $id = "{$name}";

                $selected_id = $field->getValue('selected_id') ?: '';
                $options_data = \Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry::getNames();
                $options = [];
                foreach ($options_data as $k => $v) {
                    $selected = '';
                    if ($k === $selected_id) {
                        $selected = 'selected';
                    }
                    $options[] = "<option {$selected} value='{$k}'>$v";
                }
                $options_html = join("\n", $options);

                return <<<HTML
<label for='{$id}'>часть речи</label>
<select id="{$id}" name="{$name}">
    {$options_html}
</select>
HTML;
            }
        );


        $this->fields[static::FIELD_ROLE]->setView(
            function (\Aot\View\Field\Base $field) {

                $name = $this->getFieldName(static::FIELD_ROLE);
                $id = "{$name}";

                $selected_id = $field->getValue('selected_id') ?: '';
                $options_data = \Aot\Sviaz\Role\Registry::getNames();
                $options = [];
                foreach ($options_data as $k => $v) {
                    $selected = '';
                    if ($k === $selected_id) {
                        $selected = 'selected';
                    }
                    $options[] = "<option {$selected} value='{$k}'>$v";
                }
                $options_html = join("\n", $options);

                return <<<HTML
<label for='{$id}'>роль</label>
<select id="{$id}" name="{$name}">
    {$options_html}
</select>
HTML;
            }
        );


        $this->fields[static::FIELD_MORPH]->setView(
            function (\Aot\View\Field\Base $field) {

                $html = [];

                foreach (MorphologyRegistry::getChastRechiPriznaki() as $chast_rechi_id => $priznaki) {

                    $html[] = "<fieldset id='fieldset-{$chast_rechi_id}'>";
                    $html[] = "<legend>" . ChastiRechiRegistry::getNames()[$chast_rechi_id] . "</legend>";
                    $html[] = "<br/>";

                    foreach ($priznaki as $priznak_group_id => $variants) {

                        $name = $this->getFieldName(static::FIELD_MORPH, [$chast_rechi_id, $priznak_group_id]);

                        $id = "{$name}";

                        $options = ["<option value=''> </option>"];
                        foreach ($variants as $priznak_id) {
                            $options[] = "<option value='{$priznak_id}'>" . MorphologyRegistry::getNames()[$priznak_id];
                        }

                        $html[] = "<label for='$id'>" . MorphologyRegistry::getNames()[$priznak_group_id];
                        $html[] = "<select name='{$name}' id='{$id}'>" . join("\n", $options) . "</select>";
                        $html[] = "<br/>";
                    }
                    $html[] = '<br/>';

                    $html[] = "</fieldset>";
                }

                return join("\n", $html);
            }
        );





    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    public static function create()
    {
        return new static();
    }




    /**
     * @param $id
     * @return \Aot\View\Field\Base
     */
    public function getField($id)
    {
        return $this->fields[$id];
    }

    public function getBuilder()
    {
        return $this->builderBase;
    }

    /**
     * @param $id
     * @return \Aot\View\Field\Base
     */
    public function __invoke($id)
    {
        return $this->fields[$id];
    }


}