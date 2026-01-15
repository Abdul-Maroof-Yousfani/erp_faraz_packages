<?php
$counter = 1;
?>
<div class="table-responsive wrapper">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="userlittab table table-bordered table-hover tableFixHead" id="exportList">
        <thead>
        <th>S.No</th>
        <th>Frequency</th>
        <th>Action</th>
        </thead>
        <tbody>
        @foreach ($data as $key => $value)
            <tr>
                <td>{{ $counter++ }}</td>
                <td>{{ $value->frequency }}</td>
                <td>
                    <div class="dropdown">
                        <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                            ...
                        </button>
                        <ul class="dropdown-menu">
                            <li onclick="showMasterEditModal('editFrequencyForm','{{ $value->id }}','Edit Frequency Form','')">
                                <a class="edit-modal">Edit</a>
                            </li>
                            <li onclick="deleteTableRecord('{{ $value->id }}','assets_frequency')">
                                <a class="delete-modal">Delete</a>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        var table = $('#exportList').DataTable({
            "paging": false,
            "ordering": false,
            "info": false,
            "searching": true, // Enable search feature
            // Add more configurations as needed
        });

        // Hide the search input
        $('#exportList_filter').addClass('hidden');

        // Add search functionality
        $('#searchInput').on('keyup', function () {
            table.search(this.value).draw();
        });
    });
</script>