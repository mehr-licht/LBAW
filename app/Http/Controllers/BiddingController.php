<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;


use Carbon\Carbon;
use App\Bidding;
use App\User;
use App\Auction;
use App\Product;
use App\Notification;



class BiddingController extends Controller
{
    private const VALIDATOR_MESSAGES = [
        'required' => 'O campo é obrigatório.',
        'integer' => 'O valor tem que ser um inteiro.',
        'date_format' => 'A data tem de ser no formato Y-m-d\TH:i',
        'regex' => 'O valor tem que ter ponto ou virgula, 1 ou 2 casas decimais e pelo menos 1 à esquerda'
    ];

    private const INPUT_FIELDS = [
        'id_auction',
        'bidder',
        'value_bid',
    ];

    private const VALIDATOR_RULES = [
        'id_auction' => 'bail | required | integer',
        'bidder' => 'bail | required | integer',
        'value_bid' => 'regex:/(^\d+([.,]\d{1,2})?$)/ | required',
        'bidding_date' => 'bail |  date_format:Y-m-d\TH:i',
    ];

    private function validateCreateOrUpdateInput(Request $request)
    {
        $data = $request->only(self::INPUT_FIELDS);

        return Validator::make($data, self::VALIDATOR_RULES, self::VALIDATOR_MESSAGES);
    }

    /**
     * Store a newly created bid made by the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auction = $request->only('id_auction');

        if (empty($auction)) {
            return response()->json('Leilão não fornecido', 422);
        }

        $validator = $this->validateCreateOrUpdateInput($request);
        Log::channel('storeLog')->info('New bid'); 
        DB::beginTransaction();
        try {

                $data = $validator->getData();

                $auction = Auction::findOrFail($data['id_auction']);

                if ($data['value_bid'] <= $auction->final_value) return response()->json('Licitação tem de ser mais alta que a actual', 422);
               
                $data['id_bid'] = Bidding::max('id_bid') + 1;
                $data['bidding_date'] = Carbon::now()->format('Y-m-d H:i:s');

                if($data['bidding_date'] > $auction->date_end_auction)return response()->json('O Leilão já terminou', 422);


                $bidding = new Bidding;
                $new = $bidding->create($data);

                $bidder = User::find($data['bidder']);
                $data['bidguy'] = $bidder->username;

                $product = Product::findOrFail($data['id_auction']);
                
                $notification = new Notification; 
                $notification->id_user = $product->id_owner;
                $notification->is_new = true;
                $notification->text_notification = 'Tem uma licitação do user '.$bidder->username. ' no valor de '.$data['value_bid']  ;
                $notification->type_ofnotification = 'bid';
                $notification->id_item = $data['id_auction'];
                $notification->id_comment = null;
                $notification->save();


                DB::commit();
                return response()->json(array('bid' => $data), 200);
            
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::channel('storeLog')->error('Bid not  done');
        }

        return response()->json($validator->errors()->all(), 422);
    }
}