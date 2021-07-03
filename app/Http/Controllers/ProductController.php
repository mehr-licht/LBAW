<?php

namespace App\Http\Controllers;

use App\ImageTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Report;
use App\Report_product;
use App\Report_comment;
use App\Comment;
use App\Product;
use App\Auction;
use App\Buyitnow;
use App\Bidding;
use App\User;


class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $productsBuyItNow = Product::take(10)->join('buyitnows', 'products.id', '=', 'buyitnows.id_buy')
                ->get();

            $productsAuctions = Product::take(10)->join('auctions', 'products.id', '=', 'auctions.id_auction')
                ->get();

            $productsArt = array();
            $productsComputers = array();
            $productsComics = array();

            foreach ($productsBuyItNow as $product) {
                switch ($product->category) {
                    case "comics":
                        array_push($productsComics, $product);

                        break;
                    case "computers":
                        array_push($productsComputers, $product);
                        break;
                    case "art":
                        array_push($productsArt, $product);
                        break;
                }
            }

            foreach ($productsAuctions as $product) {
                switch ($product->category) {
                    case "comics":
                        array_push($productsComics, $product);
                        break;
                    case "computers":
                        array_push($productsComputers, $product);
                        break;
                    case "art":
                        array_push($productsArt, $product);
                        break;
                }
            }
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }

        $dayOffers = array_merge($productsComics, $productsComputers);

        return view('pages.index', [
            'arts' => $productsArt,
            'computers' => $productsComputers,
            'comics' => $productsComics,
            'dayOffersPlaneOnes' => $dayOffers,
            'dayOffersPlaneTwos' => $productsAuctions,
        ]);
    }

    /**
     * Show a list of products.
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        try {
            $products = Product::with(['user', 'auction', 'buyitnow']);
            $productsPopulares = $products->active()->orderBy('date_placement', 'desc')->paginate(5, ['*'], 'populares');
            return view('pages.products', ['productsPopulares' => $productsPopulares]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }


    /**
     * Show a list of products.
     * @return \Illuminate\Http\Response
     */
    public function listFilter(Request $request)
    {
        try {

            $products = Product::with(['user', 'auction', 'buyitnow']);

            if($request->input('saleType') == 'Sell') {
                $products = $products->whereIn('id', function($query){
                    $query->select('id_buy')
                    ->from(with(new Buyitnow)->getTable());
                });
            }
            else if($request->input('saleType') == 'Auction') {
                $products = $products->whereIn('id', function($query){
                    $query->select('id_auction')
                    ->from(with(new Auction)->getTable());
                });
            }
            
            if($request->input('disponibility') == 'Active' || $request->input('disponibility') == 'all')
                $productsPopulares = $products->active();
            else if($request->input('disponibility') == 'Inactive')
                $productsPopulares = $products->inactive();
            else $productsPopulares = $products->bought();
            
            if($request->input('dataType') == 'today')
                $productsPopulares = $productsPopulares->where('date_placement', '>',Carbon::now()->subdays(1));
            else if($request->input('dataType') == 'week')
                $productsPopulares = $productsPopulares->where('date_placement', '>',Carbon::now()->subdays(7));
            else if($request->input('dataType') == 'dweek')
                $productsPopulares = $productsPopulares->where('date_placement', '>',Carbon::now()->subdays(14));
            else if($request->input('dataType') == 'month')
                $productsPopulares = $productsPopulares->where('date_placement', '>',Carbon::now()->subdays(30));

            $productsPopulares = $productsPopulares->orderBy('date_placement', 'desc')->paginate(5, ['*'], 'populares');
            
            return view('pages.products', ['productsPopulares' => $productsPopulares,
            'disponibility' => $request->input('disponibility'),
            'saleType' => $request->input('saleType'),
            'dataType' => $request->input('dataType'),
            'priceType' => $request->input('priceType')]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * List of most recent products
     * @return \Illuminate\Http\Response
     */
    public function recent()
    {
        try {
            $products = Product::with(['user', 'auction', 'buyitnow']);
            $productsRecentes = $products->active()->orderBy('id', 'desc')->paginate(5, ['*'], 'recentes');
            return view('pages.productsRecent', ['productsRecentes' => $productsRecentes]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * List of ending products
     */
    public function ending()
    {
        try {
            $productsEnding = Product::take(15)->join('auctions', 'products.id', '=', 'auctions.id_auction')->active()->orderBy('date_end_auction')->paginate(5, ['*'], 'aacabar');
            return view('pages.productsEnding', ['productsEnding' => $productsEnding]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * List of expensive products
     */
    public function expensive()
    {
        try {
            $productsExpensive = Product::take(10)->join('buyitnows', 'products.id', '=', 'buyitnows.id_buy')->active()->orderBy('final_value', 'desc')->paginate(5, ['*'], 'caros');
            return view('pages.productsExpensive', ['productsExpensive' => $productsExpensive]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth::check())
            return redirect('/login')->with('warning', 'Tem de estar autenticado');
        return view('pages.productForm')->with('success', 'Produto criado');
    }

    /**
     * Store a newly created Prodcut in table Products, buyitnow or auction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product;
        //$this->authorize('create', $product);

        Log::channel('storeLog')->info('validation new product info');
        $validatedData = $request->validate([
            'nameProduct' => 'bail|required|min:3|max:255',
            'category' => 'bail|required|alpha',
            'photo' => 'bail|required|image',
            'description' => 'bail|required|regex:/[A-Za-z\- ]+/i|max:1000'
        ]);

        $product->id_owner = Auth::id();
        $product->name_product = $request->input('nameProduct');
        $product->description = $request->input('description');


        if (is_null($request->input('isNew'))) {
            $product->is_new = false;
        } else {
            $product->is_new = true;
        }

        $product->category = $request->input('category');

        //Category validaton
        switch ($request->input('category')) {
            case 'houseGarden':
                $product->category = 'house_garden';
                break;
            case 'musicalInstruments':
                $product->category = 'musical_instruments';
                break;
            case 'memorabiliaPortugal':
                $product->category = 'memorabilia_portugal';
                break;
            case 'clothingAndAccessories':
                $product->category = 'clothing_and_accessories';
                break;
            case 'healthBeauty':
                $product->category = 'health_beauty';
                break;
            case 'videoGames':
                $product->category = 'video_games';
                break;
            default:
                $product->category = $request->input('category');
        }

        $product->state_product = 'active';

        $product->photo = $product->validateAndSave($request);

        $product->date_placement = Carbon::now();
        Log::channel('storeLog')->info('new product info validated');
        Log::channel('storeLog')->info('beginnig transaction');

        DB::beginTransaction();
        try {
            $product->save();
            Log::channel('storeLog')->info('product ' . $product::max('id') . ' saved');
            if (null !== ($request->input('biddingBase')) && null !== ($request->input('dateEndAuction'))) {
                Log::channel('storeLog')->info('validating auction ' . $product::max('id') . ' info');
                $validatedData = $request->validate([
                    'dateEndAuction' => 'bail|date|after:tomorrow',
                    'hourEndAuction' => 'nullable',
                    'biddingBase' => 'bail|required|numeric',
                ]);

                //insercao na base de dados Auctions
                $auction_array = array(
                    'id_auction' => $product::max('id'),
                    'date_end_auction' => $request->input('dateEndAuction'),
                    'bidding_base' => $request->input('biddingBase'),
                    'final_value' => $request->input('biddingBase')
                );
                Log::channel('storeLog')->info('commiting auction ' . $product::max('id'));
                $auction = new Auction;
                $auction->insert($auction_array);
                DB::commit();
                Log::channel('storeLog')->info('auction ' . $product::max('id') . ' commited');
                return redirect('products/' . $product::max('id'))->with('success', 'Leilão adicionado com sucesso!');
            } elseif (null !== ($request->input('finalValue'))  &&  null !== ($request->input('dateEnd'))) {
                Log::channel('storeLog')->info('validating buyitnow ' . $product::max('id') . ' info');
                //validacao dos parametros para venda directa
                $validatedData = $request->validate([
                    'finalValue' => 'bail|required|numeric',
                    'dateEnd' => 'bail|required|date|after:tomorrow',
                ]);
                //insercao na base de dados buyItNow
                $buy_array = array(
                    'id_buy' => $product::max('id'),
                    'date_end' => $request->input('dateEnd'),
                    'final_value' => $request->input('finalValue')
                );
                Log::channel('storeLog')->info('commiting buyitnow ' . $product::max('id'));
                $buy_it_now = new Buyitnow;
                $buy_it_now->insert($buy_array);
                DB::commit();
                Log::channel('storeLog')->info('buyitnow ' . $product::max('id') . ' commited');
                return redirect('products/' . $product::max('id'))->with('success', 'Venda adicionada com sucesso!');
            } else {
                Log::channel('storeLog')->critical('Checks on final value and date end');
                return view('errors.404', [], 404);
            }
        } catch (ModelNotFoundException $err) {
            DB::rollBack();
            Log::channel('storeLog')->error('Produt NOT stored. Error on ' . $err->getModel());
            return response(null, 404);
        }
        Log::channel('storeLog')->error('Skipped try/catch block');
        return redirect('products/')->with('error', 'Produto nao adicionado!');
    }

    /**
     * Returns the view of the specified product
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::channel('storeLog')->info('Showing product ' . $id);
        try {
            $product = Product::findOrFail($id);
            $owner = User::findOrFail($product->id_owner);
        } catch (Exception $e) {
            Log::channel('storeLog')->error('error showing product ' . $id);
            return response()->view('errors.422', ['product' => $product->id]);
        }

        if ($id === 0 or !$product)
            return response()->view('errors.422', ['product' => $product->id]);

        if ($product->state_product == 'removed' || $product->state_product == 'cancelled')
            return response()->view('errors.422', ['product' => $product->id, 'status' => $product->state_product]);

        $comments = $product->findOrFail($id)->comments;
        $commenters = array();
        for ($i = 0; $i < count($comments); $i++) {
            $commenters[$i] = [(User::find($comments[$i]->id_commenter))->username, (User::find($comments[$i]->id_commenter))->photo];
            $comments[$i]['username'] = (User::find($comments[$i]->id_commenter))->username;
            $comments[$i]['photo'] = (User::find($comments[$i]->id_commenter))->photo;
        }

        $buyit = $product->buyitnow;

        $auction = $product->auction;
        $bidguys = array();
        if ($auction) {
            $bids = Bidding::where('id_auction', $id)->get();
            for ($i = 0; $i < count($bids); $i++) {
                $text = "*******" . substr((User::find($bids[$i]->bidder))->username, -2, 2);
                $bids[$i]['bidguy'] = $text;
            }

            return view('pages.product', ['product' => $product, 'auction' => $auction, 'biddings' => $bids, 'comments' => $comments, 'user' => $owner, 'commenters' => $commenters, 'bidguys' => $bidguys]);
        }

        return view('pages.product', ['product' => $product, 'buyitnow' => $buyit, 'comments' => $comments, 'user' => $owner, 'commenters' => $commenters, 'bidguys' => $bidguys]);
    }

    /**
     * Get comment of comments
     * @param int  $id_product
     * @param int $id_comment
     * @return \Illuminate\Http\Response
     */
    public function getComments($id_product, $id_comment = null)
    {
        try {
            if ($id_product === null) {
                return response()->json('falta id do produto', 400);
            }

            if ($id_product === 0 or $id_comment === 0) {
                return response()->json('Por favor forneça um número inteiro válido', 422);
            }

            $comments = Product::findOrFail($id_product)->comments;
            if ($id_comment === null) {
                return response()->json(array('comments' => $comments), 200);
            }

            if ($id_comment > sizeof($comments)) {
                return response()->json('Não existe nenhum comentário com esse numero', 422);
            }
            return response()->json(array('comment' => $comments[$id_comment - 1]), 200);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Report a product
     * @param int  $id_product
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function report($id_product, Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect('login');
            }

            $product = Product::findOrFail($id_product);
            $this->authorize('report', $product);

            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|regex:/[A-Za-z0-9\-\.\_ ]*/i|min:1|max:50',
                'textReport' => 'required|string|regex:/[A-Za-z0-9\-\.\_ ]*/i|min:1|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('error', 'Ocorreram erros ao tentar denunciar este produto, por favor tente de novo...');
            }

            $report = new Report;
            $report->id_admin = null;
            $report->id_punished = $product->id_owner;
            $report->consequence = null;
            $report->state_report = 'assume';
            $report->observation_admin = null;
            $report->date_report = Carbon::now();
            $report->reason = $request->input('reason');
            $report->text_report = $request->input('textReport');
            $report->date_begin_punishement = null;
            $report->punishement_span = 0;
            $report->id_reporter = Auth::id();

            $report->save();

            $rep_user = new Report_product;
            $rep_user->id_report = $report->id;
            $rep_user->id_product = $id_product;
            $rep_user->save();

            $message = "Produto denunciado com sucesso!";

            return redirect()->back()->with('success', $message);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Report a product comment
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reportComment($id_comment, Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect('login');
            }

            $comment = Comment::findOrFail($id_comment);
            if (Auth::id() === $comment->id_commenter)
                return redirect()->back()->with('error', 'Não pode denunciar-se a si próprio...');

            $validator = Validator::make($request->all(), [
                'reason' => 'required|string|regex:/[A-Za-z0-9\-\.\_ ]*/i|min:1|max:50',
                'textReport' => 'required|string|regex:/[A-Za-z0-9\-\.\_ ]*/i|min:1|max:500',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->with('error', 'Existem erros ao tentar reportar um commentário, por favor tente novamente...');
            }

            $report = new Report;
            $report->id_admin = null;
            $report->id_punished = $comment->id_commenter;
            $report->consequence = null;
            $report->state_report = 'assume';
            $report->observation_admin = null;
            $report->date_report = Carbon::now();
            $report->reason = $request->input('reason');
            $report->text_report = $request->input('textReport');
            $report->date_begin_punishement = null;
            $report->punishement_span = 0;
            $report->id_reporter = Auth::id();

            $report->save();

            $rep_comment = new Report_comment;
            $rep_comment->id_report = $report->id;
            $rep_comment->id_comment = $comment->id_comment;
            $rep_comment->save();

            $message = "Comentário reportado com sucesso!";

            return redirect()->back()->with('success', $message);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Get all bids from a product id
     * @param int $id_product
     * @return \Illuminate\Http\Response
     */
    public function getBids($id_product)
    {
        try {
            $bids = Product::findOrFail($id_product)->auction->bids;
            return response()->json(array('bids' => $bids), 200);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id of a product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        if (!Auth::check()) {
            return redirect('login');
        }

        try {
            $product = Product::findOrFail($id);
            $this->authorize('show', $product);

            $owner = User::findOrFail($product->id_owner);

            if ($id === 0 or !$product or $product->state_product === 'inactive' or $product->state_product === 'cancelled' or $product->state_product === 'removed') {
                return response()->view('errors.422', ['product' => $product->id]);
            }

            $buyit = $product->buyitnow;

            $auction = $product->auction;
            $bidguys = array();
            if ($auction) {
                $bids = Bidding::where('id_auction', $id)->get();
                for ($i = 0; $i < count($bids); $i++) {
                    $text = str_repeat("*", strlen((User::find($bids[$i]->bidder))->username) - 2);
                    $text = $text . substr((User::find($bids[$i]->bidder))->username, -2, 2);
                    // array_push($bidguys,$text);
                    $bids[$i]['bidguy'] = $text;
                }

                return view('pages.product', ['product' => $product, 'auction' => $auction, 'biddings' => $bids, 'user' => $owner, 'bidguys' => $bidguys, 'editing' => true]);
            }

            return view('pages.product', ['product' => $product, 'buyitnow' => $buyit, 'user' => $owner, 'bidguys' => $bidguys, 'editing' => true]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id of a product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $this->authorize('show', $product);
        Log::channel('storeLog')->info('validation edit product info');

        $validator = Validator::make($request->all(), [
            'isNew' => 'nullable|in:true,false',
            'photo' => 'image',
            'description' => 'required|max:5000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Existem erros quando tenta editar o produto, por favor tente novamente...');
        }

        if (!is_null($request->input('isNew')))
            $product->is_new = $request->input('isNew');
        $product->description = $request->input('description');
        if ($request->file('photo'))
            $product->photo = $product->validateAndSave($request);

        Log::channel('storeLog')->info('new product info validated');
        try {
            $product->save();
        } catch (ModelNotFoundException $err) {
            Log::channel('storeLog')->error('Product NOT edited');
            return response(null, 404);
        }
        $redirect = 'products/' . $id;
        return redirect($redirect)->with('success', 'Produto editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Não apagamos. O Administrador remove e o owner cancela -> remove()
        try {
            $product = Product::findOrFail($id);
            $this->authorize('show', $product);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Removes a product after the administrator marked it to be removed
     */
    public function remove($id)
    {
        Log::channel('storeLog')->info('Removing product ' . $id);
        try {
            $product = Product::findOrFail($id);
            $product->state_product = 'removed';
            $product->save();
            Log::channel('storeLog')->info('product ' . $id . ' removed');
            return redirect()->route('products')->with('success', 'Produto removido', 200);
        } catch (Exception $e) {
            Log::channel('storeLog')->error('Error removing product ' . $id);
            return back()->with('error', 'Não foi possível remover o produto');
        }
    }


    /**
     * Cancels a product at its owner's request
     */
    public function cancel($id)
    {
        Log::channel('storeLog')->info('Canceling product ' . $id);
        try {
            $product = Product::findOrFail($id);
            $product->state_product = 'cancelled';
            $product->save();
            Log::channel('storeLog')->info('product ' . $id . ' cancelled');
            return redirect()->route('products')->with('success', 'Produto cancelado', 200);
        } catch (ModelNotFoundException $err) {
            Log::channel('storeLog')->error('Error cancelling product ' . $id);
            return back()->with('error', 'Não foi possível cancelar o produto');
        }
    }


    /**
     * Search for products according to a possible filtering by category
     * Uses the FTS trait to search both in the product (weight A) title as in the product description (weight B)
     */
    public function search(Request $request)
    {
        Log::channel('storeLog')->info('searching for ' . $request->search . ' and category ' . $request->category);
        if ($request->search !== "") {
            $category = $request->category;
            try {
                $products = Product::category($category)->active()->FTS($request->search)->paginate(10);
                if (is_null($products)) return response()->json('A sua pesquisa não devolveu quaisquer resultados', 403);

                $productsPopulares = $products;
                Log::channel('storeLog')->info('search for ' . $request->search . ' and category ' . $request->category . ' produced results');
                return view('pages.products', ['products' => $products, 'productsPopulares' => $productsPopulares, 'search' => $request->input('search')])->with('success', 'Produto cancelado', 200);
            } catch (Exception $e) {
                Log::channel('storeLog')->error('error searching for the pattern submitted');
                return back()->with('error', 'Não foi possível efetuar a pesquisa');
            }
        } else {
            Log::channel('storeLog')->info('no pattern submitted for product search');
            return back()->with('error', 'Não enviou qualquer pesquisa');
        }
    }

    /**
     * Get available categories
     * @return \Illuminate\Http\Response
     */
    public function getEnum()
    {
        try {
            return response()->json((object) \DB::SELECT('select enum_range(NULL::category_type) as categories')[0]);
        } catch (ModelNotFoundException $e) {
            Log::channel('storeLog')->error('On model ' . $e->getModel());
        }
    }

    /**
     * Verifies if any auction has ended in the last minute (the scheduler runs every minute and calls this method)
     * And marks every ended auction as inactive
     */
    public function hasAuctionEnded()
    {
        Log::channel('storeLog')->info('verifying if any auction has ended');
        $endedAuctions = Auction::where('date_end_auction', '<', Carbon::now())->get();
        if ($endedAuctions) {
            Log::channel('storeLog')->info(sizeof($endedAuctions) . ' ended auctions found');
            $i = 0;
            foreach ($endedAuctions as $endedAuction) {
                Log::channel('storeLog')->info('auction ' . $endedAuction->id_auction . ' has already ended');

                Log::channel('storeLog')->info('trying to inactivate auction ' . $endedAuction->id_auction . ' after auction ended');
                try {
                    $product = Product::findOrFail($endedAuction->id_auction);
                    $product->state_product = 'inactive';
                    $product->save();
                    $i++;
                    Log::channel('storeLog')->info('auction ' . $endedAuction->id_auction . ' marked as inactive after auction ended');
                } catch (Exception $e) {
                    Log::channel('storeLog')->error('Error ' . $e . 'on ended auction ' . $endedAuction->id_auction);
                }

                Log::channel('storeLog')->info('auction ' . $endedAuction->id_auction . ' has been inactivated');
            }
            Log::channel('storeLog')->info($i . ' of ' . sizeof($endedAuctions) . ' ended auctions have been inactivated');
        }
        return;
    }
}