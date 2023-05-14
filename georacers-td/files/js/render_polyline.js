// JavaScript Document



//text change callback
    var geojson = null;
    function render(encoded_c_path) {
      try {
        //if they have escaped escapes
        var encoded = encoded_c_path;
//		.replace(/\\\\/g, '\\');
        if(encoded.length == 0)
          return;
        //decode it
        var decoded = decode(encoded, 1e5);
        //update the display
        $('#decoded_polyline').val(JSON.stringify(decoded));
        $('#cardinality').val(decoded.length);
        //clear this if its not null
        if(geojson != null)
          geojson.removeFrom(map);
        //turn this into geojson
        var json = {
          type:'FeatureCollection',
          features: [{
            type: 'Feature',
            geometry: {
              type: 'LineString',
              coordinates: decoded
            },
            properties: {}
          }]
        };
        geojson = L.geoJson(json,{ style: function(feature) { 
          return { fillColor: feature.properties.fill,
            color: 'grey',
            opacity: 0.5,
            weight: 7,
          };
        }});
        //render the geojson
        geojson.addTo(map);
        //fit it in view
        map.fitBounds(L.GeoJSON.coordsToLatLngs(decoded));
      }
      catch(e){
        alert('Invalid Encoded Polyline');
      }
    };