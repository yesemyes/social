@extends('layouts.admin')

@section('title')
    Home
@endsection

@section('page-header')
    Dashboard
@endsection

@section('page-content')
    
    @if( Session::has('message') )
    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <p>{{ Session::get('message') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Connected accounts
                </div>
                <div class="panel-body">
                    <p>Here will be list of connected accounts {{ Auth::user()->name }}</p>
                    <ul class="">
                        @foreach($userAccounts as $item)
                            @if(isset($item['userId']))
                            <li class="list-group-item">
                                <div class="media">
                                    <img class="pull-left hidden-xs b2s-img-network" alt="Twitter" src="http://localhost/blog-to-social/wp-content/plugins/blog2social/assets/images/portale/{{ $item['icon'] }}">
                                    <div class="media-body network">
                                        <h4>{{ $item['provider'] }}
                                            <span class="b2s-network-auth-count">(Connections <span class="">1</span>/1)</span>
                                            <span class="pull-right"><a href="#">+ Profile</a></span>
                                        </h4>
                                        <ul class="">
                                            <li class="">Profile: {{ $user->name }} <span class="">(My profile)</span>
                                                <a href="/{{ $item['provider'] }}/login" class="">
                                                    <span class="glyphicon  glyphicon-refresh glyphicon-grey"></span>
                                                </a>
                                                <a class="deleteAccount" data-id="{{ $item['userId'] }}" href="#">
                                                    <span class="glyphicon  glyphicon-trash glyphicon-grey"></span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @else
                            <li class="list-group-item">
                                <div class="media">
                                    <img class="pull-left hidden-xs b2s-img-network" alt="Twitter" src="http://localhost/blog-to-social/wp-content/plugins/blog2social/assets/images/portale/{{ $item['icon'] }}">
                                    <div class="media-body network">
                                        <h4>{{ $item['provider'] }}
                                            <span class="b2s-network-auth-count">(Connections <span class="">0</span>/1)</span>
                                            <span class="pull-right"><a href="/{{ $item['provider'] }}/login">+ Profile</a></span>
                                        </h4>
                                    </div>
                                </div>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                    <a href="/posts">fb post</a>
                </div>
            </div>
        </div>
    </div>
@endsection

