$(function() {
    "use strict";
// chart 1

  var ctx = document.getElementById("chart1").getContext('2d');
   
  var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke1.addColorStop(0, '#6078ea');  
      gradientStroke1.addColorStop(1, '#17c5ea'); 
   
      var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['A. Belanja Barang', 'B. Langganan & Jasa', 'C. Belanja Kegiatan', 'D. Umum'],
          datasets: [{
            label: 'Realisasi',
            data: [65, 59, 80, 81],
            borderColor: gradientStroke1,
            backgroundColor: gradientStroke1,
            hoverBackgroundColor: gradientStroke1,
            pointRadius: 0,
            fill: false,
            borderWidth: 0
          }]
        },
		
		options:{
		  maintainAspectRatio: false,
		  legend: {
			  position: 'bottom',
              display: false,
			  labels: {
                boxWidth:8
              }
            },
			tooltips: {
			  displayColors:false,
			},	
		  scales: {
			  xAxes: [{
				barPercentage: .5
			  }]
		     }
		}
      });
	  
	 
// chart 2

 

   });	 
   