<!DOCTYPE>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E=" crossorigin="anonymous"></script>
</head>
<style media="screen">
  body{
    display: block !important;
    visibility: visible !important;
  }
</style>
<body>

<?php
  		include_once("libs/curl_jquery.php");
  		include_once("libs/simple_html_dom.php");
  		require_once ("libs/PHPExcel-1.8/Classes/PHPExcel.php");

       //  $ip_url = 'https://api.ipify.org/';
       //  $pageip = file_get_contents($ip_url);
       // echo $pageip = str_get_html($pageip);


    $start_time = new DateTime();
  	$root_url = "https://www.toy.ru";
  	$url = "https://www.toy.ru/catalog/boy_toys_igrovye_nabory/";
  	$patterns = "/\/catalog\/boy_toys_igrovye_nabory/";
  	$page_url = preg_replace($patterns, "", $url);
  	$result = file_get_contents($url);
  	$start_page = str_get_html($result);
  	$category_name = "boy_toys_igrovye_nabory";
  	$category_id = 85;
    $image_collum = 2;
    $atriibuts_collum = 2;
    $num = 2;
    $image_name = 0;
    $product_id = 110;
  	//Для записи в ecxel
  	$objPHPExcel = new PHPExcel();
  	$second_sheet = $objPHPExcel->createSheet()->setTitle('AdditionalImages');
  	$ProductAttributes = $objPHPExcel->createSheet()->setTitle('ProductAttributes');
  	$objWritter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  	// функция для транспиляции, для поля seo
  	function rus2translit($string) {
  	    $converter = array(
  	        'а' => 'a',   'б' => 'b',   'в' => 'v',
  	        'г' => 'g',   'д' => 'd',   'е' => 'e',
  	        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
  	        'и' => 'i',   'й' => 'y',   'к' => 'k',
  	        'л' => 'l',   'м' => 'm',   'н' => 'n',
  	        'о' => 'o',   'п' => 'p',   'р' => 'r',
  	        'с' => 's',   'т' => 't',   'у' => 'u',
  	        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
  	        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
  	        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
  	        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
  	        'А' => 'A',   'Б' => 'B',   'В' => 'V',
  	        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
  	        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
  	        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
  	        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
  	        'О' => 'O',   'П' => 'P',   'Р' => 'R',
  	        'С' => 'S',   'Т' => 'T',   'У' => 'U',
  	        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
  	        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
  	        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
  	        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
  	    );
  	    return strtr($string, $converter);
  	}
  	function str2url($str) {
  	    $str = rus2translit($str);
  	    $str = strtolower($str);
  	    $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
  	    $str = trim($str, "-");
  	    return $str;
  	}
    // Парсинг по страницам
    $pagination_pages = $start_page->find('.pagination a');
    $page_count = $start_page->find('.pagination li',count($start_page->find('.pagination li'))-2)->plaintext;
    $full_link = $start_page->find('.pagination li', 2)->find('a',0)->href;
    $link = explode("=",$full_link);
    $link = $link[0]."=".$link[1]."=";
    // echo $pagination_link = $page->find('.pagination a',2)->href."<br>";
    // Создание папки картинок
    if(!(mkdir("./catalog/".$category_name."/", 0700))){
      mkdir("./catalog/".$category_name."/", 0700);
    };
  for($pagination = 1; $pagination <= $page_count; $pagination++) {
      // Парсинг по товарам на сранице
      // $some_text = $root_url.$link.$pagination;
      $newline = "https://www.toy.ru/catalog/boy_toys_igrovye_nabory/?filterseccode%5B0%5D=toys_igrovye_nabory&PAGEN_8=".$pagination;
      echo $newline."<br>";
      // echo $some_text."<br>";
      $page = file_get_contents($newline);
      $page = str_get_html($page);
      foreach($page->find('script,link,head') as $tmp)$tmp->outertext = '';

      $product_cards = $page->find('.product-card');
      // echo $product_cards."<br>";
    	foreach ($product_cards as $key => $value) {
    		$product_price = $value->find('.price span',1)->plaintext;
    		$product_price = preg_replace('/\s+/', '',$product_price);
        intval($product_price);
    		$product_link = $value->find('.product-name',0)->href;
    		$product_page = file_get_contents($root_url.$product_link);
    		$product_page = str_get_html($product_page);
        foreach($product_page->find('script,link,head') as $tmp)$tmp->outertext = '';

    		$characters = $product_page->find('.characters li[itemprop="sku"]',0)->plaintext;
    		$product_name = $product_page->find('h1.detail-name',0)->plaintext;
    		$product_articul = explode(":", $product_page->find('.characters li[itemprop="sku"]',0)->plaintext)[1];

        // if($key < 5){
        //   for ($p=0; $p <= 7; $p++) {
        //     $recomended_products +=  $p != 7 ? $product_id + $p."," : $product_id + $p;
        //   }
        // }
        // else{
        //   for ($p=-3; $p <= 4; $p++) {
        //     $recomended_products +=  $p != 4 ? $product_id + $p."," : $product_id + $p;
        //   }
        // }

          $new_arr = "";

    		//$image_url = $product_page->find('.detail-slider a',0)->href;
        $objPHPExcel->setActiveSheetIndex(0);
      	$active_sheet = $objPHPExcel->getActiveSheet();
    		$active_sheet = $objPHPExcel->getActiveSheet()->setTitle('Products');
    		$active_sheet->setCellValue("A".intval($num), $product_id);
    		$active_sheet->setCellValue("B".intval($num),"$product_name");
    		$active_sheet->setCellValue("C".intval($num),"86");
    		$active_sheet->setCellValue("L".intval($num),$product_articul);
    		$active_sheet->setCellValue("N".intval($num),"catalog/".$category_name."/".$key."-0.jpeg");
    		$active_sheet->setCellValue("O".intval($num),"yes");
    		$active_sheet->setCellValue("K".intval($num),0);
    		$active_sheet->setCellValue("P".intval($num),$product_price);
    		$active_sheet->setCellValue("R".intval($num),date("Y-m-d H:i:s"));
    		$active_sheet->setCellValue("AC".intval($num),str2url($product_name));
    		$active_sheet->setCellValue("AI".intval($num),"0");
    		$active_sheet->setCellValue("AN".intval($num),"true");
    		$active_sheet->setCellValue("AH".intval($num),0);

        // Добавление сопутствующих товаров
        // for ($p=1; $p <= 7; $p++) {
        //   $recomended_products =  $p != 7 ? $product_id+$p."," :  $product_id+$p;
        //   array_push($recomended_products)
        // }

        $active_sheet->setCellValue("AK".intval($num),$recomended_products);

        $num++;
    	   // $active_sheet->setCellValue("AB2","tax_class_id");
        // Тайтлы во втором листе эксель
        $objPHPExcel->setActiveSheetIndex(1);
        $active_sheet = $objPHPExcel->getActiveSheet();
        $active_sheet->setCellValue("A1","product_id");
    		$active_sheet->setCellValue("B1","image");
    		$active_sheet->setCellValue("C1","sort_order");

        // Парсинг по товару
    		for ($i=0; $i < count($product_page->find('.detail-slider a')); $i++) {
          //  Запись в ecxel
          $active_sheet->setCellValue("A".intval($image_collum),$product_id);
          $active_sheet->setCellValue("B".intval($image_collum),"catalog/".$category_name."/".$image_name."-".$i.".jpeg");
          $active_sheet->setCellValue("C".intval($image_collum), 0);
          $image_collum++;
      		$ch = curl_init($product_page->find('.detail-slider a',$i)->href);
    			$fp = fopen('./catalog/'.$category_name."/".$image_name.'-'.$i.'.jpeg', 'wb');
    			curl_setopt($ch, CURLOPT_FILE, $fp);
    			curl_setopt($ch, CURLOPT_HEADER, 0);
    			curl_exec($ch);
    			curl_close($ch);
    			fclose($fp);
    		}

        $image_name ++;

    		//Атрибуты продукта
        $objPHPExcel->setActiveSheetIndex(2);
        $active_sheet = $objPHPExcel->getActiveSheet();
        $active_sheet->setCellValue("A1","product_id");
        $active_sheet->setCellValue("B1","attribute_group");
        $active_sheet->setCellValue("C1","attribute");
        $active_sheet->setCellValue("D1","text(ru-ru)");
    		$arrttArray = [];
    		$atrrContainer = $product_page->find('.characters ul li');
    		for($j = 0 ; $j < count($atrrContainer)-3; $j++){
    			// Атрибут = $atrrContainer[$j][0];
    			// Текст атрибута = $atrrContainer[$j][1]:
          $recomendAge = explode(" – ",$atrrContainer[$j]->plaintext);
          $atrr = explode(":",$atrrContainer[$j]->plaintext);
          if(preg_match('/Рекомендуемый возраст/i',$atrrContainer[$j])){
            $active_sheet->setCellValue("A".intval($atriibuts_collum),$product_id);
            $active_sheet->setCellValue("B".intval($atriibuts_collum),"Accessories");
            $active_sheet->setCellValue("C".intval($atriibuts_collum),"Рекомендуемый возраст");
            $active_sheet->setCellValue("D".intval($atriibuts_collum), preg_replace('/[^\S\r\n]+/', ' ', $recomendAge[1]));
          }
          elseif (preg_match('/Размер упаковки/i',$atrrContainer[$j])) {
            $active_sheet->setCellValue("A".intval($atriibuts_collum),$product_id);
            $active_sheet->setCellValue("B".intval($atriibuts_collum),"Accessories");
            $active_sheet->setCellValue("C".intval($atriibuts_collum),"Размер упаковки");
            $active_sheet->setCellValue("D".intval($atriibuts_collum), preg_replace('/[^\S\r\n]+/', ' ', $atrr[1]));
          }
          else{
            $active_sheet->setCellValue("A".intval($atriibuts_collum),$product_id);
            $active_sheet->setCellValue("B".intval($atriibuts_collum),"Accessories");
            $active_sheet->setCellValue("C".intval($atriibuts_collum),preg_replace('/\s+/', '',$atrr[0]));
            $active_sheet->setCellValue("D".intval($atriibuts_collum), preg_replace('/[^\S\r\n]+/', ' ', $atrr[1]));
          }
          $atriibuts_collum++;
    		}
    	  $objWritter->save($category_name.'.xlsx');
        $hide = '-false';
        $product_id++;

        // подчищаем за собой
        $product_page->clear();
        unset($product_page);
        break;
    	}
        echo "Обработана ".$key." товаров на ".$pagination." страницах"."<br> id последненго товара - ".$product_id;
        echo "<br> Работа скрипта: ".$years = date_diff($start_time, new DateTime())->s." секунд"."<br>";


        // подчищаем за собой
        $page->clear();
        unset($page);
   }
  exit();
  	?>

</body>
</html>
