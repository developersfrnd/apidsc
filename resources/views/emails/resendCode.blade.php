@extends('emails.layouts')
<!-- START MAIN CONTENT AREA -->
@section('content')
<tr>
    <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
        <tr>
            <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi <?php echo $user->name;?>,</p>            
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Please verify your email with below code </p>
            <p>
                <?php
                 echo $user->remember_token
                ?>
            </p>
            </td>
        </tr>
        
        <tr>
            <td height="20"></td>
        </tr>
        <tr>
            <td>
            <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;"><strong>Best Regards,</strong><br>Team DesiSexiChat  </p>
            

            </td>
        </tr>
        </table>
    </td>
</tr>    
@endsection

            
              
