<?php

namespace Tests\Unit\Repositories;

use App\Repositories\PeopleRepository;
use Exception;
use Factories\Article;
use Factories\Page;
use Factories\People;
use Factories\PeopleGroup;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Str;
use Tests\TestCase;
use Mockery as Mockery;
use Waynestate\Api\News;
use Waynestate\Api\People as PeopleApi;

class PeopleRepositoryTest extends TestCase
{
    /**
     * @covers App\Repositories\PeopleRepository::__construct
     * @covers App\Repositories\PeopleRepository::getDropdownOptions
     * @test
     */
    public function getting_dropdown_options_should_return_options(): void
    {
        // Get a random group id
        $random_group_id = $this->faker->numberBetween(1, 9);

        // No parameters
        $options = app(PeopleRepository::class)->getDropdownOptions();
        $this->assertEquals(['selected_group' => null, 'hide_filtering' => false], $options);

        // If user selects group
        $options = app(PeopleRepository::class)->getDropdownOptions($random_group_id);
        $this->assertEquals(['selected_group' => $random_group_id, 'hide_filtering' => false], $options);

        // If custom page fields selects the group
        $options = app(PeopleRepository::class)->getDropdownOptions(null, $random_group_id);
        $this->assertEquals(['selected_group' => $random_group_id, 'hide_filtering' => true], $options);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getFields
     * @test
     */
    public function getting_fields_should_return_all_types(): void
    {
        $fields = app(PeopleRepository::class)->getFields();

        $this->assertTrue(is_array($fields));
    }

    /**
     * @covers App\Repositories\PeopleRepository::getPageTitleFromName
     * @test
     */
    public function getting_page_title_should_come_from_name(): void
    {
        $returnNameFields = [
            'name_fields' => [
                'Honorific',
                'First Name',
                'Last Name',
                'Suffix',
            ],
        ];

        $return['profile']['data'] = [
            'Honorific' => 'Dr.',
            'First Name' => 'Anthony',
            'Last Name' => 'Wayne',
            'Suffix' => 'Jr.',
        ];

        // Mock the Connector and set the return
        $people = Mockery::mock(PeopleRepository::class)->makePartial();
        $people->shouldReceive('getFields')->once()->andReturn($returnNameFields);

        // Get the page title
        $pageTitle = $people->getPageTitleFromName($return);

        // Make sure the page title equals all the name fields
        $this->assertEquals('Dr. Anthony Wayne, Jr.', $pageTitle);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getBackToProfileListUrl
     * @test
     */
    public function getting_back_to_profile_list_url_should_return_url(): void
    {
        // The default path if no referer
        $url = app(PeopleRepository::class)->getBackToProfileListUrl();
        $this->assertTrue($url == config('base.profile_default_back_url'));

        // If a referer is passed from a different domain
        $referer = $this->faker->url();
        $url = app(PeopleRepository::class)->getBackToProfileListUrl($referer, 'http', 'wayne.edu', '/');
        $this->assertTrue($url == config('base.profile_default_back_url'));

        // If a referer is passed that is the same page we are on
        $referer = $this->faker->url();
        $parsed = parse_url($referer);
        $url = app(PeopleRepository::class)->getBackToProfileListUrl($referer, $parsed['scheme'], $parsed['host'], $parsed['path']);
        $this->assertTrue($url == config('base.profile_default_back_url'));

        // If referer is passed from the same domain that the site is on
        $referer = $this->faker->url();
        $parsed = parse_url($referer);
        $url = app(PeopleRepository::class)->getBackToProfileListUrl($referer, $parsed['scheme'], $parsed['host'], $this->faker->word());
        $this->assertEquals($referer, $url);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getDropdownOfGroups
     * @test
     */
    public function getting_dropdown_of_groups_should_contain_all_the_groups(): void
    {
        // Force this config incase it is changed
        config(['base.people_parent_group_id' => 0]);

        // Fake return
        $return['data'] = app(PeopleGroup::class)->create(5);

        // Mock the connector and set the return
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andReturn($return);

        $dropdown = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getDropdownOfGroups($this->faker->numberBetween(1, 10));

        collect($return['data'])->each(function ($item) use ($dropdown) {
            // Make sure the group exists in the dropdown array
            $this->assertTrue(in_array($item['name'], current($dropdown)));
        });
    }

    /**
     * @covers App\Repositories\PeopleRepository::getDropdownOfGroups
     * @test
     */
    public function getting_dropdown_of_single_group_should_contain_single_group(): void
    {
        // Force this config incase it is changed
        config(['base.people_parent_group_id' => 0]);

        // Fake return
        $return['data'] = app(PeopleGroup::class)->create(1);
        $group_id = current($return['data'])['id'];

        // Mock the connector and set the return
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andReturn($return);

        $dropdown = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getDropdownOfGroups($this->faker->numberBetween(1, 10));

        // Make sure the single_group key exists with the ID of the group on it
        $this->assertArrayHasKey('single_group', $dropdown);
        $this->assertTrue($dropdown['single_group'] == $group_id);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getProfile
     * @test
     */
    public function getting_profile_that_doesnt_exist_should_return_blank_array(): void
    {
        $site_id = $this->faker->numberBetween(1, 10);
        $accessid = $this->faker->word();

        // Fake return
        $return = [
            'errors' => [
                "message" => "User not found with the AccessID aa2121 to the Site ID 55",
                "code" => "400",
            ],
        ];

        // Mock the connector and set the return
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andReturn($return);

        $profile = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getProfile($site_id, $accessid);

        $this->assertTrue(is_array($profile['profile']) && count($profile['profile']) == 0);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getProfiles
     * @test
     */
    public function getting_profiles_with_api_error_should_return_blank_array(): void
    {
        // Fake return
        $return = [
            'errors' => [
                "message" => "You MUST pass 1 active Site ID (site_id)",
                "code" => "400",
            ],
        ];

        // Mock the connector and set the return
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andReturn($return);

        $profiles = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getProfiles($this->faker->numberBetween(1, 10));

        // Since the API returned an error we shouldn't have any profiles
        $this->assertEmpty($profiles['profiles']);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getProfiles
     * @test
     */
    public function getting_profiles_should_append_data(): void
    {
        // Fake return
        $return['data'] = app(People::class)->create(5);

        // Remove Factory (Styleguide data) to test against the getProfiles
        $return['data'] = collect($return['data'])->map(function ($profile) {
            unset($profile['link']);
            unset($profile['data']);

            return $profile;
        })->toArray();

        // Mock the connector and set the return
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andReturn($return);

        $profiles = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getProfiles($this->faker->numberBetween(1, 10));

        collect($profiles['profiles'])->each(function ($item) {
            $this->assertNotEmpty($item['link']);
            $this->assertNotEmpty($item['data']['Picture']['url']);
        });
    }

    /**
     * @covers App\Repositories\PeopleRepository::getProfile
     * @test
     */
    public function getting_profile_should_append_data(): void
    {
        $site_id = $this->faker->numberBetween(1, 10);
        $accessid = $this->faker->word();

        // Fake return
        $return['data'] = app(People::class)->create(1, true);

        // Remove Factory (Styleguide data) to test against the getProfiles
        unset($return['data']['link']);
        unset($return['data']['data']);

        // Mock the connector and set the return
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andReturn($return);

        $profile = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getProfile($site_id, $accessid);

        $this->assertNotEmpty($profile['profile']['link']);
        $this->assertNotEmpty($profile['profile']['data']['Picture']['url']);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getGroupIds
     * @test
     */
    public function getting_profile_group_ids_should_return_correct_string(): void
    {
        // Fake a dropdown array of group_id => group name
        $limit = $this->faker->numberBetween(1, 10);
        $dropdown = $this->faker->words($limit, false);

        // If no forced ID and no selection has been made the result should be all group_ids from the dropdown
        $group_ids = app(PeopleRepository::class)->getGroupIds(null, null, $dropdown);
        $this->assertEquals(implode(',', array_keys($dropdown)), $group_ids);

        // Forcing a group ID
        $forced_id = $this->faker->numberBetween(0, $limit - 1);
        $group_ids = app(PeopleRepository::class)->getGroupIds(null, $forced_id, $dropdown);
        $this->assertEquals($forced_id, $group_ids);

        // Selected from the dropdown
        $selected = array_rand($dropdown, 1);
        $group_ids = app(PeopleRepository::class)->getGroupIds($selected, null, $dropdown);
        $this->assertEquals($selected, $group_ids);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getProfilesByGroup
     * @covers App\Repositories\PeopleRepository::sortGroupsByDisplayOrder
     * @test
     */
    public function profiles_should_be_grouped(): void
    {
        $site_id = $this->faker->numberBetween(1, 10);

        // Force this config incase it is changed
        config(['base.people_parent_group_id' => 0]);

        // Mock the user listing
        $return_user_listing['data'] = app(People::class)->create(10);
        // Mock the connector and set the return
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->with('sites/' . $site_id . '/users', Mockery::type('array'))->andReturn($return_user_listing);

        // The People factory creates groups. We need those values rather than factoring more groups that users aren't in.
        $return_group_listing['data'] = collect($return_user_listing['data'])
            ->map(function ($item, $key) use ($site_id) {
                return collect($item['groups'])->first();
            })
            ->toArray();

        // Mock the groups listing
        $peopleApi->shouldReceive('request')->with('sites/' . $site_id . '/groups', Mockery::type('array'))->andReturn($return_group_listing);

        $profiles = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getProfilesByGroup($site_id, $return_group_listing);

        // Make sure the root keys are all of the groups
        collect($return_group_listing['data'])->each(function ($item) use ($profiles) {
            $this->assertTrue(array_key_exists($item['name'], $profiles['profiles']));
        });
    }

    /**
     * @covers App\Repositories\PeopleRepository::getProfilesByGroupOrder
     * @test
     */
    public function profile_group_ids_should_return_ordered_array(): void
    {
        $site_id = $this->faker->numberBetween(1, 10);

        // Mock the user listing
        $return_user_listing['data'] = app(People::class)->create(10);

        $groups = collect($return_user_listing['data'])->map(function ($item) {
            return array_shift($item['groups']);
        })->keyBy('id')->unique()->reverse()->toArray();

        $comma_groups = implode(',', array_keys($groups));

        $return_user_listing['data'] = collect($return_user_listing['data'])->mapWithKeys(function ($item, $key) use ($groups) {
            $group_id = array_search($item['groups'][0], $groups);
            $item['groups'] = [$group_id => $item['groups'][0]];

            return [$key => $item];
        });

        // Mock the connector and set the return
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->with('sites/' . $site_id . '/users', Mockery::type('array'))->andReturn($return_user_listing);

        $profiles = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getProfilesByGroupOrder($site_id, $comma_groups);

        $this->assertEquals(collect($groups)->pluck('name')->toArray(), array_values(array_keys($profiles['profiles'])));
        $this->assertEquals(collect($groups)->pluck('name')->toArray(), array_values(array_keys($profiles['anchors'])));
        foreach ($profiles['anchors'] as $key => $slug) {
            $this->assertEquals($slug, Str::slug($key));
        }
    }

    /**
     * @covers App\Repositories\PeopleRepository::getProfiles
     * @test
     */
    public function getting_profiles_with_exception_should_return_empty_array(): void
    {
        // Mock the connector and thrown Exception
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andThrow(new Exception());

        $profiles = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getProfiles($this->faker->numberBetween(1, 10));

        $this->assertCount(0, $profiles['profiles']);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getProfile
     * @test
     */
    public function getting_profile_with_exception_should_return_empty_array(): void
    {
        $site_id = $this->faker->numberBetween(1, 10);
        $accessid = $this->faker->word();

        // Mock the connector and thrown Exception
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andThrow(new Exception());

        $profiles = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getProfile($site_id, $accessid);

        $this->assertCount(0, $profiles['profile']);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getDropdownOfGroups
     * @test
     */
    public function getting_dropdown_groups_with_exception_should_return_empty_array(): void
    {
        // Force this config incase it is changed
        config(['base.people_parent_group_id' => 0]);

        // Mock the connector and thrown Exception
        $peopleApi = Mockery::mock(PeopleApi::class);
        $peopleApi->shouldReceive('request')->andThrow(new Exception());

        $dropdown = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getDropdownOfGroups($this->faker->numberBetween(1, 10));

        $this->assertCount(1, $dropdown['dropdown_groups']);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getSiteID
     * @test
     */
    public function getting_people_site_id_should_return_the_correct_site_id_based_on_custom_field(): void
    {
        // Mock people API
        $peopleApi = Mockery::mock(PeopleApi::class);

        // Create a fake data request for custom page field data
        $profile_site_id = $this->faker->numberBetween(1, 1000);
        $custom_field_page = app(Page::class)->create(1, true, [
            'data' => [
                'profile_site_id' => $profile_site_id,
            ],
        ]);
        $return_profile_site_id = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getSiteID($custom_field_page);
        $this->assertEquals($profile_site_id, $return_profile_site_id);

        // Create a fake data request for site config people id
        $people_site_id = $this->faker->numberBetween(1, 1000);
        $site_config_page = app(Page::class)->create(1, true, [
            'site' => [
                'people' => [
                    'site_id' => $people_site_id,
                ],
            ],
        ]);
        $return_people_site_id = app(PeopleRepository::class, ['peopleApi' => $peopleApi])->getSiteID($site_config_page);
        $this->assertEquals($people_site_id, $return_people_site_id);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getNewsArticles
     * @test
     */
    public function getting_people_profile_should_get_articles(): void
    {
        // Fake return
        $return = app(Article::class)->create(5);

        // Mock the connector and set the return
        $newsApi = Mockery::mock(News::class);
        $newsApi->shouldReceive('request')->andReturn($return);

        // Get the articles
        $articles = app(PeopleRepository::class, ['newsApi' => $newsApi])->getNewsArticles('aa0000');

        $this->assertEquals($return['data'], $articles);
    }

    /**
     * @covers App\Repositories\PeopleRepository::getNewsArticles
     * @test
     */
    public function getting_people_profile_articles_should_be_empty_if_exception_was_thrown(): void
    {
        // Mock the connector and set the return
        $newsApi = Mockery::mock(News::class);
        $newsApi->shouldReceive('request')->andThrow(new TransferException('test'));

        // Get the articles
        $articles = app(PeopleRepository::class, ['newsApi' => $newsApi])->getNewsArticles('aa0000');

        $this->assertEmpty($articles);
    }
}
