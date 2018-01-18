<html>
<head>
    <style>
        * {
            box-sizing: border-box;
        }
        html {
            height: 100%;
            background-size: cover;
            background-color: #34495e;
            background-image: -webkit-radial-gradient(top, circle cover, #6F9FAD, #34495e 80%);
            background-image: -moz-radial-gradient(top, circle cover, #6F9FAD, #34495e 80%);
            background-image: -o-radial-gradient(top, circle cover, #6F9FAD, #34495e 80%);
            background-image: radial-gradient(top, circle cover, #6F9FAD, #34495e 80%);
        }
        body {
            margin: 0;
        }
        .poopy-browser {
            width: 1064px;
            min-height: 150px;
            height: calc(100% - 200px);
            border-radius: 5px 5px 0 0;
            margin-left: calc(50% - 532px);
            position: relative;
            margin-top: 200px;
            overflow: hidden;
            background-color: #ecf0f1;
        }
        .error {
            text-align: center;
            color: #34495e;
            font: 100% 'Lato', sans-serif;
            position: absolute;
            width: 500px;
            margin-top: 25px;
            margin-left: calc(50% - 250px);
        }
        .error h1 {
            font-size: 13rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0px 3px 0px #7f8c8d;
            line-height: 100%;
        }
        .error p {
            margin: 0;
            line-height: 100%;
            font-size: 7.5rem;
            text-shadow: 0px 3px 0px #7f8c8d;
            font-weight: 100;
        }
    </style>
</head>
<body>
<div class="poopy-browser">
    <div class="error">
        <h1>{{ $code }}</h1>
        <p>ERROR</p>
    </div>
</div>
</body>
</html>