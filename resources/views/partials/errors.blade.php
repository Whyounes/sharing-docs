<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if (session('messages') && count(session('messages')) > 0)
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    @if (count(session('messages')) == 1)
                        {{ session('messages')[0] }}
                    @else
                        <ul>
                            @foreach (session('messages') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            @if (count($errors) > 0)
                <!-- Form Error List -->
                <div class="alert alert-danger ">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>{{ trans('errors.whoops') }}</strong>

                    <br>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>