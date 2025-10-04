<style>
.b128-table {
  border-collapse: collapse;
  margin: 0;
  padding: 0;
}
.b128-bar-wrapper {
  display: inline-block;
  transform: scaleX(0.5); /* Compress barcode horizontally */
  transform-origin: left;
}
.b128-bar {
  display: inline-block;
  background-color: #000;
  height: 30px;
  width: 1px;
  margin-right: 1px;
  vertical-align: bottom;
}
.b128-text {
  font-family: Arial, sans-serif;
  font-size: 10px;
  margin-top: 2px;
  text-align: left;
  color: #333;
}
</style>

<?php
global $char128asc, $char128wid;
$char128asc = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
$char128wid = array(
  '212222','222122','222221','121223','121322','131222','122213','122312','132212','221213',
  '221312','231212','112232','122132','122231','113222','123122','123221','223211','221132',
  '221231','213212','223112','312131','311222','321122','321221','312212','322112','322211',
  '212123','212321','232121','111323','131123','131321','112313','132113','132311','211313',
  '231113','231311','112133','112331','132131','113123','113321','133121','313121','211331',
  '231131','213113','213311','213131','311123','311321','331121','312113','312311','332111',
  '314111','221411','431111','111224','111422','121124','121421','141122','141221','112214',
  '112412','122114','122411','142112','142211','241211','221114','413111','241112','134111',
  '111242','121142','121241','114212','124112','124211','411212','421112','421211','212141',
  '214121','412121','111143','111341','131141','114113','114311','411113','411311','113141',
  '114131','311141','411131','211412','211214','211232','23311120'
);

function bar128($text) {
  global $char128asc, $char128wid;
  $w = $char128wid[$sum = 104];
  $onChar = 1;

  for ($x = 0; $x < strlen($text); $x++) {
    if (($pos = strpos($char128asc, $text[$x])) !== false) {
      $w .= $char128wid[$pos];
      $sum += $onChar++ * $pos;
    }
  }

  $w .= $char128wid[$sum % 103] . $char128wid[106];

  // Generate compressed barcode HTML
  $html = "<table class='b128-table'><tr><td style='white-space:nowrap'><div class='b128-bar-wrapper'>";
  for ($x = 0; $x < strlen($w); $x += 2) {
    $barWidth = intval($w[$x]);
    $barPixelWidth = intval($w[$x + 1]) * 1;
    $html .= "<div class='b128-bar' style='transform:scaleX($barWidth); width:{$barPixelWidth}px;'></div>";
  }
  $html .= "</div><div class='b128-text'>{$text}</div></td></tr></table>";

  return $html;
}
?>
