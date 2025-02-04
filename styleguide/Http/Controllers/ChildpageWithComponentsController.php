<?php

namespace Styleguide\Http\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Factories\HeroImage;
use Factories\Spotlight;
use Factories\Button;
use Factories\GenericPromo;
use Factories\Article;
use Factories\Event;
use Factories\Video;

class ChildpageWithComponentsController extends Controller
{
    /**
     * Display a childpage using components.
     */
    public function index(Request $request): View
    {
        $request->data['base']['page']['content']['main'] = '
<p>This an example of CMS page content on a childpage with components.</p>
';
        $components = [
            'hero-1' => [
                'data' => app(HeroImage::class)->create(1, false, [
                    'relative_url' => '/styleguide/image/3200x600',
                    'option' => 'banner small',
                ]),
                'component' => [
                    'filename' => 'hero',
                ],
            ],
            'button-row' => [
                'data' => app(Button::class)->create(3, false, [
                    'option' => 'green',
                    'excerpt' => '',
                    'relative_url' => 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxMDAgMTAwIj48cGF0aCBkPSJNNTAgMi41QzIzLjggMi41IDIuNSAyMy44IDIuNSA1MFMyMy44IDk3LjUgNTAgOTcuNSA5Ny41IDc2LjIgOTcuNSA1MCA3Ni4yIDIuNSA1MCAyLjV6bS02LjIgMTguNGMxLjYtMS41IDMuNS0yLjIgNS42LTIuMiAyLjIgMCA0LjEuNyA1LjYgMi4yIDEuNiAxLjUgMi4zIDMuMiAyLjMgNS4zIDAgMi0uOCAzLjgtMi40IDUuMi0xLjYgMS40LTMuNCAyLjItNS42IDIuMi0yLjIgMC00LjEtLjctNS42LTIuMi0xLjYtMS40LTIuNC0zLjItMi40LTUuMi4xLTIuMS45LTMuOSAyLjUtNS4zem0xOS41IDYwLjRIMzcuN3YtM2MuNy0uMSAxLjQtLjEgMi4xLS4yczEuMy0uMiAxLjctLjRjLjktLjMgMS41LS44IDEuOC0xLjQuMy0uNi41LTEuNC41LTIuNFY1MC40YzAtLjktLjItMS44LS42LTIuNS0uNC0uNy0xLTEuMy0xLjYtMS43LS41LS4zLTEuMi0uNi0yLjItLjlzLTEuOS0uNS0yLjctLjZ2LTNsMTkuOC0xLjEuNi42djMyLjFjMCAuOS4yIDEuNy42IDIuNC40LjcgMSAxLjIgMS43IDEuNS41LjIgMS4xLjUgMS44LjYuNi4yIDEuMy4zIDIgLjR2My4xeiIgZmlsbD0iI2ZmZiIvPjwvc3ZnPg==',
                    'filename_alt_text' => '',
                ]),
                'component' => [
                    'heading' => 'Button row',
                    'filename' => 'button-row',
                ],
            ],
            'catalog-1' => [
                'data' => app(GenericPromo::class)->create(3, false, [
                    'description' => '',
                ]),
                'component' => [
                    'heading' => 'Three column catalog',
                    'filename' => 'catalog',
                    'columns' => '3',
                    'showDescription' => false,
                ],
            ],

            'promo-row-1' => [
                'data' => app(GenericPromo::class)->create(1, false, [
                    'youtube_id' => '',
                    'relative_url' => '',
                    'link' => '',
                    'title' => 'No image content - promo row',
                    'description' => '<p>Example placement of an introductory paragraph describing the information laid out in the accordion below.</p><p>This is a separate promo group using the "content row" component. Below this is a different promo group with the accordion data and no component heading specified.</p>',
                ]),
                'component' => [
                    'filename' => 'promo-row',
                ],
            ],

            'accordion-1' => [
                'data' => app(GenericPromo::class)->create(4, false),
                'component' => [
                    'filename' => 'accordion',
                ],
            ],

            'heading-1' => [
                'data' => [
                    0 => [
                        'heading' => 'News',
                    ],
                ],
                'component' => [
                    'filename' => 'heading',
                ],
            ],

            'promo-column-1' => [
                'data' => app(GenericPromo::class)->create(1, false, [
                    'title' => 'Featured article (promo column)',
                    'filename_url' => '/styleguide/image/770x434',
                    'description' => '',
                ]),
                'component' => [
                    'heading' => '',
                    'filename' => 'promo-column',
                    'gradientOverlay' => true,
                ],
            ],

            'news-column' => [
                'data' => app(Article::class)->create(3, false),
                'component' => [
                    'filename' => 'news-column',
                ],
            ],

            'heading-2' => [
                'data' => [
                    0 => [
                        'heading' => 'Events',
                    ],
                ],
                'component' => [
                    'filename' => 'heading',
                ],
            ],

            'events-column' => [
                'data' => app(Event::class)->create(4, false),
                'component' => [
                    'filename' => 'events-column',
                    'calendar_url' => '/myurl'
                ],
            ],

            'promo-column-2' => [
                'data' => app(GenericPromo::class)->create(1, false, [
                    'title' => 'Featured event (promo column)',
                    'filename_url' => '/styleguide/image/600x600',
                    'description' => '',
                ]),
                'component' => [
                    'heading' => '',
                    'filename' => 'promo-column',
                    'gradientOverlay' => true,
                ],
            ],

            'catalog-2' => [
                'data' => app(GenericPromo::class)->create(2, false, [
                ]),
                'component' => [
                    'heading' => 'Single column catalog',
                    'filename' => 'catalog',
                    'columns' => '1',
                    'showDescription' => false,
                    'imageSize' => 'small',
                ],
            ],

            'spotlight' => [
                'data' => app(Spotlight::class)->create(1, false),
                'component' => [
                    'heading' => 'Spotlight',
                    'filename' => 'spotlight-row',
                    'showDescription' => true,
                ],
            ],

            'promo-row-2' => [
                'data' => app(GenericPromo::class)->create(2, false),
                'component' => [
                    'heading' => 'Promo row with alternating image position',
                    'filename' => 'promo-row',
                    'imagePosition' => 'alternate',
                ],
            ],

            'promo-column-3' => [
                'data' => app(Video::class)->create(1, false),
                'component' => [
                    'heading' => 'Promo column',
                    'filename' => 'promo-column',
                ],
            ],

            'button-column' => [
                'data' => app(Button::class)->create(4, false, [
                    'option' => 'Default',
                    'excerpt' => '',
                ]),
                'component' => [
                    'heading' => 'Button column',
                    'filename' => 'button-column',
                ],
            ],
        ];

        // Assign components globally
        $request->data['base']['components'] = $components;

        if (!empty($request->data['base']['components'])) {
            // Set hero from components
            $hero = collect($request->data['base']['components'])->reject(function ($data, $component_name) {
                return !str_contains($component_name, 'hero');
            })->toArray();
        }

        if (!empty($hero)) {
            $hero_key = array_key_first($hero);

            $request->data['base']['hero'] = $request->data['base']['components'][$hero_key]['data'];

            unset($request->data['base']['components'][$hero_key]);

            config(['base.hero_full_controllers' => [$request->data['base']['page']['controller']]]);
        }

        return view('childpage', merge($request->data));
    }
}
