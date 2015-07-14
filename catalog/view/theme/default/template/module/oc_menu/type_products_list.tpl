<?php foreach($products as $product){?>
<a href="<?=$product['href']?>"><?=$product['name']?></a>
<img src="<?=$product['image']?>">
<br>
<?}?>