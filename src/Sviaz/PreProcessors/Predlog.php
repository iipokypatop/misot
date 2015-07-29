<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 27.07.2015
 * Time: 14:49
 */

namespace Aot\Sviaz\PreProcessors;


class Predlog extends Base
{
    /**
     * @param \Aot\Sviaz\Sequence $raw_sequence
     * @return \Aot\Sviaz\Sequence
     */
    public function run(\Aot\Sviaz\Sequence $raw_sequence)
    {
        $sequence = \Aot\Sviaz\Sequence::create();

        $prev_member = null;

        foreach ($raw_sequence as $current_member) {

            if ($prev_member === null) {
                if (!($current_member instanceof \Aot\Sviaz\SequenceMember\Word\Base &&
                    $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Predlog\Base)
                ) {
                    $sequence->append($current_member);
                    continue;
                }

                $prev_member = $current_member;
                continue;
            }

            if ($current_member instanceof \Aot\Sviaz\SequenceMember\Word\Base &&
                $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Predlog\Base
            ) {
                $prev_member = $current_member;
                continue;
            }

            $new_member = $current_member;

            if (
                $prev_member instanceof \Aot\Sviaz\SequenceMember\Word\Base &&
                $prev_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Predlog\Base &&
                $current_member instanceof \Aot\Sviaz\SequenceMember\Word\Base &&
                !$current_member instanceof \Aot\Sviaz\SequenceMember\Word\WordWithPreposition &&
                (
                    $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Suschestvitelnoe\Base
                    || $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Prilagatelnoe\Base
                    || $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Glagol\Base
                    || $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Prichastie\Base
                    || $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Narechie\Base
                    || $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Deeprichastie\Base
                    || $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Mestoimenie\Base
                    || $current_member->getSlovo() instanceof \Aot\RussianMorphology\ChastiRechi\Chislitelnoe\Base
                )
            ) {
                $new_member = \Aot\Sviaz\SequenceMember\Word\WordWithPreposition::create(
                    $current_member->getSlovo(),
                    $prev_member->getSlovo()
                );
            }

            $sequence->append($new_member);

            $prev_member = $new_member;
        }

        return $sequence;
    }
}