<?php
/*
 emuparadl.github.io
 emuparadise.me Downloader
 https://mit-license.org
 04 2020 - ironsix

 Dragon Xploiter
*/

set_time_limit(0);
error_reporting(0);
$uri = "https://draxploit.web.id/emuparadl.github.io/page.php";
$sbm = isset($_REQUEST['sbm']);

if ($sbm) {
  $gid = trim($_POST['gid']);
  $url = "https://www.emuparadise.me/roms/get-download.php?gid=$gid&test=true";

  $date = date('Y-m-d H:i:s');
  $mdate = (strtotime($date) * 1000);
  $tempfile = fopen('php://temp', 'r+');
  $cinit = curl_init();
  curl_setopt($cinit, CURLOPT_TIMEOUT, "4");
  curl_setopt($cinit, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) QtWebEngine/5.13.1 Chrome/73.0.3683.105 Safari/537.36"); // UA mein browser
  curl_setopt($cinit, CURLOPT_URL, "$url");
  curl_setopt($cinit, CURLOPT_REFERER, "$url");
  curl_setopt($cinit, CURLOPT_POST, true);
  curl_setopt($cinit, CURLOPT_HEADER, true);
  curl_setopt($cinit, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($cinit, CURLOPT_VERBOSE, true);
  curl_setopt($cinit, CURLOPT_STDERR, $tempfile);
  $response = curl_exec($cinit);
  $httpcode = curl_getinfo($cinit, CURLINFO_HTTP_CODE);
  $header_size = curl_getinfo($cinit, CURLINFO_HEADER_SIZE);
  $header = substr($response, 0, $header_size);
  $body = substr($response, $header_size);
  curl_close($cinit);
  fclose($tempfile);

if (preg_match_all("/location\:\ ([^\r\n]+)/i", $header, $link)) {
  $link = $link[1][0];
    $data = [
      "date" => $mdate,
      "link" => $link,
      "status" => 1
    ];
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
  $link = null;
    $data = [
      "date" => $mdate,
      "link" => $link,
      "status" => 0
    ];
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    echo json_encode($data);
}

} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>emuparadl.github.io - emuparadise.me Downloader</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container" style="min-height:100%">
    <div class="form-group row d-flex flex-md-row justify-content-center">
      <div class="col-md-7 text-center">
        <h1 class="mt-5">emuparadl.github.io</h1>
        <p class="lead text-info small"><a href="\\emuparadise.me" target="_blank">emuparadise.me</a> Downloader <span><a href="https://github.com/ironsix/emuparadl.github.io" target="_blank">1.0</a></span></p>
        <noscript><strong class="text-danger">/!\ Please enable JavaScript</strong></noscript>
        <form name="emupara" method="post" onsubmit="return false;">
        <fieldset class="form-group">
        <label for="urle" class="font-weight-bold">URL ROM / ISO atau Game ID:</label>
        <input type="text" id="urle" name="urle" class="form-control" autofocus="autofocus" placeholder="https://www.emuparadise.me/Sony_Playstation_2_ISOs/ICO_(USA)/150725" required>
        <br>
        </fieldset>
        <div class="d-flex flex-md-row flex-column justify-control-between">
          <div class="col">
            <button type="submit" id="submit" class="btn btn-primary font-weight-bold mb-1" onclick="downloadE();">Get Download</button>
          </div>
          <div class="col">
            <button type="submit" id="reset" class="btn btn-warning font-weight-bold mb-1" onclick="resetE();">Reset URL</button>
          </div>
        </div>
        </form>
        <hr>
        <div id="result" class="text-center">
          <font class="font-weight-bold" style="word-wrap: break-word;">
          Contoh URL & Game ID:<br>
          https://www.emuparadise.me/Sony_Playstation_2_ISOs/ICO_(USA)/<font class="text-danger">150725</font><br>
          <font class="text-danger">150725</font> = Game ID
          </font>
          <hr>
          <pre>Gunakan browser modern seperti<br>Chrome / Firefox / Brave...</pre>
        </div>
      </div>
    </div>
  </div>
  <footer class="container text-right border-top p-2 small font-weight-bold">
    &#169; 2020. <a href="https://github.com/ironsix">ironsix</a>. Powered by <a href="//draxploit.web.id">Dragon Xploiter</a>.
  </footer>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script>
Swal.fire({position: 'center', icon: 'info', title: 'Notice!', html: "Gunakan browser modern seperti<br>Chrome / Firefox / Brave..."});

function nname(named){
  return named
    .replace(/\%20/g, '\ ')
    .replace(/\%5B/g, '[')
    .replace(/\%5D/g, ']')
    .replace(/\%28/g, '(')
    .replace(/\%29/g, ')')
    .replace(/\%21/g, '!')
    .replace(/\%26/g, '&')
    .replace(/\%27/g, '\'');
}


function downloadE() {
  let url = document.getElementById("urle").value;
  let id = url.split('/').pop();
  let btnchk = document.getElementById("submit");
  let result = document.getElementById("result");
  btnchk.disabled = true;
  btnchk.innerText = "Tunggu...";

  result.innerHTML = "";
  setTimeout(function() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "<?php echo $uri; ?>", true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    let params = "sbm&gid="+id;
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          console.log('%c '+this.status, 'background:#000;color:#859900;font-weight:bold;');
          console.log('%c '+url, 'background:#000;color:#8484ff;font-weight:bold;');
          btnchk.disabled = false;
          btnchk.innerText = "Get Download";
          let data = JSON.parse(xhr.responseText);
          let urld = data.link;
          let rslt = document.createElement("font");
          rslt.className = "font-weight-bold";
          rslt.innerHTML = "Hasil:";
          let br = document.createElement("br");
          if (data.status == 1) {
            let named = urld.split('/').pop();
            let dl = document.createElement("a");
            let dlText = document.createTextNode("Download: "+nname(named));
            dl.className = "btn btn-danger font-weight-bold mb-1 text-center";
            dl.href = urld;
            dl.target = "_blank";
            dl.appendChild(dlText);

            result.appendChild(rslt);
            result.appendChild(br);
            result.appendChild(dl);
            Swal.fire({position: 'center', icon: 'success', title: 'Proses Selesai!', showConfirmButton: false, timer:1000});
          } else {
            let err = document.createElement("font");
            err.className = "text-danger font-weight-bold";
            err.innerHTML = "ERROR - COBA LAGI";

            result.appendChild(rslt);
            result.appendChild(br);
            result.appendChild(err);
            Swal.fire({position: 'center', icon: 'error', title: 'Error!', showConfirmButton: false, timer:1300});
          }
        }
    }
    xhr.onerror = function() {
      console.log('%c '+url, 'background:#f00;color:#fff;font-weight:bold;');
      console.log('%c '+'ERROR', 'background:#f00;color:#fff;font-weight:bold;');
    };
    xhr.send(params);
  }, 3000);
}


function resetE() {
  document.getElementById('urle').value = "";
  document.getElementById('result').innerHTML = "";
}
</script>
</body>
</html>
<?php
}
?>
