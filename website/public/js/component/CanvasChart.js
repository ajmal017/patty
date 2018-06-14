var CanvasChart = (function() {
    var that = {};
    that.generate = function(element_id, data) {
        var chart = new CanvasJS.Chart(element_id, {
        	animationEnabled: true,
        	theme: "light2", // "light1", "light2", "dark1", "dark2"
        	exportEnabled: true,
            zoomEnabled: true,
        	title:{
        		text: ""
        	},
        	axisX: {
        		valueFormatString: "MMM"
        	},
        	axisY: {
        		includeZero:false,
        		prefix: "",
        		title: "가격 원화"
        	},
        	toolTip: {
        		shared: true
        	},
        	data: data
        });
        chart.render();
    };
    return that;
})();
