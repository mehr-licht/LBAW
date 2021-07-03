<tr class="userSearch-info" data-id-user="{{ $users->id }}" data-id-username="{{ $users->username }}">

    <th scope="row">{{ $users->id }}</th>
    <td><a href="/users/{{ $users->id }}">{{ $users->username }}</a></td>
    <td>{{ $users->email }}</td>
    <td><button class="btn btn-white btn-sm searchBanBtn" type="button" data-toggle="modal" data-target="#BanModel">
        <i class="fa fa-ban"></i></button>
    </td>

</tr>

<!-- Modals -->
<!-- Ban Modal -->
<div class="modal fade" id="BanModel" tabindex="-1" role="dialog" aria-labelledby="BanModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="BanModelLabel">Banir {{ $users->username }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ url('/admin/bans/member/' . $users->id) }}"> 
                @csrf
                @method('PUT')
                <input type="hidden" name="consequence" value="ban">

                <div class="modal-body text-left">
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
                        <label for="id_product">Id Produto (opcional)</label>
                        <input type="number" class="form-control" id="id_product" name="id_product" min="1">
                    </div>
                    <div class="form-group" id="punishement_spanForm" required>
                        <label for="punishement_span">Dias de ban *</label>
                        <input type="number" class="form-control" id="punishement_span" name="punishement_span" min="1" required>
                    </div>
                    <div class="form-group" id="banReasonTextForm" required>
                        <label for="banReasonText">Razão *</label>
                        <textarea class="form-control" id="banReasonText" name="observation_admin" rows="3" placeholder="Razões para consequência..." maxlength="1000" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button id="consequenceSearchConfirmation" type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
