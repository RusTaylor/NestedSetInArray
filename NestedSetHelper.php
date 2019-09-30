<?php

namespace NestedSetInArray;

class NestedSetHelper
{
    /**
     * @param array $array
     * @param int $lft
     * @return array
     */
    public static function convertArrayToNestedSetNode($array, $lft)
    {
        $array['lft'] = $lft;
        $lft += 1;
        $array['rgt'] = $lft;
        return $array;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function convertArrayToNestedSet($array)
    {
        $tree = [];
        foreach ($array as $node) {
            if (empty($tree)) {
                $tree[] = self::convertArrayToNestedSetNode($node, 1);
                continue;
            }
            $tree = self::append($tree, $node);
        }
        return $tree;
    }

    /**
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
     * @param array $nestedSetArray
     * @param int $lastNode
     * @param int $condition
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