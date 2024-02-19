<?php

namespace App\Http\Controllers;

use App\Models\food_transactions;
use App\Models\rate;
use Illuminate\Http\Request;
use App\Models\Users;


class RateController extends Controller
{
    public function rating(Request $request)
    {
        // Check if rating is missing
        if ($request->rating === null) {
            return response()->json(['error' => 'Vui lòng chọn điểm đánh giá'], 200);
        }
        // Check if rating is valid
        $validRatings = [1, 2, 3, 4, 5];
        if (!in_array($request->rating, $validRatings)) {
            return response()->json(['error' => 'Vui lòng chọn điểm đánh giá hợp lệ'], 200);
        }
        // Check if review is too long
        if (strlen($request->reviewContent) > 200) {
            return response()->json(['error' => 'Vui lòng nhập đánh giá ngắn hơn'], 200);
        }
        // Check if the food transaction exists
        $transaction = food_transactions::find($request->transaction_id);
        if (!$transaction) {
            return response()->json(['error' => 'Giao dịch này không tồn tại'], 200);
        }
        if ($transaction->receiver_status == 1) {
            return response()->json(['error' => 'Bạn đã đánh giá'], 200);
        }
        // Check if the user is allowed to rate
        $username = session('username');
        $user = Users::where('username', $username)->first();
        if (!$user || $transaction->receiver_id !== $user->id) {
            return response()->json(['error' => 'Bạn không được phép đánh giá'], 200);
        }
        // Save the rating
        $rate = new Rate();
        $rate->food_transaction_id = $transaction->id;
        $rate->rating = $request->rating;
        $rate->review = $request->reviewContent;
        $rate->save();
        $transaction->receiver_status = 1;
        $transaction->save();
        return response()->json(['message' => 'Bạn đã đánh giá thành công']);
    }
}