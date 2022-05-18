<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        if ($request->ajax()) {
            $productID = $request->productId;
			$data = $request->all();

            // Check product
            $product = Product::find($productID);
            if (!$product) {
                return response(['code' => '404']);
            }

            // Check load lại page để hiện popup captcha
            // if (\Auth::user()->count_comment >= 2) {
            //     return response([
            //         'code' => '501'
            //     ]);
            // }

			$images = $request->images;
            $data      = [
                'cmt_user_id'    => \Auth::user()->id,
                'cmt_content'    => $request->comment,
                'cmt_product_id' => $productID,
                'cmt_parent_id'  => $request->commentId ?? 0,
                'created_at'     => Carbon::now()
            ];
			$dataImage = [];

//			if ($images)
//			{
//				foreach ($images as $key => $fileImage) {
//					$ext    = $fileImage->getClientOriginalExtension();
//					$extend = [
//						'png', 'jpg', 'jpeg', 'PNG', 'JPG'
//					];
//
//					if (!in_array($ext, $extend)) return false;
//
//					$filename = date('Y-m-d__') . Str::slug($fileImage->getClientOriginalName()) . '.' . $ext;
//					$path     = public_path() . '/uploads/' . date('Y/m/d/');
//					if (!\File::exists($path)) {
//						mkdir($path, 0777, true);
//					}
//
//					$fileImage->move($path, $filename);
//					$dataImage[] = $filename;
//				}
//			}
//
//			$data['cmt_images'] = json_encode($dataImage);
            $commentId = Comments::insertGetId($data);
            if ($commentId) {
                for ($i = 1 ; $i <= 100 ; $i ++)
                {
                    Cache::forget('COMMENT_PRODUCT_'.$productID.'_PAGE_'.$i);
                }

                $comment = Comments::with('user:id,name')->find($commentId);
                \DB::table('users')->where('id', \Auth::user()->id)
                        ->increment('count_comment');
				Cache::forget('COMMENT_PRODUCT_'. $productID);

                $html    = view('frontend.pages.product_detail.include._inc_comment_item', compact('comment'))->render();
                return response([
                    'code' => '200',
                    'html' => $html
                ]);
            }

            return response(['code' => '401']);
        }
    }
}
