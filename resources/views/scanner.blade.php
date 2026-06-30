<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPATH QR Scanner</title>

    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: #f4f6f9;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .container {
            width: 100%;
            max-width: 720px;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .header {
            background: #1f4e79;
            color: #ffffff;
            padding: 25px 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.95;
        }

        .content {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            font-size: 15px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        select {
            width: 100%;
            padding: 12px 15px;
            font-size: 15px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background: #fff;
            outline: none;
            transition: .2s ease;
        }

        select:focus {
            border-color: #1f4e79;
            box-shadow: 0 0 0 3px rgba(31,78,121,.15);
        }

        #reader {
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            overflow: hidden;
            background: #fafafa;
            padding: 10px;
        }

        .footer {
            text-align: center;
            padding: 18px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 13px;
            background: #fafafa;
        }

        @media (max-width: 768px) {

            body {
                padding: 15px;
            }

            .header {
                padding: 20px;
            }

            .content {
                padding: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>PPATH QR Scanner</h1>
        <p>Scan participant QR codes to record attendance.</p>
    </div>

    <div class="content">

        <div class="form-group">
            <label for="activity_id">Select Attendance Activity</label>

            <select id="activity_id">
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">
                        {{ $activity->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div id="reader"></div>

    </div>

    <div class="footer">
        PPATH Attendance Monitoring System
    </div>

</div>

<script>

let isScanning = false;

function onScanSuccess(decodedText) {

    if (isScanning) return;
    isScanning = true;

    console.log("Scanned:", decodedText);

    let activity_id = document.getElementById('activity_id').value;

    // Expected format: Name/Gender
    let data = decodedText.split("/");

    if (data.length !== 2) {
        alert("Invalid QR Format!\nExpected: Name/Gender");
        resetScanner();
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
    .then(response => response.json())
    .then(data => {

        console.log(data);

        if (data.success) {
            alert('Attendance Recorded Successfully');
        } else {
            alert(data.message || 'Failed to record attendance');
        }

    })
    .catch(error => {
        console.error(error);
        alert("An error occurred while recording attendance.");
    })
    .finally(() => {
        resetScanner();
    });
}

function resetScanner() {
    setTimeout(() => {
        isScanning = false;
    }, 2000);
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
