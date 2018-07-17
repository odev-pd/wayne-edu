<?php

namespace Styleguide\Pages;

class Menuleft extends Page
{
    /**
     * {@inheritdoc}
     */
    public function getPageData()
    {
        return app('Factories\Page')->create(1, [
            'page' => [
                'controller' => 'MenuLeftController',
                'title' => 'Menu left',
                'id' => 103100100,
            ],
        ]);
    }
}
