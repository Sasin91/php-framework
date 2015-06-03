<link rel="stylesheet" href="/css/login.css">
<link rel="stylesheet" href="/css/dashboard.css">
<link rel="stylesheet" href="/css/dashboard_shop.css">
<div class="grid grid-pad">
    <ul class="breadcrumb">
        <?php

        $array = app::currentUrl(false);
        array_shift($array);
        foreach ($array as $url) {
            if($url == app::currentUrl(true, true))
            {
                ?>
                <li class="active"><?php echo $url ?></li>
            <?php
            } else {
                $base = app::baseUrl();
                if($url != 'dashboard')
                {
                    $path = $base.DS.'dashboard'.DS.$url;
                } else {
                    $path = $base.DS.$url;
                }
                ?>
                <li><a href="<?php echo $path ?>"><?php echo $url ?></a> <span class="divider">/</span></li>
            <?php
            }
        }

        ?>
    </ul>
    <div class="col-1-1">
        <h1>Orders</h1>
        <input type="search" class="slide purple-slide table-search" data-table="Orders" placeholder="Filtrer" />
        <script>
            (function(document) {
                'use strict';

                var LightTableFilter = (function(Arr) {

                    var _input;

                    function _onInputEvent(e) {
                        _input = e.target;
                        var tables = document.getElementsByClassName(_input.getAttribute('data-table'));
                        Arr.forEach.call(tables, function(table) {
                            Arr.forEach.call(table.tBodies, function(tbody) {
                                Arr.forEach.call(tbody.rows, _filter);
                            });
                        });
                    }

                    function _filter(row) {
                        var text = row.textContent.toLowerCase(), val = _input.value.toLowerCase();
                        row.style.display = text.indexOf(val) === -1 ? 'none' : 'table-row';
                    }

                    return {
                        init: function() {
                            var inputs = document.getElementsByClassName('table-search');
                            Arr.forEach.call(inputs, function(input) {
                                input.oninput = _onInputEvent;
                            });
                        }
                    };
                })(Array.prototype);

                document.addEventListener('readystatechange', function() {
                    if (document.readyState === 'complete') {
                        LightTableFilter.init();
                    }
                });

            })(document);
        </script>

        <!-- Borrowed from http://codepen.io/ashblue/pen/mCtuA, i take no credit for it. -->
        <div id="table" class="table-editable">
            <span class="table-add pull-left fa fa-plus"></span>
            <table class="Orders">
                <thead>
                <tr>
                    <?php
                    $Orders = $this->content['data'];
                    echo "<th>" . implode('</th> <th>',  array_keys($Orders[0])) . "</th>";
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                for($i = 0; $i < count(array_keys($Orders)); $i++)
                {
                    ?>
                    <tr>
                        <?php
                        foreach($Orders[$i] as $key => $order) {
                            ?>
                            <td contenteditable="true"><?php echo $order ?></td>
                        <?php
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
                <td>
                    <span class="table-remove fa fa-remove"></span>
                </td>
                <td>
                    <span class="table-up fa fa-arrow-up"></span>
                    <span class="table-down fa fa-arrow-down"></span>
                </td>
                <!-- This is our clonable table line -->
                <tr class="hide">
                    <?php for($i = 0; $i < count(array_keys($Orders)); $i++)
                    {
                        ?> <td contenteditable="true">Placeholder</td> <?php
                    }
                    ?>
                    <td>
                        <span class="table-remove fa fa-remove"></span>
                    </td>
                    <td>
                        <span class="table-up fa fa-arrow-up"></span>
                        <span class="table-down fa fa-arrow-down"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <button id="export-btn" class="button blue">Show data</button>
        <button id="upload-btn" class="button green">Upload data</button>
        <pre class="prettyprint hide" id="export"></pre>
        <form id="postform" class="hide" action="<?php echo app::currentUrl(false, true) ?>Orders/modify" method="post">

        </form>
        <script>
            var $TABLE = $('#table');
            var $BTN = $('#export-btn');
            var $UPL = $('#upload-btn');
            var $EXPORT = $('#export');
            var $FORM = $('#postform');

            $('.table-add').click(function () {
                var $clone = $TABLE.find('tr.hide').clone(true).removeClass('hide table-line');
                $TABLE.find('table').append($clone);
            });

            $('.table-remove').click(function () {
                $(this).parents('tr').detach();
            });

            $('.table-up').click(function () {
                var $row = $(this).parents('tr');
                if ($row.index() === 1) return; // Don't go above the header
                $row.prev().before($row.get(0));
            });

            $('.table-down').click(function () {
                var $row = $(this).parents('tr');
                $row.next().after($row.get(0));
            });

            // A few jQuery helpers for exporting only
            jQuery.fn.pop = [].pop;
            jQuery.fn.shift = [].shift;

            $BTN.click(function () {
                $EXPORT.removeClass('hide');

                // Output the result
                $EXPORT.text(JSON.stringify(makeDataArray));
            });

            $UPL.click(function() {
                makeDataForm(makeDataArray());
            });

            function makeDataForm(data)
            {
                var items = [];
                if(Object.keys(data).length > 1)
                {
                    $.each(data, function(index, obj){
                        $.each(obj, function(key, val){
                            items.push(
                                "<span><label class='label'>"+ key +"</label>",
                                "<input name='" + key + "'class='slide' value='"+ val +"'></input></span><br>"
                            );

                        });
                    });

                } else {
                    $.each(data, function(key, val){
                        items.push(
                            "<span><label class='label'>"+ key +"</label>",
                            "<input name='" + key + "'class='slide' value='"+ val +"'></input></span><br>"
                        );

                    });
                }

                $( "<div/>", {
                    "class": "data",
                    html: items.join( "" )
                }).appendTo( $FORM );
                $($FORM).submit();

            }

            function makeDataArray()
            {
                var $rows = $TABLE.find('tr:not(:hidden)');
                var headers = [];
                var data = [];

                // Get thcollectione headers (add special header logic here)
                $($rows.shift()).find('th:not(:empty)').each(function () {
                    headers.push($(this).text().toLowerCase());
                });

                // Turn all existing rows into a loopable array
                $rows.each(function () {
                    var $td = $(this).find('td');
                    var h = {};

                    // Use the headers from earlier to name our hash keys
                    headers.forEach(function (header, i) {
                        h[header] = $td.eq(i).text();
                    });

                    data.push(h);

                });
                return data;

            }
        </script>
    </div>
</div>