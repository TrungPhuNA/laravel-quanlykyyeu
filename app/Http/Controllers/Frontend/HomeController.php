<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends FrontendController
{
	public function index()
	{

		// Lấy slide trang chủ
		$slides = Cache::remember('HOME.SLIDE', 60 * 24 * 10, function () {
			return Slide::where('sd_active', 1)
				->orderBy('sd_sort', 'asc')
				->get();
		});

		$articlesHot = Cache::remember('HOME.ARTICLE_HOT', 60 * 24, function () {
			return Article::where([
				'a_active' => 1,
				'a_hot'    => 1
			])
				->select('id', 'a_name', 'a_slug', 'a_description', 'a_avatar', 'created_at')
				->orderByDesc('id')
				->limit(4)
				->get();
		});


		$viewData = [
			'slides'      => $slides,
			'title_page'  => "Home",
			'articlesHot' => $articlesHot
		];
        dd("111");
		return view('frontend.pages.home.index', $viewData);
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 * Lấy sản phẩm vừa xem
	 */
	public function getLoadProductRecently(Request $request)
	{
		if ($request->ajax()) {
			$listID = $request->id;
			array_reverse($listID);
			$products = Product::whereIn('id', $listID)
				->orderByDesc('id')
				->limit(5)
				->select('id', 'pro_name', 'pro_slug', 'pro_sale', 'pro_avatar', 'pro_price', 'pro_review_total','pro_number', 'pro_review_star')
				->get();
			$html     = view('frontend.pages.home.include._recently', compact('products'))->render();

			return response()->json(['data' => $html]);
		}
	}

	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 * Lấy sản phẩm thuộc danh mục nổi bật
	 */
	public function getLoadProductByCategory(Request $request)
	{
		if ($request->ajax()) {
			$categoriesHot = Cache::remember('HOME.CATEGORY_HOT', 60 * 24, function () {
				// Sản phẩm thuộc danh mục hot
				return Category::with([
					'products' => function ($q) {
						$q->where('pro_active', 1)
							->select('id', 'pro_name', 'pro_slug', 'pro_category_id', 'pro_sale', 'pro_avatar','pro_number', 'pro_price', 'pro_review_total', 'pro_review_star')
							->limit(20)
							->orderByDesc('id')
							->get();
					}
				])
					->where([
						'c_hot'    => 1,
						'c_status' => 1
					])->get();
			});


			$html = view('frontend.pages.home.include._inc_product_by_category_hot', compact('categoriesHot'))->render();
			return response()->json(['data' => $html]);
		}
	}
}
