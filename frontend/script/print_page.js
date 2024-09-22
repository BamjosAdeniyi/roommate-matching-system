function printPage() {
    window.print();
}

// Get the chart elements
const traitChart = document.getElementById('traitChart').getContext('2d');
const facetCharts = [];

// Loop over the facet chart canvases to get their contexts
document.querySelectorAll('canvas[id$="FacetChart"]').forEach(canvas => {
    facetCharts.push(canvas.getContext('2d'));
});

// Function to adjust charts for print
function adjustChartsForPrint() {
    // Resize trait chart
    traitChart.canvas.width = 800; // Adjust the width to fit the paper size
    traitChart.canvas.height = 400;

    // Resize all facet charts
    facetCharts.forEach(chart => {
        chart.canvas.width = 800;
        chart.canvas.height = 400;
    });

    // Redraw the charts
    window.myTraitChart.update();
    facetCharts.forEach(chartInstance => chartInstance.update());
}

// Resize charts before printing
window.onbeforeprint = function() {
    adjustChartsForPrint();
};

// Optionally, reset the chart size after printing
window.onafterprint = function() {
    // Reset the chart sizes if necessary
    traitChart.canvas.width = 600; // Reset to original size
    traitChart.canvas.height = 300;

    facetCharts.forEach(chart => {
        chart.canvas.width = 600;
        chart.canvas.height = 300;
    });

    // Redraw the charts after printing
    window.myTraitChart.update();
    facetCharts.forEach(chartInstance => chartInstance.update());
};
