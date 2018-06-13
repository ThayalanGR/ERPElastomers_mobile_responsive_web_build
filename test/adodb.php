<?php
$conn = new COM("ADODB.Connection") or die("ADODB Oops!");
$conn->Open("DRIVER={Microsoft Access Driver (*.mdb)};DBQ=F:\Stock.mdb");
$data = $conn->Execute("SELECT AutoId,batch,ts2,tc90 FROM RheoData where AutoId > 100 ORDER BY AutoId ASC");

print "<TABLE border='1'><TR><TD colspan='3'>DATA</TD><TR>";
while (!$data->EOF)
{
print "<tr>";
print "<td>" . $data ->Fields[0]->value . " </td>";
print "<td>" . $data ->Fields[1]->value . " </td>";
print "<td>" . $data ->Fields[2]->value . " </td>";
print "</tr>";
$data ->MoveNext();
}
echo "</TABLE>";
?>