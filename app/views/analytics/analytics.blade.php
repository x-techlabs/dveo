@extends('template.template')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>

<div class="settings height content">
    <div class="title-name">
        <i class="fa fa-calendar"></i>
        <div class="title">Analytics</div>
    </div>
    <div class="clear"></div>

    <div class="settings_wrapper" style='background:#ecf0f1;'>
        <table width='100%'>
        <tr><td colspan=2>
            <div id="schedule-body" class="height content">
                Streamlyzer website is opened in another tab.
            </div>
        </td></tr>
        <tr>
<!--
1 how many viewers and 
2 average time watched per viewer
3 total minutes watched per device
4 how many min for live streaming
5 and for VOD total
6 VOD each individual asset
-->

            <td class="col-md-4" valign='top'>
                <ul class="tabs">
                    <li class="active" onclick='OnTabChange(1)'><a href="#tab2">Live Analytics</a></li>
                    <li onclick='OnTabChange(2)'><a href="#tab1">No Of Viewers</a></li>
                    <li onclick='OnTabChange(3)'><a href="#tab2">Viewer Time Distribution</a></li>
                    <li onclick='OnTabChange(5)'><a href="#tab5">VOD Popularity</a></li>
                </ul>
            </td>
            <td class="col-md-8" valign='top'>
                <div style='border:1px solid #777;padding:5px;' id="tab1">
                    <canvas id="tab1Chart" style='width:400px;height:300px;'></canvas>
                </div>
            </td>
        </tr></table>
    </div>
</div>

<script language='javascript'>

var myChart;
var menuid = 0;
var rMin = 0, rMax = 99991231;
var subMenu = "Y";
var historyData = '';

window.onload = function()
{
    window.open('https://dashboard.streamlyzer.com/', 'analytics');
    OnTabChange('1');

    $("#tab1Chart").click(function(e) {
        var elementsArray = myChart.getElementAtEvent(e);
        if (elementsArray.length > 0)
        {
            e.preventDefault();
            e.stopPropagation();

            k = elementsArray[0]._index;
            if (menuid==2 || menuid==3)
            {
                if (subMenu=='Y') { subMenu = 'M'; rMin = myChart.data.labels[k] + '0101';  rMax = myChart.data.labels[k] + '1231'; DrawGraph(); return;  }
                else if (subMenu=='M') { subMenu = 'D'; rMin = myChart.data.labels[k]; rMax = historyData; DrawGraph(); return; }
                else if (subMenu=='D') { subMenu = 'H'; rMin = myChart.data.labels[k]; rMax = historyData; DrawGraph(); return; }
            }
        }
    });
}

function SetinitialMenuOptions(x)
{
    if (x==1) { menuid = x; rMin = 0; rMax = 0; subMenu = ""; }
    else if (x==2 || x==3) { menuid = x; rMin = 0; rMax = 99991231; subMenu = "Y"; }
}

function OnTabChange(x)
{
    SetinitialMenuOptions(x);
    DrawGraph();
}

function DrawGraph()
{
    $.ajax({
        url: ace.path('analytics_get_data_for'),
        type: "GET",
   	    data: { "menuid"  : menuid,
   	            "rmin"    : rMin,
   	            "rmax"    : rMax,
   	            "submenu" : subMenu 
   	          },
        success: function (msg) {
            if (menuid==1) DrawLiveGraph(msg);
            else DrawGraphResponse(menuid, msg);
        }
    });
}

function DrawLiveGraph(msg)
{
    parts = msg.split('^');
    while(parts.length < 10) parts.push('');

    if (myChart != undefined) myChart.destroy();
    var ctx = document.getElementById('tab1Chart').getContext('2d');
    myChart = new Chart(ctx, { type: 'line',
                               data: { labels: parts[0].split(';'),
                                       datasets: [{ label: parts[1], data: parts[3].split(';'), backgroundColor: parts[2] },
                                                  { label: parts[4], data: parts[6].split(';'), backgroundColor: parts[5] },
                                                  { label: parts[7], data: parts[9].split(';'), backgroundColor: parts[8] }
                                                 ]
                                     },
                               options: { title: { display: true, text: "Live chart of last 30 minutes" },
                                         scales: { yAxes: [{ display: true, 
                                                            ticks: { beginAtZero: true } 
                                                          }]
                                                 }
                                        }
                       });
    window.setTimeout('DrawGraph()', 30000);
}

function DrawGraphResponse(x, msg)
{
    parts = msg.split('^');
    while(parts.length < 5) parts.push('');

    if (myChart != undefined) myChart.destroy();
    var ctx = document.getElementById('tab1Chart').getContext('2d');
    myChart = new Chart(ctx, { type: 'bar',
                               data: { labels: parts[0].split(';'),
                                       datasets: [{ label: parts[1], data: parts[2].split(';'), backgroundColor: "rgba(153,255,51,1)" }]
                                     },
                               options: { title: { display: true, text: parts[3] },
                                         scales: { yAxes: [{ display: true, 
                                                            ticks: { beginAtZero: true } 
                                                          }]
                                                 }
                                        }
                       });

    historyData = parts[4];
}

</script>
@stop