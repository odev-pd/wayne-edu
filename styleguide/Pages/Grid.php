<?php

namespace Styleguide\Pages;

class Grid extends Page
{
    /**
     * {@inheritdoc}
     */
    public function getPageData()
    {
        return app('Factories\Page')->create(1, true, [
            'page' => [
                'controller' => 'GridController',
                'title' => 'Figure',
                'id' => 101106,
            ],
        ]);
    }
}
