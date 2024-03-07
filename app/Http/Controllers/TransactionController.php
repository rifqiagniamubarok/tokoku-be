<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function checkout(Request $request): JsonResponse
    {
        $user = Auth::user();
        $basket = Basket::with('basketItems')->where('status', 'pending')->where('items', '>', 0)->where('user_id', $user->id)->first();

        if (!$basket) {
            return response()->json([
                'error' => 'You dont have any product on your basket',
            ]);
        }
        $basket->status = 'submit';

        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->basket_id = $basket->id;
        $transaction->status = 'payment_pending';
        $transaction->save();
        $basket->save();

        $getTransaction = Transaction::with('basket')->find($transaction->id);

        return response()->json([
            'data' => $getTransaction,
        ]);

    }
    public function get(Request $request): JsonResponse
    {
        $user = Auth::user();
        $transactions = Transaction::with('basket')->where('user_id', $user->id)->get();

        return response()->json([
            'data' => $transactions,
        ]);
    }
    public function getDetail(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        $transaction = Transaction::with('basket')->where('user_id', $user->id)->find($id);
        if (!$transaction) {
            return response()->json([
                'data' => 'transaction not found',
            ]);
        }

        return response()->json([
            'data' => $transaction,
        ]);
    }
    public function pay(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        $transaction = Transaction::with('basket')->where('user_id', $user->id)->where('status', 'payment_pending')->find($id);
        if (!$transaction) {
            return response()->json([
                'data' => 'transaction not found or transaction has been paid',
            ]);
        }

        $transaction->status = "confirmation_pending";
        $transaction->save();

        return response()->json([
            'data' => $transaction,
        ]);
    }
    public function adminGet(Request $request): JsonResponse
    {
        $status = $request->status;
        $user = Auth::user();
        if ($status) {
            $transactions = Transaction::with('basket')->where('status', $status)->get();
        } else {
            $transactions = Transaction::with('basket')->get();
        }

        return response()->json([
            'data' => $transactions,
        ]);
    }

    public function adminConfirm(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        $transaction = Transaction::with('basket')->where('status', 'confirmation_pending')->find($id);
        if (!$transaction) {
            return response()->json([
                'data' => 'transaction not found or transaction has been confirmed',
            ])->setStatusCode(400);
        }

        $transaction->status = "completed";
        $transaction->save();

        return response()->json([
            'data' => $transaction,
        ]);
    }
}
