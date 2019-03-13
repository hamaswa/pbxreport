@if(($type=="queue") OR ($type=="month") OR ($type=="week") OR $type=='day' OR $type=="hour" OR $type=="dayweek")
    <tr id="sub_tr">
        <td colspan="6" id="queue_sub" class="box">
            <table class="col-lg-12 subdata table dataTable">
                <thead>
                <tr>
                    <th>Call ID</th>
                    <th>Date</th>
                    <th>verb</th>
                    <th>agent</th>
                    <th>event</th>
                    <th>data</th>
                    <th>data1</th>
                    <th>data2</th>
                    <th>data3</th>
                    <th>data4</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $sub_data)

                    <tr>
                        <td>{{ $sub_data->call_id }}</td>
                        <td>{{ $sub_data->date }}</td>
                        <td>{{ $sub_data->verb }}</td>
                        <td>{{ $sub_data->agent }}</td>
                        <td>{{ $sub_data->event }}</td>
                        <td>{{ $sub_data->data }}</td>
                        <td>{{ $sub_data->data1 }}</td>
                        <td>{{ $sub_data->data2 }}</td>
                        <td>{{ $sub_data->data3 }}</td>
                        <td>{{ $sub_data->data4 }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </td>

    </tr>
    @endif
