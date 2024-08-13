document.addEventListener('DOMContentLoaded', function () {
  // Chart.js code to display the main personality trait scores
  const ctxTrait = document.getElementById('traitChart').getContext('2d');
  new Chart(ctxTrait, {
      type: 'bar',
      data: {
          labels: ['Agreeableness', 'Conscientiousness', 'Extraversion', 'Neuroticism', 'Openness'],
          datasets: [{
              label: 'Trait Scores',
              data: [
                  traitScores.agreeableness,
                  traitScores.conscientiousness,
                  traitScores.extraversion,
                  traitScores.neuroticism,
                  traitScores.openness
              ],
              backgroundColor: [
                  'rgba(0, 206, 63, 0.75)',
                  'rgba(0, 66, 207, 0.75)',
                  'rgba(255, 173, 0, 0.75)',
                  'rgba(255, 21, 119, 0.75)',
                  'rgba(127, 0, 213, 0.75)'
              ]
          }]
      },
      options: {
          scales: {
              y: {
                  beginAtZero: true,
                  max: 120
              }
          },
          plugins: {
              datalabels: {
                  color: 'white',
                  anchor: 'center',
                  align: 'center',
                  formatter: function(value) {
                      return value;
                  }
              }
          }
      },
      plugins: [ChartDataLabels]
  });

  // Define colors for facets
  const facetColors = [
        'rgba(0, 206, 63, 0.75)',
        'rgba(0, 66, 207, 0.75)',
        'rgba(255, 173, 0, 0.75)',
        'rgba(255, 21, 119, 0.75)',
        'rgba(127, 0, 213, 0.75)',
        'rgba(243, 117, 0, 0.75)'
  ];

  // Loop through each domain and create a bar chart for its facets
  for (const domain in facetScores) {
      if (facetScores.hasOwnProperty(domain)) {
          const ctxFacet = document.getElementById(domain + 'FacetChart').getContext('2d');
          const labels = Object.keys(facetScores[domain]);
          const data = Object.values(facetScores[domain]);
          const backgroundColor = facetColors.slice(0, data.length);

          new Chart(ctxFacet, {
              type: 'bar',
              data: {
                  labels: labels,
                  datasets: [{
                      label: traitNames[domain] + ' Facet Scores',
                      data: data,
                      backgroundColor: backgroundColor,
                      borderColor: backgroundColor.map(color => color.replace('0.8', '1')),  // Make the borders the same color
                      borderWidth: 1
                  }]
              },
              options: {
                  scales: {
                      y: {
                          beginAtZero: true,
                          max: 20
                      }
                  },
                  plugins: {
                      datalabels: {
                          color: 'white',
                          anchor: 'center',
                          align: 'center',
                          formatter: function(value) {
                              return value;
                          }
                      }
                  }
              },
              plugins: [ChartDataLabels]
          });
      }
  }
});
