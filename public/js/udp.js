/*
* <System Name> iBMS
* <Program Name> udp.js
*
* <Create> 2018.06.21 TP Bryan
* <Update> 2019.06.21 TP Harvey
* <Update> 2019.12.12 TP Ivin Create code for flag mode
*/

//Get .env File Data
require('dotenv').config({path: '/usr/share/nginx/html/iBMS-HoteRes/.env'});
var request = require('request');

var port = process.env.PORT_GATEWAY;
var host = process.env.IP_PUSH;
var test = process.env.TEST_FLAG;

port = parseInt(port);                                                          //Convert to Integer

var dgram = require('dgram');
var server = dgram.createSocket('udp4');

const { performance } = require('perf_hooks');

server.on('listening', function () {
    var address = server.address();
});

server.on('message', function(data, remote) {

    if(test == 1){
      var jikan1Data = getJikan1(data);
    }
    var data = JSON.parse(data.toString());

    var url = data['url'];
    sendArr = {
        "MESSAGE" : data['message'],
        "IV" :      data['iv_key'],
        "GATEWAY_IP" : remote.address,
        "URL" :      data['url']
    }

    if(test == 1){
        if(data['count'] == undefined){
            sendArr['COUNT'] = 0;
        }else{
            sendArr['COUNT'] = data['count'];
        }
      var jikan2Data = getJikan2(jikan1Data[1]);
    }

    sendRequest(url, sendArr);

    if(test == 1){
      var jikan3Data = getJikan3(data, remote, jikan1Data[0], jikan2Data[0], jikan2Data[1], jikan2Data[2]);
    }
});

server.bind(port, host);

function sendRequest(url, data) {
    var address = "https://" + host + "/api/" + url;

    console.log(address);
    request.post({url: address, formData: data,rejectUnauthorized:false}, function optionalCallback(err, httpResponse, body) {
        if (err) {
            return console.error('upload failed:', err);
        }
    });
}
function getJikan1(data){
    var currentTimeStart = performance.now();
    var d;
    var hh;
    var mm;
    var ss;
    var dd;
    var jikan1;
    d  = new Date();
    hh = ("0" + d.getHours()).slice(-2);
    mm = ("0" + d.getMinutes()).slice(-2);
    ss = ("0" + d.getSeconds()).slice(-2);
    dd = ("0" + d.getMilliseconds()).slice(-3);
    jikan1 = hh + ":" + mm + ":" + ss + "." + dd;
    var kaigyou = "\n";
    var fs = require("fs");
    fs.appendFile('currentTimeStart.txt', jikan1, 'utf-8', function(err){});
    fs.appendFile('currentTimeStart.txt', kaigyou, 'utf-8', function(err){});
    fs.appendFile('currentTimeStart.txt', data, 'utf-8', function(err){});
    fs.appendFile('currentTimeStart.txt', kaigyou, 'utf-8', function(err){});

    return [jikan1, currentTimeStart];
}

function getJikan2(currentTimeStart){
    var currentTimeMiddle = performance.now();
    var time = (currentTimeMiddle - currentTimeStart).toFixed(3);
    d  = new Date();
    hh = d.getHours();
    mm = d.getMinutes();
    ss = d.getSeconds();
    dd = d.getMilliseconds();
    var jikan2 = hh + ":" + mm + ":" + ss + "." + dd;

    return [jikan2, currentTimeMiddle, time];
}

function getJikan3(data, remote, jikan1, jikan2, currentTimeMiddle, time){
    var currentTimeEnd = performance.now();
    var restime = (currentTimeEnd - currentTimeMiddle).toFixed(3);
    d  = new Date();
    hh = d.getHours();
    mm = d.getMinutes();
    ss = d.getSeconds();
    dd = d.getMilliseconds();
    var jikan3 = hh + ":" + mm + ":" + ss + "." + dd;

    console.log(' start time = ' + jikan1 + ' l point time = ' + jikan2 + ' l end time = ' + jikan3 + ' l process time = ' + time + 'mes l res time =  ' + restime + 'mes  l IP:'+ remote.address +' count = '+data['count']);
}

