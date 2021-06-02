<table class="table table-bordered ">
    <thead>
        <tr>
            <th class="text-left"> Files</th>
        </tr>
    </thead>
    <tbody>
        @foreach($files as $key => $file)
            <tr>
                
                <td class="text-left">
                   {{ $file }}
                </td>
            </tr>
        
        @endforeach
    </tbody>
</table>