@extends('product::layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="twelve column">
                <h1>Products list</h1>
            </div>
        </div>
        <div class="row">
            <div class="six columns">
                <label for="exampleEmailInput">Search:</label>
                <input class="u-full-width" type="text" placeholder="Product name" id="search">
            </div>
        </div>
        <div class="row inner">
            <div id="pagination"></div>
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


                </tbody>
            </table>

        </div>
    </div>
    <script>
        $(document).ready(function(){
            let products = {!! json_encode($items,JSON_HEX_TAG) !!};

            $.each(products, function(index, value){
                $("tbody").append(
                    `<tr> ` +
                    `<td> <button class='add-to-db' id=${value['id']}> Add </button> </td>` +
                    `<td> ${value['id']} </td>` +
                    `<td> ${value['name']} </td>` +
                    `<td> ${value['categories']} </td>` +
                    `<td> <img src="${value['image']}"> </td>` +
                    `</tr>`)
            })

            $(".add-to-db").on('click', function () {
                let id = $(this).attr("id")
                let product = products.filter(x => x.id === id)[0]

                $.post( "{{ route('api.product.store') }}", product)
                    .done(function( data ) {
                        if(data.success) {
                            alert('Successfully saved to the base')
                        } else {
                            alert('Error occurred while saving!')
                        }
                    });
            })

            let perPage = 3;
            let items = $("tbody tr");

            let numItems = items.length;

            items.slice(perPage).hide();

            $("#pagination").pagination({
                items: numItems,
                itemsOnPage: perPage,
                cssStyle: "light-theme",

                // This is the actual page changing functionality.
                onPageClick: function(pageNumber) {
                    // We need to show and hide `tr`s appropriately.
                    var showFrom = perPage * (pageNumber - 1);
                    var showTo = showFrom + perPage;

                    // We'll first hide everything...
                    items.hide()
                        // ... and then only show the appropriate rows.
                        .slice(showFrom, showTo).show();
                }
            });

            $("#search").on('keyup', function () {
                let value = $(this).val().toLowerCase()
                $("tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
                if($(this).val() === '') {
                    $("tbody tr").slice(perPage).hide();
                }
            })
        });
    </script>

@endsection
