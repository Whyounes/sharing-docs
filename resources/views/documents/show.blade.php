@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Document - {{ $document->name }}
                    </div>

                    <div class="panel-body">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <a href="{{ Storage::url($document->path) }}" target="_blank">Download</a>
                            </li>

                            <li class="list-group-item">
                                {{ human_filesize($document->size) }}
                            </li>

                            <li class="list-group-item">
                                {{ $document->mime_type }}
                            </li>
                        </ul>

                        <hr>

                        @if($document->isOwner())
                            @if($document->shares->count() > 0)
                                <h4>Shares</h4>

                                <ul class="list-group">
                                    @foreach($document->shares as $share)
                                        @if($share->user)
                                            <li class="list-group-item">
                                                <form action="{{ route('documents.share.destroy', ['document' => $document, 'share' => $share]) }}"
                                                      method="post">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}

                                                    <input type="submit" class="btn btn-danger" value="Stop sharing"/>
                                                </form>
                                                {{ $share->user->first_name . " " . $share->user->last_name }}
                                                - {{ $share->user->email }}
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <h4>This document is not shared yet!</h4>
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('documents.share.store', $document) }}" method="post"
                                          class="form-inline">
                                        {{ csrf_field() }}

                                        <input type="email" name="user" class="form-control"
                                               placeholder="john@gmail.com"/>
                                        <input type="submit" class="btn btn-primary" value="Share"/>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection