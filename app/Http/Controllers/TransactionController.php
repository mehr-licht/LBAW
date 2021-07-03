<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;
use App\Transaction;
use App\Buyitnow;
use App\User;
use App\Product;
use App\Notification;


class TransactionController extends Controller
{
    private const VALIDATOR_MESSAGES = [
        'required' => 'O campo é obrigatório.',
        'integer' => 'O valor tem que ser um inteiro.',
        'date_format' => 'A data tem de ser no formato Y-m-d\TH:i',
        'regex' => 'O valor tem que ter ponto ou virgula, 1 ou 2 casas decimais e pelo menos 1 à esquerda'
    ];

    private const INPUT_FIELDS = [
        'id_transac',
        'id_buy',
        'buyer',
        'seller',
        'value'
    ];

    private const VALIDATOR_RULES = [
        'id_buy' => 'bail | required | integer',
        'buyer' => 'bail | required | integer',
        'seller' => 'bail | required | integer',
        'value' => 'regex:/(^\d+([.,]\d{1,2})?$)/ | required',
        'vote_inSeller' => 'bail | integer',
        'vote_inBuyer' => 'bail | integer',
        'date_payment' => 'bail | date_format:Y-m-d\TH:i'
    ];

    private function validateCreateOrUpdateInput(Request $request)
    {
        $data = $request->only(self::INPUT_FIELDS);

        return Validator::make($data, self::VALIDATOR_RULES, self::VALIDATOR_MESSAGES);
    }

    /**
     * Store a newly created transaction.
     * 'surpassed', 'payment', 'bid', 'end_of_auction', 'buy', 'comment'
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $buyitnow = $request->only('id_buy');

        if (empty($buyitnow)) {
            return response()->json('Venda não fornecida', 422);
        }

        $validator = $this->validateCreateOrUpdateInput($request);

        DB::beginTransaction();
        try {

            $buyitnow = Buyitnow::where('id_buy', $request->input('id_buy'))->first();
            $buyitnow->date_end = now(); //Carbon::now()->toArray()["formatted"]
            $buyitnow->save();

            $product = Product::find($request->input('id_buy'));
            $product->state_product = 'bought';
            $product->save();

            $data['id_transac'] = Transaction::max('id_transac') + 1;
            $data['id'] = $request->input('id_buy');
            $data['id_buyer'] = $request->input('buyer');
            $data['id_seller'] = $request->input('seller');
            $data['value'] = $request->input('value');
            $transaction = new Transaction;

            $new = $transaction->create($data);

            $notification = new Notification; 
            $notification->id_user = $request->input('seller');
            $notification->is_new = true;
            $notification->text_notification = 'O seu produto foi comprado por '.$request->input('buyer');
            $notification->type_ofnotification = 'buy';
            $notification->id_item = $request->input('id_buy');
            $notification->id_comment = null;
            $notification->save();
            
            DB::commit();

            return response()->json('comprado', 200);
        } catch (ModelNotFoundException $err) {
            DB::rollBack();
            Log::channel('storeLog')->info('Transaction creation FAILED');
        }

        return response()->json($validator->errors()->all(), 422);
    }
}