
$(document).ready(function () {
    $("select#covid19APIReport-countries")
        .change(function () {
            let country = "";
            $("select#covid19APIReport-countries option:selected").each(function () {
                country =  $(this).val();
            });

            const request = new XMLHttpRequest();
            request.open('GET', 'https://api.covid19api.com/total/country/'+country+'');
            request.onload = function () {
                if (request.status >= 200 && request.status < 400) {
                    let data = JSON.parse(request.responseText);
                    displayContent(data);
                    $('#covid19APIReport_display').DataTable(
                        {
                            "order": [[ 1, "desc" ]]
                        }
                    );
                } else {
                    console.log('Status: ' + request.status);
                }
            };
            request.onerror = function () {
                console.log('Connection error.');
            };
            request.send();
        });
});

function displayContent(data)
{
    const container = document.getElementById('covid19APIReport-display-details');
    let output = `<h5>${data[0].Country}</h5>`;
    output += `<table id="covid19APIReport_display" class="display" width="100%">
    <thead>
        <tr>
            <th>Date</th>
            <th>Confirmed</th>
            <th>Deaths</th>
            <th>Recovered</th>
            <th>Active</th>
        </tr>
    </thead><tbody>`
    ;
    for ( let i = (data.length-1); i > 0; i-- ) {
        const date   = new Date(data[i].Date);
        output += `
        <tr>
            <td>${date.getDate()}/${date.getMonth()+1}/${date.getFullYear()}</td>
            <td>`+parseInt(data[i].Confirmed).toLocaleString()+`</td>
            <td>`+parseInt(data[i].Deaths).toLocaleString()+`</td>
            <td>`+parseInt(data[i].Recovered).toLocaleString()+`</td>
            <td>`+parseInt(data[i].Active).toLocaleString()+`</td>
        </tr>
        `;
    }
    output += `</tbody></table>`;
    container.innerHTML = output;
}

