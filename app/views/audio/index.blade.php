@extends('template.template')

@section('content')
    <style>
        h1 {
            font-weight: 100;
            display: inline-block;
        }
        h1:hover {
            cursor: pointer;
        }

        main {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 50vh;
        }
	.div_for_on_air_part .on-air-sign{
			padding:5px!important;
	}
        .on-air-sign {
            color: #555;
            border: 2px solid #555;
            padding: 1em 2em;
            border-radius: 5px;
            text-transform: uppercase;
            transition: all 1s;
        }
        .on-air-sign.on-air {
            color: red;
            border: 2px solid red;
            -webkit-animation: flicker 10s infinite;
            animation: flicker 10s infinite;
            box-shadow: 0 0 2px 2px #cc0000, inset 0 0 2px 2px #990000;
            text-shadow: 0 0 10px red;
        }
    </style>

    <div class="row center-block height" id="content-wrap">
        <div class="col-md-12 list-wrap height-inherit" id="audiosWrapper">
            <main>
                <h1 class="on-air-sign" onclick="toggle(this)">Coming Soon</h1>
            </main>
        </div>
    </div>
    <script>
        function toggle(el) {
            el.classList.toggle('on-air');
        }
    </script>
@stop