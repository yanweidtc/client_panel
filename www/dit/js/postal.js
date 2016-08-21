/////////////////////////////////////////////////////////////////////end clearMakers extension/////////////////////


	// INIT VARIABLES
	var geocoder;
	var zip_map;
	var WindsorServiceArea, SherbrookeServiceArea, QuebecServiceArea, OshawaServiceArea, OttawaServiceArea, MontrealServiceArea, GTAServiceArea, LondonServiceArea, KitchenerServiceArea, HamiltonServiceArea, GatineauServiceArea;


/////////////////////////////////////////////////////////////////////polygon check/////////////////////////	

//Check point within polygon: Ray Cast Algorithm
       function get_bounds(polygon) {
	    var bounds = new google.maps.LatLngBounds();
	    var paths = polygon.getPaths();
	    var path;
	    
	    for (var p = 0; p < paths.getLength(); p++) {
	      path = paths.getAt(p);
	      for (var i = 0; i < path.getLength(); i++) {
		bounds.extend(path.getAt(i));
	      }
	    }

	    return bounds;
	  }


	// Polygon containsLatLng - method to determine if a latLng is within a polygon
	function containsLatLng(polygon,latLng) {
	  // Exclude points outside of bounds as there is no way they are in the poly
	 
	  var lat, lng;

	    var bounds = get_bounds(polygon);

	    if(bounds != null && !bounds.contains(latLng)) {
	      return false;
	    }
	    lat = latLng.lat();
	    lng = latLng.lng();


	  // Raycast point in polygon method
	  var inPoly = false;

	  var numPaths = polygon.getPaths().getLength();
	  for(var p = 0; p < numPaths; p++) {
	    var path = polygon.getPaths().getAt(p);
	    var numPoints = path.getLength();
	    var j = numPoints-1;

	    for(var i=0; i < numPoints; i++) { 
	      var vertex1 = path.getAt(i);
	      var vertex2 = path.getAt(j);

	      if (vertex1.lng() < lng && vertex2.lng() >= lng || vertex2.lng() < lng && vertex1.lng() >= lng) {
		if (vertex1.lat() + (lng - vertex1.lng()) / (vertex2.lng() - vertex1.lng()) * (vertex2.lat() - vertex1.lat()) < lat) {
		  inPoly = !inPoly;
		}
	      }

	      j = i;
	    }
	  }

	  return inPoly;
	}

	function select_area_init(address,flag){
		geocoder = new google.maps.Geocoder();


		var myLatLng = new google.maps.LatLng(44.64899, -77.32293);
		  var mapOptions = {
		    zoom: 6,
		    center: myLatLng
		  };

		/*var WindsorService = [
                      new google.maps.LatLng(42.16951, -83.11192),
                      new google.maps.LatLng(42.19393,  -83.05904),
                      new google.maps.LatLng(42.18529,  -83.04600),
                      new google.maps.LatLng(42.18249,  -82.96772),
                        new google.maps.LatLng(42.19292, -82.96635),
                        new google.maps.LatLng(42.18961, -82.86438),
                        new google.maps.LatLng(42.19546, -82.85683),
                        new google.maps.LatLng(42.20411, -82.87605),
                        new google.maps.LatLng(42.27959, -82.87090),
                        new google.maps.LatLng(42.27909, -82.84618),
                        new google.maps.LatLng(42.29255, -82.84378),
                        new google.maps.LatLng(42.28950, -82.75108),
                        new google.maps.LatLng(42.30017, -82.75040),
                        new google.maps.LatLng(42.32555, -82.85957),
                        new google.maps.LatLng(42.33520, -82.88292),
                        new google.maps.LatLng(42.33926, -82.91451),
                        new google.maps.LatLng(42.34383, -82.94884),
                        new google.maps.LatLng(42.33469, -82.95708),
                        new google.maps.LatLng(42.33266, -82.97630),
                        new google.maps.LatLng(42.32708, -83.01750),
                        new google.maps.LatLng(42.31591, -83.06282),
                        new google.maps.LatLng(42.30677, -83.07999),
                        new google.maps.LatLng(42.29001, -83.09475),
                        new google.maps.LatLng(42.25673, -83.10986),
                        new google.maps.LatLng(42.24326, -83.12325),
                        new google.maps.LatLng(42.23767, -83.12702),
                        new google.maps.LatLng(42.19215, -83.12359),
                      new google.maps.LatLng(42.16951, -83.11192)
                  ];*/

                  var WindsorService = [
                        new google.maps.LatLng(42.16951, -83.11192),
                        new google.maps.LatLng(42.11869, -83.11163),
                        new google.maps.LatLng(42.14721, -82.80539),
                        new google.maps.LatLng(42.29467, -82.69003),
                        new google.maps.LatLng(42.32555, -82.85957),
                        new google.maps.LatLng(42.33520, -82.88292),
                        new google.maps.LatLng(42.33926, -82.91451),
                        new google.maps.LatLng(42.34383, -82.94884),
                        new google.maps.LatLng(42.33469, -82.95708),
                        new google.maps.LatLng(42.33266, -82.97630),
                        new google.maps.LatLng(42.32708, -83.01750),
                        new google.maps.LatLng(42.31591, -83.06282),
                        new google.maps.LatLng(42.30677, -83.07999),
                        new google.maps.LatLng(42.29001, -83.09475),
                        new google.maps.LatLng(42.25673, -83.10986),
                        new google.maps.LatLng(42.24326, -83.12325),
                        new google.maps.LatLng(42.23767, -83.12702),
                        new google.maps.LatLng(42.19215, -83.12359),
                      new google.maps.LatLng(42.16951, -83.11192)
                  ];

                var SherbrookeService = [
                        new google.maps.LatLng(45.17816, -72.30652),
                        new google.maps.LatLng(45.00462, -72.29279),
                        new google.maps.LatLng(45.00754, -71.96182),
                        new google.maps.LatLng(45.24105, -71.95770),
                        new google.maps.LatLng(45.33284, -71.75308),
                        new google.maps.LatLng(45.37193, -71.74553),
                        new google.maps.LatLng(45.39074, -71.73798),
                        new google.maps.LatLng(45.43074, -71.74622),
                        new google.maps.LatLng(45.43797, -71.76201),
                        new google.maps.LatLng(45.44713, -71.76270),
                        new google.maps.LatLng(45.45965, -71.72424),
                        new google.maps.LatLng(45.50490, -71.80458),
                        new google.maps.LatLng(45.50587, -71.81969),
                        new google.maps.LatLng(45.46206, -71.85608),
                        new google.maps.LatLng(45.44905, -71.96388),
                        new google.maps.LatLng(45.50827, -72.07375),
                        new google.maps.LatLng(45.47024, -72.11975),
                        new google.maps.LatLng(45.47458, -72.14584),
                        new google.maps.LatLng(45.46639, -72.16370),
                        new google.maps.LatLng(45.39315, -72.17125),
                        new google.maps.LatLng(45.36807, -72.14447),
                        new google.maps.LatLng(45.36517, -72.12593),
                        new google.maps.LatLng(45.32270, -72.17674),
                        new google.maps.LatLng(45.24927, -72.22137),
                        new google.maps.LatLng(45.17816, -72.30652)
                  ];


                var QuebecService = [
                        new google.maps.LatLng(46.83718, -71.54091),
                        new google.maps.LatLng(46.73045, -71.41319),
                        new google.maps.LatLng(46.73421, -71.36169),
                        new google.maps.LatLng(46.74504, -71.34521),
                        new google.maps.LatLng(46.74645, -71.29234),
                        new google.maps.LatLng(46.74268, -71.28994),
                        new google.maps.LatLng(46.72927, -71.27998),
                        new google.maps.LatLng(46.72692, -71.26831),
                        new google.maps.LatLng(46.71303, -71.27209),
                        new google.maps.LatLng(46.69632, -71.24737),
                        new google.maps.LatLng(46.67488, -71.22849),
                        new google.maps.LatLng(46.65085, -71.24290),
                        new google.maps.LatLng(46.62492, -71.21647),
                        new google.maps.LatLng(46.60912, -71.21510),
                        new google.maps.LatLng(46.59709, -71.19896),
                        new google.maps.LatLng(46.65651, -71.09116),
                        new google.maps.LatLng(46.67206, -71.09322),
                        new google.maps.LatLng(46.69373, -71.06163),
                        new google.maps.LatLng(46.68101, -71.04378),
                        new google.maps.LatLng(46.72103, -70.97923),
                        new google.maps.LatLng(46.71680, -70.97305),
                        new google.maps.LatLng(46.78314, -70.91949),
                        new google.maps.LatLng(46.81557, -70.89203),
                        new google.maps.LatLng(46.81980, -70.89546),
                        new google.maps.LatLng(46.85033, -70.87074),
                        new google.maps.LatLng(46.87709, -70.91469),
                        new google.maps.LatLng(46.87193, -70.93597),
                        new google.maps.LatLng(46.83295, -71.11656),
                        new google.maps.LatLng(46.84751, -71.19484),
                        new google.maps.LatLng(46.85737, -71.16531),
                        new google.maps.LatLng(46.90032, -71.11553),
                        new google.maps.LatLng(46.93667, -71.17664),
                        new google.maps.LatLng(46.89821, -71.25252),
                        new google.maps.LatLng(46.89633, -71.28754),
                        new google.maps.LatLng(46.89445, -71.30093),
                        new google.maps.LatLng(46.90220, -71.30745),
                        new google.maps.LatLng(46.89469, -71.31569),
                        new google.maps.LatLng(46.90759, -71.31809),
                        new google.maps.LatLng(46.91299, -71.33904),
                        new google.maps.LatLng(46.90220, -71.37131),
                        new google.maps.LatLng(46.89797, -71.36925),
                        new google.maps.LatLng(46.88483, -71.39912),
                        new google.maps.LatLng(46.86911, -71.38126),
                        new google.maps.LatLng(46.85925, -71.40770),
                        new google.maps.LatLng(46.87545, -71.44409),
                        new google.maps.LatLng(46.86277, -71.46057),
                        new google.maps.LatLng(46.86489, -71.46503),
                        new google.maps.LatLng(46.85878, -71.48014),
                        new google.maps.LatLng(46.85620, -71.50726),
                        new google.maps.LatLng(46.84164, -71.54057),
                        new google.maps.LatLng(46.83718, -71.54091)
                  ];



                var OshawaService = [
                        new google.maps.LatLng(43.93993, -79.01711),
                        new google.maps.LatLng(43.83304, -78.97144),
                        new google.maps.LatLng(43.84641, -78.94192),
                        new google.maps.LatLng(43.84592, -78.91171),
                        new google.maps.LatLng(43.84468, -78.90209),
                        new google.maps.LatLng(43.85186, -78.88596),
                        new google.maps.LatLng(43.84963, -78.86982),
                        new google.maps.LatLng(43.86424, -78.82038),
                        new google.maps.LatLng(43.86671, -78.71344),
                        new google.maps.LatLng(43.96094, -78.75704),
                        new google.maps.LatLng(43.96687, -78.81317),
                        new google.maps.LatLng(43.98565, -78.82175),
                        new google.maps.LatLng(43.93993, -79.01711)
                  ];


/*              var OttawaService = [
                        new google.maps.LatLng(45.28817, -76.15345),
                        new google.maps.LatLng(45.23936, -76.09097),
                        new google.maps.LatLng(45.25531, -76.06728),
                        new google.maps.LatLng(45.21784, -76.01715),
                        new google.maps.LatLng(45.20333, -76.03981),
                        new google.maps.LatLng(45.16534, -75.99277),
                        new google.maps.LatLng(45.12829, -75.99861),
                        new google.maps.LatLng(45.08952, -75.92926),
                        new google.maps.LatLng(45.10503, -75.89767),
                        new google.maps.LatLng(45.10939, -75.92514),
                        new google.maps.LatLng(45.16800, -75.87914),
                        new google.maps.LatLng(45.16219, -75.80360),
                        new google.maps.LatLng(45.17429, -75.77202),
                        new google.maps.LatLng(45.18930, -75.78781),
                        new google.maps.LatLng(45.21010, -75.73700),
                        new google.maps.LatLng(45.14573, -75.68138),
                        new google.maps.LatLng(45.16219, -75.64224),
                        new google.maps.LatLng(45.16074, -75.62714),
                        new google.maps.LatLng(45.18349, -75.62576),
                        new google.maps.LatLng(45.21445, -75.63057),
                        new google.maps.LatLng(45.23767, -75.61546),
                        new google.maps.LatLng(45.27585, -75.57907),
                        new google.maps.LatLng(45.34346, -75.40741),
                        new google.maps.LatLng(45.40520, -75.43762),
                        new google.maps.LatLng(45.40231, -75.45410),
                        new google.maps.LatLng(45.42303, -75.46646),
                        new google.maps.LatLng(45.42593, -75.43007),
                        new google.maps.LatLng(45.44520, -75.44243),
                        new google.maps.LatLng(45.45146, -75.42526),
                        new google.maps.LatLng(45.47554, -75.43831),
                        new google.maps.LatLng(45.46976, -75.45547),
                        new google.maps.LatLng(45.50057, -75.48569),
                        new google.maps.LatLng(45.45820, -75.68550),
                        new google.maps.LatLng(45.43797, -75.70267),
                        new google.maps.LatLng(45.42544, -75.70610),
                        new google.maps.LatLng(45.40472, -75.76035),
                        new google.maps.LatLng(45.37482, -75.80498),
                        new google.maps.LatLng(45.35408, -75.82832),
                        new google.maps.LatLng(45.36903, -75.88188),
                        new google.maps.LatLng(45.42111, -75.93201),
                        new google.maps.LatLng(45.38688, -75.99998),
                        new google.maps.LatLng(45.36614, -75.97321),
                        new google.maps.LatLng(45.30242, -76.07346),
                        new google.maps.LatLng(45.32125, -76.10229),
                        new google.maps.LatLng(45.28817, -76.15345)
                  ];*/

                var OttawaService = [
                        new google.maps.LatLng(45.31033, -75.09331),
                        new google.maps.LatLng(45.02567, -75.70030),
                        new google.maps.LatLng(45.01597, -75.95299),
                        new google.maps.LatLng(45.05479, -76.07658),
                        new google.maps.LatLng(45.23495, -76.21941),
                        new google.maps.LatLng(45.40490, -76.29082),
                        new google.maps.LatLng(45.51471, -76.15074),
                        new google.maps.LatLng(45.61085, -75.51354),
                        new google.maps.LatLng(45.50123, -75.20317),
                        new google.maps.LatLng(45.31033, -75.09331)
                  ];


                var MontrealService = [
                        new google.maps.LatLng(45.58137, -74.43375),
                        new google.maps.LatLng(45.57656, -74.37401),
                        new google.maps.LatLng(45.52992, -74.38637),
                        new google.maps.LatLng(45.48372, -74.34036),
                        new google.maps.LatLng(45.48276, -74.29367),
                        new google.maps.LatLng(45.47265, -74.28062),
                        new google.maps.LatLng(45.45724, -74.22707),
                        new google.maps.LatLng(45.37771, -74.24492),
                        new google.maps.LatLng(45.35215, -74.22638),
                        new google.maps.LatLng(45.29083, -74.32182),
                        new google.maps.LatLng(45.24105, -74.24767),
                        new google.maps.LatLng(45.21978, -74.11789),
                        new google.maps.LatLng(45.18494, -74.06502),
                        new google.maps.LatLng(45.21591, -73.99567),
                        new google.maps.LatLng(45.22219, -73.95996),
                        new google.maps.LatLng(45.23815, -73.93661),
                        new google.maps.LatLng(45.24637, -73.91327),
                        new google.maps.LatLng(45.19414, -73.84254),
                        new google.maps.LatLng(45.18155, -73.84804),
                        new google.maps.LatLng(45.16171, -73.71552),
                        new google.maps.LatLng(45.18058, -73.68530),
                        new google.maps.LatLng(45.17236, -73.67500),
                        new google.maps.LatLng(45.18349, -73.65784),
                        new google.maps.LatLng(45.16219, -73.63243),
                        new google.maps.LatLng(45.18300, -73.51364),
                        new google.maps.LatLng(45.17816, -73.49716),
                        new google.maps.LatLng(45.19268, -73.45390),
                        new google.maps.LatLng(45.16558, -73.46489),
                        new google.maps.LatLng(45.16994, -73.27194),
                        new google.maps.LatLng(45.13701, -73.27332),
                        new google.maps.LatLng(45.13749, -73.10715),
                        new google.maps.LatLng(45.21107, -73.11058),
                        new google.maps.LatLng(45.22219, -73.09204),
                        new google.maps.LatLng(45.21445, -73.03162),
                        new google.maps.LatLng(45.22606, -73.01994),
                        new google.maps.LatLng(45.24492, -73.01926),
                        new google.maps.LatLng(45.25507, -73.03436),
                        new google.maps.LatLng(45.27730, -73.03299),
                        new google.maps.LatLng(45.28938, -73.01376),
                        new google.maps.LatLng(45.35022, -73.00964),
                        new google.maps.LatLng(45.37193, -73.02612),
                        new google.maps.LatLng(45.37530, -72.99110),
                        new google.maps.LatLng(45.41147, -73.00003),
                        new google.maps.LatLng(45.54483, -72.96501),
                        new google.maps.LatLng(45.57560, -72.98492),
                        new google.maps.LatLng(45.60347, -72.96913),
                        new google.maps.LatLng(45.61692, -72.98492),
                        new google.maps.LatLng(45.61884, -73.06458),
                        new google.maps.LatLng(45.68795, -73.18336),
                        new google.maps.LatLng(45.67596, -73.19847),
                        new google.maps.LatLng(45.72584, -73.26714),
                        new google.maps.LatLng(45.73446, -73.26439),
                        new google.maps.LatLng(45.76369, -73.30696),
                        new google.maps.LatLng(45.75459, -73.33305),
                        new google.maps.LatLng(45.70378, -73.37151),
                        new google.maps.LatLng(45.73063, -73.41476),
                        new google.maps.LatLng(45.78045, -73.40103),
                        new google.maps.LatLng(45.84315, -73.48961),
                        new google.maps.LatLng(45.83406, -73.51639),
                        new google.maps.LatLng(45.84602, -73.54523),
                        new google.maps.LatLng(45.85224, -73.57132),
                        new google.maps.LatLng(45.85702, -73.62144),
                        new google.maps.LatLng(45.79434, -73.69148),
                        new google.maps.LatLng(45.78213, -73.69835),
                        new google.maps.LatLng(45.79123, -73.76461),
                        new google.maps.LatLng(45.77016, -73.81886),
                        new google.maps.LatLng(45.73303, -73.82263),
                        new google.maps.LatLng(45.74620, -73.90125),
                        new google.maps.LatLng(45.71433, -73.94314),
                        new google.maps.LatLng(45.72440, -73.95927),
                        new google.maps.LatLng(45.72224, -73.97095),
                        new google.maps.LatLng(45.74692, -74.00768),
                        new google.maps.LatLng(45.73255, -74.07669),
                        new google.maps.LatLng(45.75171, -74.06639),
                        new google.maps.LatLng(45.76034, -74.10072),
                        new google.maps.LatLng(45.72440, -74.17145),
                        new google.maps.LatLng(45.69659, -74.15291),
                        new google.maps.LatLng(45.62796, -74.39804),
                        new google.maps.LatLng(45.62124, -74.40285),
                        new google.maps.LatLng(45.62316, -74.42207),
                        new google.maps.LatLng(45.58137, -74.43375)
                  ];


                var GTAService = [
                        new google.maps.LatLng(43.67482, -79.89120),
                        new google.maps.LatLng(43.60923, -79.79507),
                        new google.maps.LatLng(43.58835, -79.81293),
                        new google.maps.LatLng(43.52665, -79.72778),
                        new google.maps.LatLng(43.47086, -79.63577),
                        new google.maps.LatLng(43.50374, -79.59595),
                        new google.maps.LatLng(43.53461, -79.59595),
                        new google.maps.LatLng(43.58338, -79.53690),
                        new google.maps.LatLng(43.59432, -79.49432),
                        new google.maps.LatLng(43.62713, -79.47372),
                        new google.maps.LatLng(43.63608, -79.45450),
                        new google.maps.LatLng(43.60028, -79.37485),
                        new google.maps.LatLng(43.61719, -79.31992),
                        new google.maps.LatLng(43.65694, -79.31305),
                        new google.maps.LatLng(43.66986, -79.27322),
                        new google.maps.LatLng(43.80877, -79.08852),
                        new google.maps.LatLng(43.80282, -79.05556),
                        new google.maps.LatLng(43.83255, -78.96973),
                        new google.maps.LatLng(44.00862, -79.04732),
                        new google.maps.LatLng(43.94191, -79.39133),
                        new google.maps.LatLng(43.99775, -79.39751),
                        new google.maps.LatLng(44.00566, -79.40712),
                        new google.maps.LatLng(44.04071, -79.41399),
                        new google.maps.LatLng(44.02393, -79.50050),
                        new google.maps.LatLng(43.92708, -79.47853),
                        new google.maps.LatLng(43.87859, -79.71405),
                        new google.maps.LatLng(43.84815, -79.69723),
                        new google.maps.LatLng(43.74382, -79.81464),
                        new google.maps.LatLng(43.74779, -79.82014),
                        new google.maps.LatLng(43.72993, -79.84348),
                        new google.maps.LatLng(43.72422, -79.83593),
                        new google.maps.LatLng(43.67482, -79.89120)
                  ];


                /*var LondonService = [
                        new google.maps.LatLng(42.85734, -81.73553),
                        new google.maps.LatLng(42.74172, -81.57761),
                        new google.maps.LatLng(42.78961, -81.51787),
                        new google.maps.LatLng(42.77298, -81.49418),
                        new google.maps.LatLng(42.78507, -81.47186),
                        new google.maps.LatLng(42.74752, -81.41453),
                        new google.maps.LatLng(42.77121, -81.37951),
                        new google.maps.LatLng(42.69859, -81.29402),
                        new google.maps.LatLng(42.69102, -81.21780),
                        new google.maps.LatLng(42.67537, -81.21300),
                        new google.maps.LatLng(42.67335, -81.09970),
                        new google.maps.LatLng(42.73340, -81.09970),
                        new google.maps.LatLng(42.73239, -81.03447),
                        new google.maps.LatLng(42.81404, -81.03310),
                        new google.maps.LatLng(42.82260, -80.98160),
                        new google.maps.LatLng(42.96396, -81.01730),
                        new google.maps.LatLng(42.97049, -80.98640),
                        new google.maps.LatLng(43.13005, -81.06194),
                        new google.maps.LatLng(43.11953, -81.09421),
                        new google.maps.LatLng(43.22669, -81.14777),
                        new google.maps.LatLng(43.13557, -81.46706),
                        new google.maps.LatLng(43.14609, -81.53366),
                        new google.maps.LatLng(43.11352, -81.54533),
                        new google.maps.LatLng(43.05083, -81.62636),
                        new google.maps.LatLng(42.95642, -81.63185),
                        new google.maps.LatLng(42.91319, -81.68060),
                        new google.maps.LatLng(42.90414, -81.67065),
                        new google.maps.LatLng(42.85734, -81.73553)
                  ];*/



                var LondonService = [
                        new google.maps.LatLng(42.76888, -80.72792),
                        new google.maps.LatLng(42.65182, -81.21681),
                        new google.maps.LatLng(42.71240, -81.40907),
                        new google.maps.LatLng(42.86357, -81.74416),
                        new google.maps.LatLng(42.99630, -81.74965),
                        new google.maps.LatLng(43.16881, -81.58486),
                        new google.maps.LatLng(43.22888, -81.45302),
                        new google.maps.LatLng(43.22888, -81.14540),
                        new google.maps.LatLng(43.04048, -80.67299),
                        new google.maps.LatLng(42.76888, -80.72792)
                  ];

                var KitchenerService = [
                        new google.maps.LatLng(43.17923, -80.33484),
                        new google.maps.LatLng(43.23529, -80.58753),
                        new google.maps.LatLng(43.47892, -80.79077),
                        new google.maps.LatLng(43.61429, -80.66443),
                        new google.maps.LatLng(44.01068, -79.45593),
                        //new google.maps.LatLng(43.61827, -79.30762),
			new google.maps.LatLng(44.04612, -79.15015),
                        new google.maps.LatLng(44.06202, -78.95880),
                        new google.maps.LatLng(43.99093, -78.76105),
                        new google.maps.LatLng(43.86037, -78.67316),
                        new google.maps.LatLng(43.19125, -79.53558),
                        new google.maps.LatLng(42.98264, -79.68939),
                        new google.maps.LatLng(42.98666, -80.27716),
                        new google.maps.LatLng(43.05894, -80.39801),
                        new google.maps.LatLng(43.15519, -80.39252),
                        new google.maps.LatLng(43.17923, -80.33484)
                  ];

/*              var KitchenerService = [
                        new google.maps.LatLng(43.56547, -80.70694),
                        new google.maps.LatLng(43.52565, -80.71312),
                        new google.maps.LatLng(43.52565, -80.68291),
                        new google.maps.LatLng(43.47485, -80.69080),
                        new google.maps.LatLng(43.45242, -80.67604),
                        new google.maps.LatLng(43.44843, -80.69870),
                        new google.maps.LatLng(43.33791, -80.61356),
                        new google.maps.LatLng(43.33542, -80.62729),
                        new google.maps.LatLng(43.32892, -80.62008),
                        new google.maps.LatLng(43.33392, -80.59055),
                        new google.maps.LatLng(43.30944, -80.58094),
                        new google.maps.LatLng(43.33242, -80.44361),
                        new google.maps.LatLng(43.34615, -80.44945),
                        new google.maps.LatLng(43.35165, -80.41649),
                        new google.maps.LatLng(43.37286, -80.42439),
                        new google.maps.LatLng(43.38659, -80.37083),
                        new google.maps.LatLng(43.40006, -80.36739),
                        new google.maps.LatLng(43.37661, -80.32413),
                        new google.maps.LatLng(43.41602, -80.29736),
                        new google.maps.LatLng(43.42250, -80.33169),
                        new google.maps.LatLng(43.47559, -80.30182),
                        new google.maps.LatLng(43.48581, -80.31418),
                        new google.maps.LatLng(43.49901, -80.29598),
                        new google.maps.LatLng(43.55751, -80.37529),
                        new google.maps.LatLng(43.57517, -80.36671),
                        new google.maps.LatLng(43.60028, -80.56000),
                        new google.maps.LatLng(43.57591, -80.56480),
                        new google.maps.LatLng(43.57865, -80.58987),
                        new google.maps.LatLng(43.60252, -80.58643),
                        new google.maps.LatLng(43.59979, -80.63587),
                        new google.maps.LatLng(43.55974, -80.64583),
                        new google.maps.LatLng(43.56547, -80.70694)
                  ];*/


                var HamiltonService = [
                        new google.maps.LatLng(43.21619, -80.21393),
                        new google.maps.LatLng(43.02523, -79.65157),
                        new google.maps.LatLng(43.07039, -79.65294),
                        new google.maps.LatLng(43.08193, -79.68315),
                        new google.maps.LatLng(43.21969, -79.63646),
                        new google.maps.LatLng(43.25020, -79.76006),
                        new google.maps.LatLng(43.30369, -79.79645),
                        new google.maps.LatLng(43.31968, -79.79851),
                        new google.maps.LatLng(43.37261, -79.71542),
                        new google.maps.LatLng(43.47211, -79.63131),
                        new google.maps.LatLng(43.58686, -79.81155),
                        new google.maps.LatLng(43.56547, -79.83765),
                        new google.maps.LatLng(43.58735, -79.86786),
                        new google.maps.LatLng(43.55850, -79.90219),
                        new google.maps.LatLng(43.57989, -79.92760),
                        new google.maps.LatLng(43.53486, -79.98150),
                        new google.maps.LatLng(43.51445, -79.94957),
                        new google.maps.LatLng(43.42101, -80.06699),
                        new google.maps.LatLng(43.38060, -80.01205),
                        new google.maps.LatLng(43.34965, -80.09102),
                        new google.maps.LatLng(43.28070, -80.06836),
                        new google.maps.LatLng(43.26421, -80.16380),
                        new google.maps.LatLng(43.21619, -80.21393)
                  ];


                var GatineauService = [
                        new google.maps.LatLng(45.46254, -75.94299),
                        new google.maps.LatLng(45.40665, -75.89355),
                        new google.maps.LatLng(45.37675, -75.83656),
                        new google.maps.LatLng(45.37820, -75.80086),
                        new google.maps.LatLng(45.41147, -75.76309),
                        new google.maps.LatLng(45.42593, -75.70610),
                        new google.maps.LatLng(45.45435, -75.69237),
                        new google.maps.LatLng(45.46663, -75.64842),
                        new google.maps.LatLng(45.48541, -75.55195),
                        new google.maps.LatLng(45.49961, -75.52654),
                        new google.maps.LatLng(45.56262, -75.53581),
                        new google.maps.LatLng(45.54844, -75.74249),
                        new google.maps.LatLng(45.52247, -75.74112),
                        new google.maps.LatLng(45.51838, -75.76927),
                        new google.maps.LatLng(45.49383, -75.76069),
                        new google.maps.LatLng(45.48685, -75.74867),
                        new google.maps.LatLng(45.46013, -75.76687),
                        new google.maps.LatLng(45.45820, -75.80463),
                        new google.maps.LatLng(45.46639, -75.82798),
                        new google.maps.LatLng(45.46639, -75.84412),
                        new google.maps.LatLng(45.46952, -75.85064),
                        new google.maps.LatLng(45.46254, -75.94299)
                  ];
			

//fill:#43c742
		   // strokeColor: '#E47297',
		   //strokeOpacity: 0.8,
		   // strokeWeight: 1,
		   // fillColor: '#E47297',
		   // fillOpacity: 0.2
		WindsorServiceArea = new google.maps.Polygon({
		    paths: WindsorService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});

		SherbrookeServiceArea = new google.maps.Polygon({
		    paths: SherbrookeService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});
		QuebecServiceArea = new google.maps.Polygon({
		    paths: QuebecService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});
		OshawaServiceArea = new google.maps.Polygon({
		    paths: OshawaService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});
		OttawaServiceArea = new google.maps.Polygon({
		    paths: OttawaService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});
		MontrealServiceArea = new google.maps.Polygon({
		    paths: MontrealService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});
		GTAServiceArea = new google.maps.Polygon({
		    paths: GTAService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});
		LondonServiceArea = new google.maps.Polygon({
		    paths: LondonService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});
		KitchenerServiceArea = new google.maps.Polygon({
		    paths: KitchenerService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});	
		HamiltonServiceArea = new google.maps.Polygon({
		    paths: HamiltonService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});
		GatineauServiceArea = new google.maps.Polygon({
		    paths: GatineauService,
		    strokeColor: '#43c742',
		    strokeOpacity: 0.8,
		    strokeWeight: 1,
		    fillColor: '#43c742',
		    fillOpacity: 0.2
		});


		console.log("level1" + address);

		  geocoder.geocode( { 'address': address}, function(results, status) {
		  //geocoder.geocode( { 'address': address}, function(results, status) {
		    if (status == google.maps.GeocoderStatus.OK) {


		        var isWithinPolygon = false;
			var isComingSoon = false;
			
			if( containsLatLng(WindsorServiceArea,results[0].geometry.location)){
				//WindsorServiceArea.setMap(zip_map);
				isWithinPolygon = true;
			}else if( containsLatLng(SherbrookeServiceArea,results[0].geometry.location)){
				//SherbrookeServiceArea.setMap(zip_map);
				isWithinPolygon = true;
				isComingSoon = false;
			}else if( containsLatLng(QuebecServiceArea,results[0].geometry.location)){
				//QuebecServiceArea.setMap(zip_map);
				isWithinPolygon = true;
				isComingSoon = false;
			}else if( containsLatLng(OshawaServiceArea,results[0].geometry.location)){
				//OshawaServiceArea.setMap(zip_map);
				isWithinPolygon = true;
			}else if( containsLatLng(OttawaServiceArea,results[0].geometry.location)){
				//OttawaServiceArea.setMap(zip_map);
				isWithinPolygon = true;
			}else if( containsLatLng(MontrealServiceArea,results[0].geometry.location)){
				//MontrealServiceArea.setMap(zip_map);
				isWithinPolygon = true;
				isComingSoon = false;
			}else if( containsLatLng(GTAServiceArea,results[0].geometry.location)){
				//GTAServiceArea.setMap(zip_map);
				isWithinPolygon = true;
			}else if( containsLatLng(LondonServiceArea,results[0].geometry.location)){
				//LondonServiceArea.setMap(zip_map);
				isWithinPolygon = true;
			}else if( containsLatLng(KitchenerServiceArea,results[0].geometry.location)){
				//KitchenerServiceArea.setMap(zip_map);
				isWithinPolygon = true;
			}else if( containsLatLng(HamiltonServiceArea,results[0].geometry.location)){
				//HamiltonServiceArea.setMap(zip_map);
				isWithinPolygon = true;
			}else if( containsLatLng(GatineauServiceArea,results[0].geometry.location)){
				//GatineauServiceArea.setMap(zip_map);
				isWithinPolygon = true;
			}


		if (isWithinPolygon)
                {
                        document.getElementById("zip").innerHTML = "<style>a.ex6:hover,a.ex6:active,a.ex6:hover,a.ex6:link {color:#3f85b4;text-decoration:none;}</style><br><br><br><a class=ex6 href=/tv.html><h3><strong>HD TV Service for $39.95/month</strong> - <br />"+
"                                  Most premium channels included for free within the Basic service plan </h3></a><br><br>"+
"                                  <table cellpadding='5' cellspacing='0'>"+
"                                    <tbody>"+
"                                      <tr>"+
"                                        <td align='left' valign='top'><table cellpadding='0' cellspacing='0' id='tblLocal'>"+
"                                          <tbody>"+
"                                            <tr>"+
"                                              <td><div class='slider-banner'> "+
"                                                <p><br />"+
"                                                <p>100% digital channels, Free PVR and HD is default. Zazeen backs it all up with a 30 day money back guarantee. Experience our TV service for yourself here:"+
"<a href=/tv.html></p><br><br><img src=/images/Tvpage.png style='width: 110px;'></a><br><br><br>"+
"<td></tr>"+
"</tbody></table>"+
"</tr>"+
"</td></tr></tbody></table>";
		
			if($("#check_done").length != 0){
				$("#check_done").html("<img src=\'./js/OK_Icons.png\'>");				
				if(flag){
					$('#oform').unbind('submit');
					$('#oform').bind('submit',function() {
						console.log("here in the bind");
						return form1_onsubmit(false);
					});

					$('#oform').submit();
					return bReturn;
				}
			}
			console.log("level2");
                }
                else
                {
			
			if($("#check_done").length != 0){
				$("#check_done").html("<img src=\'./js/not_OK_Icons.png\'>");

				if(flag){
				    if ( typeof bReturn !== "undefined" && bReturn) {
					bReturn = false;
					alert("Invalid Postal Code!");
					console.log(bReturn);
				    } 
				}
			}

			console.log("level3");
                        document.getElementById("zip").innerHTML = "";
                }

		
		    } else {

			if($("#check_done").length != 0){
				$("#check_done").html("<img src=\'./js/not_OK_Icons.png\'>");
				if(flag){

				    if ( typeof bReturn !== "undefined" && bReturn) {
					bReturn = false;
					alert("Invalid Postal Code!");
					console.log(bReturn+status);
				    } 
				}
			}

			console.log("level4"+status);
               		 document.getElementById("zip").innerHTML = "";

		    }
		  });



       }	//check address



