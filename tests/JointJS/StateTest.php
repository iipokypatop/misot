<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.08.2015
 * Time: 14:46
 */

namespace AotTest\JointJS;

class StateTest extends \MivarTest\Base
{
    public function testAllFieldsWorks()
    {
        $ob = \Aot\JointJS\Objects\Basic\Rect::create();

        $ob->setPosition(\Aot\JointJS\Objects\Position::create(1, 2));

        $ob->setId('id');




        $attrs = \Aot\JointJS\Objects\Attr::create();
        $text = \Aot\JointJS\Objects\Text::create();
        $text->setText("label");
        $attrs->setText($text);


        $ob->setAttrs($attrs);

        $ob->setAngle(200);

        $ob->setSize(\Aot\JointJS\Objects\Size::create(300, 400));

        $ob->setZ(500);

        $ob->setEmbeds([600, 700]);

        $ob->setParent('parent_id');

        #echo  json_encode($ob);

        $this->assertEquals(
          '{"id":"id","type":"basic.Rect","attrs":{"text":{"text":"label"}},"position":{"x":1,"y":2},"angle":200,"size":{"width":300,"height":400},"z":500,"embeds":[600,700],"parent":"parent_id"}',
            json_encode($ob)
        );
    }

    public function testSerializeOnlySetFields()
    {
        $ob = \Aot\JointJS\Objects\Basic\Rect::create();

        $ob->setPosition(\Aot\JointJS\Objects\Position::create(1, 2));

        $ob->setId('id');

        $ob->setZ(500);

        $ob->setParent('parent_id');

        $this->assertEquals(
            '{"id":"id","type":"basic.Rect","position":{"x":1,"y":2},"z":500,"parent":"parent_id"}',
            json_encode($ob)
        );
    }
}