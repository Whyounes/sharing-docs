@extends('layouts.app')

@section('content')
<div class="container">
    <center>
    <img src="{{URL::asset('/Logo.png')}}" alt="box" height="100" width="150">
    <div class="row">
        
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                
                <div class="panel-heading" >
                    <h4>Dashboard</h4>
                </div> 

                <div class="panel-body">
                    <h4>  You are logged in!</h4>
                </div>  
            
            </div>
        </div>
    </div>
</div>
</center>
@endsection
