<?php

namespace App\Http\Controllers;

use App\Models\district;
use App\Models\Food;
use App\Models\food_transactions;
use App\Models\ImagesFood;
use App\Models\Location;
use App\Models\province;
use App\Models\rate;
use App\Models\Users;
use App\Models\ward;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Notification;
use Illuminate\Support\Facades\Session;

class FoodTransactionsController extends Controller
{
    public function collect_food(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_quantity' => 'required|integer|min:1',
        ], [
            'food_quantity.required' => 'Vui lòng nhập số lượng.',
            'food_quantity.integer' => 'Số lượng phải là số nguyên.',
            'food_quantity.min' => 'Số lượng phải lớn hơn hoặc bằng 1.',
        ]);

        $username = session('username');
        $user = Users::where('username', $username)->first();

        if (!$user) {
            session()->put('id_product', $request->id);
            return response()->json(['errors' => 'erroruser'], 200);
        }

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return response()->json(['errors' => $errors], 200);
        }

        $quantity = $request->input('food_quantity');
        $food = Food::findOrFail($request->id);
        $foodTransaction = food_transactions::where('food_id', $request->id)
            ->where('receiver_id', $user->id)
            ->latest('created_at')
            ->first();

        if ($foodTransaction) {
            $pickupTime = $foodTransaction->created_at;
            $currentTime = now();
            $timeDiff = $currentTime->diffInHours($pickupTime);
            if ($timeDiff < 4) {
                return response()->json(['error' => 'Bạn đã nhận thực phẩm này trong vòng 4 giờ trước đó.'], 200);
            }
        }

        if ($food->quantity < $quantity) {
            return response()->json(['error' => 'Số lượng không đủ'], 200);
        }

        $foodTrans = new food_transactions([
            'food_id' => $request->id,
            'receiver_id' => $user->id,
            'quantity_received' => $quantity,
        ]);

        $food->quantity -= $quantity;
        $food->status = 1;
        $foodtemp = $food;
        try {
            DB::beginTransaction();

            if ($foodTrans->save() && $food->save()) {
                DB::commit();
                $user = $food->user;
                $notification = new Notification();
                $notification->transaction_id = $foodTrans->id;
                $notification->user_id = $user->id; // Assuming user_id is the correct attribute name
                $notification->message = $username . ' muốn nhận ' . $food->title . ' với số lượng là: ' . $quantity . '. Bạn có chấp nhận không?'; // Note the corrected message string
                $notification->save();
                return response()->json(['message' => 'Nhận Thành Công, Vui lòng đợi người tặng xác nhận'], 200);
            } else {
                DB::rollback();
                return response()->json(['error' => 'Failed to update records'], 500);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'An error occurred'], 500);
        }
    }

    public function show_received_list()
    {
        $username = session('username');
        $user = Users::where('username', $username)->first();
        if ($user) {
            $received_food = food_transactions::where('receiver_id', $user->id)
                ->with(['food.user'])
                ->paginate(10);
            return view('user/received_list', compact('received_food'));
        }
        return redirect()->back()->with('error', 'Không tìm thấy tài khoản.');
    }
    public function cancel_received($received_id)
    {
        $foodTransaction = food_transactions::find($received_id);
        if (!$foodTransaction) {
            return response()->json(['errors' => 'Không tìm thấy giao dịch thực phẩm.'], 404);
        }
        $foodTransaction->status = 2;
        $foodTransaction->save();

        $food = $foodTransaction->food;
        $food->quantity += $foodTransaction->quantity_received;
        $food->save();
        // Trả về phản hồi JSON thành công
        return response()->json(['message' => 'Đã hủy nhận thực phẩm thành công.']);
    }
    public function detail_received($received_id)
    {
        $foodTransaction = food_transactions::find($received_id);
        $rating = rate::where('food_transaction_id', $received_id)->first();
        if (!$rating) {
            $rating = null;
        }
        $username = session('username');
        $receiver = $foodTransaction->receiver;
        if ($receiver->username != $username) {
            return View('error404');
        }
        if (empty($foodTransaction)) {
            return redirect('/search');
        }
        $food = Food::find($foodTransaction->food_id);
        $province = province::find($foodTransaction->food_id);
        $district = district::find($foodTransaction->food_id);
        $ward = ward::find($foodTransaction->food_id);
        $donor = $food->user;
        $images = ImagesFood::where('food_id', $foodTransaction->food_id)->pluck('image_url')->toArray();
        return view('user/detail_received', compact('foodTransaction', 'food', 'donor', 'province', 'ward', 'district', 'images', 'rating'));
    }
    public function rollback_receiving_time($receivedId)
    {
        $foodTransaction = food_transactions::find($receivedId);
        if (!$foodTransaction) {
            return response()->json(['error' => 'Không tìm thấy giao dịch'], 404);
        }
        if ($foodTransaction->status == 3) {
            return response()->json(['error' => 'Không thể hủy giao dịch'], 400);
        }
        $food = Food::find($foodTransaction->food_id);
        if (!$food) {
            return response()->json(['error' => 'Không tìm thấy thực phẩm này'], 404);
        }
        $foodTransaction->update(['status' => 3]);
        $food->update(['quantity' => $food->quantity + $foodTransaction->quantity_received]);
        return response()->json(['message' => 'Giao Dịch Đã Bị Hủy Bỏ'], 200);
    }
    public function cofirm_collect_food($received_id)
    {
        $foodTransaction = food_transactions::find($received_id);
        if (!$foodTransaction) {
            return response()->json(['error' => 'Không tìm thấy giao dịch'], 404);
        }
        if ($foodTransaction->status == 1) {
            return response()->json(['error' => 'Đã xác nhận giao dịch'], 400);
        }
        $currentTime = now();
        $foodTransaction->update([
            'status' => 1,
            'pickup_time' => $currentTime,
        ]);
        return response()->json(['message' => 'Xác Nhận Đã Lấy Thành Công'], 200);
    }
    public function history_transactions()
    {
        $username = session('username');
        $user = Users::where('username', $username)->first();

        if ($user) {
            $foods = $user->foods;

            $transactions = [];

            foreach ($foods as $food) {
                $foodTransactions = $food->foodTransactions;
                foreach ($foodTransactions as $transaction) {
                    $transactions[] = $transaction;
                }
            }
            if (!empty($transactions)) {
                return view('user/history_transactions', ['transactions' => $transactions]);
            } else {
                return redirect()->back()->with('error', 'Không có giao dịch nào.');
            }
        }
        return redirect()->back()->with('error', 'Không tìm thấy tài khoản.');
    }
    public function show_detail_donated_food($food_donate_id)
    {
        $food = Food::find($food_donate_id);
        if (empty($food)) {
            return redirect('/user/search');
        } else {
            $imageUrls = ImagesFood::where('food_id', $food_donate_id)->pluck('image_url')->toArray();
            $foodData = $food->toArray();
            $user = Users::find($foodData['user_id']);
            $province = province::find($foodData['province_id']);
            $district = district::find($foodData['district_id']);
            $ward = ward::find($foodData['district_id']);
            $combinedData = array_merge($foodData, ['imageUrls' => $imageUrls]);
            $transactions = [];
            $ratings = [];
            if ($food->foodTransactions) {
                foreach ($food->foodTransactions as $transaction) {
                    $transactions[] = $transaction;
                    $transactionRatings = rate::where('food_transaction_id', $transaction->id)->first();
                    if (isset($transactionRatings)) {
                        $ratings[$transaction->id] = $transactionRatings;
                    } else {
                        $ratings[$transaction->id] = null;
                    }
                }
            }
            return view('user/detail_donated_food', compact('foodData', 'imageUrls', 'user', 'province', 'district', 'ward', 'transactions', 'ratings'));
        }
    }

    public function doner_confirm_notification($transaction_id)
    {
        $transaction = food_transactions::find($transaction_id);
        $food = $transaction->food;
        $user = $food->user;
        $receiver_id = $transaction->receiver->id;
        if (!$transaction) {
            return View('error404');
        }
        $transaction->donor_status = 1;
        $transaction->donor_confirm_time = now();
        $transaction->save();
        $username = session('username');
        $notification = new Notification();
        $notification->transaction_id = $transaction->id;
        $notification->user_id = $receiver_id; 
        $notification->message = $user->username . ' đã chấp nhận bạn tới lấy ' . $food->title . '. Vui lòng kiểm tra lại thời gian cho phép nhận thực phẩm để không bỏ lỡ thực phẩm bạn nhé!';
        $notification->save();
        $notification = "Cảm ơn bạn đã đồng hành cùng foodshare, mỗi đống góp của bạn là một niềm vui cho người kém may mắn!";
        return View('user/done_notification', compact('notification', 'transaction'));
    }
    public function doner_cancel_notification($transaction_id)
    {
        $transaction = food_transactions::find($transaction_id);
        if (!$transaction) {
            return response()->json(['errors' => 'Không tìm thấy giao dịch thực phẩm.'], 404);
        }
        $receiver_id = $transaction->receiver->id;
        $food = $transaction->food;
        $user = $food->user;
        $transaction->status = 2;
        $transaction->donor_status = 2;
        $transaction->save();
        $username = session('username');
        $notification = new Notification();
        $notification->transaction_id = $transaction->id;
        $notification->user_id = $receiver_id; // Assuming user_id is the correct attribute name
        $notification->message = $username . ' đã từ chối tặng sản phẩm ' . $food->title . '. Vui lòng nhận thực phẩm khác bạn nhé!';
        $notification->save();
        $food = $transaction->food;
        $food->quantity += $transaction->quantity_received;
        $food->save();
        $notification = "Bạn đã từ chối tặng thực phẩm. Cảm ơn bạn đã đồng hành cùng foodshare, mỗi đống góp của bạn là một niềm vui cho người kém may mắn!";
        return View('user/done_notification', compact('notification'));
    }
}