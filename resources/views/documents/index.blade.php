@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        Documents

                        <a href="{{ route('documents.create') }}" class="btn btn-primary pull-right">
                            Upload new document
                        </a>
                    </div>

                    <div class="panel-body">
                        @if($documents->count() > 0)
                            <ul class="list-group">
                                @foreach($documents as $document)
                                    <li class="list-group-item">
                                        {{ $document->name }} - {{ $document->mime_type }}
                                        
                                        <form class="pull-right" action="{{ route("documents.destroy", $document) }}" method="post">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}

                                            <input type="submit" class="btn btn-danger" value="Delete"/>
                                        </form>
                                        &nbsp;
                                        <a class="btn btn-primary pull-right" href="{{ route('documents.show', $document) }}">
                                            Details
                                        </a>
                                        &nbsp;
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <h2>You did not upload any documents yet!</h2>
                        @endif

                        @if($sharedDocuments->count() > 0)
                        <hr/>
                            <h4>Documents shared with me</h4>
                            <ul class="list-group">
                                @foreach($sharedDocuments as $share)
                                    <li class="list-group-item">
                                        {{ $share->document->name }} - {{ $share->document->mime_type }}

                                        <a class="btn btn-primary pull-right"
                                           href="{{ route('documents.show', $share->document) }}">
                                            Details
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <h2>No documents are shared with you yet!</h2>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection