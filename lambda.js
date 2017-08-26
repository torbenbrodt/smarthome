'use strict';
let http = require('http');
 
exports.handler = function(event, context, callback) {
  var path = "", method = "POST";

  switch(event.result.metadata.intentName) {
    case "Transmitter Intent":
      path = '/transmitter'+
        '?device='+event.result.parameters['Device']+
        '&switch='+event.result.parameters['Switch'];
    break;
    case "TV Channel Intent":
      path = '/tv/channel'+
        '?channel='+event.result.parameters['TvChannel'];
    break;
    case "Temperature Intent":
      path = '/temperature'+
        '?sensor='+event.result.parameters['Sensor'];
      method = 'GET';
    break;
    case "TV Command Intent":
      path = '/tv/command'+
        '?command='+event.result.parameters['TvCommand'];
    break;
  }
  
  let options = {
        host: 'mydyndns.com',
        port: 9900,
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
