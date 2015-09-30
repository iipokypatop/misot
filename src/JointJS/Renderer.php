<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 10.08.2015
 * Time: 14:13
 */

namespace Aot\JointJS;


class Renderer
{
    protected $driver;


    public function __construct()
    {
        $this->driver = [$this, 'jsonEncodeDriver'];
    }

    protected function render()
    {
        return call_user_func_array($this->driver, func_get_args());
    }

    public static function create()
    {
        return new static();
    }


    public function renderSequence(\Aot\Sviaz\Sequence $sequence)
    {
        $cells = [];
        /** @var  \Aot\JointJS\Objects\Basic\Rect[] $points */
        $points = [];
        foreach ($sequence as $index => $member) {
            if (!$member instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
                continue;
            }
            $cells[] = $points[spl_object_hash($member)] = $this->getPoint($index, $member);
        }


        $links = [];
        foreach ($sequence->getSviazi() as $index => $sviaz) {

            $link = $this->getLink($sviaz);

            if (null === $link) {
                continue;
            }

            $x =
                $points[spl_object_hash($sviaz->getMainSequenceMember())]->getPosition()->getX()
                + $points[spl_object_hash($sviaz->getDependedSequenceMember())]->getPosition()->getX()
                + 80;

            $x /= 2;

            $link->setVertices([
                \Aot\JointJS\Objects\Point::create(
                    $x,
                    300
                )
            ]);

            $links[] = $link;
            $cells[] = $link;
        }

        return $this->render([
            'cells' => $cells
        ]);
    }


    /**
     * @param \Aot\Sviaz\Podchinitrelnaya\Base $sviaz
     * @return Objects\Fsa\Arrow | null
     */
    protected function getLink(\Aot\Sviaz\Podchinitrelnaya\Base $sviaz)
    {
        $main = $sviaz->getMainSequenceMember();
        if (!$main instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            return null;
        }
        $depended = $sviaz->getDependedSequenceMember();
        if (!$depended instanceof \Aot\Sviaz\SequenceMember\Word\Base) {
            return null;
        }

        $text = get_class($sviaz);

        $link = \Aot\JointJS\Objects\Fsa\Arrow::create()
            ->setSource(
                \Aot\JointJS\Objects\Id::create()
                    ->setId(
                        $main->getId()
                    )
            )
            ->setTarget(
                \Aot\JointJS\Objects\Id::create()
                    ->setId(
                        $depended->getId()
                    )
            )
            ->setAttrs(
                \Aot\JointJS\Objects\Attr::create()
                    ->setText(
                        \Aot\JointJS\Objects\Text::create()
                            ->setText(
                                $text
                            )
                    )
            )->setId(
                $sviaz->getId()
            );

        return $link;
    }

    /**
     * @param $index
     * @param \Aot\Sviaz\SequenceMember\Word\Base $member
     * @return \Aot\JointJS\Objects\Basic\Rect
     */
    protected function getPoint($index, \Aot\Sviaz\SequenceMember\Word\Base $member)
    {
        $x_shift = 100;
        $y = 100;

        return Objects\Basic\Rect::create()
            ->setAttrs(
                \Aot\JointJS\Objects\Attr::create()
                    ->setText(
                        \Aot\JointJS\Objects\Text::create()
                            ->setText(
                                $member->getSlovo()->getText()
                            )
                            ->setFontWeight(800)
                            ->setMagnet(true)
                    )->setDot(
                        \Aot\JointJS\Objects\Text::create()
                            ->setMagnet(true)
                    )
            )
            ->setPosition(
                \Aot\JointJS\Objects\Position::create(
                    $x_shift * ($index + 1),
                    $y
                )
            )
            ->setSize(
                \Aot\JointJS\Objects\Size::create(80, 60)
            )
            ->setId(
                $member->getId()
            );
    }

    protected function jsonEncodeDriver($ob)
    {
        return json_encode($ob);
    }
}