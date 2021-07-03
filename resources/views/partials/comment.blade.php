<!DOCTYPE html>
@section('scripts')
<script src="{{ URL::asset ('/js/api.js') }}" defer></script>
@stop

<article class="comment" data-id="{{ $comment->id_comment }}">

    <li class="media">
        <a href="{{route('showuser', ['id' => $comment->id_commenter])}}" class="pull-left">
            <img src="{{$comment->photo}}" width="64px" height="64px" alt="Foto de perfil" class="img-circle mr-2">
        </a>
        
        <div class="media-body">
            <div class="imgAbt pull-right">
                @if(Auth::id() !== $comment->id_commenter)
                <button type="button" class="btn btn-lg" data-toggle="modal" data-target="#reportComment" data-id="{{ $comment->id_comment }}">
                    ...
                </button>
                @endif
            </div>
            <div>
             <a href="{{route('showuser', ['id' => $comment->id_commenter])}}">{{$comment->username}}</a>
            <span class="text-muted pull-right">
                <small class="text-muted commentDates" id="commentDate">{{ $comment->date_comment }}</small>
                <!-- {{ date("Y/m/d H:i:s",strtotime($comment->date_comment)) }} -->
            </span>
            </div>
            <p>
                {{$comment->msg_ofcomment}}
                <span class="text-muted pull-right">
                @if (Auth::guard('admin')->check())
                    <button style="background-color:#ffffff;border:0">
                    <img src="../trashcan.svg" style="width:20px;" class="remove-comment" alt="remove comment" id="{{ $comment->id_comment }}" product="{{ $product->id }}"
                    comment="{{ $comment->id_comment }}" liker="{{Auth::id()}}" />
                    </button>
                @elseif(Auth::check()) 
                    @can('delete',$comment)
                        <button style="background-color:#ffffff;border:0">
                            <img src="../trashcan.svg" style="width:20px;" class="remove-comment" alt="remove comment" id="{{ $comment->id_comment }}" product="{{ $product->id }}"
                                comment="{{ $comment->id_comment }}" liker="{{Auth::id()}}" />
                        </button>
                    @endcan
                    @cannot('delete',$comment)
                        <button style="background-color:#ffffff;border:0">
                        <img src="../like.svg" style="width:20px;" class="put-like" id="putLike" product="{{ $product->id }}"
                        comment="{{ $comment->id_comment }}" liker="{{Auth::id()}}" alt="like comment"/>
                        </button>
                    @endcan
                @endif
                    <small class="text-muted" id="numberlikes">{{ $comment->comment_likes }}<i>likes</i></small>
                </span>

            </p>
        </div> 
        <div>

        </div>
        
    </li>
  
</article>

<div class="modal fade" id="reportComment" tabindex="-1" role="dialog" aria-labelledby="reportCommentModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportCommentModelLabel">Denúncia de Comentário:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ url('/products/comments/report/' . $comment->id_comment) }}"> 
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="title ${{ $comment->id_comment }}" name="reason"
                                placeholder="título da denúncia" required pattern="[a-zA-Z0-9\-\.\_]*" maxlength="50">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-2">
                            <textarea class="form-control" name="textReport" placeholder="Explicitar Denúncia." required
                                pattern="[a-zA-Z0-9\-\.\_]*" maxlength="500"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="confirm {{ $comment->id_comment }}" type="submit" class="btn btn-primary reportConfirmation">Submeter</button>
                </div>
            </form>
        </div>
    </div>
</div>
