@extends('product::layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="twelve column">
                <h1>Products list</h1>
            </div>
        </div>
        <div class="row">
            <table class="u-full-width">
                <thead>
                <tr>
                    <th>Action</th>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Image</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Add</td>
                    <td>Dave Gamache</td>
                    <td>26</td>
                    <td>Male</td>
                    <td>San Francisco</td>
                </tr>
                <tr>
                    <td>Add</td>
                    <td>Dwayne Johnson</td>
                    <td>42</td>
                    <td>Male</td>
                    <td>Hayward</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
