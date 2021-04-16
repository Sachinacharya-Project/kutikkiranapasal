google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
var data = google.visualization.arrayToDataTable(coord);

var options = {
    title: 'Annual Transactions',
    curveType: 'function',
    legend: { position: 'bottom' }
};

var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

chart.draw(data, options);
}

const load_view = ()=>{
    const asked = document.getElementById('show_stat_').value
    if(asked === 'graph'){
        document.getElementById('curve_chart').classList.add('activate')
        document.getElementById('stat').classList.remove('activate')
    }else{
        document.getElementById('curve_chart').classList.remove('activate')
        document.getElementById('stat').classList.add('activate')
    }
}
/**
 
                                    <
                                    <tr>
                                        <td>2000</td>
                                        <td>20,000/-</td>
                                        <td>20,00/-</td>
                                        <td>2,00/-</td>
                                    </tr>
 */
const stat = document.getElementById('stat')
let output = '<table>';
let cnt = 0;
coord.forEach(item => {
    let dolla;
    if (cnt === 0){
        dolla = '';
    }else{
        dolla = '/-'
    }
    output += `
    <tr>
        <td>${item[0]}</td>
        <td>${item[1]}${dolla}</td>
        <td>${item[2]}${dolla}</td>
        <td>${item[3]}${dolla}</td>
    </tr>`
    cnt++;
})
output += '</table>';
stat.innerHTML = output;