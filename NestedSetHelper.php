<?php

namespace NestedSetInArray;

class NestedSetHelper
{
    /**
     * Расставляет lft и rgt в массиве относительно $lft переданного в функцию
     * @param array $array
     * @param int $lft
     * @return array
     */
    public static function convertArrayToNestedSetNode($array, $lft)
    {
        $array['lft'] = $lft;
        $array['rgt'] = $lft + 1;
        return $array;
    }

    /**
     * Конвертирует переданный массив в Nested Set структуру на основе вложенности ('depth'),
     * пример передаваемого массива:
     * ```php
     * $array = [
     *     ['title' => '{name}', 'depth' => 0],
     *     ['title' => '{name}', 'depth' => 1],
     *     ['title' => '{name}', 'depth' => 2],
     *     ['title' => '{name}', 'depth' => 1]
     * ];
     * ```
     * Результат:
     * ```php
     * $nestedSetArray = [
     *     ['title' => '{name}', 'depth' => 0,'lft' => 1, 'rgt' => 8],
     *     ['title' => '{name}', 'depth' => 1, 'lft' => 2, 'rgt' => 5],
     *     ['title' => '{name}', 'depth' => 2, 'lft' => 3, 'rgt' => 4],
     *     ['title' => '{name}', 'depth' => 1, 'lft' => 6, 'rgt' => 7]
     * ];
     * ```
     * @param array $array
     * @return array
     */
    public static function convertArrayToNestedSet($array)
    {
        $firstNode = $array[0];
        unset($array[0]);
        $tree[] = self::convertArrayToNestedSetNode($firstNode, 1);

        foreach ($array as $node) {
            $tree = self::append($tree, $node);
        }
        return $tree;
    }

    /**
     * Добавляет узел в уже созданное дерево NestedSet с любым уровнем вложенности ('depth'), пример:
     * ```php
     * $nestedSetArray = [
     *     ['title' => '{name}', 'depth' => 0,'lft' => 1, 'rgt' => 4],
     *     ['title' => '{name}', 'depth' => 1, 'lft' => 2, 'rgt' => 3]
     * ];
     * $node = [
     *     'title' => '{name}',
     *     'depth' => 1
     * ];
     * ```
     * Результат:
     * ```php
     * $nestedSetArray = [
     *     ['title' => '{name}', 'depth' => 0,'lft' => 1, 'rgt' => 6],
     *     ['title' => '{name}', 'depth' => 1, 'lft' => 2, 'rgt' => 3],
     *     ['title' => '{name}', 'depth' => 1, 'lft' => 4, 'rgt' => 5]
     * ]
     * @param array $tree
     * @param array $node
     * @return array
     */
    public static function append($tree, $node)
    {
        $lastNode = count($tree) - 1;
        if ($tree[$lastNode]['depth'] > $node['depth'] || $tree[$lastNode]['depth'] == $node['depth']) {
            $updatedTree = self::appendNode($tree, $node);
        }
        if (empty($updatedTree)) {
            $updatedTree = self::appendChild($tree, $node);
        }
        return $updatedTree;
    }

    /**
     * Делает вставку узла в Nested Set дерево
     * @param array $tree
     * @param array $node
     * @return array
     */
    private static function appendNode($tree, $node)
    {
        $lastNode = count($tree) - 1;
        $nodeRgt = 0;
        for ($i = $lastNode; $i > -1; $i--) {
            if ($tree[$i]['depth'] == $node['depth']) {
                $nodeRgt = $tree[$i]['rgt'] + 1;
                break;
            }
        }

        self::moveNodes($tree, $lastNode, $nodeRgt);
        $tree[] = self::convertArrayToNestedSetNode($node, $nodeRgt);
        return $tree;
    }

    /**
     * Делает вставку узла в роли ребёнка в NestedSet дерево
     * @param array $tree
     * @param array $node
     * @return array
     */
    private static function appendChild($tree, $node)
    {
        $lastNode = count($tree) - 1;
        $nodeRgt = $tree[$lastNode]['rgt'];

        self::moveNodes($tree, $lastNode, $nodeRgt);
        $tree[] = self::convertArrayToNestedSetNode($node, $nodeRgt);
        return $tree;
    }

    /**
     * Двигает все узлы для вставки в Nested Set
     * @param array $nestedSetArray
     * @param int $lastNode // Последний индекс массива Nested Set
     * @param int $condition // Значение rgt родителя/соседа узла
     */
    private static function moveNodes(&$nestedSetArray, $lastNode, $condition)
    {
        for ($j = $lastNode; $j > -1; $j--) {
            if ($nestedSetArray[$j]['rgt'] >= $condition) {
                $nestedSetArray[$j]['rgt'] += 2;
            }
            if ($nestedSetArray[$j]['lft'] >= $condition) {
                $nestedSetArray[$j]['lft'] += 2;
            }
        }
    }
}