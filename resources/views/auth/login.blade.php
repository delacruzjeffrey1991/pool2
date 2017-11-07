@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Login</div>
        <div class="panel-body">


          <form class="form-horizontal" action="index.html" method="post">

            <div class="form-group">
              <label for="email" class="col-md-4 control-label"> E-mail Adress</label>
              <div class="col-md-6">
                <input class="form-control" type="'text'" name="email">
              </div>
            </div>

            <div class="form-group">
              <label for="password" class="col-md-4 control-label"> Password</label>
              <div class="col-md-6">
                <input class="form-control" type="'text'" name="password">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-8 col-md-offset-4">
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="remember">Remember Me
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-8 col-md-offset-4">
                <button type="submit" class="btn btn-primary">Login
                </button>

                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                  <a class="btn btn-link">Forgot Your Password?
                  </a>

                </div>
              </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
  @endsection
