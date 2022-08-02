@component('mail::message')
# List of Vehicles that is expired / soon to be expired.

<h4>EXPIRED</h4>
<table border="1" style=" width: 800px;">
    <thead>
    <tr>
            <th width="50">#</th>
            <th width="100">Plate No</th>
            <th width="200">Car</th>
            <th width="50">Year</th>
            <th width="150">Chassis No.</th>
            <th width="125">Reg. Expiration</th>
            <th width="125">Ins. Expiration</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['details']['expired'] as $k => $v)
        <tr>
            <td>{{ $k+1 }}</td>
            <td>{{ $v['plate_no'] }}</td>
            <td>{{ $v['title'] }}</td>
            <td>{{ $v['year'] }}</td>
            <td>{{ $v['chassis_no'] }}</td>
            <td>{{ $v['registration_expiry'] }}</td>
            <td>{{ $v['insurance_expiry'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table> 
<br/>
<h4>SOON TO EXPIRE!</h4>
<table border="1" style="border-collapse:collapse; width: 800px;">
    <thead>
        <tr>
            <th width="50">#</th>
            <th width="100">Plate No</th>
            <th width="200">Car</th>
            <th width="50">Year</th>
            <th width="150">Chassis No.</th>
            <th width="125">Reg. Expiration</th>
            <th width="125">Ins. Expiration</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['details']['expire_soon'] as $k => $v)
        <tr>
            <td>{{ $k+1 }}</td>
            <td>{{ $v['plate_no'] }}</td>
            <td>{{ $v['title'] }}</td>
            <td>{{ $v['year'] }}</td>
            <td>{{ $v['chassis_no'] }}</td>
            <td>{{ $v['registration_expiry'] }}</td>
            <td>{{ $v['insurance_expiry'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table> 
  
@endcomponent