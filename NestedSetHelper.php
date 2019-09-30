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
     * @param array $tree
     * @return array
     */
    public static function convertArrayToNestedSetBranch($array, $tree)
    {
        foreach ($array as $node) {
            if (empty($tree)) {
                $tree[] = self::convertArrayToNestedSetNode($node, 1);
                continue;
            }
            $tree = self::append($tree, $node);
        }
        return $tree;
    }

    public static function convertArrayToNestedSet($array)
    {
        $tree = [];
        $tree = self::convertArrayToNestedSetBranch($array, []);
        return $tree;
    }

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
        for ($j = $lastNode; $j > -1; $j--) {
            if ($tree[$j]['rgt'] >= $nodeRgt) {
                $tree[$j]['rgt'] += 2;
            }
            if ($tree[$j]['lft'] >= $nodeRgt) {
                $tree[$j]['lft'] += 2;
            }
        }
        $tree[] = self::convertArrayToNestedSetNode($node, $nodeRgt);
        return $tree;
    }

    private static function appendChild($tree, $node)
    {
        $lastNode = count($tree) - 1;
        $nodeRgt = $tree[$lastNode]['rgt'];
        for ($j = $lastNode; $j > -1; $j--) {
            if ($tree[$j]['rgt'] >= $nodeRgt) {
                $tree[$j]['rgt'] += 2;
            }
            if ($tree[$j]['lft'] >= $nodeRgt) {
                $tree[$j]['lft'] += 2;
            }
        }
        $tree[] = self::convertArrayToNestedSetNode($node, $nodeRgt);
        return $tree;
    }
}