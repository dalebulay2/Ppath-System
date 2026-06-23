    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>PPATH Dashboard</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('Ppa.png') }}">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    </head>

    <body class="bg-gray-100">

    <style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #f3f4f6;
        color: #1f2937;
    }

    nav {
        background: #ffffff;
        padding: 15px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }

    nav b {
        font-size: 18px;
        color: #4f46e5;
        letter-spacing: 1px;
    }

    nav a {
        text-decoration: none;
        color: #dc2626;
        font-weight: bold;
    }

    main {
        max-width: 1100px;
        margin: 30px auto;
        padding: 20px;
    }

    .bg-white {
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        background: white;
    }

    thead {
        background: #f9fafb;
    }

    th,
    td {
        padding: 12px;
        border-bottom: 1px solid #e5e7eb;
        text-align: left;
    }

    tbody tr:hover {
        background: #f3f4f6;
    }

    .bg-blue-200 {
        background: #dbeafe;
        color: #1e3a8a;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: bold;
    }

    .bg-purple-200 {
        background: #ede9fe;
        color: #5b21b6;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: bold;
    }

    .bg-green-200 {
        background: #dcfce7;
        color: #166534;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: bold;
    }

    .bg-gray-200 {
        background: #e5e7eb;
        color: #374151;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        table {
            font-size: 14px;
        }

        nav {
            flex-direction: column;
            gap: 10px;
            text-align: center;
        }
    }
    </style>

   <nav class="border-b shadow-sm px-8 py-4 flex justify-between items-center"
     style="background-color: #2F4B63;">

      <div class="flex items-center gap-2">
    
    <!-- PPATH -->
    <span class="text-3xl font-bold text-indigo-600">
        PPATH
    </span>

    <!-- LOGO -->
    <img src="svg.png" alt="Logo" class="h-12 w-12 object-contain">

    <!-- SEPARATOR + TEXT -->
    <span class="text-white text-lg ml-2">
        | Monitoring System
    </span>

</div>

        <div class="flex items-center gap-6">

            <div class="text-right">
                <div class="text-xs uppercase text-white">
                    Logged In As
                </div>

                <div class="font-bold text-lg text-white">
                    Admin: {{ e(session('userName')) }}
                </div>
            </div>

            <a href="/logout"
            class="border border-red-500 text-red-500 px-5 py-2 rounded-lg hover:bg-red-50">
                Log Out
            </a>

        </div>

    </nav>
<div class="max-w-6xl mx-auto mt-6 flex justify-between items-center px-4">
    
    <h2 class="text-2xl font-bold text-gray-900">
        Registered Attendees Matrix
    </h2>

    
</div>
<div class="max-w-6xl mx-auto mt-6 flex justify-end px-4">
    <button onclick="addTable()"
        class=" text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
         style="background-color: #6F8DA6;">
        + New Table
    </button>
</div>
    
<main>
   <div id="tablesContainer" class="space-y-8">
    
    @forelse($activities as $activity)

        <div class="bg-white border rounded-2xl shadow-sm p-8">

          <div class="flex justify-between items-center mb-4">

   <div class="flex flex-col">
    <h3 class="text-2xl font-bold text-gray-900">
        {{ $activity->title }}
    </h3>

    <p class="text-sm text-gray-500 mt-1">
        {{ \Carbon\Carbon::parse($activity->date)->format('F d, Y') }}
    </p>
</div>

    

</div>
<div class="flex justify-end gap-2 mb-4">

    <button onclick="exportCSV(this)"
        class="px-3 py-1 rounded border"
        style="background-color: #D8DEE4;">
        Export CSV
    </button>

    <button onclick="exportExcel(this)"
        class=" text-white px-3 py-1 rounded"
        style="background-color: #0E1A24;">
        Export Excel
    </button>

</div>
<br>
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-blue-50 p-4 rounded">
        Total: 0
    </div>

    <div class="bg-green-50 p-4 rounded">
        Male: 0
    </div>

    <div class="bg-purple-50 p-4 rounded">
        Female: 0
    </div>
</div>
            <table class="w-full border attendance-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Category</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="5" class="text-center">
                            No attendees yet
                        </td>
                    </tr>
                </tbody>

            </table>
            <br>
<a href="javascript:void(0)"
   onclick="confirmDelete('/activities/delete/{{ $activity->id }}')"
   class="text-white px-4 py-2 text-sm rounded hover:bg-red-600"
   style="background-color: #CB0000;">
    Delete table
</a>
        </div>

    @empty

        <div id="emptyState" class="text-center text-gray-400 text-sm mt-6">
            No recent activity found yet.
        </div>

    @endforelse

</div>
</main>
      
    

    <script>

function exportExcel(button) {

    const tableBlock = button.closest('.bg-white');
    const table = tableBlock.querySelector('table');

    const workbook = XLSX.utils.table_to_book(table, {
        sheet: "PPATH Records"
    });

    XLSX.writeFile(workbook, "PPATH_Attendees_Report.xlsx");
}
   function exportCSV(button) {

    const tableBlock = button.closest('.bg-white');
    const table = tableBlock.querySelector('table');

    const rows = table.querySelectorAll('tr');

    let csv = [];

    rows.forEach(row => {
        const cols = row.querySelectorAll('th, td');
        let rowData = [];

        cols.forEach(col => {
            rowData.push(`"${col.innerText.trim()}"`);
        });

        csv.push(rowData.join(","));
    });

    const blob = new Blob([csv.join("\n")], { type: "text/csv" });

    const url = URL.createObjectURL(blob);

    const a = document.createElement("a");
    a.href = url;
    a.download = "PPATH_Attendees_Report.csv";

    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    URL.revokeObjectURL(url);
}
    </script>
<script>
function addTable() {

    const container = document.getElementById("tablesContainer");
    const emptyState = document.getElementById("emptyState");
if (emptyState) {
    emptyState.remove();
}

    const tableBlock = document.createElement("div");

    tableBlock.className = "bg-white border rounded-2xl shadow-sm p-8";

    tableBlock.innerHTML = `
        <!-- TITLE -->
        <div class="flex gap-2 mb-4">

    <div class="mb-4">

    <div class="flex gap-2">

        <input
            type="text"
            placeholder="Enter Activity / Day Title"
            class="activity-title flex-1 border p-2 rounded font-semibold">

        <button
            onclick="confirmTitle(this)"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Confirm
        </button>

    </div>

    <input
        type="date"
        class="activity-date border p-2 rounded w-56 mt-2">

</div>
</div>

        <!-- EXPORT BUTTONS -->
        <div class="flex justify-end gap-2 mb-4">
        <button onclick="exportCSV(this)" class=" px-3 py-1 rounded border"
        style="background-color: #D8DEE4;">
    Export CSV
</button>

<button onclick="exportExcel(this)" class=" text-white px-3 py-1 rounded"
style="background-color: #0E1A24;">
    Export Excel
</button>
        </div>

        <!-- SAMPLE STATS -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded">Total: 0</div>
            <div class="bg-green-50 p-4 rounded">Male: 0</div>
            <div class="bg-purple-50 p-4 rounded">Female: 0</div>
        </div>

        <!-- TABLE -->
        <table class="w-full border attendance-table">
            <thead class="bg-gray-100">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center text-gray-400 py-6">
                        No attendees yet
                    </td>
                </tr>
            </tbody>
        </table>
    `;

    // IMPORTANT: add NEW table on TOP
    container.insertBefore(tableBlock, container.firstChild);
}
function confirmDelete(url) {

    Swal.fire({
        title: 'Delete Table?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#CB0000',
        cancelButtonText: 'Cancel',
        confirmButtonText: 'Delete'
    }).then((result) => {

        if (result.isConfirmed) {
            window.location.href = url;
        }

    });

}
function confirmTitle(button) {

  const tableBlock = button.closest('.bg-white');

const title = tableBlock.querySelector('.activity-title').value.trim();

const date = tableBlock.querySelector('.activity-date').value;
   if (!title || !date) {
    Swal.fire({
    icon: 'warning',
   
    text: 'Please enter Title and a Date.',
    timer: 1500,
    showConfirmButton: false
});
    return;
}
    // SAVE TO DATABASE FIRST
    fetch('/activities', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
    title: title,
    date: date
})
    })  
    .then(res => res.json())
    .then(data => {

        if (data.success) {
const tableBlock = button.closest('.bg-white');

const titleSection = tableBlock.querySelector('.mb-4');

const formattedDate = new Date(date).toLocaleDateString(
    'en-US',
    {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }
);
const deleteBtn = document.createElement('a');

deleteBtn.href = `/activities/delete/${data.activity.id}`;
deleteBtn.onclick = () => confirm('Delete this table?');

deleteBtn.className =
    'text-white px-4 py-2 text-sm rounded hover:bg-red-600';

deleteBtn.style.backgroundColor = '#CB0000';

deleteBtn.textContent = 'Delete table';

tableBlock.appendChild(document.createElement('br'));
tableBlock.appendChild(deleteBtn);

titleSection.innerHTML = `
    <div class="flex flex-col">
        <h3 class="text-2xl font-bold text-gray-900">
            ${title}
        </h3>

        <p class="text-sm text-gray-500 mt-1">
            ${formattedDate}
        </p>
    </div>
`;

          Swal.fire({
    icon: 'success',
    title: 'Saved!',
    text: 'Activity created successfully.',
    timer: 1500,
    showConfirmButton: false
});
        }

    })
    .catch(err => {
        console.error(err);
        Swal.fire({
    icon: 'success',
    title: 'Saved!',
    text: 'Activity created successfully.',
    timer: 1500,
    showConfirmButton: false
});
    });
}

</script>
    
    </body>
    </html>
