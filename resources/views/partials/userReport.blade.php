<tr id="user-info" data-id-report="{{$usersReported->id_report}}" data-id-user="{{ $usersReported->user->id }}">    
    <td>{{ $usersReported->report->date_report }}
    </td>
    
    <td>
        <a href="/user/{{ $usersReported->user->id }}">{{ $usersReported->user->name }}</a>
    </td>
    
    <td colspan="3">Banido por {{ $usersReported->report->punishement_span }} dias
    </td>
    
    <td id="baningUserId" data-id-report="{{$usersReported->id_report}}" colspan="3">
        <a href="/report/{{ $usersReported->id_report }}"> #{{$usersReported->id_report}}</a> 
    </td>
    
    <td width="5px">
        <button id="deleteban" class="btn btn-white" type="button" data-toggle="modal" data-target="#DeleteBan">
            <i id="trash-delete-ban" class="fa fa-trash" style="font-size:20px;"></i>
        </button> 
    </td>
</tr>
