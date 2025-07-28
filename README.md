Развертывание приложения
1.Убедиться, что в системе установлен docker и git
2.Склонировать приложение из GitHub  git clone git@github.com:Oleg1979k/QrSite.git
3.Перейти в папку проекта cd QrSite/
4.Для построения докер-контейнеров выполнить команду
docker-compose up -d --build
5.Убедиться, что появился контейнер yii2-web и для установки необходимых модулей Yii2 выполнить 
docker exec -it yii2-web composer update
6.Накатить миграции exec -it yii2-web php yii migrate
7.В браузере открыть окно с адресом http://localhost:8080/site/ajax-form, в поле Url ввести полный адрес сайта,
например https://yandex.ru и нажать кнопку Ok. В окне должен появиться Qr-код для перехода по заданному
адресу.
