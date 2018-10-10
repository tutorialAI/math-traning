<?php
function curl_get($url, $referer = "www.google.com/")
{
		$proxies = ["89.250.10.14:56422","191.36.172.66:55952"];
		$steps = count($proxies);
		$step = 0;
		$try = true;
		echo $proxies[$step]."<br>";
		while($try){
				// create curl resource
				$ch = curl_init();
				// $proxy = isset($proxies[$step]) ? $proxies[$step] : null;
				$proxy = $proxies[$step];

				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_REFERER, $referer);
				curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_PROXY, $proxy);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //return the transfer as a string
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

				$output = curl_exec($ch); // get content
				$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Получаем HTTP-код

				// close curl resource to free up system resources
				curl_close($ch);

				$step++;
				$try = (($step < $steps) && ($http_code != 200));
				echo $http_code."<br>";
		}
		return $output;
}
?>
