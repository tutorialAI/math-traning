<?php
function curl_get($url, $referer = "www.google.com/")
{
		//"89.250.10.14:56422",
		// $proxies = ["95.31.211.160:54623"];
		// $proxys_file = fopen("result/proxy_file.txt", "r");
		//
		// if ($proxys_file) {
		// 		while (($buffer = fgets($proxys_file, 4096)) !== false) {
		// 				$proxies = (explode(",",$buffer));
		// 		}
		// 		if (!feof($proxys_file)) {
		// 				echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
		// 		}
		// 		fclose($proxys_file);
		// }



		// $steps = count($proxies);
		// $step = 1;
		// $try = true;
		// while($try){
				// create curl resource
				$ch = curl_init();
				// $proxy = isset($proxies[$step]) ? $proxies[$step] : null;
				$proxy = $proxies[$step];

				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_REFERER, $referer);
				curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
				curl_setopt($ch, CURLOPT_URL, $url);
				// curl_setopt($ch, CURLOPT_PROXY, $proxy);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

				$output = curl_exec($ch); // get content
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Получаем HTTP-код

				// close curl resource to free up system resources
				curl_close($ch);

				// $step++;
				// $try = (($step <= $steps) && ($http_code != 200));
				// echo $try."<br>";
		// }
		return $output;
}
?>
