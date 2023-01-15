<h4>Hi {{$document->process_owner_info->name}},</h4>

<p>
    Please be notified that you have <strong> document </strong> to <strong>discuss</strong> in <strong>E-DMS</strong>.
</p>

<center>
    <table style="border:1px solid black;border-collapse:collapse;width:60%">
        <tr>
            <td colspan="6" style="border:1px solid black;padding:3px">
                <center>
                    <font color="#000000" face="arial" style="FONT-SIZE:10pt">
                        <b>Document Details</b>
                    </font>
                </center>
            </td>
        </tr>
        <tr>
            <td style="border:1px solid black;padding:3px">
                Control Code
            </td>
            <td style="border:1px solid black;padding:3px">
                Title
            </td>
            <td style="border:1px solid black;padding:3px">
                Company
            </td>
            <td style="border:1px solid black;padding:3px">
                Department
            </td>
            <td style="border:1px solid black;padding:3px">
                Category
            </td>
            <td style="border:1px solid black;padding:3px">
                Effective Date
            </td>
        </tr>
        <tr>
            <td width="100px" style="border:1px solid black;padding:3px">
                {{ $document->control_code }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $document->title }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $document->company_info ? $document->company_info->company_name : "" }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $document->department_info ? $document->department_info->department : "" }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $document->document_category_info ? $document->document_category_info->category_description : "" }}
            </td>
            <td style="border:1px solid black;padding:3px">
                {{ $document->effective_date }}
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
