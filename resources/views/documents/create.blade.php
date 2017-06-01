@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        New Document
                    </div>

                    <div class="panel-body">
                        <ul class="list-group">
                            <form action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <p>
                                    Document Name:
                                    <input type="text" name="name" class="form-control"/>
                                </p>

                                <p>
                                    Select a Document:
                                    <input type="file" name="document"/>
                                </p>

                                <hr>
                                <p>
                                    <input type="submit" value="Upload" class="btn btn-primary"/>
                                </p>
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection