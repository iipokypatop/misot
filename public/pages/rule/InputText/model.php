<?php
/**
 * Created by PhpStorm.
 * User: p.semenyuk
 * Date: 31.07.2015
 * Time: 17:38
 */

/** @var array $post */


if (true === $post['form']['text_for_parse']['submit']) {

    $words = preg_split('/\s+/', $post['form']['text_for_parse']['text']);

    $words = array_filter($words);

    $slova = \Aot\RussianMorphology\Factory::getSlova($words);

    $matrix = \Aot\Text\Matrix::create($slova);

    $normalized_matrix = \Aot\Text\NormalizedMatrix::create($matrix);

    $raw_member_builder = \Aot\Sviaz\SequenceMember\RawMemberBuilder::create();

    $raw_sequences = $raw_member_builder->getRawSequences(
        $normalized_matrix
    );
}