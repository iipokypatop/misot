<?php


class VSOTest extends \AotTest\AotDataStorage
{
    public function testRun()
    {
//        $seq = $this->getRawSequence();
//        print_r($seq);
        $mivar = new DMivarText(['txt' => 'Папа пошел в лес.']);
        $mivar->semantic_model();
        $vso = $mivar->getSemanticModel();

        /** @var \Objects\Rule $rule */
        foreach ($vso as $rule) {
            print_r($rule->get_name_V());
        }

    }


}