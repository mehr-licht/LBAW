@if ($message = Session::get('success'))
<div class="alert alert-success alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <i class="fa fa-check-circle">{{ $message }}</i>
</div>
@endif


@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <i class="fa fa-times-circle"> {{ $message }}</i>
</div>
@endif


@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <i class="fa fa-warning"> {{ $message }}</i>
</div>
@endif


@if ($message = Session::get('info'))
<div class="alert alert-info alert-block text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <i class="fa fa-info-circle"> {{ $message }}</i>
</div>
@endif


@if ($errors->any())
<div class="alert alert-danger text-center">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <i class="fa fa-edit"> Please check the form below for errors</i>
</div>
@endif

<!--contact-->
@if($message = Session::get('flash_message'))
<div class="alert alert-success alert-block text-center">
    <button type="button" class="close" data-dismiss="alert" id="formMsgGoodbye">×</button>
    <i class="fa fa-envelope-o"> {{ $message }}</i>
</div>
@endif