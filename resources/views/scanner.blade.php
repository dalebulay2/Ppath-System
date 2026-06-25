<!DOCTYPE html>
<html>
<head>
    <title>PPATH QR Scanner</title>

    <script src="https://unpkg.com/html5-qrcode"></script>

</head>
<body>

    <h1>PPATH QR Scanner</h1>

    <h3>Select Attendance Table</h3>

    <select id="activity_id">

        @foreach($activities as $activity)

            <option value="{{ $activity->id }}">
                {{ $activity->title }}
            </option>

        @endforeach

    </select>

    <br><br>

    <div id="reader"></div>


    <script>

function onScanSuccess(decodedText) {

    // Show what was scanned
    alert("Scanned: " + decodedText);

    let activity_id = document.getElementById('activity_id').value;

    let data = decodedText.split(/[\/|]/);

    // Check if QR format is correct
    if(data.length !== 2){
        alert("Invalid QR Format!\nExpected: Name|Gender");
        return;
    }

    let name = data[0].trim();
    let gender = data[1].trim();

    fetch('/scanner/save', {

        method: 'POST',

        headers: {

            'Content-Type': 'application/json',

            'X-CSRF-TOKEN': '{{ csrf_token() }}'

        },

        body: JSON.stringify({

            activity_id: activity_id,
            name: name,
            gender: gender

        })

    })

  .then(async response => {

    let text = await response.text();

    console.log(text);

    alert(text);

    return JSON.parse(text);

})

.then(data => {

    console.log(data);

    if(data.success){

        alert('Attendance Recorded Successfully');

    }else{

        alert(data.message);

    }

})

   .then(data => {

    console.log(data);

    if(data.success){

        alert('Attendance Recorded Successfully');

    }else{

        alert(data.message);

    }



    })

    .catch(error => {

    console.log(error);

    alert("Error:\n" + error);

});

}



let html5QrcodeScanner = new Html5QrcodeScanner(

    "reader",

    {
        fps: 10,
        qrbox: 250
    }

);


html5QrcodeScanner.render(onScanSuccess);

</script>
</body>
</html>
