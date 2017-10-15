'use strict';
let http = require('http');
 
exports.handler = function(event, context, callback) {
  var path = "", port = 0, method = "POST";

  switch(event.result.metadata.intentName) {
    case "Transmitter Intent":
      port = 9900;
      path = '/transmitter'+
        '?device='+event.result.parameters['Device']+
        '&switch='+event.result.parameters['Switch'];
    break;
    case "TV Channel Intent":
      port = 9900;
      path = '/tv/channel'+
        '?channel='+event.result.parameters['TvChannel'];
    break;
    case "TV Command Intent":
      port = 9900;
      path = '/tv/command'+
        '?command='+event.result.parameters['TvCommand'];
    break;
    case "Tab Intent":
      port = 9900;
      path = '/browser/tab'+
        '?action='+event.result.parameters['TabAction'];
    break;
    case "Chromecast Intent":
      port = 9900;
      path = '/chromecast/control'+
        '?action='+event.result.parameters['ChromecastAction'];
    break;
    case "Stream Intent":
      port = 9900;
      path = '/chromecast/stream'+
        '?url='+encodeURIComponent(event.result.parameters['Url']);
    break;
    case "Search Intent":
      port = 9900;
      path = '/browser/search'+
        '?q='+encodeURIComponent(event.result.parameters['any']);
    break;
    case "System Intent":
      port = 9900;
      path = '/system/restart';
    break;
    case "Temperature Intent":
      port = 9901;
      path = '/temperature'+
        '?sensor='+event.result.parameters['Sensor'];
      method = 'GET';
    break;
  }
  
  let options = {
        host: 'mydyndns.com',
        port: port,
        method: method,
        path: path,
        auth: 'apiai:XXX'
  };
  
  makeRequest(options, function( data, error) {
    let result = data.result[0];
    if (result) {
        callback(null, {"speech": result.result});
    }
    else {
        callback(null, {"speech": "I'm not sure!"});
    }
  });
};
 
function makeRequest(options, callback) {
    var request = http.request(options, function(response) {
        var responseString = '';
        response.on('data', function(data) {
            responseString += data;
        });
         response.on('end', function() {
            var responseJSON = JSON.parse(responseString);
            callback(responseJSON, null);
        });
    });
    request.end();
}
