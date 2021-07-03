<?php

namespace App\Http\Controllers;

use Session;
use App\Comment;
use App\User;
use App\Admin;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class CommentController extends Controller
{
    private const VALIDATOR_MESSAGES = [
        'required' => 'O campo é obrigatório.',
        'integer' => 'O valor tem que ser um inteiro.',
        'string' => 'A campo tem que ter texto.',
        'max' => 'O texto não pode ter mais de 255 carateres.',
    ];

    private const INPUT_FIELDS = [
        'id_commenter',
        'id',
        'date_comment',
        'msg_ofcomment',
        'comment_likes',
    ];

    private const VALIDATOR_RULES = [
        'id_commenter' => 'bail | required | integer',
        'id' => 'bail | required | integer',
        'date_comment' => 'bail |  date_format:Y-m-d\TH:i',
        'msg_ofcomment' => 'bail | required | string | max:255',
        'comment_likes' => 'bail | integer',
    ];

    private function validateCreateOrUpdateInput(Request $request)
    {
        $data = $request->only(self::INPUT_FIELDS);

        return Validator::make($data, self::VALIDATOR_RULES, self::VALIDATOR_MESSAGES);
    }

    /**
     * Store a newly created comment onthe product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateCreateOrUpdateInput($request);

        try{
            if ($validator->passes()) {

                $data = $validator->getData();
                $data['id_comment'] = Comment::max('id_comment') + 1;
                $comment = new Comment;


                if ($data['id_commenter'] > 0) {
                    $commenter = User::findOrFail($data['id_commenter']);
                } else {
                    $commenter = Admin::findOrFail(-$data['id_commenter']);
                    $data['id_commenter'] = -$data['id_commenter'];
                }
                $new = $comment->create($data);

                $data['username'] = $commenter->username;
                $data['photo'] = $commenter->photo;
                $data['date_comment'] = Carbon::now()->format('Y-m-d H:i:s');
                $data['comment_likes'] = 0;
                return response()->json(array('comment' => $data), 200);
            }

            return response()->json($validator->errors()->all(), 422);
        }catch(ModelNotFoundException $e){
            Log::channel('storeLog')->error('On model ' . $e->getModel() ) ;
        }    
    }

    /**
     * Delete a comment 
     *
     * @param  int  $id user id
     * @param int $id_comment 
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $id_comment)
    {
        try {
            $comment = Comment::findOrFail($id_comment);
            $comment->delete();
        } catch (ModelNotFoundException $err) {
            Log::channel('storeLog')->error('Error on remove comment'. $err->getModel());
            return response()->json("an error occurred", 422);
        }

        return response()->json("OK", 200);
    }

    /**
     * Add a like to a comment
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function putLike(Request $request)
    {
        $comment = Comment::findOrFail($request->id_comment);

        try {
            $new = $comment->update(['comment_likes' => $comment->comment_likes + 1]);
        } catch (ModelNotFoundException $err) {
            Log::channel('storeLog')->error('Error on  update likes comment');
        }
        return response()->json("OK", 200);
    }
}
