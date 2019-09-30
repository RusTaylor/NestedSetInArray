<?php

require_once 'vendor/autoload.php';

use NestedSetInArray\NestedSetHelper;
use Tests\TestCase;


class NestedSetHelperTest extends TestCase
{
    public function testConvertArrayToNestedSetNode()
    {
        $array = [
            'title' => 'kek',
            'depth' => 1
        ];
        $expectedNestedSet = [
            'title' => 'kek',
            'depth' => 1,
            'lft' => 1,
            'rgt' => 2
        ];
        $actualNestedSet = NestedSetHelper::convertArrayToNestedSetNode($array, 1);
        $this->assertEquals($expectedNestedSet, $actualNestedSet);

        $array['depth'] = 5;
        $expectedNestedSet = [
            'title' => 'kek',
            'depth' => 5,
            'lft' => 10,
            'rgt' => 11
        ];
        $actualNestedSet = NestedSetHelper::convertArrayToNestedSetNode($array, 10);
        $this->assertEquals($expectedNestedSet, $actualNestedSet);
    }

    public function testConvertArrayToNestedSet()
    {
        $array = [
            ['title' => 'node 1', 'depth' => 1],
            ['title' => 'node 2 1', 'depth' => 2],
            ['title' => 'node 2 2', 'depth' => 2],
            ['title' => 'node 2 2 1', 'depth' => 3]
        ];
        $expectedNestedSet = [
            ['title' => 'node 1', 'depth' => 1, 'lft' => 1, 'rgt' => 8],
            ['title' => 'node 2 1', 'depth' => 2, 'lft' => 2, 'rgt' => 3],
            ['title' => 'node 2 2', 'depth' => 2, 'lft' => 4, 'rgt' => 7],
            ['title' => 'node 2 2 1', 'depth' => 3, 'lft' => 5, 'rgt' => 6]
        ];
        $actualNestedSet = NestedSetHelper::convertArrayToNestedSet($array);
        $this->assertEquals($expectedNestedSet, $actualNestedSet);

        $array[] = ['title' => 'node 2 3', 'depth' => 2];
        $expectedNestedSet = [
            ['title' => 'node 1', 'depth' => 1, 'lft' => 1, 'rgt' => 10],
            ['title' => 'node 2 1', 'depth' => 2, 'lft' => 2, 'rgt' => 3],
            ['title' => 'node 2 2', 'depth' => 2, 'lft' => 4, 'rgt' => 7],
            ['title' => 'node 2 2 1', 'depth' => 3, 'lft' => 5, 'rgt' => 6],
            ['title' => 'node 2 3', 'depth' => 2, 'lft' => 8, 'rgt' => 9]
        ];
        $actualNestedSet = NestedSetHelper::convertArrayToNestedSet($array);
        $this->assertEquals($expectedNestedSet, $actualNestedSet);

        $array = [
            ['title' => 'root', 'depth' => 0],
            ['title' => 'node 1', 'depth' => 1],
            ['title' => 'node 1 1', 'depth' => 2],
            ['title' => 'node 1 2', 'depth' => 2],
            ['title' => 'node 1 2 1', 'depth' => 3],
            ['title' => 'node 1 2 1 1', 'depth' => 4],
            ['title' => 'node 1 3', 'depth' => 2],
            ['title' => 'node 2', 'depth' => 1],
            ['title' => 'node 2 1', 'depth' => 2],
            ['title' => 'node 2 2', 'depth' => 2],
        ];
        $expectedNestedSet = [
            ['title' => 'root', 'depth' => 0, 'lft' => 1, 'rgt' => 20],
            ['title' => 'node 1', 'depth' => 1, 'lft' => 2, 'rgt' => 13],
            ['title' => 'node 1 1', 'depth' => 2, 'lft' => 3, 'rgt' => 4],
            ['title' => 'node 1 2', 'depth' => 2, 'lft' => 5, 'rgt' => 10],
            ['title' => 'node 1 2 1', 'depth' => 3, 'lft' => 6, 'rgt' => 9],
            ['title' => 'node 1 2 1 1', 'depth' => 4, 'lft' => 7, 'rgt' => 8],
            ['title' => 'node 1 3', 'depth' => 2, 'lft' => 11, 'rgt' => 12],
            ['title' => 'node 2', 'depth' => 1, 'lft' => 14, 'rgt' => 19],
            ['title' => 'node 2 1', 'depth' => 2, 'lft' => 15, 'rgt' => 16],
            ['title' => 'node 2 2', 'depth' => 2, 'lft' => 17, 'rgt' => 18],
        ];
        $actualNestedSet = NestedSetHelper::convertArrayToNestedSet($array);
        $this->assertEquals($expectedNestedSet, $actualNestedSet);

        $array = [
            ['title' => 'root 1', 'depth' => 0],
            ['title' => 'node 1', 'depth' => 1],
            ['title' => 'node 1 1', 'depth' => 2],
            ['title' => 'root 2', 'depth' => 0],
            ['title' => 'node 2 ', 'depth' => 1],
            ['title' => 'node 3', 'depth' => 1]
        ];
        $expectedNestedSet = [
            ['title' => 'root 1', 'depth' => 0, 'lft' => 1, 'rgt' => 6],
            ['title' => 'node 1', 'depth' => 1, 'lft' => 2, 'rgt' => 5],
            ['title' => 'node 1 1', 'depth' => 2, 'lft' => 3, 'rgt' => 4],
            ['title' => 'root 2', 'depth' => 0, 'lft' => 7, 'rgt' => 12],
            ['title' => 'node 2 ', 'depth' => 1, 'lft' => 8, 'rgt' => 9],
            ['title' => 'node 3', 'depth' => 1, 'lft' => 10, 'rgt' => 11]
        ];
        $actualNestedSet = NestedSetHelper::convertArrayToNestedSet($array);
        $this->assertEquals($expectedNestedSet, $actualNestedSet);
    }
}