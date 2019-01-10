<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $version=app()->version();
        $HTML="
        <!DOCTYPE html>
        <html>
        <header>
        <title>Lumen + JWT</title>
        <style>
        body{
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#d3572a+0,d67028+35,ed7825+58,e8a27d+100 */
            background: #d3572a; /* Old browsers */
            background: -moz-linear-gradient(left,  #d3572a 0%, #d67028 35%, #ed7825 58%, #e8a27d 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(left,  #d3572a 0%,#d67028 35%,#ed7825 58%,#e8a27d 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to right,  #d3572a 0%,#d67028 35%,#ed7825 58%,#e8a27d 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#d3572a', endColorstr='#e8a27d',GradientType=1 ); /* IE6-9 */

			font-family: fantasy,Monospace;
        }
        .full-height{
            height: 100vh;
        }
        .flex-center{
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref{
            position: relative;
        }

        .top-right{
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content{
            text-align: center;
        }

        .title{
            font-size: 60px;
            margin-left:20px;
            margin-right:20px;
        }

        .paragraph{
            font-size: 30px;
            margin-left:20px;
            margin-right:20px;
        }

        .links > a{
            color: #fff;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md{
            margin-bottom: 30px;
        }
        </style>
        </header>
        <body>
        <div class='flex-center position-ref full-height'>
            <div class='content'>
                <div class='title m-b-md'>
                    Lumen + JWT
                </div>
                <div class='paragraph m-b-md'>
                    ".$version."
                </div>
                <div class='links'>
                    <a href='https://laravel.com/docs'>Laravel Docs</a>
                    <a href='https://lumen.laravel.com/docs'>Lumen Docs</a>
                    <a href='https://laracasts.com'>Laracasts</a>
                </div>
            </div>
        </div>
        </body>
        </html>
        ";
        return $HTML;
    }
}
