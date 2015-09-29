<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 05.07.2015
 * Time: 2:47
 */

namespace AotTest\Functional\Sviaz\Processor;


use Aot\Sviaz\SubSequence;
use MivarTest\PHPUnitHelper;

use Aot\RussianMorphology\ChastiRechi\ChastiRechiRegistry as ChastiRechiRegistry;

use Aot\RussianMorphology\ChastiRechi\MorphologyRegistry;
use \Aot\Sviaz\Sequence as Sequence;
use Aot\Sviaz\Role\Registry as RoleRegistry;


use Aot\Sviaz\Rule\AssertedLink\Builder\Base as AssertedLinkBuilder;

use Aot\RussianMorphology\ChastiRechi\Glagol\Base as Glagol;


use Aot\RussianMorphology\ChastiRechi\Predlog\Base as Predlog;
use Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base as Prilagatelnoe;
use Aot\RussianMorphology\ChastiRechi\Soyuz\Base;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base as Suschestvitelnoe;
use Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Base as SuschestvitelnoePadeszhBase;
use Aot\RussianSyntacsis\Punctuaciya\Zapiataya;

use Aot\Sviaz\Rule\AssertedLink\AssertedMatching\MorphologyMatchingOperator\Eq;
use Aot\Sviaz\Rule\AssertedLink\Checker\Registry as LinkCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\Checker\Registry as MemberCheckerRegistry;
use Aot\Sviaz\Rule\AssertedMember\PositionRegistry;


class SubSequenceTest extends \AotTest\AotDataStorage
{
    /**
     * @brief Если нет связей вообще
     * @see \Aot\Sviaz\Processor\Base::detectSubSequences()
     *
     * detectSubSequences(\Aot\Sviaz\Sequence $sequence) - Тестирование всех связей, проверка, одинаковые ли связи в последовательности.
     * нужно для выяснения, сколько пар подлежащее-сказуемое найдено (идеально - 1, остальное неправильно)
     */
    public function testDetectSubSequencesNoSviaz()
    {
        $processor = \Aot\Sviaz\Processor\Base::create();
        $sequence = \Aot\Sviaz\Sequence::create();
        $result = PHPUnitHelper::callProtectedMethod($processor, "detectSubSequences", [$sequence]);
        $this->assertEquals(0, count($sequence->getSubSequences()));
        $this->assertNull($result);
    }


    /**
     * @brief Если одна единственная связь
     * @see \Aot\Sviaz\Processor\Base::detectSubSequences()
     *
     * detectSubSequences(\Aot\Sviaz\Sequence $sequence) - Тестирование всех связей, проверка, одинаковые ли связи в последовательности.
     * нужно для выяснения, сколько пар подлежащее-сказуемое найдено (идеально - 1, остальное неправильно)
     */
    public function testDetectSubSequencesOneSviaz()
    {
        $main = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);///<Главное слово
        $depended = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);///<Зависимое слово

        ///Т.к. мы будем вызывать два метода
        $sviazi[0] = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, [
                'getMainSequenceMember',
                'getDependedSequenceMember'
            ]
        );

        //Реализуем первый метод
        $sviazi[0]->expects($this->at(0))
            ->method('getMainSequenceMember')
            ->with()
            ->will($this->returnValue($main));

        //Реализуем второй метод
        $sviazi[0]->expects($this->at(1))
            ->method('getDependedSequenceMember')
            ->with()
            ->will($this->returnValue($depended));

        //Создаём последовательность
        /** @var  \Aot\Sviaz\Sequence | \PHPUnit_Framework_MockObject_MockObject $sequence */
        $sequence = $this->getMock(\Aot\Sviaz\Sequence::class,
            ['getSviazi', 'getPosition']
        );

        $length_sequence=10;
        for ($i=0;$i<$length_sequence;$i++)
            $sequence->append($this->getUniqueValue());

        //Первое что - получить связи, к этому моменту они уже должны существовать
        $sequence
            ->expects($this->at(0))
            ->method('getSviazi')
            ->with()
            ->will($this->returnValue($sviazi));

        $position_main = 3;
        $position_depended = 6;

        //если результат - все ОК, то мы устанавливаем подпоследовательность
        //Для этого необходимо сначала определить возвращаемые значения двух методов
        $sequence
            ->expects($this->at(1))
            ->method('getPosition')
            ->with($main)
            ->will($this->returnValue($position_main));

        $sequence
            ->expects($this->at(2))
            ->method('getPosition')
            ->with($depended)
            ->will($this->returnValue($position_depended));



        $processor = \Aot\Sviaz\Processor\Base::create();
        $result = PHPUnitHelper::callProtectedMethod($processor, "detectSubSequences", [$sequence]);
        $this->assertNull($result);

        $sub_sequences=$sequence->getSubSequences();

        $this->assertEquals(0, PHPUnitHelper::getProtectedProperty($sub_sequences[0],'index_start'));
        $this->assertEquals($position_main, PHPUnitHelper::getProtectedProperty($sub_sequences[0],'index_end'));
        $this->assertEquals($position_main, PHPUnitHelper::getProtectedProperty($sub_sequences[1],'index_start'));
        $this->assertEquals($position_depended, PHPUnitHelper::getProtectedProperty($sub_sequences[1],'index_end'));
        $this->assertEquals($position_depended, PHPUnitHelper::getProtectedProperty($sub_sequences[2],'index_start'));
        $this->assertEquals($length_sequence-1, PHPUnitHelper::getProtectedProperty($sub_sequences[2],'index_end'));

    }

    public function getMock(
        $originalClassName,
        $methods = array(),
        array $arguments = array(),
        $mockClassName = '',
        $callOriginalConstructor = false,
        $callOriginalClone = true,
        $callAutoload = true,
        $cloneArguments = false,
        $callOriginalMethods = false
    ) {
        return parent::getMock($originalClassName, $methods, $arguments, $mockClassName, $callOriginalConstructor,
            $callOriginalClone, $callAutoload, $cloneArguments,
            $callOriginalMethods); // TODO: Change the autogenerated stub
    }


    /**
     * @brief Если связей несколько, но они совпадают (все выделяют одну и туже основу)
     * @see \Aot\Sviaz\Processor\Base::detectSubSequences()
     *
     * detectSubSequences(\Aot\Sviaz\Sequence $sequence) - Тестирование всех связей, проверка, одинаковые ли связи в последовательности.
     * нужно для выяснения, сколько пар подлежащее-сказуемое найдено (идеально - 1, остальное неправильно)
     */
    public function testDetectSubSequencesSomeSviaziWithSameGrammaticalFoundation()
    {
        $main = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);///<Главное слово
        PHPUnitHelper::setProtectedProperty($main, 'id', 100);
        $depended = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);///<Зависимое слово
        PHPUnitHelper::setProtectedProperty($depended, 'id', 200);
        ///Т.к. мы будем вызывать два метода
        $sviazi[0] = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, [
                'getMainSequenceMember',
                'getDependedSequenceMember'
            ]
        );

        $sviazi[1] = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, [
                'getMainSequenceMember',
                'getDependedSequenceMember'
            ]
        );

        $sviazi[2] = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, [
                'getMainSequenceMember',
                'getDependedSequenceMember'
            ]
        );

        //Реализуем первый метод
        $sviazi[0]->expects($this->at(0))
            ->method('getMainSequenceMember')
            ->with()
            ->will($this->returnValue($main));

        //Реализуем второй метод
        $sviazi[0]->expects($this->at(1))
            ->method('getDependedSequenceMember')
            ->with()
            ->will($this->returnValue($depended));

        //Реализуем первый метод
        $sviazi[1]->expects($this->at(0))
            ->method('getMainSequenceMember')
            ->with()
            ->will($this->returnValue($main));

        //Реализуем второй метод
        $sviazi[1]->expects($this->at(1))
            ->method('getDependedSequenceMember')
            ->with()
            ->will($this->returnValue($depended));

        //Реализуем первый метод
        $sviazi[2]->expects($this->at(0))
            ->method('getMainSequenceMember')
            ->with()
            ->will($this->returnValue($main));

        //Реализуем второй метод
        $sviazi[2]->expects($this->at(1))
            ->method('getDependedSequenceMember')
            ->with()
            ->will($this->returnValue($depended));

        //Создаём последовательность
        /** @var  \Aot\Sviaz\Sequence | \PHPUnit_Framework_MockObject_MockObject $sequence */
        $sequence = $this->getMock(\Aot\Sviaz\Sequence::class,
            ['getSviazi', 'getPosition']
        );

        $length_sequence=10;
        for ($i=0;$i<$length_sequence;$i++)
            $sequence->append($this->getUniqueValue());

        //Первое что - получить связи, к этому моменту они уже должны существовать
        $sequence
            ->expects($this->at(0))
            ->method('getSviazi')
            ->with()
            ->will($this->returnValue($sviazi));

        $position_main = 3;
        $position_depended = 6;

        //если результат - все ОК, то мы устанавливаем подпоследовательность
        //Для этого необходимо сначала определить возвращаемые значения двух методов
        $sequence
            ->expects($this->at(1))
            ->method('getPosition')
            ->with($main)
            ->will($this->returnValue($position_main));

        $sequence
            ->expects($this->at(2))
            ->method('getPosition')
            ->with($depended)
            ->will($this->returnValue($position_depended));





        $processor = \Aot\Sviaz\Processor\Base::create();
        $result = PHPUnitHelper::callProtectedMethod($processor, "detectSubSequences", [$sequence]);

        $sub_sequences=$sequence->getSubSequences();
        $this->assertEquals(0, PHPUnitHelper::getProtectedProperty($sub_sequences[0],'index_start'));
        $this->assertEquals($position_main, PHPUnitHelper::getProtectedProperty($sub_sequences[0],'index_end'));
        $this->assertEquals($position_main, PHPUnitHelper::getProtectedProperty($sub_sequences[1],'index_start'));
        $this->assertEquals($position_depended, PHPUnitHelper::getProtectedProperty($sub_sequences[1],'index_end'));
        $this->assertEquals($position_depended, PHPUnitHelper::getProtectedProperty($sub_sequences[2],'index_start'));
        $this->assertEquals($length_sequence-1, PHPUnitHelper::getProtectedProperty($sub_sequences[2],'index_end'));


        $this->assertNull($result);

        /*
        $processor = \Aot\Sviaz\Processor\Base::create();

        $sequences = $processor->go(
            $this->getNormalizedMatrix1(),
            array_merge([self::getRule1001()], [self::getRule1001()])

        );
        $sequence = $sequences[0];
        $this->assertEquals(3, count($sequence->getSubSequences()));*/
    }

    /**
     * @brief Если связей несколько и они НЕ совпадают (выделяют разные основы предложения)
     * @see \Aot\Sviaz\Processor\Base::detectSubSequences()
     *
     * detectSubSequences(\Aot\Sviaz\Sequence $sequence) - Тестирование всех связей, проверка, одинаковые ли связи в последовательности.
     * нужно для выяснения, сколько пар подлежащее-сказуемое найдено (идеально - 1, остальное неправильно)
     */
    public function testDetectSubSequencesSomeSviaziWithDifferentGrammaticalFoundationV1()
    {
        $main = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);///<Главное слово
        PHPUnitHelper::setProtectedProperty($main, 'id', 100);
        $depended = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);///<Зависимое слово
        PHPUnitHelper::setProtectedProperty($depended, 'id', 200);
        $enother_depended = $this->getMock(\Aot\Sviaz\SequenceMember\Base::class);///<Зависимое слово
        PHPUnitHelper::setProtectedProperty($depended, 'id', 200);
        ///Т.к. мы будем вызывать два метода
        $sviazi[0] = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, [
                'getMainSequenceMember',
                'getDependedSequenceMember'
            ]
        );

        $sviazi[1] = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, [
                'getMainSequenceMember',
                'getDependedSequenceMember'
            ]
        );

        $sviazi[2] = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, [
                'getMainSequenceMember',
                'getDependedSequenceMember'
            ]
        );

        //Реализуем первый метод
        $sviazi[0]->expects($this->at(0))
            ->method('getMainSequenceMember')
            ->with()
            ->will($this->returnValue($main));

        //Реализуем второй метод
        $sviazi[0]->expects($this->at(1))
            ->method('getDependedSequenceMember')
            ->with()
            ->will($this->returnValue($depended));

        //Реализуем первый метод
        $sviazi[1]->expects($this->at(0))
            ->method('getMainSequenceMember')
            ->with()
            ->will($this->returnValue($main));

        //Реализуем второй метод
        $sviazi[1]->expects($this->at(1))
            ->method('getDependedSequenceMember')
            ->with()
            ->will($this->returnValue($depended));

        //Реализуем первый метод
        $sviazi[2]->expects($this->at(0))
            ->method('getMainSequenceMember')
            ->with()
            ->will($this->returnValue($main));

        //Реализуем второй метод
        $sviazi[2]->expects($this->at(1))
            ->method('getDependedSequenceMember')
            ->with()
            ->will($this->returnValue($enother_depended));

        //Создаём последовательность
        /** @var  \Aot\Sviaz\Sequence | \PHPUnit_Framework_MockObject_MockObject $sequence */
        $sequence = $this->getMock(\Aot\Sviaz\Sequence::class,
            ['getSviazi', 'getPosition']
        );




        //Первое что - получить связи, к этому моменту они уже должны существовать
        $sequence
            ->expects($this->once())
            ->method('getSviazi')
            ->with()
            ->will($this->returnValue($sviazi));



        $position_main = 3;
        $position_depended = 6;

        //если результат - все ОК, то мы устанавливаем подпоследовательность
        //Для этого необходимо сначала определить возвращаемые значения двух методов
        $sequence
            ->expects($this->never())
            ->method('getPosition')
            ->with($main)
            ->will($this->returnValue($position_main));

        $sequence
            ->expects($this->never())
            ->method('getPosition')
            ->with($depended)
            ->will($this->returnValue($position_depended));


        $sequence
            ->expects($this->never())
            ->method('setSubSequence');



        $processor = \Aot\Sviaz\Processor\Base::create();
        $result = PHPUnitHelper::callProtectedMethod($processor, "detectSubSequences", [$sequence]);

        $this->assertNull($result);

    }


    /**
     * @brief Если связей несколько и они НЕ совпадают (выделяют разные основы предложения)
     * @see \Aot\Sviaz\Processor\Base::detectSubSequences()
     *
     *
     * @dataProvider detectSubSequencesSviaziWithMultiNumberOfGrammaticalFoundationsProvider
     */
    public function testDetectSubSequencesSviaziWithMultiNumberOfGrammaticalFoundations($sentence, $sviazi_in, $expected)
    {
        $sentence_length=count($sentence);
        $sviazi_length=count($sviazi_in);

        $sequence = \Aot\Sviaz\Sequence::create();///<Создаём последовательность

        //Забиваем последовательность элементами
        $members=[];
        for($i=0;$i<$sentence_length;$i++)
        {
            $members[$i]=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
            PHPUnitHelper::setProtectedProperty($members[$i], 'id', $i);
            $sequence->append($members[$i]);
        }


        //Создаём последовательность
        /** @var  \Aot\Sviaz\Sequence | \PHPUnit_Framework_MockObject_MockObject $sequence */
        $sequence = $this->getMock(\Aot\Sviaz\Sequence::class,
            ['getSviazi','getPosition']
        );


        $sviazi = [];
        for ($i = 0; $i < $sviazi_length; $i++)
        {
            $sviazi[$i] = $this->getMock(\Aot\Sviaz\Podchinitrelnaya\Base::class, [
                    'getMainSequenceMember',
                    'getDependedSequenceMember'
                ]
            );
            //Реализуем первый метод
            $main_position=$sviazi_in[$i][0];
            $main=$members[$main_position];
            $sviazi[$i]->expects($this->at(0))
                ->method('getMainSequenceMember')
                ->with()
                ->will($this->returnValue($main));
            $sequence
                ->expects($this->at(1))
                ->method('getPosition')
                ->with($main)
                ->will($this->returnValue($main_position));


            //Реализуем второй метод
            $depended_position=$sviazi_in[$i][1];
            $depended=$members[$depended_position];
            $sviazi[$i]->expects($this->at(1))
                ->method('getDependedSequenceMember')
                ->with()
                ->will($this->returnValue($members[$sviazi_in[$i][1]]));

            $sequence
                ->expects($this->at(2))
                ->method('getPosition')
                ->with($depended)
                ->will($this->returnValue($depended_position));
        }




        //Первое что - получить связи, к этому моменту они уже должны существовать
        $sequence
            ->expects($this->at(0))
            ->method('getSviazi')
            ->with()
            ->will($this->returnValue($sviazi));




        $processor = \Aot\Sviaz\Processor\Base::create();
        $result = PHPUnitHelper::callProtectedMethod($processor, "detectSubSequences", [$sequence]);

        $sub_sequences=$sequence->getSubSequences();
        /*
        $this->assertEquals(0, PHPUnitHelper::getProtectedProperty($sub_sequences[0],'index_start'));
        $this->assertEquals($position_main, PHPUnitHelper::getProtectedProperty($sub_sequences[0],'index_end'));
        $this->assertEquals($position_main, PHPUnitHelper::getProtectedProperty($sub_sequences[1],'index_start'));
        $this->assertEquals($position_depended, PHPUnitHelper::getProtectedProperty($sub_sequences[1],'index_end'));
        $this->assertEquals($position_depended, PHPUnitHelper::getProtectedProperty($sub_sequences[2],'index_start'));
        $this->assertEquals($length_sequence-1, PHPUnitHelper::getProtectedProperty($sub_sequences[2],'index_end'));

*/
        $this->assertNull($result);



        /*
        $processor = \Aot\Sviaz\Processor\Base::create();

        $sequences = $processor->go(
            $this->getNormalizedMatrix1(),
            array_merge([self::getRule1002()], [self::getRule1001()])

        );
        $sequence = $sequences[10];
        $this->assertEquals(0, count($sequence->getSubSequences()));
        */
    }


    public function detectSubSequencesSviaziWithMultiNumberOfGrammaticalFoundationsProvider()
    {
        return [
/*
            'Набор 1' =>
                [
                    //структура предложения
                    [0, 0, 1, 0, 2, 0],
                    //связи
                    [
                        [2, 4]
                    ],
                    //Индексы начала и конца групп
                    [
                        [0, 2],
                        [2, 4],
                        [4, 5]
                    ]
                ],

            'Набор 2' =>
                [
                    //структура предложения
                    [0, 1, 0, 2],
                    //связи
                    [
                        [1, 3]
                    ],
                    //Индексы начала и конца групп
                    [
                        [0, 1],
                        [1, 3]
                    ]
                ],
*/
            'Набор 3' =>
                [
                    //структура предложения
                    [0, 1, 0, 1, 0, 2, 0],
                    //связи
                    [
                        [1, 5],
                        [3, 5]
                    ],
                    //Индексы начала и конца групп
                    [
                        [0, 1],
                        [1, 3],
                        [3, 5],
                        [5, 6]
                    ]
                ],

        ];
    }


    /**
     * @brief Проверка построения подпоследовательностей
     * при прямом порядке (сначала подлежащее потом сказуемое)
     *
     */
    public function testCreateSubSequencesDirectOrderOfMainAndDepended()
    {
        $sequence = \Aot\Sviaz\Sequence::create();
        $main_index=3;
        $dependent_index=6;
        $length_sequence=10;
        for ($i=0;$i<$length_sequence;$i++){
            $sequence->append("");
        }
        $result = \Aot\Sviaz\SubSequence::createSubSequences($sequence,$main_index,$dependent_index);
        $this->assertEquals(3, count($result));///<Всего три подпоследовательности
        //print_r($result);
        $this->assertEquals(0, PHPUnitHelper::getProtectedProperty($result[0],'index_start'));
        $this->assertEquals($main_index, PHPUnitHelper::getProtectedProperty($result[0],'index_end'));
        $this->assertEquals($main_index, PHPUnitHelper::getProtectedProperty($result[1],'index_start'));
        $this->assertEquals($dependent_index, PHPUnitHelper::getProtectedProperty($result[1],'index_end'));
        $this->assertEquals($dependent_index, PHPUnitHelper::getProtectedProperty($result[2],'index_start'));
        $this->assertEquals($length_sequence-1, PHPUnitHelper::getProtectedProperty($result[2],'index_end'));
    }

    /**
     * @brief Проверка построения подпоследовательностей
     * при братном порядке (сначала сказуемое потом подлежащее)
     *
     */
    public function testCreateSubSequencesReverseOrderOfMainAndDepended()
    {
        $sequence = \Aot\Sviaz\Sequence::create();
        $main_index=6;
        $dependent_index=3;
        $length_sequence=10;
        for ($i=0;$i<$length_sequence;$i++){
            $sequence->append("");
        }
        $result = \Aot\Sviaz\SubSequence::createSubSequences($sequence,$main_index,$dependent_index);
        $this->assertEquals(3, count($result));///<Всего три подпоследовательности
        //print_r($result);
        $this->assertEquals(0, PHPUnitHelper::getProtectedProperty($result[0],'index_start'));
        $this->assertEquals($dependent_index, PHPUnitHelper::getProtectedProperty($result[0],'index_end'));
        $this->assertEquals($dependent_index, PHPUnitHelper::getProtectedProperty($result[1],'index_start'));
        $this->assertEquals($main_index, PHPUnitHelper::getProtectedProperty($result[1],'index_end'));
        $this->assertEquals($main_index, PHPUnitHelper::getProtectedProperty($result[2],'index_start'));
        $this->assertEquals($length_sequence-1, PHPUnitHelper::getProtectedProperty($result[2],'index_end'));
    }

    /**
     * @brief Проверка построения подпоследовательностей
     * при совпадении подлежащего и сказуемого (чего быть не может)
     *
     */
    public function testCreateSubSequencesError()
    {
        $sequence = \Aot\Sviaz\Sequence::create();
        $main_index=5;
        $dependent_index=5;
        $length_sequence=10;
        for ($i=0;$i<$length_sequence;$i++){
            $sequence->append("");
        }

        try{
            $result = \Aot\Sviaz\SubSequence::createSubSequences($sequence,$main_index,$dependent_index);
            $this->fail("Не должно было тут быть! Должно быть брошен экзепшн");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("wtf: последовательность состоит из одного элемента", $e->getMessage());
        }

    }

    /**
     * @brief Тестируем функцию определения принадлежности элемента
     * к текущей подпоследовательности
     *
     * Элемент принадлежит последовательности
     */
    public function testIsMemberInSequencesTrue()
    {
        $sequence = \Aot\Sviaz\Sequence::create();///<Создаём подпоследовательность
        $length_sequence_part_1=5;///< Количество элементов минус один до того элемента, который потом будем искать
        $length_sequence_part_2=10;///<Всего элементов, включая искомый элемент
        //Задаём диапозон подпоследовательности
        $sub_sequence=\Aot\Sviaz\SubSequence::create($sequence,$length_sequence_part_1-2,$length_sequence_part_1+2);
        //Забиваем последовательность элементами
        for ($i=0;$i<$length_sequence_part_1;$i++)
        {
            $member=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
            $sequence->append($member);
        }
        //Вставляем элемент, который будем искать
        $member_search=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $sequence->append($member_search);
        //забиваем последовательность элементами после искомого элемента
        for ($i=$length_sequence_part_1+1;$i<$length_sequence_part_2;$i++)
        {
            $member=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
            $sequence->append($member);
        }
        //проверяем, входит ли элемент в подпоследовательность
        $this->assertEquals(true,$sub_sequence->isMemberInSequences($member_search));
    }


    /**
     * @brief Тестируем функцию определения принадлежности элемента
     * к текущей подпоследовательности
     *
     * Элемент не принадлежит последовательности
     */
    public function testIsMemberInSequencesFalse()
    {
        $sequence = \Aot\Sviaz\Sequence::create();///<Создаём подпоследовательность
        $length_sequence_part_1=5;///< Количество элементов минус один до того элемента, который потом будем искать
        $length_sequence_part_2=10;///<Всего элементов, включая искомый элемент
        //Задаём диапозон подпоследовательности
        $sub_sequence=\Aot\Sviaz\SubSequence::create($sequence,$length_sequence_part_1+1,$length_sequence_part_1+2);
        //Забиваем последовательность элементами
        for ($i=0;$i<$length_sequence_part_1;$i++)
        {
            $member=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
            $sequence->append($member);
        }
        //Вставляем элемент, который будем искать
        $member_search=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $sequence->append($member_search);
        //забиваем последовательность элементами после искомого элемента
        for ($i=$length_sequence_part_1+1;$i<$length_sequence_part_2;$i++)
        {
            $member=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
            $sequence->append($member);
        }
        //проверяем, входит ли элемент в подпоследовательность
        $this->assertEquals(false,$sub_sequence->isMemberInSequences($member_search));
    }

    /**
     * @brief Тестируем функцию определения принадлежности элемента
     * к текущей подпоследовательности
     *
     *
     */
    public function testIsMemberInSequencesPositionIsNull()
    {
        $sequence = $this->getMock(\Aot\Sviaz\Sequence::class,
            ['getPosition']
        );
        $member=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $member_search=$this->getMock(\Aot\Sviaz\SequenceMember\Base::class);
        $sequence
            ->expects($this->once())
            ->method('getPosition')
            ->with($member)
            ->will($this->returnValue(null));

        $length_sequence_part_1=5;///< Количество элементов минус один до того элемента, который потом будем искать
        //Задаём диапозон подпоследовательности
        $sub_sequence=\Aot\Sviaz\SubSequence::create($sequence,$length_sequence_part_1+1,$length_sequence_part_1+2);

        try{
            //проверяем, входит ли элемент в подпоследовательность
            $this->assertEquals(false,$sub_sequence->isMemberInSequences($member_search));
            $this->fail("Не должно было тут быть! Должно быть брошен экзепшн");
        }
        catch(\RuntimeException $e)
        {
            $this->assertEquals("wtf: позиция не определена", $e->getMessage());
        }
    }

    /**

    public static function getRule1001()
    {

        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    $builder_main = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                        ->podlezhachee()
                )
                ->depended(
                    $builder_depended = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->skazuemoe()
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                );

        $rule = $builder->get();

        return $rule;


    }

    public static function getRule1003()
    {

        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    $builder_main = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                        ->podlezhachee()
                )
                ->depended(
                    $builder_depended = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::GLAGOL,
                        RoleRegistry::OTNOSHENIE
                    )
                        ->skazuemoe()
                )
                ->link(
                    AssertedLinkBuilder::create()
                        ->morphologyMatching(
                            MorphologyRegistry::CHISLO
                        )
                );

        $rule = $builder->get();

        return $rule;


    }

    public function getNormalizedMatrix1()
    {
        $matrix = $this->getMatrix1();

        $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

        return $normalized_matrix;
    }

    public function getMatrix1()
    {
        $mixed = $this->getWordsAndPunctuation1();

        $matrix = \Aot\Text\Matrix::create($mixed);

        return $matrix;
    }

    protected function getWordsAndPunctuation1()
    {
        <<<TEXT
Над горами появились облака – сначала легкие и воздушные, затем серые, с рваными краями
TEXT;
        //$nad[0] = $this->getSafeMockLocal1(Predlog::class, ['__set', 'getMorphology', '__get', 'getMorphologyByClass_TEMPORARY']);
        $nad[0] = $this->getMock(Predlog::class, ['_']);
        PHPUnitHelper::setProtectedProperty($nad[0], 'text', 'Над');

        $gorami[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($gorami[0], 'text', 'горами');

        $gorami[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $gorami[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $gorami[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $gorami[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $gorami[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Zhenskii::create();
        $gorami[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $poiavilis[0] = $this->getMock(Glagol::class, ['_']);
        PHPUnitHelper::setProtectedProperty($poiavilis[0], 'text', 'появилось');
        $poiavilis[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Chislo\Edinstvennoe::create();
        $poiavilis[0]->litso = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Litso\Tretie::create();
        $poiavilis[0]->naklonenie = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Naklonenie\Izyavitelnoe::create();
        $poiavilis[0]->perehodnost = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Perehodnost\Perehodnyj::create();
        $poiavilis[0]->rod = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Rod\Null::create();
        $poiavilis[0]->spryazhenie = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Spryazhenie\Pervoe::create();
        $poiavilis[0]->vid = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vid\Sovershennyj::create();
        $poiavilis[0]->vozvratnost = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vozvratnost\Nevozvratnyj::create();
        $poiavilis[0]->vremya = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Vremya\Proshedshee::create();
        $poiavilis[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Glagol\Morphology\Zalog\Null::create();

        $add[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($add[0], 'text', 'красивое');
        $add[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $add[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $add[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $add[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $add[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $add[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();

        $oblaka[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[0], 'text', 'облако');
        $oblaka[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $oblaka[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Roditelnij::create();
        $oblaka[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $oblaka[1] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[1], 'text', 'облако');
        $oblaka[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $oblaka[1]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[1]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $oblaka[1]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[1]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $oblaka[2] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($oblaka[2], 'text', 'облако');
        $oblaka[2]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $oblaka[2]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $oblaka[2]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $oblaka[2]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::create();
        $oblaka[2]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $oblaka[2]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();


        $legkie[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[0], 'text', 'легкие');
        $legkie[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $legkie[1] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[1], 'text', 'легкие');
        $legkie[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[1]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[1]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[1]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[1]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $legkie[2] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[2], 'text', 'легкие');
        $legkie[2]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Edinstvennoe::create();
        $legkie[2]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[2]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[2]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Vinitelnij::create();
        $legkie[2]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[2]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $legkie[3] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($legkie[3], 'text', 'легкое');
        $legkie[3]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $legkie[3]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $legkie[3]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $legkie[3]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Imenitelnij::create();
        $legkie[3]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Srednij::create();
        $legkie[3]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        $i[0] = $this->getMock(Base::class, ['_']);
        PHPUnitHelper::setProtectedProperty($i[0], 'text', 'и');


        $vozdushnue[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($vozdushnue[0], 'text', 'воздушные');
        $vozdushnue[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $vozdushnue[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $vozdushnue[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $vozdushnue[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        //$vozdushnue[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $vozdushnue[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Srednij::create();
        $vozdushnue[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();

        $vozdushnue[1] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($vozdushnue[1], 'text', 'воздушные');
        $vozdushnue[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $vozdushnue[1]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $vozdushnue[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::create();
        $vozdushnue[1]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $vozdushnue[1]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $vozdushnue[1]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();

        $zapiztaya[0] = $this->getMock(Zapiataya::class, ['_']);

        $serye[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($serye[0], 'text', 'серые');

        $serye[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $serye[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $serye[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Imenitelnij::create();
        $serye[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $serye[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $serye[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();

        $serye[1] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($serye[1], 'text', 'серые');
        $serye[1]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $serye[1]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $serye[1]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Vinitelnij::create();
        $serye[1]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $serye[1]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $serye[1]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();

        $zapiztaya[1] = $this->getMock(Zapiataya::class, ['_']);

        #     $s[0] = $this->getSafeMockLocal1(Predlog::class);
        $s[0] = $this->getMock(Predlog::class, ['_']);
        PHPUnitHelper::setProtectedProperty($s[0], 'text', 'с');

        $rvanymi[0] = $this->getMock(Prilagatelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($rvanymi[0], 'text', 'рваными');
        $rvanymi[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $rvanymi[0]->forma = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Forma\Polnaya::create();
        $rvanymi[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $rvanymi[0]->razryad = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Razryad\Null::create();
        $rvanymi[0]->rod = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\Rod\Null::create();
        $rvanymi[0]->stepen_sravneniia = \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Morphology\StepenSravneniya\Null::create();


        $krayami[0] = $this->getMock(Suschestvitelnoe::class, ['_']);
        PHPUnitHelper::setProtectedProperty($krayami[0], 'text', 'краями');
        $krayami[0]->chislo = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Chislo\Mnozhestvennoe::create();
        $krayami[0]->naritcatelnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Naritcatelnost\ImiaNaritcatelnoe::create();
        $krayami[0]->odushevlyonnost = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Odushevlyonnost\Neodushevlyonnoe::create();
        $krayami[0]->padeszh = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Padeszh\Tvoritelnij::create();
        $krayami[0]->rod = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Rod\Muzhskoi::create();
        $krayami[0]->sklonenie = \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Morphology\Sklonenie\Null::create();

        return [
            'nad' => $nad,
            'gorami' => $gorami,
            'poiavilis' => $poiavilis,
            'add' => $add,
            'oblaka' => $oblaka,
            'legkie' => $legkie,
            'i' => $i,
            'vozdushnue' => $vozdushnue,
            'zapiztaya' => $zapiztaya,
            'serye' => $serye,
            's' => $s,
            'rvanymi' => $rvanymi,
            'krayami' => $krayami,
        ];
    }


    public static function getRule1002()
    {

        $builder =
            \Aot\Sviaz\Rule\Builder2::create()
                ->main(
                    $builder_main = \Aot\Sviaz\Rule\AssertedMember\Builder\Main\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::VESCH
                    )
                )
                ->depended(
                    $builder_depended = \Aot\Sviaz\Rule\AssertedMember\Builder\Depended\Base::create(
                        ChastiRechiRegistry::SUSCHESTVITELNOE,
                        RoleRegistry::SVOISTVO
                    )

                )
                ->link(
                    AssertedLinkBuilder::create()
                );

        $rule = $builder->get();

        return $rule;


    }*/
}