			<?php

$html = '<!DOCTYPE HTML>
<html>
 <body> 
 <div style="width:940px;  background:#FFF;  padding:20px; font-family:Arial, Helvetica, sans-serif; margin:0 auto;">
 <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
	
      <td style="text-align: center;padding-bottom: 30px;"><span style="text-decoration: underline;font-weight: 600;">RECEIPT</span><h2 style="font-weight: 600; font-size:30px; margin: 0; text-align:center;">INTEGRATED SOCIAL INITIATIVES</h2>
		<span>10, Institutional Area, Lodhi Road, New Delhi-110003</span>
		</td>
		<td><span style="float: right;">Tel. :49534132</span>
		<p style="margin: 0;text-align: right;">49534133</p></td>
    </tr>
  </table>
  <div style="width:52%;float:left;">
  <table width="100%" cellpadding="5" border="0" style="font-size:13px;font-family:Arial, Helvetica, sans-serif;" cellspacing="0">
  <tr> 
	 <td> <strong>SL. NO.</strong><span style="padding-left:10px;"><strong style="font-size:18px"> 1813</strong></span></td>
	   <td align="right"><strong style="display:inline-block;">DATE
        </strong> <span style="border-bottom:1px dotted #000;">4/10/17</span></td>
    </tr>
	<tr>
              <td colspan="2">
              <strong>RECEIVED with thanks from</strong><span></span>
              
              </td> 
            </tr>
			 
			
			<tr>
              <td colspan="2">
              <strong>The sum of Rupees</strong><span>one Thousand seven hundred</span>
              
              </td> 
            </tr>
		
			<tr>
              <td  height="40px;">
              <strong>by RTGS/Cash/MO/Cheque/DD No</strong><span></span>
              
              </td> 
			  <td colspan="2">
              <strong>dt</strong><span></span>
              
              </td> 
            </tr>
          <tr>
              <td colspan="2">
              <strong>Drawn on</strong><span></span>
              
              </td> 
            </tr>
			
			<tr>
              <td colspan="2">
              <strong>on account of</strong><span></span>
              
              </td> 
            </tr>
			<tr>
              <td>
              <strong>U.S. $</strong><span></span>
              
              </td> 
			  <td colspan="2">
              <strong>Realised on</strong><span></span>
              
              </td> 
            </tr>
		
  </table>
  </div>
  <div style="width:48%;float:left" >
 <table width="100%" cellpadding="0" border="0" style="font-size:13px; border: 1px solid black;font-family:Arial, Helvetica, sans-serif;" cellspacing="0">
 <tr> 
 <th colspan="2" style="border-bottom: 1px solid #000;border-right: 1px solid #000;padding: 10px;width:1%;"> PARTICULARS </th>
 <th style="border-bottom: 1px solid #000;width: 30%;">Magazine</th>
 </tr> 
 <tr>
 <td style="border-right: 1px solid #000;padding: 5px;"> Subs. </td>
 <td style="border-right: 1px solid #000;padding: 5px;">Current </td>
 <td style="padding: 5px;">  </td>

 </tr> 
 <tr>
 <td style="border-right: 1px solid #000;padding: 5px;border-top: 1px solid #000;"> Subs.Adv. </td>
 <td style="border-right: 1px solid #000;padding: 5px;border-top: 1px solid #000;"> </td>
 <td style="padding: 5px;border-top: 1px solid #000;">  </td>
 
 </tr>
  <tr>
 <td style="border-right: 1px solid #000;padding: 5px;border-top: 1px solid #000;"> Subs.Adv. </td>
 <td style="border-right: 1px solid #000;padding: 5px;border-top: 1px solid #000;"> </td>
 <td style="padding: 5px;border-top: 1px solid #000;">  </td>
 
 </tr>
  <tr>
 <td style="border-right: 1px solid #000;padding: 5px;border-top: 1px solid #000;"> Subs.Adv. </td>
 <td style="border-right: 1px solid #000;padding: 5px;border-top: 1px solid #000;"> </td>
 <td style="padding: 5px;border-top: 1px solid #000;">  </td>
 
 </tr>
   <tr>
 <td style="padding: 5px;border-top: 1px solid #000;"> Life Membership </td>
 <td style="padding: 5px;border-top: 1px solid #000;"> </td>
 <td style="padding: 5px;border-top: 1px solid #000;">  </td>
 
 </tr>
    <tr>
 <td style="padding: 5px;border-top: 1px solid #000;"> Local Distribution </td>
 <td style="padding: 5px;border-top: 1px solid #000;"> </td>
 <td style="padding: 5px;border-top: 1px solid #000;">  </td>
 
 </tr>
 
 </table> 
 </div>
  <table width="100%" style="font-size:13px; font-family:Arial, Helvetica, sans-serif;margin-top:30px;" cellpadding="2" cellspacing="0">
      <tr>
       <td><p style="margin: 0;font-weight: 600;"><strong style="font-size:20px;">Rs.</strong><br/>Cheque subject to realisation</p> </td>
	   <td><p style="margin: 0;text-align:right;font-size: 20px;">For <strong>INTEGRATED SOCIAL INITIATIVES</strong></p> </td>
	   </tr>
  </table>
 </div>
 </body> 
 </html>
	';			
		

include("mpdf.php");
$mpdf=new mPDF('en-GB-x','A4','','',10,10,10,10,6,3);
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($html);
$file= rand().".pdf";
$path ="Subscriber-invoice/".$file;
$mpdf->Output($path,'F');
exit;
//==============================================================
//==============================================================
//==============================================================


?>