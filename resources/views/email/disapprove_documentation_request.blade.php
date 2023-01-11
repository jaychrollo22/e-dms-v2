<h4>Hi {{$request->requestor_info->name}},</h4>

<p>
    Please be notified that your <strong> Documentation Request </strong> has been <strong>{{$request->status}}</strong> with Remarks : {{$request->status_remarks}}.
</p>

<center>
    <table style="border:1px solid black;border-collapse:collapse;width:60%">
        <tr>
            <td colspan="6" style="border:1px solid black;padding:3px">
                <center>
                    <font color="#000000" face="arial" style="FONT-SIZE:10pt">
                        <b>Document Request Details</b>
                    </font>
                </center>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid black;padding:3px">
                Date
            </td>
            <td style="border:1px solid black;padding:3px">
                DICR No.
            </td>
            <td style="border:1px solid black;padding:3px">
                Requestors Name
            </td>
            <td style="border:1px solid black;padding:3px">
                Title
            </td>
            <td style="border:1px solid black;padding:3px">
                Proposed Effective Date
            </td>
            <td style="border:1px solid black;padding:3px">
                Type of Request
            </td>
        </tr>
        <tr>
            <td style="border:1px solid black;padding:3px">
                {{date('F d, Y h:i A',strtotime($request->created_at)) }}
            </td>
            <td width="100px" style="border:1px solid black;padding:3px">
                {{ $request->dicr_number }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $request->requestor_info->name }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $request->title }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $request->proposed_effective_date }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $request->type_of_request }}
            </td>
        </tr>
    </table>
</center>

<p>
    Thank you,
</p>
<p>
    E-DMS
</p>
<p>
    ************ This is a system generated email. Please do not reply. ************
</p>
