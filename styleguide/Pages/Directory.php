<?php

namespace Styleguide\Pages;

class Directory extends Page
{
    /**
     * {@inheritdoc}
     */
    public function getPageData()
    {
        return app('Factories\Page')->create(1, true, [
            'page' => [
                'controller' => 'DirectoryController',
                'title' => 'Directory',
                'id' => 101104,
            ],
            'site' => [
                'parent' => [
                    'id' => 1,
                ],
            ],
        ]);
    }
}
