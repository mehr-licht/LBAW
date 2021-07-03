<a href="/report/{{ $report->id }}" class="list-group-item list-group-item-action">
    <div class="d-sm-flex w-100 justify-content-between">
    <h5><span class="badge badge-info">#{{$report->id}}</span></h5>
    <h5 class="mb-1">{{$report->reason}}</h5>
        <h5>
            @if($report->state_report == 'assume')
                <button class="btn btn-success btn-sm" 
                    type="button" 
                    style="width: 5rem" 
                    data-toggle="modal" 
                    data-target="#AssumeDen">Assumir</button>

            @elseif($report->state_report == 'assumed')
                <button class="btn btn-secondary btn-sm" 
                        type="button" 
                        style="width: 5rem" 
                        data-toggle="modal" 
                        data-target="#AssumeDen">Assumido</button>       
            @else
                <button class="btn btn-danger btn-sm" 
                        type="button" 
                        style="width: 5rem" 
                        data-toggle="modal" 
                        data-target="#AssumeDen">{{$report->state_report}}</button>       
            @endif

    </h5>
    </div>
    <hr class="mt-2" />
    <p class="mb-1 text-left">{{$report->text_report}}</p>
    <div class="d-flex w-100 mt-3 justify-content-between">
    <small>Denunciante: <strong>{{$report->userReporter->username}}</strong></small> <small>{{$report->date_report}}</small>
    </div>
</a>
