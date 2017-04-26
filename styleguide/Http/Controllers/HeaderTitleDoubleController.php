<?php

namespace Styleguide\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Faker\Factory;

class HeaderTitleDoubleController extends Controller
{
    /**
     * Construct the HeaderTitleDoubleController.
     *
     * @param Factory $faker
     */
    public function __construct(Factory $faker)
    {
        $this->faker = $faker->create();
    }

    /**
     * Display the double header view.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        config(['app.sub_title' => $this->faker->sentence($this->faker->numberBetween(2, 4))]);

        return view('styleguide-childpage', merge($request->data));
    }
}
