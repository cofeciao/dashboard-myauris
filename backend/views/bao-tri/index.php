<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27-Mar-19
 * Time: 8:53 AM
 */

?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Bảo trì</title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    <style>
        html {
            height: 100%;
        }
        body {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -webkit-justify-content: center;
            -ms-flex-pack: center;
            justify-content: center;
            height: 100%;
        }
        .page-error-box {
            background: #fff;
            border: solid 1px #d8e2e7;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            padding: 50px 30px 55px;
            text-align: center;
            margin: 0 auto;
            width: 100%;
            max-width: 475px;
            color: #919fa9;
            line-height: 1;
        }
        .page-error-box .error-code {
            font-size: 9.375rem /*150/16*/;
            font-weight: 600;
        }
        .page-error-box .error-title {
            font-size: 2.25rem /*36/16*/;
            font-weight: 600;
            margin: 0 0 1.5rem /*24/16*/;
        }
        @media (max-width: 767px) {
            .page-error-box {
                padding: 25px 15px;
            }
            .page-error-box .error-code {
                font-size: 5.5rem;
            }
            .page-error-box .error-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="page-error-box">
    <div class="error-code">400</div>
    <div class="error-title">Website đang bảo trì.</div>
</div>
