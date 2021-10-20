<?php
/*
* Status: Public
* Description: Promo Listing template
* Default: false
*/

namespace App\Http\Controllers;

use Contracts\Repositories\PromoListingRepositoryContract;
use Illuminate\Http\Request;

class PromoListingController extends Controller
{
    /**
     * Construct the controller.
     *
     * @param PromoListingRepositoryContract $promo
     */
    public function __construct(PromoListingRepositoryContract $promo)
    {
        $this->promo = $promo;
    }

    /**
     * Display the view.
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View|void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function index(Request $request)
    {
        $promos = $this->promo->getPromoListingPromos($request->data);

        if (!empty($request->data['base']['data']['listing_promo_group_id'])) {
            return view('promo-listing', merge($request->data, $promos));
        }

        if (!empty($request->data['base']['data']['grid_promo_group_id'])) {
            return view('promo-grid', merge($request->data, $promos));
        }
    }

    /**
     * Display the individual item.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function show(Request $request)
    {
        $promo = $this->promo->getPromoView($request->id);

        if (empty($promo['promo'])) {
            abort('404');
        }

        if (!empty($promo['promo']['title'])) {
            $request->data['base']['page']['title'] = $promo['promo']['title'];
        }

        // Set the back URL
        $request->data['back_url'] = $this->promo->getBackToPromoListing($request->server->get('HTTP_REFERER'), $request->server->get('REQUEST_SCHEME'), $request->server->get('HTTP_HOST'), $request->server->get('REQUEST_URI'));

        return view('promo-view', merge($request->data, $promo));
    }
}
