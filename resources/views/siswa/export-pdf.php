{{-- filepath: resources/views/siswa/export-pdf.blade.php --}}
<table border="1" cellpadding="5" cellspacing="0" width="100%">
    <thead>
        <tr>
            @foreach ($headings as $heading)
                <th>{{ $heading }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($rows as $row)
            <tr>
                @foreach ($row as $cell)
                    <td>{{ $cell }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
