<?php

namespace Styleguide\Repositories;

use App\Repositories\PromoRepository as Repository;
use Factories\AccordionItems;
use Factories\FooterContact;
use Factories\FooterSocial;
use Factories\HeroImage;
use Factories\PromoPage;
use Factories\PromoPageWithOptions;
use Factories\Button;
use Faker\Factory;

class PromoRepository extends Repository
{
    /**
     * Construct the factory.
     */
    public function __construct(Factory $faker)
    {
        $this->faker = $faker->create();
    }

    /**
     * {@inheritdoc}
     */
    public function getHomepagePromos(int $page_id = null)
    {
        return [
            //'key' => app(\Factories\YourFactory::class)->create(5),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestData(array $data)
    {
        // Define the pages that have under menu promos: page_id => quanity
        $under_menu_page_ids = [
            114100 => 3, // Styleguide
        ];

        // Only pull under_menu promos if they match the page_ids that are specified
        $under_menu = !empty($under_menu_page_ids[$data['page']['id']]) ? app(Button::class)->create($under_menu_page_ids[$data['page']['id']]) : null;

        // Define the pages that have hero images
        $hero_page_ids = [
            // Homepage
            101101 => app(HeroImage::class)->create(1, false),
            // Contained
            105100100 => app(HeroImage::class)->create(1, false),
            // Full width
            105100103 => app(HeroImage::class)->create(1, false),
            // Rotate
            105100104 => app(HeroImage::class)->create(4, false),
            // Text overlay
            105100105 => app(HeroImage::class)->create(1, false, [
                'option' => 'Text Overlay',
            ]),
            // SVG overlay
            105100106 => app(HeroImage::class)->create(1, false, [
                'option' => 'SVG Overlay',
                'secondary_relative_url'  => "data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgMTYwMCA1ODAiPjxzdHlsZT4uc3Qwe2ZpbGw6I2ZmZn08L3N0eWxlPjxwYXRoIGQ9Ik0wIDBoMTAwdjEwMEgwek0xNTAwIDQ4MGgxMDB2MTAwaC0xMDB6TTAgNDgwaDEwMHYxMDBIMHpNMTUwMCAwaDEwMHYxMDBoLTEwMHoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMzc0LjkgNDk0LjVoLS4yTDM3MCA1MzloLTE4bC05LjMtODMuNGgxMi43bDYuOSA2NS44aC4ybDYuMi02NS44aDEyLjZsNi40IDY2LjJoLjJsNi43LTY2LjJINDA2bC05IDgzLjRoLTE3LjRsLTQuNy00NC41ek00NTMuNCA1MzloLTEzLjJsLTIuMy0xNS4xaC0xNi4xbC0yLjMgMTUuMWgtMTJsMTMuMy04My40SDQ0MGwxMy40IDgzLjR6bS0yOS45LTI2LjVoMTIuNmwtNi4yLTQyLjJoLS4ybC02LjIgNDIuMnpNNDY1LjkgNTExLjNsLTE2LjYtNTUuOEg0NjNsOS45IDM4aC4ybDkuOS0zOGgxMi41bC0xNi42IDU1LjhWNTM5aC0xMy4xdi0yNy43ek01MTIuOCA0NzguNmgtLjJWNTM5aC0xMS44di04My40aDE2LjRsMTMuMiA0OS45aC4ydi00OS45aDExLjdWNTM5aC0xMy41bC0xNi02MC40ek01NjQuOCA0OTAuN2gxOHYxMS45aC0xOFY1MjdoMjIuNnYxMmgtMzUuN3YtODMuNGgzNS43djExLjloLTIyLjZ2MjMuMnpNNjMxLjcgNDU0LjZjMTIuNyAwIDE5LjMgNy42IDE5LjMgMjF2Mi42aC0xMi40di0zLjVjMC02LTIuNC04LjItNi42LTguMnMtNi42IDIuMy02LjYgOC4yYzAgNi4xIDIuNiAxMC42IDExLjIgMTguMSAxMSA5LjYgMTQuNCAxNi42IDE0LjQgMjYuMSAwIDEzLjMtNi43IDIxLTE5LjUgMjEtMTIuOSAwLTE5LjUtNy42LTE5LjUtMjF2LTUuMWgxMi40djZjMCA2IDIuNiA4LjEgNi44IDguMSA0LjIgMCA2LjgtMi4xIDYuOC04LjEgMC02LjEtMi42LTEwLjYtMTEuMi0xOC4xLTExLTkuNi0xNC40LTE2LjYtMTQuNC0yNi4xIDAtMTMuNCA2LjUtMjEgMTkuMy0yMXpNNjU0LjggNDU1LjZoNDAuNXYxMS45aC0xMy43VjUzOWgtMTMuMXYtNzEuNWgtMTMuN3YtMTEuOXpNNzM4LjkgNTM5aC0xMy4ybC0yLjMtMTUuMWgtMTYuMUw3MDUgNTM5aC0xMmwxMy4zLTgzLjRoMTkuMmwxMy40IDgzLjR6TTcwOSA1MTIuNWgxMi42bC02LjItNDIuMmgtLjJsLTYuMiA0Mi4yek03MzYuNiA0NTUuNmg0MC41djExLjloLTEzLjdWNTM5aC0xMy4xdi03MS41aC0xMy43di0xMS45ek03OTYgNDkwLjdoMTh2MTEuOWgtMThWNTI3aDIyLjZ2MTJoLTM1Ljd2LTgzLjRoMzUuN3YxMS45SDc5NnYyMy4yek04NTcuNyA0NTUuNnY2NC4zYzAgNiAyLjYgOC4xIDYuOCA4LjEgNC4yIDAgNi44LTIuMSA2LjgtOC4xdi02NC4zaDEyLjR2NjMuNWMwIDEzLjMtNi43IDIxLTE5LjUgMjFzLTE5LjUtNy42LTE5LjUtMjF2LTYzLjVoMTN6TTkwNC41IDQ3OC42aC0uMlY1MzloLTExLjh2LTgzLjRoMTYuNGwxMy4yIDQ5LjloLjJ2LTQ5LjlIOTM0VjUzOWgtMTMuNWwtMTYtNjAuNHpNOTQzLjQgNDU1LjZoMTMuMVY1MzloLTEzLjF2LTgzLjR6TTk4NS43IDUyMy42aC4ybDkuOS02OGgxMkw5OTUgNTM5aC0xOS41bC0xMi45LTgzLjRoMTMuMmw5LjkgNjh6TTEwMjcuMSA0OTAuN2gxOHYxMS45aC0xOFY1MjdoMjIuNnYxMkgxMDE0di04My40aDM1Ljd2MTEuOWgtMjIuNnYyMy4yek0xMDg1LjIgNTM5Yy0uNy0yLjEtMS4yLTMuNS0xLjItMTAuMnYtMTMuMWMwLTcuNy0yLjYtMTAuNi04LjYtMTAuNmgtNC41djM0aC0xMy4xdi04My40aDE5LjhjMTMuNiAwIDE5LjQgNi4zIDE5LjQgMTkuMnY2LjZjMCA4LjYtMi43IDE0LjEtOC42IDE2Ljh2LjJjNi42IDIuNyA4LjcgOC45IDguNyAxNy42VjUyOWMwIDQuMS4xIDcgMS40IDEwLjFoLTEzLjN6bS0xNC4zLTcxLjV2MjUuNmg1LjFjNC45IDAgNy45LTIuMSA3LjktOC44di04LjJjMC02LTItOC42LTYuNy04LjZoLTYuM3pNMTEyMy4zIDQ1NC42YzEyLjcgMCAxOS4zIDcuNiAxOS4zIDIxdjIuNmgtMTIuNHYtMy41YzAtNi0yLjQtOC4yLTYuNi04LjItNC4yIDAtNi42IDIuMy02LjYgOC4yIDAgNi4xIDIuNiAxMC42IDExLjIgMTguMSAxMSA5LjYgMTQuNCAxNi42IDE0LjQgMjYuMSAwIDEzLjMtNi43IDIxLTE5LjUgMjFzLTE5LjUtNy42LTE5LjUtMjF2LTUuMWgxMi40djZjMCA2IDIuNiA4LjEgNi44IDguMSA0LjIgMCA2LjgtMi4xIDYuOC04LjEgMC02LjEtMi42LTEwLjYtMTEuMi0xOC4xLTExLTkuNi0xNC40LTE2LjYtMTQuNC0yNi4xIDAtMTMuNCA2LjYtMjEgMTkuMy0yMXpNMTE1MC4zIDQ1NS42aDEzLjFWNTM5aC0xMy4xdi04My40ek0xMTY5LjEgNDU1LjZoNDAuNXYxMS45aC0xMy43VjUzOWgtMTMuMXYtNzEuNWgtMTMuN3YtMTEuOXpNMTIyNy42IDUxMS4zbC0xNi42LTU1LjhoMTMuN2w5LjkgMzhoLjJsOS45LTM4aDEyLjVsLTE2LjYgNTUuOFY1MzloLTEzLjF2LTI3Ljd6Ii8+PC9zdmc+",
            ]),
            // Logo overlay
            105100107 => app(HeroImage::class)->create(1, false, [
                'option' => 'Logo Overlay',
                'secondary_relative_url' => '/styleguide/image/600x250?text=600x250',
            ]),
        ];

        // Only pull hero promos if they match the page ids that are specificed
        $hero = !empty($hero_page_ids[$data['page']['id']]) ? $hero_page_ids[$data['page']['id']] : null;

        // Full width page IDs
        $hero_full_width_ids = [
            105100103,
            105100104,
            105100105,
            105100106,
            105100107,
        ];

        // Set the config for full width hero if they match the page ids that are specified
        if(in_array($data['page']['id'], $hero_full_width_ids)) {
            config([
                'base.hero_contained' => false,
                'base.hero_full_controllers' => ['HeroController'],
            ]);
        }

        // Define the pages that the childpage accordion should show on page_id => quanity
        $accordion_page_ids = [
            107100 => 5,
        ];

        // Only pull accordion for childpage template
        $accordion = !empty($accordion_page_ids[$data['page']['id']]) ? app(AccordionItems::class)->create($accordion_page_ids[$data['page']['id']]) : null;

        // Get all the social icons
        $social = collect([
            'x',
            'twitter',
            'tiktok',
            'facebook',
            'instagram',
            'youtube',
            'snapchat',
            'linkedin',
            'flickr',
            'pinterest',
            'mastodon',
        ])->map(function ($name) {
            return app(FooterSocial::class)->create(1, true, ['title' => $name]);
        })
        ->reject(function ($item) {
            return empty($item);
        })
        ->toArray();

        return [
            'contact' => app(FooterContact::class)->create(1),
            'social' => $social,
            'hero' => $hero,
            'under_menu' => $under_menu,
            'accordion_page' => $accordion,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoView($id)
    {
        return [
            'promo' => app(PromoPage::class)->create(1, true, [
                'description' => '
                    <p>'.$this->faker->text(300).' <a href="https://wayne.edu">'.$this->faker->sentence(3).'</a></p>
                    <p>'.$this->faker->text(100).' <a href="https://wayne.edu">'.$this->faker->sentence(3).'</a> '. $this->faker->text(200).'</p>
                    <p>'.$this->faker->text(50).' <a href="https://wayne.edu">'.$this->faker->sentence(3).'</a> '. $this->faker->text(250).'</p>
                    <figure class="figure float-left mb-4 w-full md:w-1/2 lg:w-1/3">
                        <img src="/styleguide/image/600x450?text=Embedded in description" class="p-2" alt="">
                        <figcaption class="mt-1">This image is from the promotion description</figcaption>
                    </figure>
                    <p>'.$this->faker->text(200).' <a href="https://wayne.edu">'.$this->faker->sentence(3).'</a> '. $this->faker->text(100).'</p>
                    <p>'.$this->faker->text(200).' <a href="https://wayne.edu">'.$this->faker->sentence(3).'</a> '. $this->faker->text(100).'</p>
                    <p>'.$this->faker->text(300).' <a href="https://wayne.edu">'.$this->faker->sentence(3).'</a> '. $this->faker->text(100).'</p>
                ',
                'relative_url' => '/styleguide/image/600x450?text=Primary%20promo%20image'
            ]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getBackToPromoListing($referer = null, $scheme = null, $host = null, $uri = null)
    {
        return '/styleguide/promolist';
    }

    /**
     * {@inheritdoc}
     */
    public function getPromoPagePromos(array $data, $limit = 75)
    {
        if ($data['page']['id'] === 101110200 || $data['page']['id'] === 101110400) {
            // No options
            $promos['promos'] = app(PromoPage::class)->create(12);
        } else {
            $promos['promos'] = app(PromoPageWithOptions::class)->create(12);
        }

        if (!empty($data['data']['promoPage'])) {
            $group_info = $this->parsePromoJSON($data);

            // Assign template markers to promos array
            $promos['template'] = $group_info;

            // Manage data with template flags
            $promos = $this->changePromoDisplay($promos, $group_info);
        }

        // Organize promos by option
        $promos = $this->organizePromoItemsByOption($promos);

        return $promos;
    }
}
