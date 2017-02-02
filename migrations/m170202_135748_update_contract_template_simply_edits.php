<?php

use yii\db\Migration;

class m170202_135748_update_contract_template_simply_edits extends Migration
{
    public function up()
    {
        $description = '<table width="570" style="max-width: 570px; margin-left: auto; margin-right: auto; border-collapse: collapse;">

<tr style = "height: 100%; box-sizing: border-box; border-collapse: collapse;">
    <td width="285" style =" vertical-align: top; border-left: 1px solid black; border-right: 1px solid black; height: 100%; box-sizing: border-box; border-collapse: collapse; padding: 5px; font-family:\'Times New Roman\';font-size:10px; padding: 5px;">
        <table width="285" style="margin:0;border-collapse: collapse;border: 0;">
            <tr>
                <td align="center" style="margin: 0; font-family:\'Times New Roman\';font-size:10px;">����������</td>
            </tr>
            <tr>
                <td align="justify" style="margin: 0; font-family:\'Times New Roman\';font-size:10px;">
                    <p><span style="color: #ffffff;">.</span></p>
                    <p>����������: <strong>������� ������ �������</strong></p>
                    <p>������ �����������: <strong>UA 08294</strong></p>
                    <p><strong>������� ���., �. ����</strong></p>
                    <p><strong>���. �����i����� �.8� ��.128</strong></p>
                    <p>������� �����������: <strong>26002057002108</strong></p>
                    <p>SWIFT ���: <strong>PBANUA2X</strong></p>
                    <p>���� �����������: <strong>Privatbank, Dnipropetrovsk, Ukraine</strong></p>
                    <p>IBAN Code: <strong>UA323515330000026002057002108</strong></p>
                    <p>����-�������������: <strong>JP Morgan</strong></p>
                    <p><strong>Chase Bank,New York ,USA</strong></p>
                    <p>������� � �����-�������������: <strong>001-1-000080</strong></p>
                    <p>SWIFT ��� �������������: <strong>CHASUS33</strong></p>
                </td>
            </tr>
        </table>
    </td>
    <td width="284" style =" vertical-align: top; border-collapse: collapse; border-left: 1px solid black; border-right: 1px solid black; height: 100%; box-sizing: border-box; padding: 5px; font-family:\'Times New Roman\'; font-size:10px; padding: 5px;">
        <table width="284" style="margin:0;border-collapse: collapse;border: 0;">
            <tr>
                <td align="center" style="margin: 0; font-family:\'Times New Roman\';font-size:10px;">Contractor</td>
            </tr>
            <tr>
                <td align="justify" style="margin: 0; font-family:\'Times New Roman\';font-size:10px;">
                    <p><span style="color: #ffffff;">.</span></p>
                    <p>BENEFICIARY: <strong>Prozhoga Oleksii Yuriyovich</strong></p>
                    <p>BENEFICIARY ADDRESS: <strong>UA 08294 Kiyv,</strong></p>
                    <p><strong>Bucha, Tarasivska st. 8a/128</strong></p>
                    <p><span style="color: #ffffff;">.</span></p>
                    <p>BENEFICIARY ACCOUNT: <strong>26002057002108</strong></p>
                    <p>SWIFT CODE: <strong>PBANUA2X</strong></p>
                    <p>BANK OF BENEFICIARY: <strong>Privatbank,</strong></p>
                    <p><strong>Dnipropetrovsk, Ukraine</strong></p>
                    <p>IBAN Code: <strong>UA323515330000026002057002108</strong></p>
                    <p>CORRESPONDENT BANK: <strong>JP Morgan</strong></p>
                    <p><strong>Chase Bank,New York ,USA</strong></p>
                    <p>CORRESPONDENT ACCOUNT: <strong>001-1-000080</strong></p>
                    <p>SWIFT Code of correspondent bank: <strong>CHASUS33</strong></p>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>';
$content = '
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<table width="570" style=" margin-left: auto; margin-right: auto; border-collapse: collapse;">
    <tr style = "height: 100%; box-sizing: border-box; border-collapse: collapse; ">
        <td style =" vertical-align: top; border: 1px solid black; height: 100%; box-sizing: border-box; border-collapse: collapse; padding: 5px 4px 5px 4px;">
            <table width="285" style="margin:0;border-collapse: collapse;border: 0;">
                <tr>
                    <td align="center" style="margin: 0; font-family:\'Times New Roman\';font-size:10px;"><strong>�������� �var_contract_id</strong></strong></td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>�� ������� ������</strong></td>
                </tr>
                <tr>
                    <td align="right" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">var_start_date</td>
                </tr>
                <tr>
                    <td align="right" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><span style="color: #ffffff;">.</span></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;">
                        <p style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">
                            <span style="color: #ffffff;">.....</span>������� "var_company_name" ���
                            �� ������ "��������" � ������� ��� ������� �.�.,
                            ������,� ���� ������� ������ ��������,
                            ������ �� ������ ���������
                            �22570000000001891 �� 01.05.2001�. ��� ��
                            ������ "����������", ��� �� ������ �������,
                            ������ ��� �������� ��� ��������:<br><span style="color: #ffffff;">.</span><br>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>1. ������� ���������</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"> 1.1.���������� �����\'������� �� ���������
                        ��������� ������ ������� �������:
                        �������� ����������� ������������(���
                        �����)
                    </td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>2. ֳ�� � �������� ���� ���������</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">2.1. ������� ������� �������������� � <strong>$var_total</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">2.2.  �������� ���� ��������� ��������� <strong>$var_total</strong></td>
                </tr>
                <tr>
                    <td align="left" style="margin: 0;letter-spacing:0px;font-family:\'Times New Roman\';font-size:10px;">
                        2.3.� ��� ���� ���� ��������� �� ������
                        �����, ������� �����\'�������� �������� ��������� ����� �� ������ ��������� ���
                        ��������� ��� ��������� �������� ���� ���������.<br><span style="color: #ffffff;">.</span></td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>3. ����� �������</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">3.1.�������� ������� ������ ����������
                        ��������� �� ������� ��������� �������� 5
                        ����������� ��� � ������� ��������� ����
                        �������-�������� ������� ������.</td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">
                        3.2. �������� ������� ������ ��������</td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">
                        3.3. ������ ������� � USD.</td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>4. ����� ������� ������</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">4.1.���������� ���� ������� �� ������
                        ����� ��������� � ������� �� �����.</td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>5. ³������������ �����</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">5.1.������� �����\'�������� �����
                        ������������� �� ����������� ���
                        ��������� ��������� �����\'����� �� ���
                        ����������.</td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>6. ������糿</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">6.1 ������糿 ���� ����� ������� �� �����
                        ���������� ������ ������ ���� ����\'�����
                        �� ����� 3 ������� ��� � ��� ���������
                        ���� �������-�������� ������� ������.</td>
                </tr>
            </table>
        </td>
        <td style =" vertical-align: top; border-collapse: collapse; border: 1px solid black; height: 100%; box-sizing: border-box; padding: 5px 4px 5px 4px;">
            <table width="285" style="margin:0;border-collapse: collapse;border: 0;">
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>CONTRACT �var_contract_id</strong></td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>FOR SERVICES</strong></td>
                </tr>
                <tr>
                    <td align="right" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">var_start_date</td>
                </tr>
                <tr>
                    <td align="right" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><span style="color: #ffffff;">.</span></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;">
                        <p style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">
                            <span style="color: #ffffff;">.....</span>The company "var_company_name"
                            hereinafter referred to as "Customer" and the
                            company "<strong>FOP Prozhoga O.Y.</strong>" Ukraine,
                            represented by Prozhoga Oleksii Yuriyovich, who is
                            authorized by check �22570000000001891 from
                            01.05.2001, hereinafter referred to as "Contractor",
                            and both Companies hereinafter referred to as
                            "Parties", have c� �oncluded the present Contract as
                            follows:
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>1. Subject of the Contract</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">1.1.The Contractor undertakes to provide the
                        following services to Customer: Software
                        development (web site)<br><span style="color: #ffffff;">.</span></td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>2. Contract Price and total sum</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">2.1.The price for the Services is established in
                        <strong>$var_total</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">
                        2.2.The preliminary total sum of the Contract
                        makes <strong>$var_total</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">
                        2.3.In case of change of the sum of the Contract,
                        the Parties undertake to sign the additional
                        agreement to the given Contract on increase or
                        reduction of a total sum of the Contract.</td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>3. Payment Conditions</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">3.1.The Customer shall pay by bank transfer to
                        the account within 5 calendar days from the date
                        of signing the acceptance of the Services.</td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">
                        3.2. Bank charges are paid by customer.</td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">
                        3.3. The currency of payment is USD.<br><span style="color: #ffffff;">.</span><br></td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>4. Realisation Terms</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">4.1.The Contractor shall deliver of the services on
                        consulting services terms.</td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>5. The responsibility of the Parties</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">5.1. The Parties under take to bear the
                        responsibility for default or inadequate
                        performance of obligations under the present
                        contract</td>
                </tr>
                <tr>
                    <td align="center" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;"><strong>6. Claims</strong></td>
                </tr>
                <tr>
                    <td align="justify" style="margin: 0;font-family:\'Times New Roman\';font-size:10px;">6.1.Claims of quality and quantity of the services
                        delivered according to the present Contract can be
                        made not later 3 days upon the receiving of the
                        Goods.</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
';

$this->update('contract_templates', ['content' => $content], 'name=:name', [':name' => 'Default template']);
$this->update('payment_methods', ['description' => $description], 'name=:name', [':name' => 'Default payment method']);


}

public function down()
{
echo "m170201_134821_update_contract_template cannot be reverted.\n";

return false;
}

/*
// Use safeUp/safeDown to run migration code within a transaction
public function safeUp()
{
}

public function safeDown()
{
}
*/
}