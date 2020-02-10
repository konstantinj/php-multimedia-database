<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('//maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css') }}
        {{ stylesheet_link('//cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css') }}
        {{ stylesheet_link('//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css') }}
        {{ stylesheet_link('//bootstrap.themes.guide/greyson/theme.css') }}
        {{ stylesheet_link('//vjs.zencdn.net/7.3.0/video-js.min.css') }}
        {{ javascript_include('//code.jquery.com/jquery-3.3.1.js') }}
        {{ javascript_include('//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js') }}
        {{ javascript_include('//cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js') }}
        {{ javascript_include('//vjs.zencdn.net/7.3.0/video.min.js') }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style type="text/css">
            .container h1 {
                margin:30px 0;
            }
            .container {
                margin-top: 30px;
            }
        </style>
    </head>
    <body>
        {{ content() }}
    </body>
</html>
