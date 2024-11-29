<?
// Подключаем пролог ядра Bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

// Устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

// Инициализируем ядро AJAX
CJSCore::Init(array('ajax'));

// Устанавливаем уникальный идентификатор для AJAX-запроса
$sidAjax = 'testAjax';

// Проверяем, является ли запрос AJAX-запросом с указанным идентификатором
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
   // Перезапускаем буфер вывода
   $GLOBALS['APPLICATION']->RestartBuffer();

   // Возвращаем JSON-ответ с результатом
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));

   // Завершаем выполнение скрипта
   die();
}
?>

<!-- Создаем блок для отображения результата AJAX-запроса -->
<div class="group">
   <div id="block"></div>
   <div id="process">wait ... </div>
</div>

<script>
   // Включаем режим отладки
   window.BXDEBUG = true;

   // Функция для загрузки данных через AJAX
   function DEMOLoad(){
      // Скрываем блок с результатом
      BX.hide(BX("block"));

      // Показываем блок с индикатором процесса
      BX.show(BX("process"));

      // Выполняем AJAX-запрос и передаем URL и функцию обратного вызова
      BX.ajax.loadJSON(
         '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
         DEMOResponse
      );
   }

   // Функция для обработки ответа AJAX-запроса
   function DEMOResponse (data){
      // Выводим отладочную информацию
      BX.debug('AJAX-DEMOResponse ', data);

      // Обновляем содержимое блока с результатом
      BX("block").innerHTML = data.RESULT;

      // Показываем блок с результатом
      BX.show(BX("block"));

      // Скрываем блок с индикатором процесса
      BX.hide(BX("process"));

      // Генерируем пользовательское событие
      BX.onCustomEvent(
         BX(BX("block")),
         'DEMOUpdate'
      );
   }

   // Функция, выполняемая при загрузке страницы
   BX.ready(function(){
      /*
      // Добавляем пользовательское событие для обновления страницы
      BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
         window.location.href = window.location.href;
      });
      */

      // Скрываем блоки с результатом и индикатором процесса
      BX.hide(BX("block"));
      BX.hide(BX("process"));

      // Привязываем обработчик события клика к элементам с классом 'css_ajax'
      BX.bindDelegate(
         document.body, 'click', {className: 'css_ajax' },
         function(e){
            if(!e)
               e = window.event;

            // Вызываем функцию загрузки данных через AJAX
            DEMOLoad();

            // Предотвращаем стандартное поведение события
            return BX.PreventDefault(e);
         }
      );
   });
</script>

<!-- Создаем элемент, на который будет привязан обработчик события клика -->
<div class="css_ajax">click Me</div>

<?
// Подключаем эпилог ядра Bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>

